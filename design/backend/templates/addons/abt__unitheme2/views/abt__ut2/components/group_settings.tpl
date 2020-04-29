{foreach $settings as $s}
{if $s.is_group and $s.is_group == 'Y' and $s.items}
{include file="common/subheader.tpl" title=__("`$ls`.`$section`.`$s@key`_group") target="#`$section`_`$s@key`_group"}
<div id="{"`$section`_`$s@key`_group"}" class="collapsed in" style="overflow: hidden;">
<p>{__("`$ls`.`$section`.`$s@key`_group_description")}</p>
{include file="addons/abt__unitheme2/views/abt__ut2/components/types/simple_settings.tpl"
settings=$s.items f_group="`$s@key`."
}
{include file="addons/abt__unitheme2/views/abt__ut2/components/types/device_settings.tpl"
settings=$s.items f_group="`$s@key`."
}
</div>
{/if}
{/foreach}
