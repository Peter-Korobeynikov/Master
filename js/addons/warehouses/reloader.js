(function (_, $) {
    $.ceEvent('on', 'ce:geomap:location_set_after', function (location, $container, response, auto_detect) {
        if (auto_detect) {
            return;
        }

        $.ceAjax('request', _.current_url, {
            result_ids: _.container,
            full_render: true
        });
    });
})(Tygh, Tygh.$);
