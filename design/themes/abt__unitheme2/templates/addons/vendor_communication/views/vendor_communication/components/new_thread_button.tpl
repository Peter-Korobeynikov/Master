{if $auth.user_id}
    <a title="{__("vendor_communication.ask_a_question")}" class="vc__l {if "MULTIVENDOR"|fn_allowed_for}ty-vendor-communication__post-write{/if} cm-dialog-opener cm-dialog-auto-size" data-ca-target-id="new_thread_dialog_{$object_id}" rel="nofollow">
        <i class="ut2-icon-outline-announcement"></i>
        <span class="ajx-link">{__("vendor_communication.ask_a_question")}</span>
    </a>
{else}
    {assign var="return_current_url" value=$config.current_url|escape:url}

    <a title="{__("vendor_communication.ask_a_question")}" href="{"auth.login_form?return_url=`$return_current_url`"|fn_url}" data-ca-target-id="new_thread_login_form" class="vc__l cm-dialog-opener cm-dialog-auto-size {if "MULTIVENDOR"|fn_allowed_for}ty-vendor-communication__post-write{/if}" rel="nofollow">
        <i class="ut2-icon-outline-announcement"></i>
        <span class="ajx-link">{__("vendor_communication.ask_a_question")}</span>
    </a>

    {if $show_form}
        {include file="addons/vendor_communication/views/vendor_communication/components/login_form.tpl"}
    {/if}
{/if}