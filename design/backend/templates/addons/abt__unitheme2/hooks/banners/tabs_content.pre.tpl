{foreach ['tablet', 'mobile'] as $device}
<div id="content_abt__ut2_banner_tab_{$device}" class="hidden clearfix">
{include file="addons/abt__unitheme2/views/banners/components/abt__ut2_fields.tpl" enabled_fields=$devices_enabled_fields.$device}
<!--content_abt__ut2_banner_tab_{$device}--></div>
{/foreach}
