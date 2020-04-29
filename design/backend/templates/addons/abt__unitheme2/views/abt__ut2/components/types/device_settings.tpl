{capture name="abt__ut2_devices"}
{foreach $settings as $s}
{if (!$s.is_for_all_devices or $s.is_for_all_devices != 'Y') and (!$s.is_group or $s.is_group != 'Y')}
{$f_id="settings.abt__ut2.`$section`.`$f_group``$s@key`"}
{$r_id="`$section`.`$f_group``$s@key`"}
<tr id="{$r_id}"{if $smarty.request.highlighted === $r_id} class="ut2-highlighted-setting-row"{/if}>
<td data-th="{__('abt__ut2.settings.name')}">
<label for="{$f_id}">
{__("`$ls`.`$section`.`$f_group``$s@key`")}
{include file="common/tooltip.tpl" tooltip={__("`$ls`.`$section`.`$f_group``$s@key`.tooltip")}}
{if $s.is_addon_dependent and $s.is_addon_dependent == 'Y'}<i class="icon-is-addon"></i>{/if}
</label>
</td>
{foreach ['desktop', 'tablet', 'mobile'] as $device_type}
<td data-th="{__("abt__ut2.settings.value.`$device_type`")}">
{include file="addons/abt__unitheme2/views/abt__ut2/components/value.tpl"
f_type=$device_type
f_id=$f_id
f_section=$section
f_name=$s@key
f=$s
}
</td>
{/foreach}
</tr>
{/if}
{/foreach}
{/capture}
{if $smarty.capture.abt__ut2_devices|trim|strlen}
<table class="table table-middle table-responsive">
<thead>
<tr>
<th width="25%">{__('abt__ut2.settings.name')}</th>
<th width="25%">{__('abt__ut2.settings.value.desktop')}</th>
<th width="25%">{__('abt__ut2.settings.value.tablet')}</th>
<th width="25%">{__('abt__ut2.settings.value.mobile')}</th>
</tr>
</thead>
<tbody>
{$smarty.capture.abt__ut2_devices nofilter}
</tbody>
</table>
{/if}
