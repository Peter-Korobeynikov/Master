(function(_, $) {
    function fn_change_option_class() {
        var $offersImportModeSetting = $('#addon_option_rus_exim_1c_exim_1c_import_mode_offers'),
            $offersImportModeContainer = $('#container_addon_option_rus_exim_1c_exim_1c_import_mode_offers'),
            offersImportMode = $offersImportModeSetting.val(),
            $optionNameContainer = $('#container_addon_option_rus_exim_1c_exim_1c_import_option_name');

        if (offersImportMode !== 'standart' && offersImportMode !== 'standart_general_price') {
            $optionNameContainer.addClass('hidden');
        }

        if ($offersImportModeSetting.find('option').length === 1) {
            $offersImportModeContainer.addClass('hidden');
        }
    }

    $.ceEvent('on', 'ce.commoninit', function(context) {
        $('#addon_option_rus_exim_1c_exim_1c_import_mode_offers').on('change', function () {
            fn_change_option_class();
        });
    });

    function fn_toggle_offers_import_mode_selector_availability() {
        var $offersImportModeSetting = $('#addon_option_rus_exim_1c_exim_1c_import_mode_offers'),
            $offersImportModeContainer = $('#container_addon_option_rus_exim_1c_exim_1c_import_mode_offers'),
            $optionTypeContainer = $('#container_addon_option_rus_exim_1c_exim_1c_type_option'),
            $optionNameContainer = $('#container_addon_option_rus_exim_1c_exim_1c_import_option_name');

        if ($offersImportModeSetting.find('option').length === 1) {
            $offersImportModeContainer.addClass('hidden');
            $optionTypeContainer.addClass('hidden');
            $optionNameContainer.addClass('hidden');
        }
    }

    $(document).ready(function() {
        fn_change_option_class();
        fn_toggle_offers_import_mode_selector_availability();
    });
}(Tygh, Tygh.$));
