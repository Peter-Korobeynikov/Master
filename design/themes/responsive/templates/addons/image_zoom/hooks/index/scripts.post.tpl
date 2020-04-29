{script src="js/addons/image_zoom/lib/easyzoom.min.js"}
{script src="js/addons/image_zoom/index.js"}

<script type="application/javascript">
    (function (_, $) {
        $.ceEvent('on', 'ce.commoninit', function (context) {
            var positionId = {$addons.image_zoom.cz_zoom_position};
            if ('{$language_direction}' === 'rtl') {
                positionId = $.ceImageZoom('translateFlyoutPositionToRtl', positionId);
            }

            var $body = $('body', _.doc);

            $('.cm-previewer', context).each(function (i, elm) {
                setTimeout(function() {
                    var isMobile = $body.hasClass('screen--xs') ||
                        $body.hasClass('screen--xs-large') ||
                        $body.hasClass('screen--sm') ||
                        $body.hasClass('screen--sm-large');

                    if (isMobile && Modernizr.touchevents) {
                        return false;
                    }

                    $.ceImageZoom('init', $(elm), positionId);
                }, 220);
            });
        });
    })(Tygh, Tygh.$);
</script>
