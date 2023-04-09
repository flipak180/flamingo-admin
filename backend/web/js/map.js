if (typeof ymaps !== 'undefined') {
    ymaps.ready(() => {

        const map = new ymaps.Map('place-map', {
            center: [59.938955, 30.315644],
            zoom: 11,
            controls: ['zoomControl', 'searchControl'],
        });

        // // Создадим экземпляр элемента управления «поиск по карте»
        // // с установленной опцией провайдера данных для поиска по организациям.
        // const searchControl = new ymaps.control.SearchControl({
        //     options: {
        //         provider: 'yandex#search'
        //     }
        // });
        //
        // map.controls.add(searchControl);
        //
        // // Программно выполним поиск определённых кафе в текущей
        // // прямоугольной области карты.
        // searchControl.search('Шоколадница');

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
            if (!Array.isArray(geometry) || !geometry.length || !geometry[0].length) {
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

        let geocodePlacemark;
        $('#place-title').change(function() {
            ymaps.geocode('Санкт-Петербург, ' + $(this).val(), {
                results: 1
            }).then(function (res) {
                // Выбираем первый результат геокодирования.
                const firstGeoObject = res.geoObjects.get(0),
                    coords = firstGeoObject.geometry.getCoordinates();

                if (!geocodePlacemark) {
                    geocodePlacemark = new ymaps.Placemark(coords, {}, {
                        preset: 'islands#violetStretchyIcon'
                    });

                    map.geoObjects.add(geocodePlacemark);
                } else {
                    geocodePlacemark.geometry.setCoordinates(coords);
                }

                map.setBounds(geocodePlacemark.geometry.getBounds(), {
                    //checkZoomRange: true,
                    zoomMargin: 10
                });
                map.setZoom(16);
            });
        });

    });
}

