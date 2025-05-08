document.body.addEventListener('htmx:configRequest', function(e) {
    e.detail.parameters['_sf_context'] = document.querySelector('meta[name="sf_context"]').content;
});


const boostedEl = document.querySelector('[hx-boost=true]');
document.body.addEventListener("sitefrog:refresh", function (e) {
    htmx.ajax('GET', window.location.pathname, {target: boostedEl ? boostedEl : 'body'});
})


