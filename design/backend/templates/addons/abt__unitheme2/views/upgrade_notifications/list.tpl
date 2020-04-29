<div class="abt-ut2-upgrade-text">{__('abt__ut2.upgrade_notifications.text', ['[ver]' => $ver]) nofilter}</div>
{if $notifications}
<div class="abt-ut2-upgrade-notifications">
{foreach $notifications as $n}
<div class="abt-ut2-upgrade-notification">
<div class="abt-ut2-upgrade-notification-title">{$n.title nofilter}</div>
<div class="abt-ut2-upgrade-notification-text">{$n.text nofilter}</div>
</div>
{/foreach}
</div>
{/if}
