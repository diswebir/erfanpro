/**
 * erfanpro section manager (manager tab).
 *
 * - Collapsible group cards; open/closed state persists in
 *   localStorage (first visit: only the first group is open).
 * - Drag-and-drop reorder inside a group (HTML5 DnD, Vanilla JS):
 *   dropping renumbers the hidden order inputs and the visible
 *   position badges, so the next save (floating AJAX save or classic
 *   submit) stores the new order. Up/down buttons stay for keyboard.
 * - Toggle switches are real checkboxes: flipping one dims the row,
 *   shows the «غیرفعال» badge, and updates the group/summary counts.
 * - Sticky summary bar: counts, jump links, and a search box that
 *   filters rows by section/page name. Without JS every group is
 *   open and the search box and drag handles are hidden by CSS, so
 *   the manager still works as plain checkboxes + submit buttons.
 * - Vanilla JS, zero external requests.
 */
(function () {
	'use strict';

	var root = document.querySelector('[data-erfanpro-sm-manager]');
	if (!root) {
		return;
	}
	root.classList.add('erfanpro-sm-js');

	var STORAGE_KEY = 'erfanpro_sections_open';
	var groups = Array.prototype.slice.call(root.querySelectorAll('[data-erfanpro-sm-group]'));
	var searchInput = root.querySelector('[data-erfanpro-sm-search]');
	var disabledCountEl = root.querySelector('[data-erfanpro-sm-disabled-count]');

	function faDigits(n) {
		return String(n).replace(/[0-9]/g, function (d) {
			return '۰۱۲۳۴۵۶۷۸۹'[+d];
		});
	}

	/* ---- collapsed state (localStorage) ---- */
	function loadOpenState() {
		try {
			var raw = window.localStorage.getItem(STORAGE_KEY);
			if (raw) {
				return JSON.parse(raw) || {};
			}
		} catch (e) { /* private mode: fall through to defaults */ }
		return null;
	}

	function saveOpenState(state) {
		try {
			window.localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
		} catch (e) { /* ignore */ }
	}

	function setGroupOpen(group, open, save) {
		group.classList.toggle('is-collapsed', !open);
		var btn = group.querySelector('[data-erfanpro-sm-toggle]');
		if (btn) {
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		}
		if (save) {
			var state = loadOpenState() || {};
			state[group.getAttribute('data-erfanpro-sm-group')] = !!open;
			saveOpenState(state);
		}
	}

	function applyStoredState() {
		var stored = loadOpenState();
		groups.forEach(function (group, i) {
			var key = group.getAttribute('data-erfanpro-sm-group');
			var open = stored ? !!stored[key] : 0 === i;
			setGroupOpen(group, open, false);
		});
	}

	groups.forEach(function (group) {
		var btn = group.querySelector('[data-erfanpro-sm-toggle]');
		if (btn) {
			btn.addEventListener('click', function () {
				setGroupOpen(group, group.classList.contains('is-collapsed'), true);
			});
		}
	});

	/* ---- counts ---- */
	function updateGroupStatus(group) {
		var rows = group.querySelectorAll('.erfanpro-sm-row');
		var on = 0;
		rows.forEach(function (row) {
			var box = row.querySelector('input[type="checkbox"]');
			if (box && box.checked) {
				on++;
			}
		});
		var status = group.querySelector('[data-erfanpro-sm-group-status]');
		if (status) {
			status.textContent = faDigits(on) + ' از ' + faDigits(rows.length) + ' فعال';
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
			var group = row.closest('[data-erfanpro-sm-group]');
			if (group) {
				updateGroupStatus(group);
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

	groups.forEach(function (group) {
		var list = group.querySelector('[data-erfanpro-sm-list]');
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

	/* ---- search / filter ---- */
	function norm(s) {
		return (s || '').replace(/\s+/g, ' ').trim().toLowerCase();
	}

	function applySearch() {
		if (!searchInput) {
			return;
		}
		var q = norm(searchInput.value);
		if (!q) {
			groups.forEach(function (group) {
				group.classList.remove('is-hidden');
				group.querySelectorAll('.erfanpro-sm-row').forEach(function (row) {
					row.classList.remove('is-hidden');
				});
			});
			applyStoredState();
			return;
		}
		groups.forEach(function (group) {
			var label = norm(group.getAttribute('data-page-label'));
			var anyVisible = false;
			group.querySelectorAll('.erfanpro-sm-row').forEach(function (row) {
				var hit = norm(row.textContent).indexOf(q) !== -1 || label.indexOf(q) !== -1;
				row.classList.toggle('is-hidden', !hit);
				if (hit) {
					anyVisible = true;
				}
			});
			group.classList.toggle('is-hidden', !anyVisible);
			if (anyVisible) {
				setGroupOpen(group, true, false);
			}
		});
	}

	if (searchInput) {
		searchInput.addEventListener('input', applySearch);
	}

	/* ---- jump links: expand the target group and scroll to it ---- */
	root.querySelectorAll('[data-erfanpro-sm-jump]').forEach(function (link) {
		link.addEventListener('click', function (ev) {
			ev.preventDefault();
			var key = link.getAttribute('data-erfanpro-sm-jump');
			var group = root.querySelector('[data-erfanpro-sm-group="' + key + '"]');
			if (!group) {
				return;
			}
			setGroupOpen(group, true, true);
			group.scrollIntoView({ behavior: 'smooth', block: 'start' });
		});
	});

	applyStoredState();
	updateSummaryDisabled();
})();
