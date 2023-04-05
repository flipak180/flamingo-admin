ymaps.ready(function () {
    console.log(123);

    new ymaps.Map('coords-map', {
        zoom: 17,
        center: [59.938955, 30.315644],
        controls: ['fullscreenControl', 'zoomControl'],
    });
});
