{hook name="buttons:add_to_cart"}
    {assign var="c_url" value=$config.current_url|escape:url}
    {if $settings.Checkout.allow_anonymous_shopping == "allow_shopping" || $auth.user_id}
        {include file="buttons/button.tpl" but_id=$but_id but_text=$but_text|default:__("abt__ut2.add_to_cart") but_name=$but_name but_onclick=$but_onclick but_href=$but_href but_target=$but_target but_role=$but_role|default:"text" but_meta="ty-btn__primary ty-btn__add-to-cart cm-form-dialog-closer" but_icon="ut2-icon-outline-cart"}
    {else}

        {if $runtime.controller == "auth" && $runtime.mode == "login_form"}
            {assign var="login_url" value=$config.current_url}
        {else}
            {assign var="login_url" value="auth.login_form?return_url=`$c_url`"}
        {/if}

        {include file="buttons/button.tpl" but_id=$but_id but_text=__("sign_in_to_buy") but_title=__("text_login_to_add_to_cart") but_href=$login_url but_role=$but_role|default:"text" but_name="" but_meta="cm-tooltip ty-btn__tertiary ut2-allow-shopping" but_icon="ut2-icon-outline-info-circle"}
    {/if}
{/hook}
{* Change the Buy now button behavior using a hook *}
{$show_buy_now = $show_buy_now scope = parent}