ymaps.ready(() => {

    const map = new ymaps.Map("place-map", {
        center: [59.938955, 30.315644],
        zoom: 11,
        controls: ['zoomControl'],
    });

    $('#place-coords_field, #place-radius').change(function() {
        const coords = $('#place-coords_field').val().split(', ');
        if (coords.length !== 2) {
            return;
        }

        map.geoObjects.removeAll()
        map.setCenter(coords);

        //
        const placemark = new ymaps.Placemark(coords, {}, {
            preset: 'islands#circleDotIcon',
            iconColor: '#e22e7e',
            draggable: true,
        });

        placemark.events.add('dragend', function (e) {
            const newCoords = placemark.geometry.getCoordinates();
            $('#place-coords_field').val(newCoords.join(', ')).trigger('change');
        });

        const circle = new ymaps.Circle([coords, $('#place-radius').val()]);

        map.geoObjects.add(placemark);
        map.geoObjects.add(circle);

        map.setBounds(map.geoObjects.getBounds(), {
            checkZoomRange: true,
            zoomMargin: 9
        });
    });

    $('#place-coords_field').trigger('change');
});
