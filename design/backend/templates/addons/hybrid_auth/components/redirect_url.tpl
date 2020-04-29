{$protocol = ($settings.Security.secure_storefront === "YesNo::YES"|enum) ? "https" : "http"}
<div class="control-group">
    <label class="control-label">{{__('hybrid_auth.live_redirect_urls')}}: </label>
    <div class="controls">
        <input type="text"
               class="span8"
               readonly="readonly"
               value="{fn_url("", "C", $protocol)}index.php"
               onclick="this.select()"
        />
    </div>
</div>
