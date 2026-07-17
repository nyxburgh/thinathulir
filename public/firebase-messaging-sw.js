/* firebase-messaging-sw.js
 * Service Worker for Firebase Cloud Messaging background push.
 * Replace PLACEHOLDER values with your Firebase config after project creation.
 */

importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey:            'AIzaSyBWdNNZwAjHVI6-eCHqT2G3uzHePRyVvnc',
  authDomain:        'thinathulir-2026.firebaseapp.com',
  projectId:         'thinathulir-2026',
  storageBucket:     'thinathulir-2026.firebasestorage.app',
  messagingSenderId: '715840049923',
  appId:             '1:715840049923:web:e4a79e1eaa938a3f730f36',
});

const messaging = firebase.messaging();

/* Background message handler */
messaging.onBackgroundMessage(function (payload) {
  const n    = payload.notification || {};
  const data = payload.data         || {};

  // Paths are resolved relative to this script's own location (not the
  // domain root) so they still work when the site is deployed under a
  // subdirectory, e.g. /thinathulir/public/firebase-messaging-sw.js.
  const iconUrl = new URL('assets/img/logo-192.png', self.location).href;

  const notifOptions = {
    body:    n.body  || data.body  || '',
    icon:    n.icon  || iconUrl,
    image:   n.image || data.image || undefined,
    badge:   iconUrl,
    data:    { click_url: data.click_url || n.click_action || '/' },
    actions: [{ action: 'open', title: 'Read More' }],
  };

  return self.registration.showNotification(n.title || data.title || 'Tamil News', notifOptions);
});

/* Click handler */
self.addEventListener('notificationclick', function (event) {
  event.notification.close();
  const url = event.notification.data?.click_url || '/';
  event.waitUntil(clients.openWindow(url));
});
