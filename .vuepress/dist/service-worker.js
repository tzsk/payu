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
    "revision": "3a5a9bd464c1cac1797978c3927d82a6"
  },
  {
    "url": "5.x/changes.html",
    "revision": "7fadccfc2b8b0ab559d41ca6d862001c"
  },
  {
    "url": "5.x/configuration.html",
    "revision": "5919a4cbf3b1f824e0192c497601b990"
  },
  {
    "url": "5.x/features/concerns.html",
    "revision": "381e550544fa55e94947768c78829af1"
  },
  {
    "url": "5.x/features/events.html",
    "revision": "3c78d379215404cc5af5a31147cac788"
  },
  {
    "url": "5.x/features/gateways.html",
    "revision": "899b0646906080ecee04079b80cf7dc0"
  },
  {
    "url": "5.x/features/index.html",
    "revision": "02ed6ee799993b23622f0e866ea54474"
  },
  {
    "url": "5.x/features/payment.html",
    "revision": "fd03394295ad346871d9949ed2e6677f"
  },
  {
    "url": "5.x/features/relationship.html",
    "revision": "1435c1bcf148beb170cbb766e3126367"
  },
  {
    "url": "5.x/features/verification.html",
    "revision": "a4213ecd98c45e7ae2c1e52702f6101c"
  },
  {
    "url": "5.x/index.html",
    "revision": "6512a0385f549b36794703f0800aef10"
  },
  {
    "url": "5.x/installation.html",
    "revision": "08a2da880384ef44525203d0c724fbb1"
  },
  {
    "url": "5.x/introduction.html",
    "revision": "224a12d65a21d15d8cf978bf2694ed6f"
  },
  {
    "url": "5.x/upgrade.html",
    "revision": "310986d94e18748b345d71af54f640c2"
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
    "url": "assets/js/10.0fe1cc36.js",
    "revision": "33f7198ac83260ffc3a639a0d8d19ad2"
  },
  {
    "url": "assets/js/11.ebff88a0.js",
    "revision": "d3d5dcb215ae748db91bb3d55313dbbe"
  },
  {
    "url": "assets/js/12.7ff24f59.js",
    "revision": "acc9d8876098d755ec31158690cde07f"
  },
  {
    "url": "assets/js/13.e8c7340b.js",
    "revision": "03532b5dfeff9c85cb9e23ccbe2b0a8a"
  },
  {
    "url": "assets/js/14.f024c311.js",
    "revision": "4b021696914217a60f34cb8cbc14e8bf"
  },
  {
    "url": "assets/js/15.9976b781.js",
    "revision": "4d74b346e9cfb0365570c9c98127ac02"
  },
  {
    "url": "assets/js/16.3a3d4fb9.js",
    "revision": "504bfeb713a9f42614c239676270ab39"
  },
  {
    "url": "assets/js/17.69ffe615.js",
    "revision": "8850cc497e52e2f94ce1e2872cf12afe"
  },
  {
    "url": "assets/js/18.7489eddf.js",
    "revision": "60c219643713f86bc62fff0af30cda4e"
  },
  {
    "url": "assets/js/19.b210a936.js",
    "revision": "b68e6be49dffef1d6a4af72ba47e13a2"
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
    "url": "assets/js/6.d23d38f8.js",
    "revision": "c6fb51d7a862b75062ce237e3dbf0a50"
  },
  {
    "url": "assets/js/7.5e1b20c3.js",
    "revision": "870a047ff852b3a8b67856a7885e1d06"
  },
  {
    "url": "assets/js/8.f1f3d1eb.js",
    "revision": "18f47f0101959ac69df2fef07a8fd349"
  },
  {
    "url": "assets/js/9.aa048016.js",
    "revision": "48a19c27236f1276b36108c2d30f4f72"
  },
  {
    "url": "assets/js/app.66224966.js",
    "revision": "340803ab529d6efef4c3d95f9711cab3"
  },
  {
    "url": "index.html",
    "revision": "8e09bd97959866e8ceb61555f6a9cfc5"
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
