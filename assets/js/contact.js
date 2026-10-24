/* Phase 9: contact form AJAX submission.
 * Posts the form (action erfanpro_contact_form + nonce from the form
 * itself) to admin-ajax.php; without JS the form falls back to its
 * admin-post.php action. No external requests. */
(function () {
	'use strict';
	var form = document.querySelector('[data-erfanpro-contact-form]');
	if (!form || typeof window.erfanproContact === 'undefined') return;
	var status = form.parentElement.querySelector('[data-erfanpro-contact-status]');
	var submit = form.querySelector('[data-erfanpro-contact-submit]');

	function show(message, ok) {
		if (!status) return;
		status.classList.remove('hidden');
		status.classList.remove('border-emerald-200', 'bg-emerald-50', 'text-emerald-800');
		status.classList.remove('border-red-200', 'bg-red-50', 'text-red-800');
		if (ok) {
			status.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-800');
		} else {
			status.classList.add('border-red-200', 'bg-red-50', 'text-red-800');
		}
		status.textContent = message;
	}

	form.addEventListener('submit', function (ev) {
		ev.preventDefault();
		var data = new FormData(form);
		if (submit) submit.disabled = true;
		fetch(window.erfanproContact.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		}).then(function (res) {
			return res.json();
		}).then(function (json) {
			if (json && json.success) {
				show(json.data.message, true);
				form.reset();
			} else {
				show(json && json.data && json.data.message ? json.data.message : window.erfanproContact.error, false);
			}
		}).catch(function () {
			show(window.erfanproContact.error, false);
		}).finally(function () {
			if (submit) submit.disabled = false;
		});
	});
})();
