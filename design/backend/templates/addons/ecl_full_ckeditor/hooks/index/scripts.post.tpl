{if $runtime.controller == 'addons' && $runtime.mode == 'manage' && !$smarty.capture.ecl_icon}
<script type="text/javascript" class="cm-ajax-force">
(function(_, $) {
    $(document).ready(function(){
            $('[id^="addon_ecl_"] .bg-icon').css('background-image', 'url(https://ecom-labs.com/images/ecl_logo.png)').css('background-size', 'cover');
            $('[id^="addon_ecl_"] .bg-icon i').css('display', 'none');
    });
}(Tygh, Tygh.$));
</script>
{capture name="ecl_icon"}Y{/capture}
{/if}