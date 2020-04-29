{if "ab__fast_navigation.view"|fn_check_view_permissions}
<div class="control-group{if !"ab__fast_navigation.manage"|fn_check_view_permissions} cm-hide-inputs{/if}">
<label class="control-label" for="ab__fn_menu_status_{$id}">{__("ab__fn.menu.status")}
{include file="common/tooltip.tpl" tooltip=__("ab__fn.menu.status.tooltip")}:
</label>
<div class="controls">
<input type="hidden" name="static_data[ab__fn_menu_status]" value="N" />
<input type="checkbox" id="ab__fn_menu_status_{$id}" name="static_data[ab__fn_menu_status]" value="Y"{if $static_data['ab__fn_menu_status'] == "Y"} checked="checked"{/if} />
</div>
</div>
{/if}