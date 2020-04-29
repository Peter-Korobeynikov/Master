<div id="container_addon_option_facebook_store_tab_url" class="control-group setting-wide facebook_store">
    <label for="addon_option_facebook_store_page_id" class="control-label ">{__("facebook_store.tab_url")}:
    </label>

    <div class="controls">
        <input id="addon_option_facebook_store_tab_url" type="text" size="100" value="{fn_url("facebook_store.show_store", "C")}" readonly onclick="this.select()">
        <div class="right update-for-all">
        </div>
    </div>
</div>

<div id="container_addon_option_facebook_store_game_url" class="control-group setting-wide facebook_store">
    <label for="addon_option_facebook_store_page_id" class="control-label ">{__("facebook_store.game_url")}:
    </label>

    <div class="controls">
        <input id="addon_option_facebook_store_game_url" type="text" size="100" value="{fn_url("facebook_store.add_tab", "C")}" readonly onclick="this.select()">
        <div class="right update-for-all">
        </div>
    </div>
</div>
