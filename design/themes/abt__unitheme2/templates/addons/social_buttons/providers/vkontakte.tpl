{if $addons.social_buttons.vkontakte_enable == "Y" && $provider_settings.vkontakte.data && $addons.social_buttons.vkontakte_appid}
    <div id="vk_like"></div>
    <script class="cm-ajax-force">
        $.getScript('//vk.com/js/api/openapi.js', function () {
            VK.init({
                apiId: '{$addons.social_buttons.vkontakte_appid}',
                onlyWidgets: true
            });
            VK.Widgets.Like('vk_like', {$provider_settings.vkontakte.data nofilter});
        });
    </script>
{/if}