{if $addons.sd_geolocation.use_second_key == 'Y'}
    {$google_key = $addons.sd_geolocation.google_map_key}
{else}
    {$google_key = $addons.sd_geolocation.google_key}
{/if}

{if $google_key}
    <script type="text/javascript">
        (function(_, $) {
            try {
                $.getScript('https://maps.googleapis.com/maps/api/js?key={$google_key}&libraries=places&language={$smarty.const.CART_LANGUAGE|sd_ZTM4MDNmZjNlN2U4NjliNTlkMGU0NTU5}');
            } catch {}
        })
    </script>
{/if}

    <script type="text/javascript">
    (function(_, $) {
            $.extend(_, {
                'google_api_key': '{$google_key}'
            });

        var visible = 'is-visible',
            show_popup = '{$addons.sd_geolocation.show_city_confirm}',
            location_determined = '{$smarty.session.location_determined}',
            tooltip_show_map;

        function set_pos(left, right) {
            if (left < right) {
                tooltip.css('left','0');
                tooltip.css('right','auto');
            } else {
                tooltip.css('right', '0');
                tooltip.css('left', 'auto');
            }
        }

        function overlap(link) {
            link.each(function (index, value) {
                var $this = $(this);
                    geolinkW = $this.width(),
                    geolinkOffsetLeft = $this.offset().left,
                    geolinkOffsetRight = $(document).width() - (geolinkOffsetLeft + geolinkW),
                    tooltip = $this.next();

                set_pos(geolinkOffsetLeft, geolinkOffsetRight);
            });
        }

        $(_.doc).ready(function(){
            var $tooltip = $('.ty-my-location-tooltip'),
                $tooltip_on_leave = $('.no-touchevents .ty-my-location-tooltip , .ty-my-location'),
                $link_notouch = $('.no-touchevents .ty-my-location'),
                $geolink = $('.ty-my-location-link'),
                $tooltip_controls = $('.ty-my-location-tooltip__controls'),
                $tooltip_confirm = $('.ty-my-location-tooltip__controls .ty-btn__primary');

                if($geolink) {
                    overlap($geolink);
                }

            $link_notouch.on({
                mouseenter: function() {
                    $(this).find($tooltip).addClass(visible);
                }
            });

            $tooltip_on_leave.mouseleave( function() {
                if (!tooltip_show_map) {
                    $(this).find($tooltip).removeClass(visible);
                }
            });

            $tooltip_confirm.on('click', function() {
                $tooltip.removeClass(visible).delay(500).queue(function() {
                    $tooltip_controls.remove();
                    tooltip_show_map = false;
                });
            });

            if (show_popup == 'tooltip' && location_determined) {
                if (localStorage.getItem("sd_geo_tooltip_showed") === null) {
                    localStorage.setItem("sd_geo_tooltip_showed", 1);
                    $tooltip.addClass(visible);
                    tooltip_show_map = true;
                }
            }

            $(window).resize(function () {
                var scroll_delay = 150,
                    scroll_timeout;

                if (scroll_timeout) {
                    clearTimeout(scroll_timeout);
                    scroll_timeout = null;
                }

                scroll_timeout = setTimeout( function() {
                    overlap($geolink);
                }, scroll_delay);
            });

        });

    }(Tygh, Tygh.$));
    </script>
{if $smarty.const.SEARCH_GEO_JS}
    {script src="js/addons/sd_geolocation/gears_init.js"}
    {script src="js/addons/sd_geolocation/geo.js"}
    <script type="text/javascript">
        (function(_, $) {
            $.ceEvent('on', 'ce.commoninit', function (context) {
                function lookup_location() {
                    geo_position_js.getCurrentPosition(show_map, show_map_error);
                }

                function show_map(loc) {
                    var url = fn_query_remove(_.current_url, ['geo_js']);
                    window.location = fn_url('geolocation.change_location?latitude=' + loc.coords.latitude +
                                    '&longitude=' + loc.coords.longitude +
                                    '&return_url=' + encodeURIComponent(url) +
                                    '&location_determined=Y');
                }

                function show_map_error() {
                    if ('{$addons.sd_geolocation.show_city_confirm}' != 'none') {
                        $('a.ty-my-location-link').click();
                    }
                }

                if (geo_position_js.init()) {
                    lookup_location();
                }
            })
        }(Tygh, Tygh.$));
    </script>
{/if}

{script src="js/addons/sd_geolocation/auto_popup.js"}
