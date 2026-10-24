/**
 * erfanpro admin: repeaters + media pickers.
 * Vanilla JS, no dependencies besides wp.media (already enqueued).
 */
(function () {
	'use strict';

	/* ---------- repeaters ---------- */

	function nextIndex(container) {
		var max = -1;
		container.querySelectorAll('[data-erfanpro-row]').forEach(function (row) {
			var i = parseInt(row.getAttribute('data-erfanpro-index') || '-1', 10);
			if (i > max) max = i;
		});
		return max + 1;
	}

	document.addEventListener('click', function (e) {
		var addBtn = e.target.closest('[data-erfanpro-add]');
		if (addBtn) {
			e.preventDefault();
			var container = addBtn.closest('[data-erfanpro-repeater]');
			if (!container) return;
			var tpl = container.querySelector('[data-erfanpro-template]');
			if (!tpl) return;
			var html = tpl.innerHTML.replace(/__i__/g, String(nextIndex(container)));
			var wrap = document.createElement('div');
			wrap.innerHTML = html;
			var row = wrap.firstElementChild;
			container.querySelector('[data-erfanpro-rows]').appendChild(row);
			return;
		}

		var rmBtn = e.target.closest('[data-erfanpro-remove]');
		if (rmBtn) {
			e.preventDefault();
			var row = rmBtn.closest('[data-erfanpro-row]');
			var container = rmBtn.closest('[data-erfanpro-repeater]');
			if (row && container) {
				// Keep at least one row so the field shape stays visible.
				if (container.querySelectorAll('[data-erfanpro-row]').length > 1) {
					row.remove();
				} else {
					row.querySelectorAll('input, textarea, select').forEach(function (el) {
						if (el.type === 'checkbox' || el.type === 'radio') { el.checked = false; }
						else { el.value = ''; }
					});
					var prev = row.querySelector('[data-erfanpro-media-preview]');
					if (prev) { prev.innerHTML = ''; prev.style.display = 'none'; }
					var hid = row.querySelector('[data-erfanpro-media-id]');
					if (hid) { hid.value = ''; }
				}
			}
			return;
		}

		/* ---------- media pickers ---------- */

		var selectBtn = e.target.closest('[data-erfanpro-media-select]');
		if (selectBtn) {
			e.preventDefault();
			var field = selectBtn.closest('[data-erfanpro-media]');
			if (!field || !window.wp || !wp.media) return;
			var multiple = field.getAttribute('data-erfanpro-media') === 'multiple';
			var frame = wp.media({
				title: selectBtn.getAttribute('data-title') || 'انتخاب',
				multiple: multiple,
				library: { type: multiple ? 'image' : undefined }
			});
			frame.on('select', function () {
				var input = field.querySelector('[data-erfanpro-media-id]');
				var preview = field.querySelector('[data-erfanpro-media-preview]');
				if (multiple) {
					var ids = [];
					var thumbs = [];
					frame.state().get('selection').forEach(function (att) {
						att = att.toJSON();
						ids.push(att.id);
						var src = (att.sizes && att.sizes.thumbnail && att.sizes.thumbnail.url) || att.url;
						thumbs.push('<img src="' + src + '" alt="" />');
					});
					input.value = ids.join(',');
					preview.innerHTML = thumbs.join('');
					preview.style.display = 'flex';
				} else {
					var att = frame.state().get('selection').first().toJSON();
					input.value = att.id;
					var src = (att.sizes && att.sizes.thumbnail && att.sizes.thumbnail.url) || att.url;
					if (att.type !== 'image') {
						preview.innerHTML = '<span class="erfanpro-file-name">' + att.filename + '</span>';
					} else {
						preview.innerHTML = '<img src="' + src + '" alt="" />';
					}
					preview.style.display = 'flex';
				}
			});
			frame.open();
			return;
		}

		var clearBtn = e.target.closest('[data-erfanpro-media-remove]');
		if (clearBtn) {
			e.preventDefault();
			var field = clearBtn.closest('[data-erfanpro-media]');
			if (!field) return;
			field.querySelector('[data-erfanpro-media-id]').value = '';
			var preview = field.querySelector('[data-erfanpro-media-preview]');
			preview.innerHTML = '';
			preview.style.display = 'none';
		}
	});
})();
