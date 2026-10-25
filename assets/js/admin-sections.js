/**
 * erfanpro section manager (manager tab): sidebar + one panel per page.
 *
 * - Sidebar with 18 page items; clicking one shows only that page's
 *   panel. The active page persists in the URL hash and in
 *   localStorage (first visit: «صفحه خانه»), so a plain-submit reload
 *   (move/reset, classic save) reopens the same page.
 * - Drag-and-drop reorder inside a panel (HTML5 DnD, Vanilla JS):
 *   dropping renumbers the hidden order inputs and the visible
 *   position badges, so the next save (floating AJAX save or classic
 *   submit) stores the new order. Up/down buttons stay for keyboard.
 * - Toggle switches are real checkboxes: flipping one dims the row,
 *   shows the «غیرفعال» badge, and updates the panel/sidebar/summary
 *   counts.
 * - Search box above the sidebar filters sidebar items by page name
 *   and section name; clearing it restores the full sidebar and keeps
 *   the previously active page.
 * - Reset buttons ask for confirmation (they wipe the page's saved
 *   layout) and keep the same page active afterwards.
 * - Without JS every panel is visible and the sidebar links are real
 *   anchors to the panel ids, so the manager still works as plain
 *   checkboxes + submit buttons; the search box and drag handles are
 *   hidden by CSS in that mode.
 * - Vanilla JS, zero external requests.
 */
(function () {
	'use strict';

	var root = document.querySelector('[data-erfanpro-sm-manager]');
	if (!root) {
		return;
	}
	root.classList.add('erfanpro-sm-js');

	var STORAGE_KEY = 'erfanpro_sections_page';
	var panels = Array.prototype.slice.call(root.querySelectorAll('[data-erfanpro-sm-panel]'));
	var navLinks = Array.prototype.slice.call(root.querySelectorAll('[data-erfanpro-sm-nav]'));
	var navItems = Array.prototype.slice.call(root.querySelectorAll('[data-erfanpro-sm-nav-item]'));
	var searchInput = root.querySelector('[data-erfanpro-sm-search]');
	var emptyMsg = root.querySelector('[data-erfanpro-sm-nav-empty]');
	var disabledCountEl = root.querySelector('[data-erfanpro-sm-disabled-count]');
	var activePage = null;

	function faDigits(n) {
		return String(n).replace(/[0-9]/g, function (d) {
			return '۰۱۲۳۴۵۶۷۸۹'[+d];
		});
	}

	/* ---- active panel (hash + localStorage, default: home) ---- */
	function panelFor(key) {
		for (var i = 0; i < panels.length; i++) {
			if (panels[i].getAttribute('data-erfanpro-sm-panel') === key) {
				return panels[i];
			}
		}
		return null;
	}

	function activate(key, updateHash) {
		var panel = panelFor(key);
		if (!panel) {
			return false;
		}
		activePage = key;
		panels.forEach(function (p) {
			p.classList.toggle('is-active', p === panel);
		});
		navLinks.forEach(function (link) {
			var on = link.getAttribute('data-erfanpro-sm-nav') === key;
			link.classList.toggle('is-active', on);
			if (on) {
				link.setAttribute('aria-current', 'true');
			} else {
				link.removeAttribute('aria-current');
			}
		});
		try {
			window.localStorage.setItem(STORAGE_KEY, key);
		} catch (e) { /* private mode: hash still works */ }
		if (updateHash) {
			try {
				window.history.replaceState(null, '', '#erfanpro-sm-panel-' + key);
			} catch (e) { /* file/odd protocol: ignore */ }
		}
		return true;
	}

	function initialPage() {
		var hash = (window.location.hash || '').replace('#erfanpro-sm-panel-', '');
		if (hash && panelFor(hash)) {
			return hash;
		}
		try {
			var stored = window.localStorage.getItem(STORAGE_KEY);
			if (stored && panelFor(stored)) {
				return stored;
			}
		} catch (e) { /* ignore */ }
		return panels.length ? panels[0].getAttribute('data-erfanpro-sm-panel') : null;
	}

	navLinks.forEach(function (link) {
		link.addEventListener('click', function (ev) {
			ev.preventDefault();
			activate(link.getAttribute('data-erfanpro-sm-nav'), true);
		});
	});

	/* ---- counts ---- */
	function updatePanelStatus(panel) {
		var rows = panel.querySelectorAll('.erfanpro-sm-row');
		var on = 0;
		rows.forEach(function (row) {
			var box = row.querySelector('input[type="checkbox"]');
			if (box && box.checked) {
				on++;
			}
		});
		var status = panel.querySelector('[data-erfanpro-sm-panel-status]');
		if (status) {
			status.textContent = faDigits(on) + ' از ' + faDigits(rows.length) + ' فعال';
		}
		var key = panel.getAttribute('data-erfanpro-sm-panel');
		var item = root.querySelector('[data-erfanpro-sm-nav-item="' + key + '"]');
		var navStatus = item && item.querySelector('[data-erfanpro-sm-nav-status]');
		if (navStatus) {
			navStatus.textContent = faDigits(on) + ' از ' + faDigits(rows.length) + ' فعال';
		}
	}

	function updateSummaryDisabled() {
		if (!disabledCountEl) {
			return;
		}
		var n = root.querySelectorAll('.erfanpro-sm-row.is-disabled').length;
		disabledCountEl.textContent = faDigits(n);
	}

	/* ---- toggle switches (real checkboxes) ---- */
	root.querySelectorAll('.erfanpro-sm-row').forEach(function (row) {
		var box = row.querySelector('input[type="checkbox"]');
		if (!box) {
			return;
		}
		box.addEventListener('change', function () {
			row.classList.toggle('is-disabled', !box.checked);
			var badge = row.querySelector('.erfanpro-sm-badge');
			if (badge) {
				badge.hidden = box.checked;
			}
			var panel = row.closest('[data-erfanpro-sm-panel]');
			if (panel) {
				updatePanelStatus(panel);
			}
			updateSummaryDisabled();
		});
	});

	/* ---- drag-and-drop reorder ---- */
	var dragged = null;

	function renumber(list) {
		var rows = list.querySelectorAll('.erfanpro-sm-row');
		rows.forEach(function (row, i) {
			var order = row.querySelector('[data-erfanpro-sm-order]');
			if (order) {
				order.value = String(i);
			}
			var num = row.querySelector('[data-erfanpro-sm-num]');
			if (num) {
				num.textContent = faDigits(i + 1);
			}
			var moves = row.querySelectorAll('.erfanpro-sm-move');
			if (moves[0]) {
				moves[0].disabled = 0 === i;
			}
			if (moves[1]) {
				moves[1].disabled = i === rows.length - 1;
			}
		});
	}

	function rowFromEvent(ev) {
		var t = ev.target;
		while (t && t !== root) {
			if (t.classList && t.classList.contains('erfanpro-sm-row')) {
				return t;
			}
			t = t.parentNode;
		}
		return null;
	}

	panels.forEach(function (panel) {
		var list = panel.querySelector('[data-erfanpro-sm-list]');
		if (!list) {
			return;
		}
		list.addEventListener('dragstart', function (ev) {
			var row = rowFromEvent(ev);
			if (!row) {
				return;
			}
			/* Never start a drag from an interactive control. */
			if (ev.target.closest && ev.target.closest('input, button, select, textarea, a, label')) {
				ev.preventDefault();
				return;
			}
			dragged = row;
			row.classList.add('is-dragging');
			try {
				ev.dataTransfer.effectAllowed = 'move';
				ev.dataTransfer.setData('text/plain', row.getAttribute('data-section-id') || '');
			} catch (e) { /* odd browser: ignore */ }
		});
		list.addEventListener('dragend', function () {
			if (dragged) {
				dragged.classList.remove('is-dragging');
				dragged = null;
			}
		});
		list.addEventListener('dragover', function (ev) {
			if (!dragged) {
				return;
			}
			ev.preventDefault();
			var row = rowFromEvent(ev);
			if (!row || row === dragged) {
				return;
			}
			var rect = row.getBoundingClientRect();
			var before = (ev.clientY - rect.top) < rect.height / 2;
			if (before) {
				list.insertBefore(dragged, row);
			} else if (row.nextSibling) {
				list.insertBefore(dragged, row.nextSibling);
			} else {
				list.appendChild(dragged);
			}
			renumber(list);
		});
		list.addEventListener('drop', function (ev) {
			if (!dragged) {
				return;
			}
			ev.preventDefault();
			renumber(list);
		});
	});

	/* ---- search / filter the sidebar ---- */
	function norm(s) {
		return (s || '').replace(/\s+/g, ' ').trim().toLowerCase();
	}

	function applySearch() {
		if (!searchInput) {
			return;
		}
		var q = norm(searchInput.value);
		var anyVisible = false;
		navItems.forEach(function (item) {
			var hay = norm(item.getAttribute('data-page-label') + ' ' + item.getAttribute('data-section-labels'));
			var hit = !q || hay.indexOf(q) !== -1;
			item.classList.toggle('is-hidden', !hit);
			if (hit) {
				anyVisible = true;
			}
		});
		if (emptyMsg) {
			emptyMsg.hidden = anyVisible;
		}
	}

	if (searchInput) {
		searchInput.addEventListener('input', applySearch);
	}

	/* ---- reset: confirm first (it wipes the page's saved layout);
	 * the active page stays active afterwards via localStorage. ---- */
	root.querySelectorAll('[data-erfanpro-sm-reset]').forEach(function (btn) {
		btn.addEventListener('click', function (ev) {
			var label = btn.getAttribute('data-page-label') || '';
			var msg = 'چیدمان برگهٔ «' + label + '» به حالت پیش‌فرض برمی‌گردد. ادامه می‌دهید؟';
			if (!window.confirm(msg)) {
				ev.preventDefault();
			}
		});
	});

	var first = initialPage();
	if (first) {
		activate(first, false);
	}
	updateSummaryDisabled();
})();
