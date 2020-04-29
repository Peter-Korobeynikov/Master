{assign var="map_container" value="map_canvas_`$id`"}
{assign var="map_search" value="map_search_input_`$id`"}
{assign var="record" value=""|sd_ZWUzNjY2ZDgxMDJhMGJlMzAyODc0OGZj}
{assign var="name_location" value=$record|sd_MzA4YjQxYmJkZjI2YjlhYWIzMzI0YWZm}

<div class="ty-stores-popup-container cm-central-dialog" id="map_picker_{$id}">
    {script src="/js/addons/sd_geolocation/google.js"}
    <script type="text/javascript">
        (function(_, $) {
            var options = {
                'area': 'A',
                'latitude': {$location.latitude|doubleval},
                'longitude': {$location.longitude|doubleval},
                'map_container': '{$map_container}',
                'search_input': '{$map_search}',
                'zoom_control':{if $addons.sd_geolocation.zoom_control == 'Y'} true {else} false {/if},
                'scale_control':{if $addons.sd_geolocation.scale_control == 'Y'} true {else} false {/if},
                'autosave':{if $addons.sd_geolocation.autosave_location == 'Y'} true {else} false {/if},
                'street_view_control':false,
                'map_type_control':{if $addons.sd_geolocation.map_type_control == 'Y'} true {else} false {/if},
                'language': '{$smarty.const.CART_LANGUAGE|sd_ZTM4MDNmZjNlN2U4NjliNTlkMGU0NTU5}',
                'storeData': [],
                'zoom': parseInt('{$addons.sd_geolocation.map_zoom}'),
            };

            $.ceEvent('on', 'ce.dialogshow', function(context) {
                if (context.attr('id') == 'map_picker_{$id}') {
                    $.ceSDMap('showLocation', options);
                }
            });

        }(Tygh, Tygh.$));
    </script>
	<div class="ty-stores-popup clearfix">
        <div class="clearfix">
            <h4>{__("you_are_here")}:&nbsp;<div class="ty-my-location__current-city">{$name_location}</div></h4>
            <div class="ty-my-location__map-search-container">
                <input type="text" id="{$map_search}" class="ty-input-text-full" value="" placeholder="{__("change_location")}"/>
            </div>
            <div>&nbsp</div>
            <div class="ty-my-location__map-wrapper {if $addons.sd_geolocation.show_map_in_popup == 'N'}hidden{/if}" id="{$map_container}"></div>
        </div>
        <div class="buttons-container">
            <div class="ty-float-right">
                <a class="ty-btn ty-btn__primary {if $addons.sd_geolocation.autosave_location=='Y' && $addons.sd_geolocation.show_map_in_popup=='Y'}hidden{/if}" onclick="$.ceSDMap('saveLocation', '{$map_container}', false); return false;">{__("sd_geolocation_set")}</a>
            </div>
        </div>
	</div>
<!--map_picker_{$id}--></div>