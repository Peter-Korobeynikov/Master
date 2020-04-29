{if $addons.ath_instagram_2.app_id && $addons.ath_instagram_2.app_secret}
    <div id="container_addon_option_get_access_token" class="control-group setting-wide ath_instagram_2">
        <div class="controls">
            {if $addons.ath_instagram_2.access_token}
                {$button_text = __("refresh_access_token")}
            {else}
                {$button_text = __("get_access_token")}
            {/if}

            <div id="fb-root"></div>

            <script
                async
                defer
                crossorigin="anonymous"
                src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v4.0&appId={$addons.ath_instagram_2.app_id}"
            ></script>

            <div
                class="fb-login-button"
                data-width=""
                data-size="medium"
                data-button-type="login_with"
                data-auto-logout-link="true"
                data-use-continue-as="false"
                data-scope="manage_pages, instagram_basic, pages_show_list"
                onlogin="reload_page()"
            ></div>

            <script>
                function reload_page() {
                    location.reload();
                }

                function logged_in_to_facebook() {
                    document.getElementById('container_button_get_token_fb').hidden = false;;
                }

                function not_logged_in_to_facebook() {
                    document.getElementById('container_button_get_token_fb').hidden = true;
                }
            </script>

            <script type="text/javascript"
                src="https://www.facebook.com/ThemeHills/"
                onload="logged_in_to_facebook()"
                onerror="not_logged_in_to_facebook()"
                async="async"
            ></script>

            <div id="container_button_get_token_fb" hidden="true" class="control-group">
                {include
                    file="buttons/button.tpl"
                    but_href="{$url_get_access_token}"
                    but_id="addon_option_ath_instagram_2_get_access_token"
                    but_role="action"
                    but_text=$button_text
                }
            </div>
        </div>
    </div>
{/if}
