{if !$id}
    {assign var='id' value='sd_geo_user_location'}
{/if}
{assign var='map_container' value="map_canvas_`$id`"}

<div class="ty-stores-popup-container cm-central-dialog" id="{$id}">
    <script type="text/javascript">
        (function(_, $) {
            var options = {
                'latitude': {$latitude|doubleval},
                'longitude': {$longitude|doubleval},
                'map_container': '{$map_container}',
                'zoom_control':{if $addons.sd_geolocation.zoom_control == 'Y'} true {else} false {/if},
                'scale_control':{if $addons.sd_geolocation.scale_control == 'Y'} true {else} false {/if},
                'street_view_control':false,
                'map_type_control':{if $addons.sd_geolocation.map_type_control == 'Y'} true {else} false {/if},
                'language': '{$smarty.const.CART_LANGUAGE|sd_ZTM4MDNmZjNlN2U4NjliNTlkMGU0NTU5}',
                'storeData': [],
                'zoom': parseInt('{$addons.sd_geolocation.map_zoom}'),
            };

            $.ceEvent('on', 'ce.dialogshow', function(context) {
                if (context.attr('id') == '{$id}') {
                    $.ceSDMap('showLocation', options);
                }
            });

        }(Tygh, Tygh.$));
    </script>
	<div class="ty-stores-popup clearfix">
        <div class="clearfix">
            <div class="ty-my-location__map-wrapper" id="{$map_container}"></div>
        </div>
        <div class="buttons-container">
            <div class="ty-float-right">
                <a class="cm-dialog-closer cm-cancel tool-link btn">{__('cancel')}</a>
                {include file='buttons/button.tpl'
                    but_text=__('sd_geolocation_set')
                    but_role='action'
                    but_onclick="$.ceSDMap('saveLocation', '`$map_container`', true)"
                    allow_href=false
                    but_meta='btn-primary cm-dialog-closer'}
            </div>
        </div>
	</div>
<!--{$id}--></div>