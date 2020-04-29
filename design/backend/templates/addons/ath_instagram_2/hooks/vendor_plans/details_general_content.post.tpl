<div class="control-group">

    <label class="control-label" for="elm_instagram_{$id}">{__("instagram")}:</label>
    <div class="controls">
        <input type="hidden" name="plan_data[instagram_2_allow]" value="0" />
        <input type="checkbox" id="elm_instagram_{$id}" name="plan_data[instagram_2_allow]" size="10" value="1"{if $plan.instagram_2_allow} checked="checked"{/if} />
    </div>
    
</div>