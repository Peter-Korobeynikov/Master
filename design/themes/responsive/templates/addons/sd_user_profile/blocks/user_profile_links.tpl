{** block-description:block_user_profile_links **}

{if !empty($auth.user_id)}
    {hook name="user_profile:links"}
        {assign var="user_data" value=$auth.user_id|fn_get_user_info}
        <div id="account_info">
            {assign var="return_current_url" value=$config.current_url|escape:url}
            <ul class="ty-account-info user-profile">
                {hook name="profiles:my_account_menu"}
                    {if $user_data.firstname || $user_data.lastname}
                        <li class="ty-account-info__item ty-dropdown-box__item ty-account-info__name">{$user_data.firstname} {$user_data.lastname}</li>
                    {elseif $user_data.email}
                        <li class="ty-account-info__item ty-dropdown-box__item ty-account-info__name">{$user_data.email}</li>
                    {/if}
                    <li class="ty-account-info__item ty-dropdown-box__item"><a class="ty-account-info__a underlined" href="{"profiles.update"|fn_url}" rel="nofollow" >{__("profile_details")}</a></li>
                    {if $settings.General.enable_edp == "Y"}
                        <li class="ty-account-info__item ty-dropdown-box__item"><a class="ty-account-info__a underlined" href="{"orders.downloads"|fn_url}" rel="nofollow">{__("downloads")}</a></li>
                    {/if}
                    <li class="ty-account-info__item ty-dropdown-box__item"><a class="ty-account-info__a underlined" href="{"orders.search"|fn_url}" rel="nofollow">{__("orders")}</a></li>
                    {if $settings.General.enable_compare_products == 'Y'}
                        {assign var="compared_products" value=""|fn_get_comparison_products}
                        <li class="ty-account-info__item ty-dropdown-box__item"><a class="ty-account-info__a underlined" href="{"product_features.compare"|fn_url}" rel="nofollow">{__("view_comparison_list")}{if $compared_products} ({$compared_products|count}){/if}</a></li>
                    {/if}
                {/hook}
            </ul>
        </div>

        <script type="text/javascript">
            (function(_, $) {
                $("li:contains({__('my_likes')|escape:"javascript"})").last().remove();
                $("li:contains({__('following')|escape:"javascript"})").last().remove();
                $("li:contains({__('my_profile')|escape:"javascript"})").last().remove();
            }(Tygh, Tygh.$));
        </script>
    {/hook}
{/if}