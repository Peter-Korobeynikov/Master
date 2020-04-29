<div class="ty-control-group">
    {assign var="_gender" value=$user_data.gender|default:0}
    {assign var="_genders" value=$user_data.genders|default:[1=>__("male"),2=>__("female")]}
    <label for="gender" class="ty-control-group__title">{__("gender")}</label>
    <select id="gender" class="ty-profile-field__select" name="user_data[gender]">
        <option value="0">- {__("select_gender")} -</option>
        {foreach from=$_genders item="gender" key="code"}
            <option {if $_gender == $code}selected="selected"{/if} value="{$code}">{$gender}</option>
        {/foreach}
    </select>
</div>

{if $addons.age_verification.status != 'A'}
    <div class="ty-control-group">
        <label for="birthday" class="ty-control-group__title">{__("user_birthday")}</label>
        {include file="common/calendar.tpl" date_id="birthday" date_name="user_data[birthday]" date_val=$user_data.birthday}
    </div>
{/if}

{if $runtime.mode == "update"}
<div class="ty-control-group">
    <label class="ty-control-group__title">{__("userpic")}</label>
    {include file="common/image.tpl"
        images=$user_data.image_pair
        show_detailed_link=false
        image_width="120"}

    {include file="addons/sd_user_profile/views/attach_images.tpl"
        image_name="user_img"
        image_key=$auth.user_id
        image_object_type="userpic"
        image_pair=$user_data.image_pair
        hide_alt=true
        hide_titles=true
        hide_images=true
        no_detailed=true}
</div>
{/if}
