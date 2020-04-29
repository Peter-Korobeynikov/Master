{$t = ($type == 'less') ? 'less_' : ''}
{capture name="mainbox"}
<form action="{""|fn_url}" method="post" name="abt__unitheme2_{$t}settings_form" id="abt__unitheme2_{$t}settings_form">
<input type="hidden" name="selected_section" value="{$smarty.request.selected_section}" />
<input type="hidden" name="abt__ut2_type" value="{$type}" />
{if $type == 'less'}
<div class="language-wrap">
<h6 class="muted">Style {include file="common/tooltip.tpl" tooltip=__('abt__ut2.less_settings.style.tooltip')}:</h6>
{if $abt__ut2_styles|count == 1}
{$abt__ut2_style}
{else}
{include file="common/select_object.tpl" style="graphic" suffix="currency" link_tpl=$config.current_url|fn_link_attach:"style=" items=$abt__ut2_styles selected_id=$smarty.request.style|default:$abt__ut2_style display_icons=false key_name='name'}
{/if}
<input type="hidden" name="abt__ut2_style" value="{$smarty.request.abt__ut2_style|default:$abt__ut2_style}">
</div>
{/if}
{if $type == 'less'}<p>{__("`$ls`_description")}</p>{/if}
{capture name="tabsbox"}
{foreach $abt__ut2_settings as $section => $section_settings}
<div id="content_{$section}">
<div>{__("`$ls`.`$section`_description")}</div>
<div class="table-responsive-wrapper">
{include file="addons/abt__unitheme2/views/abt__ut2/components/types/simple_settings.tpl"
settings=$section_settings
}
{include file="addons/abt__unitheme2/views/abt__ut2/components/types/device_settings.tpl"
settings=$section_settings
}
{include file="addons/abt__unitheme2/views/abt__ut2/components/group_settings.tpl"
settings=$section_settings
}
</div>
<!--content_{$section}--></div>
{/foreach}
{/capture}
{include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox group_name=$runtime.controller active_tab=$smarty.request.selected_section track=true}
</form>
{/capture}
{capture name="buttons"}
{include file="buttons/button.tpl" but_text=__("save") but_role="submit-link" but_name="dispatch[abt__ut2.update_settings]" but_meta="btn-primary" but_target_form="abt__unitheme2_`$t`settings_form"}
{/capture}
{include file="common/mainbox.tpl" content=$smarty.capture.mainbox buttons=$smarty.capture.buttons}
