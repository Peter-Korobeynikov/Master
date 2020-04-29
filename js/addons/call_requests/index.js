(function (_, $) {
    $.ceEvent('on', 'ce.commoninit', function (context) {
        var time_elements = context.find('.cm-cr-mask-time');

        if (time_elements.length === 0) {
            return true;
        }

        time_elements.mask('99:99');
    });

    $.ceEvent('on', 'ce.formpre_call_requests_form', function (form, elm) {
        var val_email = form.find('[name="call_data[email]"]').val(),
            val_phone = form.find('[name="call_data[phone]"]').val(),
            allow = !!(val_email || val_phone),
            error_box = form.find('.cm-cr-error-box'),
            dlg = $.ceDialog('get_last');

        error_box.toggle(!allow);
        dlg.ceDialog('reload');

        if (allow) {
            var product_data = $('[name="' + form.data('caProductForm') + '"]').serializeObject();

            $.each(product_data, function (key, value) {
                if (key.match(/product_data/)) {
                    form.append('<input type="hidden" name="' + key + '" value="' + value + '" />');
                }
            });
        }

        return allow;
    });
})(Tygh, Tygh.$);
