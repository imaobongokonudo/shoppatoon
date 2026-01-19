/**
 * Service Worker Registration
 */
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/wp-content/plugins/shoppaton-store/assets/js/service-worker.js')
            .then(function(registration) {
                console.log('Shoppaton SW registered:', registration.scope);
            })
            .catch(function(error) {
                console.log('Shoppaton SW registration failed:', error);
            });
    });
}
