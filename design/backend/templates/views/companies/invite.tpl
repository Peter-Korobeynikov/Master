<div>
    <form action="{fn_url("companies.invite")}" method="post" name="invite_vendors_form" class="form-horizontal form-edit">
        <div class="control-group">
            <label class="control-label" for="elm_companies_invite_vendors">{__("invite_vendors_enter_emails")}:</label>
            <div class="controls">
                <textarea name="vendor_emails" id="elm_companies_invite_vendors" cols="55" rows="10" class="span9"></textarea>
                <p class="muted">{__("separate_multiple_email_addresses")}</p>
            </div>
            {if ($settings.Appearance.email_templates == "new")}
            <div class = "controls">
                {__("vendor_edit_invitation_email_template", ["[url]" => "email_templates.update?code=vendor_invitation&area=A"|fn_url])}
            </div>
            {/if}
        </div>
    </form>
    <div class="buttons-container">
        {include file="buttons/save_cancel.tpl" but_text=__("send_invitations") cancel_action="close" but_target_form="invite_vendors_form"}
    </div>
</div>