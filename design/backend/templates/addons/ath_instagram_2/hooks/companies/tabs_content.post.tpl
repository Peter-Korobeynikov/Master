{if !"ULTIMATE"|fn_allowed_for}

<div id="content_instagram">

	{if $addons.vendor_plans.status == "A"}
		{assign var="instagram_allow" value=$company_data.plan_id|fn_check_vendor_plan2}
	{/if}
	
	{if $addons.vendor_plans.status == "A" && !$instagram_allow}

		<div class="control-group center">
			{__("not_available_for_your_plan")}
			<input type="text" name="company_data[instagram_key]" id="vendor_instagram_access_token" value="" class="hide" />
		</div>
	
	{else}
	
		<div class="control-group">
		    <label for="elm_company_instagram" class="control-label">{__("vendor_instagram_name")}:</label>
		    <div class="controls">
		        <input type="text" name="company_data[instagram_2_name]" id="elm_company_instagram" value="{$company_data.instagram_2_name}" class="input-long" size="10" />
		    </div>
		</div>
			
	{/if}

</div>

{/if}