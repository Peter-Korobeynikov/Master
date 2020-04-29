function geoip_show_popup() {
    var _e = $('#opener_geoip_show_automatic_popup'),
        params = $.ceDialog('get_params', _e),
        id = _e.data('caTargetId');
    $('#' + id).ceDialog('open', params);
    $('#' + id).closest('.ui-dialog').find('.ui-dialog-titlebar-close').css('outline-style', 'none');
    return false;
}

function open_default_geoip_popup() {
    var _e = $('#opener_geoip_show_automatic_popup');
    var params = $.ceDialog('get_params', _e);
    $('#' + _e.data('caTargetId')).ceDialog('close');

    $('.ty-my-location-link-auto').click();

    return false;
}

