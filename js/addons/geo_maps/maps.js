(function (_, $) {
    var handlers = {
        init: function () {
            return true;
        },
        setCenter: function (lat, lng, zoom) {
            return true;
        },
        getCenter: function () {
            return {};
        },
        removeAllMarkers: function () {
            return true;
        },
        resize: function () {
            return true;
        },
        destroy: function () {
            return true;
        },
        addMarkers: function () {
            return true;
        },
        adjustMapBoundariesToSeeAllMarkers: function () {
            return true;
        },
        exitFullscreen: function () {
            return true;
        }
    };

    var methods = {
        prepareMarkers: function (marker_selector) {
            var markers = [];
            $(marker_selector).each(function (index, marker) {
                var $marker = $(marker);
                markers.push({
                    lat: $marker.data('caGeoMapMarkerLat'),
                    lng: $marker.data('caGeoMapMarkerLng'),
                    selected: !!$marker.data('caGeoMapMarkerSelected'),
                    content: $marker.html(),
                    static: !!$marker.data('caGeoMapMarkerStatic'),
                });
            });

            return markers;
        },

        setHandlers: function (data) {
            handlers = data;
        }
    };

    $.fn.ceGeoMap = function (method) {
        if (handlers[method]) {
            return handlers[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return handlers.init.apply(this, arguments);
        } else {
            $.error('ty.geoMap: method ' + method + ' does not exist');
        }
    };

    $.ceGeoMap = function (action, data) {
        if (methods[action]) {
            return methods[action].apply(this, Array.prototype.slice.call(arguments, 1));
        } else {
            $.error('ty.geoMap: action ' + action + ' does not exist');
        }
    }
})(Tygh, Tygh.$);
