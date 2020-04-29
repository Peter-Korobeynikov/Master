<li class="dropdown hover-show--disabled notifications-center__opener-wrapper cm-dropdown-skip-processing">
    <a class="dropdown-toggle" data-toggle="dropdown">
        <span
            class="icon icon-bell-alt cc-notify" 
            title="{__("notifications_center.notifications")}"
            data-ca-notifications-center-counter
        >
        </span>
        <span class="" ></span>
        <b class="caret"></b>
    </a>
    <ul class="dropdown-menu pull-right notifications-center__root" data-ca-notifications-center-root>
        
    </ul>
</li>

<script>
(function (_, $) {
    $.ceEvent('one', 'ce.commoninit', function () {
        var inited = false;

        $(_.doc).on('click', '.notifications-center__opener-wrapper a', function () {
            if (!inited) {
                $.ceEvent('trigger', 'ce.notifications_center.enabled', [{
                  noData: '{__("notifications_center.no_notifications")|escape:"javascript"}',
                  loading: '{__("loading")|escape:"javascript"}',
                  notifications: '{__("notifications_center.notifications")|escape:"javascript"}',
                  showMore: '{__("show_more")|escape:"javascript"}',
                  showLess: '{__("show_less")|escape:"javascript"}'
                }]);
                inited = !inited;
            }
        });
    });
})(Tygh, Tygh.$);
</script>
{script src="js/tygh/notifications_center.js"}
