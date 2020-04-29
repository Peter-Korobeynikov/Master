<div class="sidebar-row" id="om_ajax_location_map">
    <h6>{__("geo_maps.shipping_address_on_map")}</h6>
    {if $user_data.s_country_descr || $user_data.s_city || $user_data.s_address}
        <div class="cm-geo-map-container cm-aom-map-container"
             data-ca-geo-map-language="{$smarty.const.CART_LANGUAGE}"
             data-ca-aom-country="{$user_data.s_country_descr}"
             data-ca-aom-city="{$user_data.s_city}"
             data-ca-aom-address="{$user_data.s_address}"
        ></div>
    {else}
        {__('no_data')}
    {/if}
<!--om_ajax_location_map--></div>
