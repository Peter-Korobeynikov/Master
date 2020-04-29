(function(_, $) {
    $(function() {
        var tab = $('#dashboard-shipping');
        $('a[data-toggle="tab"]', tab).on('shown', function(e) {
            localStorage.setItem('tab-active', $(e.target).attr('href'));
        });

        var lastTab = localStorage.getItem('tab-active');
        if (lastTab) {
            $('a[href='+lastTab+']', tab).click();
        }

        tab.find('.cm-item, .cm-check-items').on('change', function () {
            $('#delete_rate_values').toggleClass(
                'hidden',
                tab.find('.cm-item:checked').length == 0
            );
        });
    });
}(Tygh, Tygh.$));