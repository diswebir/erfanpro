/**
 * erfanpro tabbed settings dashboard.
 *
 * - Rail links switch tabs; the active tab persists in the URL hash
 *   and in localStorage, so a plain-submit reload (section-manager
 *   move/reset, classic save) reopens the same tab.
 * - The floating save button is a submit button: without JS it submits
 *   the whole form to options.php. With JS, its click is intercepted
 *   and only the ACTIVE tab panel's fields are sent to admin-ajax
 *   (other tabs stay untouched in the DOM and in the database).
 * - Unchecked checkboxes inside the active tab are sent as '0' so the
 *   sanitizers read them as off; fields from other tabs are never
 *   sent, so they are never offed.
 * - Fonts tab: picking a file validates its extension and previews it
 *   live (FontFace from the chosen file); after a successful save the
 *   server returns the saved fonts' status and the cards refresh
 *   (name/format/size, badge, preview) without a reload, and any
 *   font-upload rejection is shown in the notice.
 * - Vanilla JS, zero external requests.
 */
(function () {
	'use strict';

	var data = window.erfanproTabs || {};
	var wrap = document.querySelector('.erfanpro-tabs');
	if (!wrap || !data.ajaxUrl || !data.nonce) {
		return;
	}

	var links = Array.prototype.slice.call(wrap.querySelectorAll('.erfanpro-tab-link'));
	var panels = Array.prototype.slice.call(wrap.querySelectorAll('.erfanpro-tab-panel'));
	var saveBtn = wrap.querySelector('#erfanpro-floating-save');
	var notice = wrap.querySelector('#erfanpro-save-notice');
	var STORAGE_KEY = 'erfanpro_active_tab';
	var activeTab = null;
	var revertTimer = null;

	function panelFor(tabId) {
		for (var i = 0; i < panels.length; i++) {
			if (panels[i].getAttribute('data-tab') === tabId) {
				return panels[i];
			}
		}
		return null;
	}

	function activate(tabId, updateHash) {
		var panel = panelFor(tabId);
		if (!panel) {
			return false;
		}
		activeTab = tabId;
		wrap.classList.add('erfanpro-js');
		links.forEach(function (link) {
			link.classList.toggle('is-active', link.getAttribute('data-tab') === tabId);
		});
		panels.forEach(function (p) {
			p.classList.toggle('is-active', p === panel);
		});
		try {
			window.localStorage.setItem(STORAGE_KEY, tabId);
		} catch (e) { /* private mode: hash still works */ }
		if (updateHash) {
			try {
				window.history.replaceState(null, '', '#erfanpro-group-' + tabId);
			} catch (e) { /* file/odd protocol: ignore */ }
		}
		return true;
	}

	function initialTab() {
		var hash = (window.location.hash || '').replace('#erfanpro-group-', '');
		if (hash && panelFor(hash)) {
			return hash;
		}
		try {
			var stored = window.localStorage.getItem(STORAGE_KEY);
			if (stored && panelFor(stored)) {
				return stored;
			}
		} catch (e) { /* ignore */ }
		return panels.length ? panels[0].getAttribute('data-tab') : null;
	}

	links.forEach(function (link) {
		link.addEventListener('click', function (ev) {
			ev.preventDefault();
			activate(link.getAttribute('data-tab'), true);
		});
	});

	function showNotice(message, isError) {
		if (!notice) {
			return;
		}
		notice.textContent = message;
		notice.hidden = false;
		notice.classList.toggle('is-error', !!isError);
	}

	function hideNotice() {
		if (!notice) {
			return;
		}
		notice.hidden = true;
		notice.textContent = '';
		notice.classList.remove('is-error');
	}

	function restoreButton() {
		if (!saveBtn) {
			return;
		}
		saveBtn.disabled = false;
		saveBtn.classList.remove('is-saved', 'is-error');
		saveBtn.textContent = data.save;
	}

	/* ---- fonts tab: live preview + no-reload refresh ---- */
	var FONT_EXTS = ['woff2', 'woff', 'ttf', 'otf'];
	var FONT_CSS_FORMATS = { woff2: 'woff2', woff: 'woff', ttf: 'truetype', otf: 'opentype' };

	function fontPreviewFamily(slot) {
		return 'ErfanproPreview' + ('display' === slot ? 'Display' : 'Primary');
	}

	function fontFallback(slot) {
		return 'display' === slot
			? "'ErfanproPreviewDisplay', 'Lalezar', 'Vazirmatn', Tahoma, sans-serif"
			: "'ErfanproPreviewPrimary', 'Vazirmatn', Tahoma, sans-serif";
	}

	function fontCard(slot) {
		return wrap.querySelector('[data-erfanpro-font-card="' + slot + '"]');
	}

	function fontExt(fileName) {
		var parts = (fileName || '').split('.');
		return parts.length > 1 ? parts.pop().toLowerCase() : '';
	}

	function showFontError(card, message) {
		if (!card) {
			return;
		}
		var el = card.querySelector('[data-erfanpro-font-error]');
		if (el) {
			el.textContent = message;
			el.hidden = false;
		}
	}

	function hideFontError(card) {
		if (!card) {
			return;
		}
		var el = card.querySelector('[data-erfanpro-font-error]');
		if (el) {
			el.textContent = '';
			el.hidden = true;
		}
	}

	function showFontSelected(card, message) {
		if (!card) {
			return;
		}
		var el = card.querySelector('[data-erfanpro-font-selected]');
		if (el) {
			el.textContent = message;
			el.hidden = !message;
		}
	}

	/* Load a font file (File object or saved URL) under the preview
	 * family name and apply it to that slot's preview lines. The
	 * preview family is separate from Vazirmatn/Lalezar, so the swap
	 * is deterministic and never touches the page's real fonts. */
	function applyPreviewFont(slot, src, ext) {
		var card = fontCard(slot);
		if (!card || !window.FontFace || !document.fonts) {
			return;
		}
		var family = fontPreviewFamily(slot);
		var format = FONT_CSS_FORMATS[ext] || 'woff2';
		var face;
		try {
			face = new FontFace(family, "url('" + src + "') format('" + format + "')");
		} catch (e) {
			return;
		}
		face.load().then(function (loaded) {
			try {
				document.fonts.add(loaded);
			} catch (e) { /* already added: keep going */ }
			var els = card.querySelectorAll('[data-erfanpro-font-preview="' + slot + '"]');
			els.forEach(function (el) {
				el.style.fontFamily = fontFallback(slot);
			});
		}).catch(function () {
			showFontError(card, data.fontInvalid);
		});
	}

	function clearPreviewFont(slot) {
		var card = fontCard(slot);
		if (!card) {
			return;
		}
		var els = card.querySelectorAll('[data-erfanpro-font-preview="' + slot + '"]');
		els.forEach(function (el) {
			el.style.fontFamily = '';
		});
	}

	/* Refresh one font card from the AJAX response (no reload):
	 * badge, file/format/size, remove row, preview font, and the
	 * file input is cleared so the same file can be picked again. */
	function refreshFontCard(slot, info) {
		var card = fontCard(slot);
		if (!card || !info) {
			return;
		}
		var badge = card.querySelector('[data-erfanpro-font-meta="badge"]');
		if (badge) {
			badge.textContent = info.custom ? (data.fontCustom || 'سفارشی') : (data.fontDefault || 'پیش‌فرض');
			badge.classList.toggle('is-custom', !!info.custom);
			badge.classList.toggle('is-default', !info.custom);
		}
		var fileEl = card.querySelector('[data-erfanpro-font-meta="file"]');
		if (fileEl) {
			fileEl.textContent = info.custom && info.file ? info.file : '—';
		}
		var formatEl = card.querySelector('[data-erfanpro-font-meta="format"]');
		if (formatEl) {
			formatEl.textContent = info.custom && info.format ? info.format.toUpperCase() : 'WOFF2';
		}
		var sizeEl = card.querySelector('[data-erfanpro-font-meta="size"]');
		if (sizeEl) {
			sizeEl.textContent = info.custom && info.size_human ? info.size_human : '—';
		}
		var removeRow = card.querySelector('[data-erfanpro-font-remove]');
		if (removeRow) {
			removeRow.hidden = !info.custom;
			var box = removeRow.querySelector('input[type="checkbox"]');
			if (box) {
				box.checked = false;
			}
		}
		var fileInput = card.querySelector('input[type="file"]');
		if (fileInput) {
			fileInput.value = '';
		}
		showFontSelected(card, '');
		hideFontError(card);
		clearPreviewFont(slot);
		if (info.custom && info.url) {
			applyPreviewFont(slot, info.url, fontExt(info.file));
		}
	}

	function refreshFontCards(fonts) {
		if (!fonts) {
			return;
		}
		if (fonts.primary) {
			refreshFontCard('primary', fonts.primary);
		}
		if (fonts.display) {
			refreshFontCard('display', fonts.display);
		}
	}

	/* File picked but not saved yet: validate the extension and show
	 * a live preview of the chosen file right away. */
	function bindFontInputs() {
		var inputs = wrap.querySelectorAll('input[type="file"][data-erfanpro-font-file]');
		inputs.forEach(function (input) {
			input.addEventListener('change', function () {
				var slot = input.getAttribute('data-erfanpro-font-file');
				var card = fontCard(slot);
				var file = input.files && input.files[0];
				if (!file) {
					showFontSelected(card, '');
					return;
				}
				if (FONT_EXTS.indexOf(fontExt(file.name)) === -1) {
					showFontError(card, data.fontInvalid);
					showFontSelected(card, '');
					input.value = '';
					return;
				}
				hideFontError(card);
				var label = (data.fontSelected || 'فایل انتخاب‌شده: %s — هنوز ذخیره نشده است.').replace('%s', file.name);
				showFontSelected(card, label);
				if (window.URL && URL.createObjectURL) {
					applyPreviewFont(slot, URL.createObjectURL(file), fontExt(file.name));
				}
			});
		});
	}

	function collectFields(panel) {
		var fd = new FormData();
		fd.append('action', 'erfanpro_save_tab');
		fd.append('nonce', data.nonce);
		fd.append('tab', activeTab);
		var fields = panel.querySelectorAll('input, select, textarea');
		fields.forEach(function (el) {
			if (el.disabled || !el.name) {
				return;
			}
			var type = (el.type || '').toLowerCase();
			if ('submit' === type || 'button' === type || 'reset' === type) {
				return;
			}
			if ('checkbox' === type) {
				fd.append(el.name, el.checked ? el.value : '0');
				return;
			}
			if ('radio' === type) {
				if (el.checked) {
					fd.append(el.name, el.value);
				}
				return;
			}
			if ('select-multiple' === type) {
				Array.prototype.forEach.call(el.options, function (opt) {
					if (opt.selected) {
						fd.append(el.name, opt.value);
					}
				});
				return;
			}
			if ('file' === type) {
				if (el.files && el.files[0]) {
					fd.append(el.name, el.files[0]);
				}
				return;
			}
			fd.append(el.name, el.value);
		});
		return fd;
	}

	if (saveBtn) {
		saveBtn.addEventListener('click', function (ev) {
			ev.preventDefault();
			var panel = panelFor(activeTab);
			if (!panel) {
				return;
			}
			if (revertTimer) {
				window.clearTimeout(revertTimer);
				revertTimer = null;
			}
			saveBtn.disabled = true;
			saveBtn.classList.remove('is-saved', 'is-error');
			saveBtn.textContent = data.saving;
			hideNotice();
			window.fetch(data.ajaxUrl, {
				method: 'POST',
				body: collectFields(panel),
				credentials: 'same-origin'
			})
				.then(function (res) { return res.json(); })
				.then(function (json) {
					if (json && json.success) {
						saveBtn.textContent = data.saved;
						saveBtn.classList.add('is-saved');
						if (json.data) {
							refreshFontCards(json.data.fonts);
							if (json.data.font_errors && json.data.font_errors.length) {
								showNotice((data.fontNotSaved || 'تب ذخیره شد، اما فونت ذخیره نشد:') + ' ' + json.data.font_errors.join(' '), true);
								revertTimer = window.setTimeout(restoreButton, 5000);
								return;
							}
						}
						revertTimer = window.setTimeout(restoreButton, 3000);
					} else {
						var msg = (json && json.data && json.data.message) ? json.data.message : data.error;
						saveBtn.classList.add('is-error');
						saveBtn.textContent = data.save;
						showNotice(msg, true);
						revertTimer = window.setTimeout(restoreButton, 5000);
					}
				})
				.catch(function () {
					saveBtn.classList.add('is-error');
					saveBtn.textContent = data.save;
					showNotice(data.error, true);
					revertTimer = window.setTimeout(restoreButton, 5000);
				})
				.finally(function () {
					saveBtn.disabled = false;
				});
		});
	}

	function init() {
		var tab = initialTab();
		if (tab) {
			activate(tab, true);
		}
		bindFontInputs();
	}

	if ('loading' === document.readyState) {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
