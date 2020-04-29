{if $auth.user_id}
    <li class="ty-account-info__item ty-dropdown-box__item"><a class="ty-account-info__a" href="{"profile_page.view&profile_id=`$auth.user_id`"|fn_url}" rel="nofollow">{__("my_profile")}</a></li>
{/if}