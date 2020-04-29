{$error_container_id=$error_container_id|default:"login_error_{$id}"}

<div class="ty-login-form__wrong-credentials-container" id="{$error_container_id}">
    {if $login_error}
        <span class="ty-login-form__wrong-credentials-text ty-error-text">{__("error_incorrect_login")}</span>
    {/if}
<!--{$error_container_id}--></div>