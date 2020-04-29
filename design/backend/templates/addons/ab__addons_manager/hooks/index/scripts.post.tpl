<script>
(function(_, $) {
$(_.doc).on('click', '.compatible-title', function (e) {
var ct = $(this).parent().find('div.compatible-text');
if (ct.hasClass('hidden')) ct.removeClass('hidden');
else ct.addClass('hidden');
});
{if $runtime.controller != 'ab__am'}
$(document).ready(function () {
var menu = $('.navbar-admin-top .nav-pills');
var ab_am_events = {$abam_events|json_encode nofilter};
var available_updates = ab_am_events.available_updates;
var notifications = ab_am_events.notifications;
if (Object.keys(ab_am_events.available_updates).length){
if (menu.find('.ab__addons_manager').length){
for (var a in ab_am_events.available_updates) {
if (menu.find('.' + a).length){
if (!menu.find('.' + a + ' > a:first .ab-am-new-version').length){
menu.find('.' + a + ' > a:first > span:first').append('<span class="ab-am-new-version">' + '{"ab__am.menu.new_version"|__|escape:"javascript"}'.replace('[ver]', ab_am_events.available_updates[a]) + '</span>');
}
}
}
menu.find('.ab__addons_manager').parent().parent().find('a:first > b').before('<span title="{"ab__am.menu.number_of_updates"|__}" class="ab-am-available-updates">' + Object.keys(ab_am_events.available_updates).length + '</span>');
}
}
if (ab_am_events.notifications > 0){
menu.find('.ab__addons_manager > a:first > span:first').append('<span class="ab-am-notifications">' + ab_am_events.notifications + '</span>');
menu.find('.ab__addons_manager').parent().parent().find('a:first > b').before('<span title="{"ab__am.menu.number_of_notifications"|__}" class="ab-am-notifications">' + ab_am_events.notifications + '</span>');
}
});
{/if}
}(Tygh, Tygh.$));
</script>
