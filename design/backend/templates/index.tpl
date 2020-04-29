<!DOCTYPE html>
<html lang="en" dir="{$language_direction}">

<head>
{strip}
<title>
    {if $page_title}
        {$page_title}
    {else}
        {if $navigation.selected_tab}{__($navigation.selected_tab)}{if $navigation.subsection} :: {__($navigation.subsection)}{/if} - {/if}{__("admin_panel")}
    {/if}
</title>
{/strip}
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
<link href="{$images_dir}/favicon.ico" rel="shortcut icon" type="image/x-icon" >
{include file="common/styles.tpl"}
{if "DEVELOPMENT"|defined && $smarty.const.DEVELOPMENT == true}
<script type="text/javascript" data-no-defer>
window.jsErrors = [];
/*window.onerror = function(errorMessage) {
    document.write('<div data-ca-debug="1" style="border: 2px solid red; margin: 2px;">' + errorMessage + '</div>');
}*/
</script>
{/if}
</head>
{include file="buttons/helpers.tpl"}

{$class = "{if $smarty.cookies.layout_status == 1}menu-toggled{/if}{if $smarty.const.ACCOUNT_TYPE === "vendor"} vendor-area{/if}"}
<body {if $class}class="{$class}"{/if} data-ca-scroll-to-elm-offset="120">

    {include file="common/loading_box.tpl"}

    {if "THEMES_PANEL"|defined}
        {include file="components/bottom_panel/bottom_panel.tpl"}
    {/if}

    {include file="common/notification.tpl"}
    {include file=$content_tpl assign="content"}

    <div class="main-wrap" id="main_column{if !$auth.user_id || $view_mode == 'simple'}_login{/if}">
    {if $view_mode != "simple"}
        <div class="admin-content">
            {include file="menu.tpl"}

            <div class="admin-content-wrap">
                {hook name="index:main_content"}{/hook}
                {$content nofilter}
                {$stats|default:"" nofilter}
            </div>
        </div>
        {else}
        {$content nofilter}
    {/if}

    <!--main_column{if !$auth.user_id || $view_mode == 'simple'}_login{/if}--></div>

    {include file="common/comet.tpl"}
    

    {if $smarty.request.meta_redirect_url|fn_check_meta_redirect}
        <meta http-equiv="refresh" content="1;url={$smarty.request.meta_redirect_url|fn_check_meta_redirect|fn_url}" />
    {/if}

    {if $auth.user_id && 'settings'|fn_check_permissions:'change_store_mode':'admin':'POST'}
        {include file="views/settings/store_mode.tpl" show=$show_sm_dialog}
        {include file="views/settings/trial_expired.tpl" show=$show_trial_dialog}
        {include file="views/settings/license_errors.tpl" show=$show_license_errors_dialog}
    {/if}

    {hook name="index:after_content"}{/hook}

    {include file="common/loading_box.tpl"}
    {include file="common/scripts.tpl"}
</body>
</html>
