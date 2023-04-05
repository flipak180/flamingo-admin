$(function () {

    /**
     *
     * @param input
     * @constructor
     */
    function Widget(input) {
        this.input = input;
        this.root = input.parent();
        const coords = this.input.val().split(', ');
        this.coords = [coords[0] || 55.76, coords[1] || 37.64];
        this.map = this.createMap();
        this.placemark = this.createPlacemark();
        this.circle = this.addCircle();
    }

    /**
     *
     * @returns {ymaps.Map}
     */
    Widget.prototype.createMap = function () {
        const mapElId = this.root.parent().find('.ymaps-map').attr('id');
        const options = this.input.data('map');
        options.center = this.coords;
        return new ymaps.Map(mapElId, options);
    };

    /**
     *
     * @returns {ymaps.Placemark}
     */
    Widget.prototype.createPlacemark = function () {
        const placemark = new ymaps.Placemark(
            this.coords,
            this.root.data('pm-prop'),
            this.root.data('pm-opt')
        );
        this.map.geoObjects.add(placemark);
        return placemark;
    };

    /**
     *
     * @returns {ymaps.Circle}
     */
    Widget.prototype.addCircle = function () {
        const radius = document.getElementById('place-radius').value || 100;
        const circle = new ymaps.Circle([this.coords, radius], {}, {
            geodesic: true
        });
        this.map.geoObjects.add(circle);
        return circle;
    };

    /**
     *
     */
    ymaps.ready(function () {
        $('.coords-input').each(function () {
            $(this).data('coords-input', new Widget($(this)));
        });
    });

});
