self.addEventListener('install', function(event) {
  console.log('Service Worker instalado com sucesso');
});

self.addEventListener('activate', function(event) {
  console.log('Service Worker ativado com sucesso');
});

self.addEventListener('fetch', function(event) {
  console.log('Requisição interceptada:', event.request);
  event.respondWith(fetch(event.request));
});
