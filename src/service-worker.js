// src/service-worker.js
importScripts('https://unpkg.com/workbox-sw@2.0.3/build/importScripts/workbox-sw.prod.v2.0.3.js');

const workboxSW = new self.WorkboxSW({
"cacheId": "manga-zero",
"skipWaiting": true
});

// ↓ ここに globPattern にマッチしたファイルがセットされる
workboxSW.precache([]);

workboxSW.router.registerRoute('/', workboxSW.strategies.networkFirst({
"cacheName": "page",
"cacheExpiration": {
"maxAgeSeconds": 86400
}
}), 'GET');
workboxSW.router.registerRoute(/\/app\/api\/.+/, workboxSW.strategies.networkFirst({
"cacheName": "api",
"cacheExpiration": {
"maxAgeSeconds": 86400
}
}), 'GET');
workboxSW.router.registerRoute(/\.(png|svg|woff|ttf|eot)/, workboxSW.strategies.cacheFirst({
"cacheName": "assets",
"cacheExpiration": {
"maxAgeSeconds": 1209600
}
}), 'GET');

workboxSW.router.registerRoute(/^https:\/\/nissan\.t4u\.bz\/nlp\/app\/upfiles\/.*\.(jpeg|jpg|png)/, workboxSW.strategies.cacheFirst({
"cacheName": "image-thumbnail",
"cacheExpiration": {
"maxEntries": 80,
"maxAgeSeconds": 86400
}
}), 'GET');
workboxSW.router.registerRoute(/^https:\/\/nissan\.t4u\.bz\/nlp\/app\/upfiles\/.*\.(jpeg|jpg|png)/, workboxSW.strategies.cacheFirst({
"cacheName": "image-banner",
"cacheExpiration": {
"maxEntries": 20,
"maxAgeSeconds": 604800
}
}), 'GET');