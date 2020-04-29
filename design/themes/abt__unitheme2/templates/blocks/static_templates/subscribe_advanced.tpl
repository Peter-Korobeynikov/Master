{** block-description:tmpl_abt__ut2__subscription **}
{if $addons.newsletters}
<div class="ty-footer-form-block no-help">
    <form action="{""|fn_url}" method="post" name="subscribe_form" class="cm-processing-personal-data">
        <input type="hidden" name="redirect_url" value="{$config.current_url}" />
        <input type="hidden" name="newsletter_format" value="2" />
        <div class="ty-footer-form-block__form ty-control-group with-side">
        <div class="ty-footer-form-block__title">{__("stay_connected")}</div>
            <!-- <i class="ty-icon-uni-mail"></i> --><div class="ty-uppercase ty-social-link__title">{__("exclusive_promotions")}<br>{__("exclusive_promotions_content")}</div>
        </div>
        
        {hook name="newsletters:email_subscription_block"}
        
        <div class="ty-footer-form-block__input cm-block-add-subscribe">
        <label class="cm-required cm-email hidden" for="subscr_email{$block.block_id}">{__("email")}</label>
            <input type="text" name="subscribe_email" id="subscr_email{$block.block_id}" size="20" placeholder="{__("email")}" class="cm-hint ty-input-text-medium ty-valign-top" />
            {include file="buttons/button.tpl" but_role="submit" but_name="dispatch[newsletters.add_subscriber]" but_text=__("subscribe") but_meta="ty-btn__subscribe ty-btn__primary"}
        </div>
        
        {/hook}
        
    </form>
</div>
{/if}