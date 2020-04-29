{styles}
{style src="addons/facebook_store/styles.css"}
{/styles}

<div class="fb_reset">
    <div id="content">
        <div id="header">
            <div id="logo-container">
                <a href="http://cs-cart.com"></a>
            </div>
        </div>
        <div id="page-title">
            <h1>{__("facebook_store.add_store_to_page", ["[product]" => $smarty.const.PRODUCT_NAME])}</h1>
        </div>
        <div id="add-page">
            {__("facebook_store.text_page_steps", ["[product]" => $smarty.const.PRODUCT_NAME]) nofilter}

            <a class="button orange-btn" target="_blank" href="{$add_tab_url}">{__("facebook_store.select_page")}</a>

            <p class="note">
                {__("facebook_store.text_new_page_note") nofilter}
            </p>
        </div>
    </div>

</div>
