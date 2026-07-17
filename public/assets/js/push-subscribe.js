/* push-subscribe.js
 * Handles Firebase push subscription on the frontend.
 * Config values injected by PHP via window.FCM_CONFIG.
 */
(function () {
  'use strict';

  var cfg = window.FCM_CONFIG;
  if (!cfg || !cfg.apiKey || cfg.apiKey === 'REPLACE_API_KEY') return; // Not configured yet
  if (!('serviceWorker' in navigator) || !('Notification' in window)) return;
  if (!window.isSecureContext) return; // FCM requires HTTPS (or localhost)

  var TOKEN_KEY     = 'tn_push_token';
  var DISMISSED_KEY = 'tn_push_prompt_dismissed';

  /* Init Firebase */
  if (!firebase.apps.length) {
    firebase.initializeApp(cfg);
  }

  var messaging = null;
  try { messaging = firebase.messaging(); } catch (e) { return; }

  var base = window._pushBase || '';

  /* Register service worker, then wait for it to actually become active
   * before touching the messaging APIs — calling getToken() against a
   * registration that is still installing fails intermittently. */
  navigator.serviceWorker.register(base + '/firebase-messaging-sw.js')
    .then(function () { return navigator.serviceWorker.ready; })
    .then(function (reg) {
      var permission = Notification.permission;
      if (permission === 'granted') {
        getToken(reg);
      } else if (permission === 'default') {
        maybeShowPermissionBanner(reg);
      }
      // permission === 'denied' → do nothing; re-asking is a no-op and just
      // spams the console, and browsers auto-block origins that keep asking.
    })
    .catch(function (err) { console.warn('Push SW registration failed:', err); });

  function maybeShowPermissionBanner(reg) {
    if (localStorage.getItem(DISMISSED_KEY)) return;

    showPermissionBanner(
      function onAllow() {
        // requestPermission() is called from a real click handler here, so
        // it fires the native browser prompt reliably instead of being
        // silently throttled the way an on-load call would be.
        Notification.requestPermission().then(function (permission) {
          if (permission === 'granted') {
            getToken(reg);
          } else {
            localStorage.setItem(DISMISSED_KEY, '1');
          }
        });
      },
      function onDismiss() {
        localStorage.setItem(DISMISSED_KEY, '1');
      }
    );
  }

  function getToken(reg) {
    messaging.getToken({ vapidKey: cfg.vapidKey, serviceWorkerRegistration: reg })
      .then(function (token) {
        if (token) subscribe(token);
      })
      .catch(function (err) { console.warn('FCM token error:', err); });
  }

  function subscribe(token) {
    if (localStorage.getItem(TOKEN_KEY) === token) return; // already synced this device

    var district = getCookie('tn_district_id') || '';

    fetch(base + '/api/push/subscribe', {
      method:  'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body:    'token=' + encodeURIComponent(token) + '&district_id=' + encodeURIComponent(district),
    }).then(function (res) {
      if (res.ok) localStorage.setItem(TOKEN_KEY, token);
    }).catch(function () {});
  }

  function getCookie(name) {
    var v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
    return v ? decodeURIComponent(v.pop()) : '';
  }

  /* Custom opt-in banner — the native permission prompt only fires
   * reliably from within a user-gesture click handler, so we collect the
   * gesture here instead of calling requestPermission() cold on load. */
  function showPermissionBanner(onAllow, onDismiss) {
    if (document.getElementById('tn-push-banner')) return;

    var banner = document.createElement('div');
    banner.id        = 'tn-push-banner';
    banner.className = 'tn-push-banner';
    banner.innerHTML =
      '<span class="tn-push-banner-icon">🔔</span>' +
      '<div class="tn-push-banner-body">' +
        '<div class="tn-push-banner-title">உடனடி செய்தி அறிவிப்புகள்</div>' +
        '<div class="tn-push-banner-msg">முக்கிய செய்திகளை உடனுக்குடன் பெற அனுமதி வழங்குங்கள்</div>' +
        '<button type="button" class="tn-push-banner-allow">அனுமதி</button>' +
      '</div>' +
      '<button type="button" class="tn-push-banner-close" aria-label="Dismiss">✕</button>';

    document.body.appendChild(banner);
    setTimeout(function () { banner.classList.add('tn-push-banner-show'); }, 300);

    banner.querySelector('.tn-push-banner-allow').addEventListener('click', function () {
      banner.classList.remove('tn-push-banner-show');
      setTimeout(function () { banner.remove(); }, 200);
      onAllow();
    });
    banner.querySelector('.tn-push-banner-close').addEventListener('click', function () {
      banner.classList.remove('tn-push-banner-show');
      setTimeout(function () { banner.remove(); }, 200);
      onDismiss();
    });
  }

  /* In-page toast for foreground messages */
  messaging.onMessage(function (payload) {
    var n   = payload.notification || {};
    var url = (payload.data || {}).click_url || '/';
    showToast(n.title || 'Tamil News', n.body || '', url, n.image);
  });

  function showToast(title, body, url, image) {
    var existing = document.getElementById('tn-push-toast');
    if (existing) existing.remove();

    var toast = document.createElement('div');
    toast.id        = 'tn-push-toast';
    toast.className = 'tn-push-toast';
    toast.innerHTML =
      (image ? '<img src="' + image + '" class="tn-push-toast-img" alt="">' : '') +
      '<div class="tn-push-toast-body">' +
        '<div class="tn-push-toast-title">' + escHtml(title) + '</div>' +
        '<div class="tn-push-toast-msg">'   + escHtml(body)  + '</div>' +
      '</div>' +
      '<button class="tn-push-toast-close" aria-label="Close" onclick="this.parentElement.remove()">✕</button>';

    toast.addEventListener('click', function (e) {
      if (!e.target.classList.contains('tn-push-toast-close')) {
        window.open(url, '_blank');
        toast.remove();
      }
    });

    document.body.appendChild(toast);
    setTimeout(function () { toast.classList.add('tn-push-toast-show'); }, 50);
    setTimeout(function () { if (toast.parentElement) toast.remove(); }, 7000);
  }

  function escHtml(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c];
    });
  }
}());
