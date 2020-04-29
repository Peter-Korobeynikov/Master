{if $option|fn_needs_image_verification}
    {if $app.antibot->getDriver()|get_class == "Tygh\Addons\Recaptcha\RecaptchaDriver"}
        {assign var="id" value="recaptcha_"|uniqid}
        <div class="captcha ty-control-group">
            <label for="{$id}" class="cm-required cm-recaptcha ty-captcha__label">{__("image_verification_label")}</label>
            <div id="{$id}" class="cm-recaptcha"></div>
        </div>
    {else}
        {assign var="id" value="recaptcha_"|uniqid}
        <div class="native-captcha{if !$full_width} native-captcha--short{/if}">
            <label for="{$id}"
                class="cm-required ty-captcha__label"
            >{__("image_verification_label")}</label>
            <div class="native-captcha__image-container">
                <img src="{$smarty.session.native_captcha.image}"
                     class="native-captcha__image"
                />
            </div>
            <input type="text"
                   id="{$id}"
                   class="input-text native-captcha__answer form-control"
                   name="native_captcha_response"
                   autocomplete="off"
                   placeholder="{__("image_verification_label")}"
            >
        </div>
    {/if}
{/if}
