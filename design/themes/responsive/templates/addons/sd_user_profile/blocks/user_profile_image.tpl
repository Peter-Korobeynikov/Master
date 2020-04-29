{** block-description:block_user_profile_image **}
{if !empty($auth.user_id)}
    {hook name="user_profile:image"}
        {assign var="is_image_circle" value=''}
        {assign var="user_data" value=$auth.user_id|fn_get_user_info}

        {if $addons.sd_user_profile.is_image_circle == 'Y'}
            {$is_image_circle = 'user-circle'}
        {/if}

        {include file="common/image.tpl"
            images=$user_data.image_pair
            show_detailed_link=false
            class=$is_image_circle
            image_width=$block.properties.image_width_px}
    {/hook}
{/if}