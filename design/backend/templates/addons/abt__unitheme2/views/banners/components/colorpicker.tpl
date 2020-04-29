{* colorpicker insertion *}
{$elm1="abt__ut2`$device_prefix`_`$field`_use"}
<input type="hidden" name="banner_data[{$elm1}]" value="N" />
<input type="checkbox" name="banner_data[{$elm1}]" id="elm_banner_{$elm1}" value="Y" {if $banner.$elm1 == "Y"}checked="checked"{/if} style="margin: 10px 5px 10px 0;"/>
<input class="cm-abt-ut2-colorpicker" style="font-family: monospace;" type="text" name="banner_data[{$elm}]" id="elm_banner_{$elm}" value="{$banner.$elm}"/>
