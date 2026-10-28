// Contact form (static site): sends via FormSubmit.co to the company inbox.
// First submission triggers a one-time activation email to mmiengpte@gmail.com - click the confirm link in it.
(function () {
  'use strict';

  var TO_EMAIL = 'mmiengpte@gmail.com';
  var WHATSAPP = 'https://wa.me/6585878190';
  var form = document.getElementById('contactForm');
  var alertBox = document.getElementById('formAlert');
  var btn = document.getElementById('contactBtn');

  function show(type, msg) {
    alertBox.innerHTML =
      '<div class="alert alert-' + type + ' alert-dismissible fade show rounded-3 border-0 shadow-sm">' +
      '<i class="fas ' + (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') + ' me-2"></i>' + msg +
      '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var name = form.name.value.trim();
    var email = form.email.value.trim();
    var message = form.message.value.trim();

    if (!name || !email || !message) return show('danger', 'All fields are required.');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return show('danger', 'Please enter a valid email address.');
    if (form._honey.value) return; // bot trap

    btn.disabled = true;
    var old = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

    fetch('https://formsubmit.co/ajax/' + TO_EMAIL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({
        name: name, email: email, message: message,
        _subject: 'New Contact Message from ' + name,
        _replyto: email, _template: 'table', _captcha: 'false'
      })
    })
      .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
      .then(function (res) {
        if (res.ok && String(res.j.success) !== 'false') {
          show('success', 'Thank you for your message! We will get back to you soon.');
          form.reset();
        } else {
          throw new Error('send failed');
        }
      })
      .catch(function () {
        show('danger', 'Sorry, we could not send your message. Please <a href="' + WHATSAPP + '" target="_blank" rel="noopener">message us on WhatsApp</a> or email ' + TO_EMAIL + '.');
      })
      .finally(function () { btn.disabled = false; btn.innerHTML = old; });
  });
})();
