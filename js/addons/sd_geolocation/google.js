(function(_, $) {

    $(document).on('click', 'button[class*="ui-dialog-titlebar-close"]', function() {
        var geolocation_link = $('a[onclick*=saveLocation]').first();
        if (geolocation_link.length > 0 && geolocation_link.hasClass('hidden')) {
            var url = fn_query_remove(_.current_url, 'features_hash');
            window.location = fn_url('geolocation.my_location?return_url=' + encodeURIComponent(url));
        }
    });

    (function($) {

        var maps = [];
        var markers = [];
        var map_data = [];
        var map_bounds = [];
        var maps_params = [];
        var search_boxes = [];

        function updatePoint(point, map_cont) {

            if (maps[map_cont]['saved_point'] && markers[map_cont]['marker']) {
                markers[map_cont]['marker'].setMap(null);
            }

            markers[map_cont]['marker'] = new google.maps.Marker({
                position: point,
                map: maps[map_cont]
            });

            markers[map_cont]['marker'].setMap(maps[map_cont]);
            maps[map_cont]['saved_point'] = point;
        }

        function addMapListeners(map_cont) {
            google.maps.event.addListener(maps[map_cont], 'click', function(event) {
                updatePoint(event.latLng, map_cont);
                if(maps[map_cont]['autosave']) {
                    $.ceSDMap('saveLocationWithoutNotifications', map_cont);
                }
            });

            if(maps[map_cont]['autosave']) {
                google.maps.event.addListener(maps[map_cont], 'tilesloaded', function(event) {
                    $.ceSDMap('saveLocationWithoutNotifications', map_cont);
                });
            }
        }

        var methods = {

            init: function(options, callback) {

                if (!('google' in window)) {
                    var key_api = '';
                    if (typeof(_.google_api_key) !== 'undefined' && _.google_api_key) {
                        key_api = '&key=' + _.google_api_key;
                    }
                    $.getScript('//www.google.com/jsapi', function() {
                        setTimeout(function() { // do not remove it - otherwise it will be slow in ff
                            google.load('maps', '3.0', {
                                other_params: "libraries=places&language=" + options.language + key_api,
                                callback: function() {
                                    $.ceSDMap('init', options, callback);
                                }
                            });
                        }, 0);
                    });

                    return false;
                }

                var map_cont = options.map_container;
                map_bounds[map_cont] = false;
                markers[map_cont] = {
                    marker: false,
                    markers: []
                };

                map_data[map_cont] = [];
                if (typeof(options.storeData) !== 'undefined') {
                    map_data[map_cont] = options.storeData;
                }

                if (typeof(options.search_input) !== 'undefined') {
                    if (!search_boxes[options.search_input])
                        search_boxes[options.search_input] = [];

                    search_boxes[options.search_input]['input'] = document.getElementById(options.search_input);
                    search_boxes[options.search_input]['data'] = new google.maps.places.Autocomplete(search_boxes[options.search_input]['input']);
                }

                // Required fields - zoom, mapTypeId, center
                maps_params[map_cont] = {
                    zoomControl: true,
                    scaleControl: true,
                    streetViewControl: false,
                    mapTypeControl: false,
                    zoom: options.zoom,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    center: new google.maps.LatLng(options.latitude, options.longitude)
                };

                var area = _.area;
                if (typeof(options.area) !== 'undefined') {
                    area = options.area;
                }
                if (area == 'A') {
                    $.extend(maps_params[map_cont], {
                        draggableCursor: 'crosshair',
                        draggingCursor: 'pointer'
                    });
                }

                $.extend(maps_params[map_cont], {
                    zoomControl: options.zoom_control,
                    mapTypeControl: options.map_type_control,
                    scaleControl: options.scale_control,
                    autosave: options.autosave
                });

                if (typeof(callback) === 'function') {
                    callback();
                }
            },

            showDialog: function(target_id) {

                var latitude = $('#elm_latitude_hidden').val(),
                    longitude = $('#elm_longitude_hidden').val();

                var target = $('#' + target_id);
                if (target.text()) {

                    var map_cont = 'map_canvas_' + target_id;
                    target.ceDialog('open', {
                        href: '',
                        keepInPlace: true,
                        dragOptimize: true
                    });

                    maps[map_cont]['saved_point'] = null;
                    markers[map_cont]['markers'] = [];

                    $.extend(maps_params[map_cont], {
                        center: new google.maps.LatLng(latitude, longitude)
                    });

                    maps[map_cont] = new google.maps.Map(document.getElementById(map_cont), maps_params[map_cont]);

                    updatePoint(maps_params[map_cont]['center'], map_cont);
                    addMapListeners(map_cont);
                } else {
                    target.ceDialog('open', {
                        height: 'auto',
                        href: fn_url('geolocation.user_location?latitude=' + latitude + '&longitude=' + longitude),
                        keepInPlace: true,
                        nonClosable: false,
                        scroll: '',
                        width: 'auto'
                    });
                }
            },

            show: function(options) {

                var map_cont = options.map_container;

                if (!maps_params[map_cont]) {
                    return $.ceSDMap('init', options, function() {
                        $.ceSDMap('show', options);
                    });
                }

                maps[map_cont] = new google.maps.Map(document.getElementById(map_cont), maps_params[map_cont]);

                map_bounds[map_cont] = new google.maps.LatLngBounds();
                markers[map_cont]['markers'] = [];
                var infoWindows = [];

                var marker;
                var i = 0;
                var storeData = map_data[map_cont];

                for (var keyvar = 0; keyvar < storeData.length; keyvar++) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(storeData[keyvar]['latitude'], storeData[keyvar]['longitude']),
                        map: maps[map_cont],
                        infoWindowIndex: i
                    });

                    marker.setMap(maps[map_cont]);
                    map_bounds[map_cont].extend(marker.position);

                    //balloon content collecting
                    var marker_html = '<div style="padding-right: 10px"><strong>' + storeData[keyvar]['name'] + '</strong><\/div>';

                    var infowindow = new google.maps.InfoWindow({
                        content: marker_html
                    });

                    google.maps.event.addListener(marker, 'click',
                        function(event) {
                            maps[map_cont].panTo(event.latLng);
                            infoWindows[this.infoWindowIndex].open(maps[map_cont], this);
                        }
                    );

                    infoWindows.push(infowindow);
                    markers[map_cont]['markers'].push(marker);
                    i++;
                }

                if (storeData.length == 1) {
                    maps[map_cont].setCenter(marker.getPosition());
                    maps[map_cont].setZoom(maps_params[map_cont]['zoom']);
                } else {
                    maps[map_cont].fitBounds(map_bounds[map_cont]);
                }
            },

            saveLocation: function(map_cont, fields) {
                if (maps[map_cont]['saved_point']) {
                    var lat = maps[map_cont]['saved_point'].lat(),
                        lng = maps[map_cont]['saved_point'].lng();
                    if (fields) {
                        $('#elm_latitude').val(lat);
                        $('#elm_latitude_hidden').val(lat);
                        $('#elm_longitude').val(lng);
                        $('#elm_longitude_hidden').val(lng);
                    } else {
                        var url = fn_query_remove(_.current_url, 'features_hash');
                        window.location = fn_url('geolocation.change_location?latitude=' + lat +
                                '&longitude=' + lng +
                                '&return_url=' + encodeURIComponent(url));
                    }
                }
                maps[map_cont]['saved_point'] = null;
            },

            saveLocationWithoutNotifications: function(map_cont) {
                if (maps[map_cont]['saved_point']) {
                    var lat = maps[map_cont]['saved_point'].lat(),
                        lng = maps[map_cont]['saved_point'].lng(),
                        url = fn_query_remove(_.current_url, 'features_hash');

                    $.ceAjax('request', fn_url('geolocation.change_location'), {
                        method: 'get',
                        data: {
                            latitude: lat,
                            longitude: lng,
                            hide_notifications: true,
                            return_url: encodeURIComponent(url)
                        }
                    });
                }
            },

            showLocation: function(options) {

                var map_cont = options.map_container;

                if (!maps_params[map_cont]) {
                    return $.ceSDMap('init', options, function() {
                        $.ceSDMap('showLocation', options);
                    });
                }

                maps[map_cont] = new google.maps.Map(document.getElementById(options.map_container), maps_params[map_cont]);
                var map_center = new google.maps.LatLng(options.latitude, options.longitude);
                maps[map_cont].setCenter(map_center);
                updatePoint(map_center, map_cont);
                addMapListeners(map_cont);

                if (typeof(options.search_input) !== 'undefined') {
                    var search_input = options.search_input;
                    $(search_boxes[search_input]['input']).on('change', function() {
                        setTimeout(function() {
                            var place = search_boxes[search_input]['data'].getPlace();

                            if (typeof(place) !== 'undefined' && typeof(place.geometry) !== 'undefined') {
                                map_center = place.geometry.location;
                                maps[map_cont].setCenter(map_center);

                                setTimeout(function() {
                                    updatePoint(map_center, map_cont, true);
                                }, 100);
                                addMapListeners(map_cont);
                            }
                        }, 500);
                    });
                }
            }
        };

        $.extend({
            ceSDMap: function(method) {
                if (methods[method]) {
                    return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
                } else {
                    $.error('ty.sdmap: method ' + method + ' does not exist');
                }
            }
        });

    })($);

}(Tygh, Tygh.$));