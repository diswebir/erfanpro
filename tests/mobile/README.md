# D38 — Mobile test templates

Real-browser mobile audit for the erfanpro theme. The browser agent
cannot change viewport (fixed ~1920 px window), so this harness runs
in a real headless Chromium instead.

## What it tests

The 9 audit views (home, shop, product, projects, blog, about,
contact, feedback, search) at **360 / 390 / 768 px** plus a **1920 px**
desktop control. Per view × width it checks the D38 acceptance
criteria:

- no horizontal scroll (`scrollWidth <= innerWidth`, worst offenders listed)
- the mobile menu opens and closes, and clones **every** desktop nav
  link (the scope file says "29 items" but the live desktop nav has 7
  links — the check compares against the live count, not 29)
- the product order card is usable: the rendered variant is judged
  against the product's real price from the WooCommerce Store API —
  a priced product must show the purchasable card (price +
  add-to-cart), an unpriced one must show the inquiry card (D3)
- the shop filters are usable (search, sort, category pills; a pill tap
  must keep the grid populated and not introduce overflow)
- the phone button `031-91091011` is reachable (`tel:03191091011`,
  header pill and/or the mobile-menu call button)
- `meta viewport` is `width=device-width, initial-scale=1`

## Run

```sh
npm install
node mobile-audit.js            # against https://new.erfansanat.com
ERFANPRO_BASE_URL=http://localhost:8080 node mobile-audit.js
```

`npm test` does the same. Exit code is 0 only when every acceptance
check passes; warnings (e.g. menu item count != 29) do not fail the run.

## Browser

The script uses `playwright-core` with the system Google Chrome
(`CHROME_PATH`, default `/usr/bin/google-chrome`). Playwright's own
Chromium download (`npx playwright install chromium`) fails on this
VM — its downloader cannot fetch through the proxy — so Chrome is
installed from the Google .deb instead:

```sh
curl -LO https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
sudo dpkg -i google-chrome-stable_current_amd64.deb
```

On this VM Chrome also cannot use the egress proxy directly: the
proxy answers Chrome's own CONNECT requests with `403
policy_denied`. `proxy-tee.js` (spawned automatically by
`mobile-audit.js` when `HTTPS_PROXY` is set) re-issues a minimal
CONNECT upstream, which the proxy accepts. The upstream proxy URL —
which carries a credential — is only ever read from the environment,
never written to a file. To run the tee by hand:

```sh
node proxy-tee.js 18081
HTTPS_PROXY=http://127.0.0.1:18081 node mobile-audit.js
```

## Output

- `results/mobile-audit-results.json` — machine-readable results (committed)
- `results/screenshots/<view>-<width>.png` — viewport screenshots,
  plus `<view>-<width>-menu-open.png` for the opened mobile menu
  (gitignored — regenerate with `node mobile-audit.js`)

## Theme breakpoints covered

360 / 640 / 782 (WP admin bar) / 900 / 1100 / 1240 px. The 360 and
390 widths exercise the 360 breakpoint, 768 exercises 640/782, and
1920 is the desktop control.
