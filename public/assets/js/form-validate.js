/* form-validate.js — shared client-side validation for forms marked data-validate.
   Required fields, email/tel format, and live duplicate checks via data-check-duplicate. */
'use strict';

var FormValidate = (function () {

  var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var PHONE_RE = /^[6-9]\d{9}$/;

  function normalizePhone(v) {
    var digits = v.replace(/\D/g, '');
    // Only strip a leading "91" country code on a full 12-digit 91+10digit
    // number — a plain 10-digit number starting with "91" (e.g. 9123456789)
    // must NOT be truncated to 8 digits.
    if (digits.length === 12 && digits.slice(0, 2) === '91') {
      digits = digits.slice(2);
    }
    return digits;
  }

  function fieldWrap(input) {
    return input.closest('.fv-field') || input.parentElement;
  }

  function showError(input, msg) {
    clearError(input);
    input.classList.add('is-invalid');
    var el = document.createElement('div');
    el.className = 'fv-error';
    el.textContent = msg;
    fieldWrap(input).appendChild(el);
  }

  function clearError(input) {
    input.classList.remove('is-invalid');
    var wrap = fieldWrap(input);
    var existing = wrap.querySelector(':scope > .fv-error');
    if (existing) existing.remove();
  }

  function validateField(input) {
    var val = (input.value || '').trim();

    if (input.hasAttribute('required') && !val) {
      showError(input, 'இந்த புலம் அவசியம்.');
      return false;
    }
    if (!val) { clearError(input); return true; }

    if (input.type === 'email' && !EMAIL_RE.test(val)) {
      showError(input, 'சரியான மின்னஞ்சல் முகவரியை உள்ளிடவும்.');
      return false;
    }
    if (input.type === 'tel' && !PHONE_RE.test(normalizePhone(val))) {
      showError(input, 'சரியான 10-இலக்க மொபைல் எண்ணை உள்ளிடவும்.');
      return false;
    }

    clearError(input);
    return true;
  }

  function checkDuplicate(input) {
    var url = input.dataset.checkUrl;
    var field = input.dataset.checkDuplicate;
    if (!url || !field) return;
    var val = (input.value || '').trim();
    if (!val || !validateField(input)) return;

    var excludeId = input.dataset.excludeId || '0';
    var qs = 'field=' + encodeURIComponent(field)
      + '&value=' + encodeURIComponent(val)
      + '&exclude_id=' + encodeURIComponent(excludeId);

    input.classList.add('fv-checking');
    fetch(url + '?' + qs, { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (d) {
        input.classList.remove('fv-checking');
        if (d && d.exists) {
          var label = field === 'contact_phone' ? 'மொபைல் எண்' : 'மின்னஞ்சல்';
          showError(input, 'இந்த ' + label + ' ஏற்கனவே பதிவு செய்யப்பட்டுள்ளது.');
        }
      })
      .catch(function () { input.classList.remove('fv-checking'); });
  }

  function init(form) {
    if (!form || form.dataset.fvInit) return;
    form.dataset.fvInit = '1';

    var fields = form.querySelectorAll('[required], input[type=email], input[type=tel], [data-check-duplicate]');

    fields.forEach(function (input) {
      input.addEventListener('blur', function () {
        validateField(input);
        if (input.dataset.checkDuplicate) checkDuplicate(input);
      });
      input.addEventListener('input', function () { clearError(input); });
    });

    form.addEventListener('submit', function (e) {
      var ok = true;
      fields.forEach(function (input) { if (!validateField(input)) ok = false; });
      if (!ok) {
        e.preventDefault();
        e.stopPropagation();
        var firstInvalid = form.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-validate]').forEach(init);
  });

  return { init: init, validateField: validateField };
}());
