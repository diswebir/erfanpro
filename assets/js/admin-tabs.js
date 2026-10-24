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
	}

	if ('loading' === document.readyState) {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
