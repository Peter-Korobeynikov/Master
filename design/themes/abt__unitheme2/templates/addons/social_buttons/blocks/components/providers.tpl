{foreach from=$provider_settings item="provider_data"}
    {if $provider_data && $provider_data.template && $provider_data.data}
        <div class="ut2-social-buttons__inline">{include file="addons/social_buttons/providers/`$provider_data.template`"}</div>
    {/if}
{/foreach}