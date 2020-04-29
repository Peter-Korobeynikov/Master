{if $d.notifications}
<hr>
<div class="ab-am-notes">
{$note_id='notifications'}
{capture name="notes_picker"}{__('ab__am.notifications.notes') nofilter}{/capture}
{include file="common/popupbox.tpl" act="link" id="content_`$note_id`_notes" link_text=__("ab__am.note") text=__("note") content=$smarty.capture.notes_picker}
</div>
{include file="common/subheader.tpl" title=__("ab__am.notifications") target="#ab__am_notifications"}
<div id="ab__am_notifications" class="ab-am-notifications in collapse" style="overflow: hidden;">
{foreach $d.notifications as $b}
<div class="ab-am-notification">
<a target="_blank" href="{$b.url|fn_url}"><img src="{$b.image}"></a>
</div>
{/foreach}
</div>
<hr>
{/if}