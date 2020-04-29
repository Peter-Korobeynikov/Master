{if $runtime.controller == 'profiles'}
    {if $addons.sd_geolocation.google_key}
        {*Checking status of "google_autocomplete_address" addon in order to avoid an error 'The Google Maps API included  multiple times on the page'*}
        {if $addons.sd_google_autocomplete_address.status != 'A'}
            <script src="https://maps.googleapis.com/maps/api/js?key={$addons.sd_geolocation.google_key}&language={$smarty.const.CART_LANGUAGE|sd_ZTM4MDNmZjNlN2U4NjliNTlkMGU0NTU5}" type="text/javascript"></script>
        {/if}
    {/if}
{/if}
