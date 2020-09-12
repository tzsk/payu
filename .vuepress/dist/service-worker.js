/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js");

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [
  {
    "url": "404.html",
    "revision": "fed316358d1d9b0e014b51ba152c47e4"
  },
  {
    "url": "5.x/changes.html",
    "revision": "50c45584deb19d44ae73c17aefd9a31d"
  },
  {
    "url": "5.x/configuration.html",
    "revision": "295308c65b6679d79a7ade6dfb613a4c"
  },
  {
    "url": "5.x/features/concerns.html",
    "revision": "28b9e3983f146d01d23e0fa90fbb1716"
  },
  {
    "url": "5.x/features/events.html",
    "revision": "3ebba6027770ee9e32f0fcee2506b79f"
  },
  {
    "url": "5.x/features/gateways.html",
    "revision": "711d07d73a45c8eaeaec8f04f918a310"
  },
  {
    "url": "5.x/features/index.html",
    "revision": "ec39838bf17088217f3a7dcc488afc11"
  },
  {
    "url": "5.x/features/payment.html",
    "revision": "c78b7d2de6c16df77b7b99cec7f82e5d"
  },
  {
    "url": "5.x/features/relationship.html",
    "revision": "77a4957808f7bbcf517722259d73957e"
  },
  {
    "url": "5.x/features/verification.html",
    "revision": "57e0fa78c27f14eccc4f42a913a7d83d"
  },
  {
    "url": "5.x/index.html",
    "revision": "66de7d8a3fdae6442c280addbf9e7aba"
  },
  {
    "url": "5.x/installation.html",
    "revision": "1c0203f8a4b8467d788999232c62f62a"
  },
  {
    "url": "5.x/introduction.html",
    "revision": "39795c9255536f8d480468b7af0cfda2"
  },
  {
    "url": "5.x/upgrade.html",
    "revision": "db056845ea4f8768ae84a1be77b8f506"
  },
  {
    "url": "assets/css/0.styles.6a0f084f.css",
    "revision": "cdc0de92051e997868b0bf374bb28caa"
  },
  {
    "url": "assets/img/search.83621669.svg",
    "revision": "83621669651b9a3d4bf64d1a670ad856"
  },
  {
    "url": "assets/js/10.28f61c60.js",
    "revision": "be3bd472971127e1bb018c1567a95c58"
  },
  {
    "url": "assets/js/11.b7dd8238.js",
    "revision": "9445b6cf3fbac17459d45730188930ef"
  },
  {
    "url": "assets/js/12.2d177dd1.js",
    "revision": "5ee87f9e6be66f1974f9f44aea056017"
  },
  {
    "url": "assets/js/13.5cb19351.js",
    "revision": "0e65c98fdaebd5ddf9e237e8e4bc7cbd"
  },
  {
    "url": "assets/js/14.5b377573.js",
    "revision": "4cc1d2933ecebd9ca5f954d047fab5a7"
  },
  {
    "url": "assets/js/15.a0535b08.js",
    "revision": "500809195172a835f2b19a43621b03d6"
  },
  {
    "url": "assets/js/16.bf1b0a62.js",
    "revision": "c192a9d103646303401769282978e6a4"
  },
  {
    "url": "assets/js/17.cd211b35.js",
    "revision": "9195c0aecfb04b33fbb357e680aedc1d"
  },
  {
    "url": "assets/js/18.17ac4854.js",
    "revision": "5c3b0f526c8373ef143bb9b1a3eba17d"
  },
  {
    "url": "assets/js/19.e150e3bd.js",
    "revision": "75956c9a3fa7beb9f3d16c6a7bc83726"
  },
  {
    "url": "assets/js/2.aa980102.js",
    "revision": "74379a109a4c4f56c7e558d38990949f"
  },
  {
    "url": "assets/js/20.7f897dcd.js",
    "revision": "03d850a038d4505f547e43fe952ede38"
  },
  {
    "url": "assets/js/3.99db4cda.js",
    "revision": "71845694d4c390b9944fe81f562048de"
  },
  {
    "url": "assets/js/4.f1dadef0.js",
    "revision": "443d057749a8e8f1085eeabd7717860a"
  },
  {
    "url": "assets/js/5.282f3f21.js",
    "revision": "2ec411a179c512bc90140ad96e910e05"
  },
  {
    "url": "assets/js/6.3be36d8b.js",
    "revision": "cf3d7b31fb21a5b996b32154b27bf17a"
  },
  {
    "url": "assets/js/7.5e2090ef.js",
    "revision": "860f46eccc573a20c621670c8147bc0d"
  },
  {
    "url": "assets/js/8.f1f3d1eb.js",
    "revision": "18f47f0101959ac69df2fef07a8fd349"
  },
  {
    "url": "assets/js/9.16d29d1b.js",
    "revision": "677feca90a4316d11b03c8c65547cc5a"
  },
  {
    "url": "assets/js/app.706ebfa5.js",
    "revision": "7c93e245871e84f5fbbbfbc0d328c64b"
  },
  {
    "url": "index.html",
    "revision": "d06f1f8df419637a5d7f87982c22a1e3"
  }
].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});
addEventListener('message', event => {
  const replyPort = event.ports[0]
  const message = event.data
  if (replyPort && message && message.type === 'skip-waiting') {
    event.waitUntil(
      self.skipWaiting().then(
        () => replyPort.postMessage({ error: null }),
        error => replyPort.postMessage({ error })
      )
    )
  }
})
