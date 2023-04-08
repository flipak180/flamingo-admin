if (typeof ymaps !== 'undefined') {
    ymaps.ready(() => {

        const map = new ymaps.Map('place-map', {
            center: [59.938955, 30.315644],
            zoom: 11,
            controls: ['zoomControl'],
        });

        const location = $('#place-location_field').val();
        const points = location ? JSON.parse(location) : [];

        const polygon = new ymaps.Polygon(points, {}, {
            editorDrawingCursor: 'crosshair',
            editorMaxPoints: 5,
            fillColor: '#e05baa',
            fillOpacity: 0.4,
            strokeColor: '#212529',
            strokeWidth: 1
        });
        map.geoObjects.add(polygon);

        polygon.events.add('geometrychange', function () {
            const geometry = polygon.geometry.getCoordinates();
            if (!Array.isArray(geometry) || !geometry.length) {
                return;
            }

            $('#place-location_field').val(JSON.stringify(geometry));
        });

        //
        const polygonEditBtn = new ymaps.control.Button({
            data: {
                content: 'Редактировать',
                image: '/admin/images/icon-edit.png',
            },
            options: {
                // Поскольку кнопка будет менять вид в зависимости от размера карты,
                // зададим ей три разных значения maxWidth в массиве.
                maxWidth: [28, 150, 178]
            }
        });

        map.controls.add(polygonEditBtn, {float: 'right'});

        polygonEditBtn.events.add("click", function () {
            if (!polygonEditBtn.isSelected()) {
                polygon.editor.startEditing();
            } else {
                polygon.editor.stopEditing();
            }
        });

        //
        if (!location) {
            polygon.editor.startDrawing();
        }

        if (map.geoObjects.getBounds()) {
            map.setBounds(map.geoObjects.getBounds(), {
                checkZoomRange: true,
                zoomMargin: 10
            });
        }
    });
}

