{capture name="mainbox"}
{if $go_to_all_stores}
<p>{__("ab__am.go_to_all_stores", ["[link]" => "ab__am.addons?switch_company_id=0"|fn_url])}</p>
{elseif !empty($d)}
{$installed_addons=$d.installed_addons}
{$all_products=$d.all_products}
{$available_products=$d.available_products}
{$events=$d.events}
{$cs_addons=$addons}
{include file="addons/ab__addons_manager/views/ab__am/components/notifications.tpl"}
<div id="ab_am_addons">
<script>
(function(_, $) {
var menu = $('.navbar-admin-top .nav-pills');
var ab_am_events = {$events|json_encode nofilter};
var available_updates = ab_am_events.available_updates;
var notifications = ab_am_events.notifications;
menu.find('.ab-am-new-version').remove();
menu.find('.ab-am-available-updates').remove();
menu.find('.ab-am-notifications').remove();
if (Object.keys(ab_am_events.available_updates).length){
if (menu.find('.ab__addons_manager').length){
for (var a in ab_am_events.available_updates) {
if (menu.find('.' + a).length){
menu.find('.' + a + ' > a:first > span:first').append('<span class="ab-am-new-version">' + '{"ab__am.menu.new_version"|__|escape:"javascript"}'.replace('[ver]', ab_am_events.available_updates[a]) + '</span>');
}
}
menu.find('.ab__addons_manager').parent().parent().find('a:first > b').before('<span title="{"ab__am.menu.number_of_updates"|__}" class="ab-am-available-updates">' + Object.keys(ab_am_events.available_updates).length + '</span>');
}
}
if (ab_am_events.notifications > 0){
menu.find('.ab__addons_manager > a:first > span:first').append('<span class="ab-am-notifications">' + ab_am_events.notifications + '</span>');
menu.find('.ab__addons_manager').parent().parent().find('a:first > b').before('<span title="{"ab__am.menu.number_of_notifications"|__}" class="ab-am-notifications">' + ab_am_events.notifications + '</span>');
}
}(Tygh, Tygh.$));
</script>
{if !empty($available_products.addons)}
{$addons=$available_products.addons}
<div>
<div class="ab-am-notes">
{$note_id='available_addons'}
{capture name="notes_picker"}{__('ab__am.addons_sets.notes') nofilter}{/capture}
{include file="common/popupbox.tpl" act="link" id="content_`$note_id`_notes" link_text=__("ab__am.note") text=__("note") content=$smarty.capture.notes_picker}
</div>
{include file="common/subheader.tpl" title=__("ab__am.available_addons") target="#ab__am_available_addons" }
<div id="ab__am_available_addons" class="in collapse" style="/*overflow: hidden;*/">
{include file="addons/ab__addons_manager/views/ab__am/components/addons.tpl" addons=$addons type='addons'}
</div>
</div>
<hr>
{/if}
{if !empty($available_products.sets)}
{$sets=$available_products.sets}
<div>
<div class="ab-am-notes">
{$note_id='available_sets'}
{capture name="notes_picker"}{__('ab__am.addons_sets.notes') nofilter}{/capture}
{include file="common/popupbox.tpl" act="link" id="content_`$note_id`_notes" link_text=__("ab__am.note") text=__("note") content=$smarty.capture.notes_picker}
</div>
{include file="common/subheader.tpl" title=__("ab__am.available_sets") target="#ab__am_available_sets"}
<div id="ab__am_available_sets" class="in collapse" style="/*overflow: hidden;*/">
<table width="100%" class="table table-middle ab-am-table">
<thead>
<tr class="first-sibling">
<th width="60%" class="cm-non-cb">{__('ab__am.set.set')}</th>
<th width="25%" class="right cm-non-cb">&nbsp;</th>
<th width="25%" class="right cm-non-cb">{__('ab__am.addon.table_head.subscription')}</th>
</tr>
</thead>
{foreach from=$sets key="s" item="set_group" }
{foreach from=$set_group key="s_key" item="set" }
{include file="addons/ab__addons_manager/views/ab__am/components/set.tpl" type='set'}
{/foreach}
{/foreach}
</table>
</div>
</div>
<hr>
{/if}
<!--ab_am_addons--></div>
{else}
<p class="no-items">{__("ab__am.no_data", ['[domain]' => $config.http_host])}</p>
{/if}
{/capture}
{include file="common/mainbox.tpl" title=__('ab__am.addons') content=$smarty.capture.mainbox buttons=$smarty.capture.buttons sidebar=$smarty.capture.sidebar}