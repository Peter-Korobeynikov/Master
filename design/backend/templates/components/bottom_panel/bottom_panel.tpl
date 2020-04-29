{if ($smarty.const.AREA == 'C')}
    {styles}{style content="@import \"../../../backend/css/tygh/bottom_panel/index.less\";"}{/styles}
{else}
    {styles}{style src="tygh/bottom_panel/index.less"}{/styles}
{/if}

{$c_url = $config.current_url|fn_url}

{hook name="bottom_panel:edition"}
{if ($config.demo_instance.type == "online" || $config.demo_instance.type == "shared")}
    {$edition = "demo"}
{elseif $config.demo_instance.type == "personal"}
    {$edition = "personal_demo"}
{else}
    {$edition = "store"}
{/if}
{/hook}

{if $runtime.controller === "products"}
    {$page = "products"}
{elseif $runtime.controller === "checkout" && $runtime.mode === "checkout"}
    {$page = "checkout"}
{/if}

{if $runtime.customization_mode.block_manager}
    {$active_mode = "build"}
{elseif $runtime.customization_mode.live_editor}
    {$active_mode = "text"}
{elseif $runtime.customization_mode.theme_editor}
    {$active_mode = "theme"}
{else}
    {$active_mode = "preview"}
{/if}

{$utm = "utm_source=`$smarty.const.PRODUCT_NAME|lower|strip:''|replace:'-':'_'`&utm_medium=`$edition`"}

<div class="bp__container">
    <div id="bp_bottom_panel"
        class="bp-panel bp-panel--{$edition} bp-panel--{$smarty.const.ACCOUNT_TYPE}
        {if $smarty.cookies.pb_is_bottom_panel_open === "false"}
            bp-panel--hidden
        {/if}"
        data-ca-bottom-pannel="true"
        data-bp-mode="demo"
        data-bp-is-bottom-panel-open="true"
        data-bp-nav-active={$smarty.const.ACCOUNT_TYPE}
        data-bp-modes-active="{$active_mode}">
        <a href="{if $smarty.const.ACCOUNT_TYPE === "customer"}{fn_url("", "C")}{else}{fn_url("", "A")}{/if}"
            class="bp-logo"
            data-bp-tooltip="true">
            {include file="backend:components/bottom_panel/icons/bp-logo.svg"}
            <div class="bp-tooltip bp-tooltip--left">
            {if $smarty.const.ACCOUNT_TYPE === "customer"}
                {__("bottom_panel.go_to_home_page")}
            {else}
                {__("bottom_panel.go_to_dashboard")}
            {/if}
            </div>
        </a>
        <div class="bp-nav
            {if "MULTIVENDOR"|fn_allowed_for}
                bp-nav--mv
            {/if}
            ">
            <a href="{"bottom_panel.redirect?url=`$config.current_url|urlencode`&area=`$smarty.const.AREA`&to_area=C"|fn_url:'A'}"
                class="bp-nav__item cm-no-ajax
                {if $smarty.const.ACCOUNT_TYPE === "customer"}
                    bp-nav__item--active
                {/if}"
                data-bp-nav-item="customer">
                <span class="bp-nav__item-text">{__("bottom_panel.storefront")}</span>
            </a>
            <a href="{fn_url("bottom_panel.redirect?url=`$config.current_url|urlencode`&area=`$smarty.const.AREA`&user_id=`$auth.user_id`&switch_company_id=0", "A")|replace:$config.vendor_index:$config.admin_index}" class="bp-nav__item cm-no-ajax
                {if $smarty.const.ACCOUNT_TYPE === "admin"}
                    bp-nav__item--active
                {/if}"
                data-bp-nav-item="admin">
                <span class="bp-nav__item-text">{__("bottom_panel.admin_panel")}</span>
            </a>
            {if "MULTIVENDOR"|fn_allowed_for}
                <a href="{fn_url("bottom_panel.redirect?url=`$config.current_url|urlencode`&area=`$smarty.const.AREA`&user_id=`$auth.user_id`", "V")}" class="bp-nav__item cm-no-ajax
                    {if $smarty.const.ACCOUNT_TYPE === "vendor"}
                        bp-nav__item--active
                    {/if}"
                    data-bp-nav-item="vendor">
                    <span class="bp-nav__item-text">{__("bottom_panel.vendor_panel")}</span>
                </a>
            {/if}
            <div id="bp-nav__active" class="bp-nav__active
                {if $smarty.const.ACCOUNT_TYPE === "customer"}
                    bp-nav__active--activated
                {/if}"></div>
        </div>
        {if $smarty.const.ACCOUNT_TYPE === "customer"}
            <div class="bp-modes">
                <a 
                    {if $active_mode === "text"}
                        href="{fn_url("customization.disable_mode?type=live_editor&return_url={$config.current_url|urlencode}")}"
                    {elseif $active_mode === "theme"}
                        href="{fn_url("customization.disable_mode?type=theme_editor&return_url={$config.current_url|urlencode}")}"
                    {elseif $active_mode === "build"}
                        href="{fn_url("customization.disable_mode?type=block_manager&return_url={$config.current_url|urlencode}")}"
                    {else}
                        href="{fn_url("")}"
                    {/if}
                    id="settings_block_manager" class="cm-no-ajax bp-modes__item bp-modes__item--preview 
                    {if $active_mode === "preview"}bp-modes__item--active{/if}" data-bp-modes-item="preview"
                    data-bp-tooltip="true">
                    {include file="backend:components/bottom_panel/icons/bp-modes__item--preview.svg"}
                    <div class="bp-tooltip">{__("bottom_panel.preview_mode")}</div>
                </a>
                {if fn_check_permissions("customization", "update_mode", "admin", "", ["type" => "live_editor"], $smarty.const.AREA, $auth.user_id)}
                    <a href="{fn_url("customization.update_mode?type=live_editor&status=enable&return_url={$c_url|urlencode}")}"
                        id="settings_live_editor"
                        class="cm-no-ajax bp-modes__item bp-modes__item--text
                        {if $active_mode === "text"}bp-modes__item--active{/if}"
                        data-bp-modes-item="text"
                        data-bp-tooltip="true">
                        {include file="backend:components/bottom_panel/icons/bp-modes__item--text.svg"}
                        <div class="bp-tooltip">{__("bottom_panel.text_mode")}</div>
                    </a>
                {/if}
                {if fn_check_permissions("customization", "update_mode", "admin", "", ["type" => "theme_editor"], $smarty.const.AREA, $auth.user_id)}
                    <a href="{fn_url("customization.update_mode?type=theme_editor&status=enable&return_url={$c_url|urlencode}")}"
                        id="settings_theme_editor"
                        class="cm-no-ajax bp-modes__item bp-modes__item--theme
                        {if $active_mode === "theme"}bp-modes__item--active{/if}"
                        data-bp-modes-item="theme"
                        data-bp-tooltip="true">
                        {include file="backend:components/bottom_panel/icons/bp-modes__item--theme.svg"}
                        <div class="bp-tooltip">{__("bottom_panel.theme_mode")}</div>
                    </a>
                {/if}
                {if fn_check_permissions("customization", "update_mode", "admin", "", ["type" => "block_manager"], $smarty.const.AREA, $auth.user_id)}
                    <a href="{if $page === "checkout"}{fn_url("customization.update_mode?type=block_manager&status=enable&return_url={$c_url|urlencode}")}{else}#{/if}"
                       id="settings_block_manager"
                       class="cm-no-ajax bp-modes__item bp-modes__item--build
                        {if $active_mode === "build"}bp-modes__item--active{/if}
                        {if $page !== "checkout"}bp-modes__item--disabled{/if}"
                       data-bp-modes-item="build"
                       data-bp-tooltip="true">
                        {include file="backend:components/bottom_panel/icons/bp-modes__item--build.svg"}
                        <div class="bp-tooltip">
                            {__("bottom_panel.build_mode")}
                            {if $page !== "checkout"}
                                <div class="bp-tooltip__secondary">
                                    {__("bottom_panel.build_mode.not_available")}
                                </div>
                            {/if}
                        </div>
                    </a>
                {/if}
                <div id="bp-modes__active" class="bp-modes__active
                    {if $active_mode === "preview"}
                        bp-modes__active--preview
                    {/if}"
                    ></div>
            </div>
        {/if}
        {hook name="bottom_panel:extra_element_on_panel"}
        {if $edition === "personal_demo" || $edition === "demo"}
            <div class="bp-demo">
                {if $edition === "personal_demo"}
                    {$trial_left = $config.demo_instance.left_days|default:0}
                    <div class="bp-info bp-info--animation">{__("bottom_panel.trial_left")}: {__("n_days", [$trial_left])}</div>
                    <a target="_blank" href="{$config.resources.demo_product_buy_url|fn_link_attach:$utm|fn_url}" class="bp-btn">
                        <span class="bp-btn__text bp-btn__text--animation">{__("bottom_panel.buy_license")}</span>
                    </a>
                {else}
                    <a target="_blank" href="{$config.resources.download|fn_link_attach:$utm|fn_url}" class="bp-btn">
                        <span class="bp-btn__text bp-btn__text--animation">{__("bottom_panel.download")}</span>
                    </a>
                {/if}
            </div>
        {/if}
        {/hook}
        <div class="bp-actions">
            {if $smarty.const.ACCOUNT_TYPE === "customer" || ($edition == "demo" && $config.demo_instance.uid && $config.demo_instance.refreshable)}
                <div class="bp-dropdown bp-actions__item">
                    <button class="bp-dropdown-button bp-dropdown-button--animation" data-bp-toggle="dropdown"
                        data-bp-tooltip="true">
                        {include file="backend:components/bottom_panel/icons/bp-dropdown-button--settings.svg"}
                        <div class="bp-tooltip">{__("bottom_panel.settings")}</div>
                    </button>
                    <div class="bp-dropdown-menu">
                        {if $smarty.const.ACCOUNT_TYPE === "customer"}
                            <div class="bp-dropdown-menu__group">
                                <a class="bp-dropdown-menu__item" href="{fn_url("themes.manage", "A")}" title="{__("bottom_panel.change_theme")}">{__("bottom_panel.change_theme")}</a>
                                <a class="bp-dropdown-menu__item" href="{fn_url("block_manager.manage&selected_location={$location_data.location_id}", "A")}" title="{__("bottom_panel.edit_layout")}">{__("bottom_panel.edit_layout")}</a>
                                <a class="bp-dropdown-menu__item" href="{fn_url("templates.manage", "A")}" title="{__("bottom_panel.edit_template")}">{__("bottom_panel.edit_template")}</a>
                                <a class="bp-dropdown-menu__item" href="{fn_url("languages.translations", "A")}" title="{__("bottom_panel.edit_translations")}">{__("bottom_panel.edit_translations")}</a>
                            </div>
                            <div class="bp-dropdown-menu__group">
                                <a class="bp-dropdown-menu__item" href="{fn_url("menus.manage", "A")}" title="{__("bottom_panel.edit_menus")}">{__("bottom_panel.edit_menus")}</a>
                                {if $page === "products"}
                                    <a class="bp-dropdown-menu__item" href="{fn_url("tabs.manage", "A")}" title="{__("bottom_panel.edit_product_tabs")}">{__("bottom_panel.edit_product_tabs")}</a>
                                {/if}
                            </div>
                        {/if}
                        {hook name="bottom_panel:extra_link_in_settings_menu"}
                        {if $edition === "demo" && $config.demo_instance.uid && $config.demo_instance.refreshable}
                            <div class="bp-dropdown-menu__group">
                                <a class="bp-dropdown-menu__item" href={$config.demo_instance.refresh_url} title="{__("bottom_panel.restore_demo")}">{__("bottom_panel.restore_demo")}</a>
                            </div>
                        {/if}
                        {/hook}
                    </div>
                </div>
            {/if}            
            <div class="bp-dropdown bp-actions__item">
                <button class="bp-dropdown-button" data-bp-toggle="dropdown" data-bp-tooltip="true">
                    {include file="backend:components/bottom_panel/icons/bp-dropdown-button--help.svg"}
                    <div class="bp-tooltip">{__("bottom_panel.help")}</div>
                </button>
                <div class="bp-dropdown-menu">
                    <div class="bp-dropdown-menu__group">
                        <a class="bp-dropdown-menu__item" target="_blank" href="{$config.resources.docs_url|fn_link_attach:$utm|fn_url}">{__("bottom_panel.documentation")}</a>
                        <a class="bp-dropdown-menu__item" target="_blank" href="{$config.resources.forum|fn_link_attach:$utm|fn_url}">{__("bottom_panel.community_forums")}</a>
                        <a class="bp-dropdown-menu__item" target="_blank" href="{$config.resources.video_tutorials|fn_link_attach:$utm|fn_url}">{__("bottom_panel.video_tutorials")}</a>
                        <a class="bp-dropdown-menu__item" target="_blank" href="{$config.resources.faq|fn_link_attach:$utm|fn_url}">{__("bottom_panel.faq")}</a>
                    </div>
                    <div class="bp-dropdown-menu__group">
                        <a class="bp-dropdown-menu__item" target="_blank" href="{$config.resources.helpdesk_url|fn_link_attach:$utm|fn_url}">{__("bottom_panel.customer_help_desk")}</a>
                        <a class="bp-dropdown-menu__item" target="_blank" href="{$config.resources.developers_catalog|fn_link_attach:$utm|fn_url}">{__("bottom_panel.hire_a_developers")}</a>
                    </div>
                    {hook name="bottom_panel:extra_link_in_help_menu"}
                    {/hook}
                </div>
            </div>
        </div>
        <button id="bp_off_bottom_panel" class="bp-close"
            data-bp-tooltip="true"
            data-bp-save-state="true">
            {include file="backend:components/bottom_panel/icons/bp-close.svg"}
            <div class="bp-tooltip bp-tooltip--right">{__("bottom_panel.hide_bottom_admin_panel")}</div>
        </button>
    </div>
    <div id="bp_bottom_buttons" class="bp-bottom-buttons
        {if $smarty.cookies.pb_is_bottom_panel_open === "false"}
            bp-bottom-buttons--active
        {/if}">
        <button id="bp_on_bottom_panel"
            class="bp-bottom-button bp-bottom-button--logo 
            {if $smarty.cookies.pb_is_bottom_panel_open === "true"}
                bp-bottom-button--disabled bp-bottom-button--disabled-panel
            {/if}"
            data-bp-bottom-buttons="panel"
            data-bp-tooltip="true">
            {include file="backend:components/bottom_panel/icons/bp-logo.svg"}
            <div class="bp-tooltip bp-tooltip--left">{__("bottom_panel.show_bottom_admin_panel")}</div>
        </button>
        {hook name="bottom_panel:extra_element_on_closed_panel"}
        {if $edition === "personal_demo"}
            <a class="bp-bottom-button bp-bottom-button--primary bp-bottom-button--text
                {if $smarty.cookies.pb_is_bottom_panel_open === "true"}        
                    bp-bottom-button--disabled bp-bottom-button--disabled-action
                {/if}"
                href="{$config.resources.demo_product_buy_url|fn_link_attach:$utm|fn_url}" data-bp-bottom-buttons="action"
                target="_blank">
                {__("bottom_panel.buy_license")}
            </a>
        {elseif $edition === "demo"}
            <a class="bp-bottom-button bp-bottom-button--primary bp-bottom-button--text
                {if $smarty.cookies.pb_is_bottom_panel_open === "true"}        
                    bp-bottom-button--disabled bp-bottom-button--disabled-action
                {/if}"
                href="{$config.resources.download|fn_link_attach:$utm|fn_url}" data-bp-bottom-buttons="action"
                target="_blank">
                {__("bottom_panel.download")}
            </a>
        {/if}
        {/hook}
    </div>
</div>
{script src="js/tygh/bottom_panel.js"}