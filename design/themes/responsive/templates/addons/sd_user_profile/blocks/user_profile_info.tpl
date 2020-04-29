{** block-description:block_user_profile_info **}
{if !empty($auth.user_id)}
    {assign var="user_data" value=$auth.user_id|fn_get_user_info}
    {assign var="age" value=$user_data.birthday|sd_MTExN2M2OGNmNmJmZTQ5YTYwZGY1ZjRm}
    {assign var="user_name" value=$user_data|sd_ODI5MmU0ZWNiNzdhNTc2ZWIxZmZiZGI5}

    <h3>{$user_name}{if $age}, {$age}{/if}</h3>
    <div class="ty-sd_user_profile-section">
        <div class="ty-sd_user_profile__body ty-sd_user_profile-profile-module clear">
            <div>
                <h3>{__("about_me")}</h3>
                <p class="wrap"></p>
                <ul class="extra wrap">
                    {hook name="user_profile:info"}
                        <li>{__("joined")} {$user_data.timestamp|date_format:"`$settings.Appearance.date_format`"}</li>
                        {if $user_data.gender}
                            <li>{$user_data.genders[$user_data.gender]}</li>
                        {/if}
                        {if $age}
                            <li>{__("age")} {$age} {__("years_old")}</li>
                            <li>{__("born")} {$user_data.birthday|date_format:"`$settings.Appearance.date_format`"}</li>
                        {/if}
                    {/hook}
                </ul>
            </div>
        </div>
    </div>
{/if}