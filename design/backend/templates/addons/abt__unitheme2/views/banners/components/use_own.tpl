<div class="abt-ut2-banner-use-own-value {if $device}for_device{/if}">
{if $field|in_array:$enabled_fields and $device}
<input type="hidden" name="banner_data[{$elm}_use_own]" value="N"/>
<input type="checkbox"
name="banner_data[{$elm}_use_own]"
id="elm_banner_{$elm}_use_own"
data-field="{$elm}"
value="Y"
{if $banner["`$elm`_use_own"] == "Y"}checked="checked"{/if}
class="cm-tooltip"
title="{__('abt__ut2.banner.use_own_info')}"
/>
{elseif $device}
<input type="checkbox"
checked="checked"
class="cm-tooltip"
disabled="disabled"
title="{__('abt__ut2.banner.use_own_info')}"
/>
{/if}
</div>
