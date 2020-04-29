<script>
    (function(_, $) {
        $.extend(_, {
            ab__stt: {
                settings: {$addons.ab__scroll_to_top|json_encode nofilter},
                units: '{$addons.ab__scroll_to_top.units|default:'px'}',
                transition: {intval($addons.ab__scroll_to_top.transition|default:600)},
            }
        });
    }(Tygh, Tygh.$));
</script>
{script src="js/addons/ab__scroll_to_top/common.js"}