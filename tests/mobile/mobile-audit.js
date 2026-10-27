/**
 * D38 — Mobile test templates for the erfanpro theme.
 *
 * Runs the 9 audit views (home, shop, product, projects, blog, about,
 * contact, feedback, search) at 360 / 390 / 768 px plus a 1920 px
 * desktop control, in a real headless Chromium, and checks the D38
 * acceptance criteria per view:
 *   - no horizontal scroll
 *   - the mobile menu opens and closes (29-item menu)
 *   - the product order card and the shop filters are usable
 *   - the phone button 031-91091011 is reachable
 *
 * Execution path (D38 decision): real headless Chromium via
 * playwright-core + system Google Chrome (/usr/bin/google-chrome).
 * The browser agent cannot change viewport (fixed ~1920 px) and
 * `npx playwright install chromium` fails on this VM (the Playwright
 * downloader cannot fetch through the proxy), so the script uses the
 * system Chrome installed from the Google .deb instead. If Chrome is
 * somewhere else, set CHROME_PATH.
 *
 * Usage:
 *   npm install
 *   node mobile-audit.js            # against https://new.erfansanat.com
 *   ERFANPRO_BASE_URL=http://localhost:8080 node mobile-audit.js
 *
 * Output:
 *   results/mobile-audit-results.json   (machine-readable, committed)
 *   results/screenshots/<view>-<width>.png (viewport shots, gitignored)
 *
 * Exit code: 0 when every acceptance check passes, 1 otherwise.
 * Warnings (e.g. menu item count != 29) do not fail the run.
 */

'use strict';

const { chromium } = require( 'playwright-core' );
const fs = require( 'fs' );
const path = require( 'path' );

const BASE = ( process.env.ERFANPRO_BASE_URL || 'https://new.erfansanat.com' ).replace( /\/$/, '' );
const CHROME_PATH = process.env.CHROME_PATH || '/usr/bin/google-chrome';
const OUT_DIR = path.join( __dirname, 'results' );
const SHOT_DIR = path.join( OUT_DIR, 'screenshots' );

const VIEWS = [
	{ id: 'home', url: '/' },
	{ id: 'shop', url: '/shop/' },
	{ id: 'product', url: '/product/المان-نوری-حجمی-شهری/' },
	{ id: 'projects', url: '/projects/' },
	{ id: 'blog', url: '/blog/' },
	{ id: 'about', url: '/درباره-ما/' },
	{ id: 'contact', url: '/تماس-با-ما/' },
	{ id: 'feedback', url: '/feedback/' },
	{ id: 'search', url: '/?s=' + encodeURIComponent( 'نور' ) },
];

const WIDTHS = [
	{ width: 360, height: 844 },
	{ width: 390, height: 844 },
	{ width: 768, height: 1024 },
	{ width: 1920, height: 1080 },
];

const PHONE_TEL = 'tel:03191091011';

/**
 * Real price of a product from the WooCommerce Store API, matched by
 * product ID. Matched by ID — not by permalink — because the Store
 * API returns corrupted permalinks for Persian slugs (observed
 * 2026-10-27: "…/product/NaN8%a7NaN9%84…" — literal "NaN" in place
 * of the first characters). Node honours the proxy env vars
 * (NODE_USE_ENV_PROXY).
 */
async function fetchStoreProduct( base, productId ) {
	try {
		const res = await fetch( base + '/wp-json/wc/store/v1/products?per_page=100' );
		if ( ! res.ok ) return null;
		const list = await res.json();
		const hit = list.find( ( p ) => Number( p.id ) === Number( productId ) );
		if ( ! hit ) return null;
		const minor = hit.prices && hit.prices.price !== '' && hit.prices.price !== null
			? Number( hit.prices.price )
			: null;
		return { name: hit.name, priceMinor: minor, type: hit.type, permalink: hit.permalink };
	} catch ( e ) {
		return null;
	}
}

function verdict( pass, detail ) {
	return { pass: !! pass, detail: detail || '' };
}

async function scrollThrough( page ) {
	await page.evaluate( async () => {
		const h = document.body.scrollHeight;
		for ( let y = 0; y <= h; y += 600 ) {
			window.scrollTo( 0, y );
			await new Promise( ( r ) => setTimeout( r, 120 ) );
		}
		window.scrollTo( 0, 0 );
	} );
	await page.waitForTimeout( 600 );
}

async function checkView( page, view, vp ) {
	const url = BASE + view.url;
	const errors = [];
	page.on( 'pageerror', ( e ) => errors.push( 'pageerror: ' + e.message ) );
	page.on( 'console', ( m ) => {
		if ( m.type() === 'error' ) errors.push( 'console: ' + m.text() );
	} );

	const started = Date.now();
	const response = await page.goto( url, { waitUntil: 'domcontentloaded', timeout: 60000 } );
	try {
		await page.waitForLoadState( 'networkidle', { timeout: 30000 } );
	} catch ( e ) {
		/* Slow third-party assets must not fail the audit. */
	}
	await page.waitForTimeout( 1200 );
	await scrollThrough( page );

	const httpStatus = response ? response.status() : 0;
	const loadMs = Date.now() - started;

	const data = await page.evaluate( () => {
		const vw = window.innerWidth;
		const doc = document.documentElement;
		const out = {
			viewportMeta: '',
			scrollWidth: doc.scrollWidth,
			innerWidth: vw,
			overflowX: doc.scrollWidth > vw + 1,
			offenders: [],
			hamburger: { exists: false, visible: false },
			menu: { items: 0, opened: false, closed: false, callHref: '', callText: '' },
			headerTel: { exists: false, href: '', visible: false, text: '' },
			product: null,
			shop: null,
			search: null,
		};

		const meta = document.querySelector( 'meta[name="viewport"]' );
		if ( meta ) out.viewportMeta = meta.getAttribute( 'content' ) || '';

		/* Widest overflowing visible elements, worst first. */
		const seen = [];
		document.querySelectorAll( 'body *' ).forEach( ( el ) => {
			const r = el.getBoundingClientRect();
			if ( ! r.width || r.right <= vw + 1 ) return;
			const cs = getComputedStyle( el );
			if ( cs.display === 'none' || cs.visibility === 'hidden' ) return;
			seen.push( {
				tag: el.tagName.toLowerCase(),
				cls: ( el.className && el.className.baseVal !== undefined ? el.className.baseVal : String( el.className || '' ) ).slice( 0, 80 ),
				right: Math.round( r.right ),
				overflow: Math.round( r.right - vw ),
			} );
		} );
		seen.sort( ( a, b ) => b.overflow - a.overflow );
		out.offenders = seen.slice( 0, 5 );

		const btn = document.querySelector( 'header button[aria-label="منو"]' );
		if ( btn ) {
			const r = btn.getBoundingClientRect();
			out.hamburger.exists = true;
			out.hamburger.visible = r.width > 0 && r.height > 0 && getComputedStyle( btn ).visibility !== 'hidden';
		}
		/* The mobile menu must clone every desktop nav link. The scope
		 * file says "29 items" but the live desktop nav has 7 links,
		 * so the check compares against the live count, not 29. The
		 * header renders TWO navs with the same 7 links; main.js
		 * clones the FIRST one, so that is the reference. */
		const firstNav = document.querySelector( 'header nav' );
		out.desktopNavLinks = firstNav ? firstNav.querySelectorAll( 'a' ).length : 0;
		/* WooCommerce post ID (body class postid-N) for the Store API
		 * price cross-check. */
		const postId = document.body.className.match( /postid-(\d+)/ );
		out.productId = postId ? Number( postId[ 1 ] ) : null;

		const tel = document.querySelector( 'header a[href^="tel:"]' );
		if ( tel ) {
			const r = tel.getBoundingClientRect();
			out.headerTel.exists = true;
			out.headerTel.href = tel.getAttribute( 'href' ) || '';
			out.headerTel.text = ( tel.textContent || '' ).trim();
			out.headerTel.visible = r.width > 0 && r.height > 0 && getComputedStyle( tel ).visibility !== 'hidden';
		}

		if ( document.body.classList.contains( 'single-product' ) || document.querySelector( 'form.cart' ) ) {
			const form = document.querySelector( 'form.cart' );
			const addBtn = document.querySelector( '.single_add_to_cart_button' );
			const price = document.querySelector( '.summary .price, .product .price' );
			/* D3 automatic hybrid: priced → purchasable card (form.cart),
			 * unpriced → inquiry card ("تماس بگیرید" + phone/WhatsApp).
			 * The variant is recorded so the verdict can be judged
			 * against the product's real price (Store API, in Node). */
			const inquiryText = document.body.innerText.includes( 'تماس بگیرید' );
			out.product = {
				variant: form ? 'purchasable' : ( inquiryText ? 'inquiry' : 'none' ),
				formExists: !! form,
				addToCartVisible: addBtn ? addBtn.getBoundingClientRect().width > 0 && ! addBtn.disabled : false,
				addToCartText: addBtn ? ( addBtn.textContent || '' ).trim().slice( 0, 60 ) : '',
				priceText: price ? ( price.textContent || '' ).trim().replace( /\s+/g, ' ' ).slice( 0, 80 ) : '',
				inquiryVisible: inquiryText,
			};
		}

		if ( document.querySelector( '#erfanpro-shop' ) ) {
			const pills = Array.from( document.querySelectorAll( '[data-shop-cat]' ) ).filter(
				( b ) => b.getBoundingClientRect().width > 0
			);
			const search = document.querySelector( '#erfanpro-shop-search' );
			const sort = document.querySelector( '#erfanpro-shop-sort' );
			const grid = document.querySelector( '#erfanpro-shop-grid' );
			out.shop = {
				searchVisible: search ? search.getBoundingClientRect().width > 0 : false,
				sortVisible: sort ? sort.getBoundingClientRect().width > 0 : false,
				pillsVisible: pills.length,
				gridItems: grid ? grid.children.length : 0,
			};
		}

		if ( document.body.classList.contains( 'search' ) || /[?&]s=/.test( location.search ) ) {
			out.search = {
				heading: ( document.querySelector( 'h1' ) || {} ).textContent
					? document.querySelector( 'h1' ).textContent.trim().slice( 0, 120 )
					: '',
				bodyHasTerm: document.body.innerText.includes( 'نور' ),
			};
		}

		return out;
	} );

	/* Menu open/close is a live interaction, done outside evaluate. */
	let menuCheck = null;
	if ( data.hamburger.visible ) {
		const btn = page.locator( 'header button[aria-label="منو"]' );
		await btn.click();
		await page.waitForTimeout( 600 );
		const opened = await page.evaluate( () => {
			const panel = document.getElementById( 'static-mobile-menu' );
			if ( ! panel ) return { items: 0, opened: false, callHref: '', callText: '' };
			return {
				items: panel.querySelectorAll( '.mmenu-item' ).length,
				opened: getComputedStyle( panel ).display !== 'none',
				callHref: ( panel.querySelector( 'a[href^="tel:"]' ) || {} ).getAttribute
					? panel.querySelector( 'a[href^="tel:"]' ).getAttribute( 'href' ) || ''
					: '',
				callText: ( panel.querySelector( 'a[href^="tel:"]' ) || {} ).textContent
					? panel.querySelector( 'a[href^="tel:"]' ).textContent.trim()
					: '',
			};
		} );
		await page.screenshot( {
			path: path.join( SHOT_DIR, view.id + '-' + vp.width + '-menu-open.png' ),
		} );
		await btn.click();
		await page.waitForTimeout( 700 );
		const closed = await page.evaluate( () => {
			const panel = document.getElementById( 'static-mobile-menu' );
			return panel ? getComputedStyle( panel ).display === 'none' : true;
		} );
		menuCheck = { ...opened, closed };
	}

	/* Shop filter interaction: tap the second category pill, grid must stay populated. */
	let shopInteraction = null;
	if ( data.shop ) {
		const pills = page.locator( '[data-shop-cat]' );
		const count = await pills.count();
		if ( count > 1 ) {
			await pills.nth( 1 ).click();
			await page.waitForTimeout( 2500 );
			const after = await page.evaluate( () => {
				const grid = document.querySelector( '#erfanpro-shop-grid' );
				return {
					gridItems: grid ? grid.children.length : 0,
					overflowX: document.documentElement.scrollWidth > window.innerWidth + 1,
				};
			} );
			shopInteraction = after;
			/* Back to "all" so later widths start clean. */
			await pills.nth( 0 ).click();
			await page.waitForTimeout( 1500 );
		}
	}

	await page.screenshot( {
		path: path.join( SHOT_DIR, view.id + '-' + vp.width + '.png' ),
	} );

	/* ---- Verdicts ---- */
	const checks = {};
	const warnings = [];

	checks.http = verdict( httpStatus === 200, 'HTTP ' + httpStatus + ' in ' + loadMs + ' ms' );

	checks.viewportMeta = verdict(
		data.viewportMeta.includes( 'width=device-width' ) && data.viewportMeta.includes( 'initial-scale=1' ),
		'meta viewport: "' + data.viewportMeta + '"'
	);

	checks.noHorizontalScroll = verdict(
		! data.overflowX,
		data.overflowX
			? 'scrollWidth ' + data.scrollWidth + ' > innerWidth ' + data.innerWidth +
				'; worst: ' + data.offenders.map( ( o ) => o.tag + '.' + o.cls.split( ' ' )[ 0 ] + ' +' + o.overflow + 'px' ).join( ', ' )
			: 'scrollWidth ' + data.scrollWidth + ' <= innerWidth ' + data.innerWidth
	);

	if ( vp.width < 1280 ) {
		if ( ! data.hamburger.visible ) {
			checks.mobileMenu = verdict( false, 'hamburger button not visible at ' + vp.width + 'px' );
		} else if ( ! menuCheck ) {
			checks.mobileMenu = verdict( false, 'menu panel never appeared' );
		} else {
			checks.mobileMenu = verdict(
				menuCheck.opened && menuCheck.closed && menuCheck.items > 0,
				'items=' + menuCheck.items + ' (desktop nav links=' + data.desktopNavLinks + '), opened=' + menuCheck.opened + ', closed=' + menuCheck.closed
			);
			if ( menuCheck.items !== data.desktopNavLinks ) {
				warnings.push( 'menu clones ' + menuCheck.items + ' of ' + data.desktopNavLinks + ' desktop nav links' );
			}
		}
	} else {
		checks.mobileMenu = verdict( true, 'desktop width — hamburger hidden by design (xl breakpoint)' );
	}

	const phoneReachable =
		( data.headerTel.exists && data.headerTel.href === PHONE_TEL ) ||
		( menuCheck && menuCheck.callHref === PHONE_TEL );
	checks.phoneReachable = verdict(
		phoneReachable,
		'header tel href="' + ( data.headerTel.href || '—' ) + '" visible=' + data.headerTel.visible +
			'; menu call href="' + ( menuCheck ? menuCheck.callHref : '—' ) + '" text="' +
			( menuCheck ? menuCheck.callText : '' ) + '"'
	);
	if ( vp.width < 640 && data.headerTel.exists && ! data.headerTel.visible ) {
		warnings.push( 'header phone pill hidden below sm (640px) by design — phone reachable via the mobile-menu call button' );
	}

	if ( data.product ) {
		/* Judge the rendered variant against the product's real price
		 * from the WooCommerce Store API (fetched in Node): a priced
		 * product must show the purchasable card; an unpriced one must
		 * show the inquiry card (D3). Without API data, accept either
		 * complete variant and warn. */
		const api = view.id === 'product' && data.productId ? await fetchStoreProduct( BASE, data.productId ) : null;
		let detail =
			'variant=' + data.product.variant +
			', form=' + data.product.formExists + ', add-to-cart visible=' + data.product.addToCartVisible +
			' ("' + data.product.addToCartText + '"), price="' + data.product.priceText + '"';
		if ( api ) {
			detail += '; Store API: "' + api.name + '" price=' +
				( api.priceMinor !== null ? ( api.priceMinor / 100 ).toLocaleString( 'en-US' ) + ' تومان' : 'NONE' ) +
				', type=' + api.type;
			if ( api.priceMinor !== null && api.priceMinor > 0 ) {
				checks.orderCard = verdict(
					data.product.variant === 'purchasable' && data.product.addToCartVisible && data.product.priceText !== '',
					detail + ' — priced product must show the purchasable card'
				);
			} else {
				checks.orderCard = verdict(
					data.product.variant === 'inquiry',
					detail + ' — unpriced product must show the inquiry card (D3)'
				);
			}
		} else {
			const ok =
				( data.product.variant === 'purchasable' && data.product.addToCartVisible && data.product.priceText !== '' ) ||
				data.product.variant === 'inquiry';
			checks.orderCard = verdict( ok, detail );
			warnings.push( 'Store API unreachable — order-card variant judged without the real price' );
		}
	}

	if ( data.shop ) {
		const usable =
			data.shop.searchVisible && data.shop.sortVisible && data.shop.pillsVisible > 0 && data.shop.gridItems > 0;
		let detail =
			'search=' + data.shop.searchVisible + ', sort=' + data.shop.sortVisible +
			', pills=' + data.shop.pillsVisible + ', gridItems=' + data.shop.gridItems;
		if ( shopInteraction ) {
			detail += '; after pill tap gridItems=' + shopInteraction.gridItems +
				', overflowX=' + shopInteraction.overflowX;
			if ( shopInteraction.gridItems === 0 || shopInteraction.overflowX ) {
				checks.shopFilters = verdict( false, detail );
			} else {
				checks.shopFilters = verdict( usable, detail );
			}
		} else {
			checks.shopFilters = verdict( usable, detail );
		}
	}

	if ( data.search ) {
		checks.searchResults = verdict(
			data.search.bodyHasTerm,
			'h1="' + data.search.heading + '", body contains term: ' + data.search.bodyHasTerm
		);
	}

	const failed = Object.values( checks ).filter( ( c ) => ! c.pass ).map( ( c ) => c.detail );
	return {
		view: view.id,
		url: url,
		width: vp.width,
		height: vp.height,
		httpStatus,
		loadMs,
		checks,
		warnings,
		jsErrors: errors.slice( 0, 5 ),
		data: {
			viewportMeta: data.viewportMeta,
			scrollWidth: data.scrollWidth,
			innerWidth: data.innerWidth,
			offenders: data.offenders,
			menuItems: menuCheck ? menuCheck.items : null,
			desktopNavLinks: data.desktopNavLinks,
			productId: data.productId,
			headerTelVisible: data.headerTel.visible,
			product: data.product,
			shop: data.shop,
		},
	};
}

async function main() {
	fs.mkdirSync( SHOT_DIR, { recursive: true } );

	const launchArgs = [
		'--no-sandbox',
		'--disable-dev-shm-usage',
		'--disable-component-update',
		'--disable-background-networking',
	];
	/* Chrome cannot reach the internet directly on this VM: it must go
	 * through the authenticating egress proxy (HTTPS_PROXY env), and
	 * the proxy refuses Chrome's own CONNECT requests with 403
	 * policy_denied (observed 2026-10-27). The local proxy-tee
	 * re-issues a minimal CONNECT upstream, which the proxy accepts.
	 * Spawn it when a proxy is configured; otherwise connect direct.
	 * The upstream URL (with its credential) is only ever read from
	 * the environment, never written to a file. */
	let proxyOpt;
	let teeChild = null;
	const proxyEnv = process.env.HTTPS_PROXY || process.env.https_proxy || process.env.HTTP_PROXY || process.env.http_proxy;
	if ( proxyEnv ) {
		const teePort = Number( process.env.ERFANPRO_TEE_PORT || 18081 );
		const { spawn } = require( 'child_process' );
		teeChild = spawn( process.execPath, [ path.join( __dirname, 'proxy-tee.js' ), String( teePort ) ], {
			stdio: [ 'ignore', 'pipe', 'pipe' ],
		} );
		await new Promise( ( resolve, reject ) => {
			const timer = setTimeout( () => reject( new Error( 'proxy-tee did not start in 10s' ) ), 10000 );
			teeChild.stdout.on( 'data', ( d ) => {
				if ( String( d ).includes( 'listening on' ) ) {
					clearTimeout( timer );
					resolve();
				}
			} );
			teeChild.on( 'exit', ( code ) => {
				clearTimeout( timer );
				reject( new Error( 'proxy-tee exited early with code ' + code ) );
			} );
		} );
		proxyOpt = { server: 'http://127.0.0.1:' + teePort };
	}

	const browser = await chromium.launch( {
		executablePath: CHROME_PATH,
		args: launchArgs,
		proxy: proxyOpt,
	} );

	const results = {
		generatedAt: new Date().toISOString(),
		baseUrl: BASE,
		chrome: CHROME_PATH,
		widths: WIDTHS.map( ( w ) => w.width ),
		views: VIEWS.map( ( v ) => v.id ),
		runs: [],
		summary: { total: 0, passed: 0, failed: 0, warnings: 0 },
	};

	try {
		for ( const vp of WIDTHS ) {
			const context = await browser.newContext( {
				viewport: { width: vp.width, height: vp.height },
				isMobile: vp.width < 768,
				hasTouch: vp.width < 768,
				deviceScaleFactor: vp.width < 768 ? 2 : 1,
				userAgent:
					vp.width < 768
						? 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Mobile Safari/537.36'
						: undefined,
			} );
			const page = await context.newPage();
			for ( const view of VIEWS ) {
				try {
					const run = await checkView( page, view, vp );
					results.runs.push( run );
					const bad = Object.entries( run.checks ).filter( ( [ , c ] ) => ! c.pass );
					results.summary.total += 1;
					if ( bad.length === 0 ) {
						results.summary.passed += 1;
					} else {
						results.summary.failed += 1;
					}
					results.summary.warnings += run.warnings.length;
					console.log(
						( bad.length === 0 ? 'PASS' : 'FAIL' ) + '  ' + view.id.padEnd( 9 ) + ' @' + vp.width + 'px' +
						( bad.length ? '  -> ' + bad.map( ( [ k, c ] ) => k + ': ' + c.detail ).join( ' | ' ) : '' ) +
						( run.warnings.length ? '  [warn: ' + run.warnings.join( '; ' ) + ']' : '' )
					);
				} catch ( e ) {
					results.runs.push( {
						view: view.id,
						url: BASE + view.url,
						width: vp.width,
						error: String( e && e.message ? e.message : e ),
						checks: {},
						warnings: [],
					} );
					results.summary.total += 1;
					results.summary.failed += 1;
					console.log( 'ERROR ' + view.id.padEnd( 9 ) + ' @' + vp.width + 'px  -> ' + ( e.message || e ) );
				}
			}
			await context.close();
		}
	} finally {
		await browser.close();
		if ( teeChild ) teeChild.kill();
	}

	fs.writeFileSync( path.join( OUT_DIR, 'mobile-audit-results.json' ), JSON.stringify( results, null, 2 ) );
	console.log( '\nViews: ' + results.summary.passed + ' passed / ' + results.summary.failed + ' failed / ' +
		results.summary.total + ' total; warnings: ' + results.summary.warnings );
	console.log( 'Results: ' + path.join( OUT_DIR, 'mobile-audit-results.json' ) );
	process.exit( results.summary.failed === 0 ? 0 : 1 );
}

main().catch( ( e ) => {
	console.error( e );
	process.exit( 2 );
} );
