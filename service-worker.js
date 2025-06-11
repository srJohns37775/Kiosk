self.addEventListener("install", event => {
  event.waitUntil(
    caches.open("kiosco-sonic-v1").then(cache => {
      return cache.addAll([
        "/",
        "index.html",
        "css/styles.css",
        "js/form_utils.js",
        "js/particles_sonic.js",
        "manifest.json",
        "img/icon-192.png",
        "img/icon-512.png"
        
      ]);
    })
  );
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(resp => {
      return resp || fetch(event.request);
    })
  );
});
