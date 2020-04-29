{include file="common/subheader.tpl" title=__("salesbeat") target="#acc_salesbeat"}
<div id="acc_salesbeat" class="collapse in">
    <div class="control-group">
	    <label class="control-label" for="show_sb_in_tab">{__("sb_in_tab")}:</label>
	    <div class="controls">
	        <input type="hidden" name="product_data[sb_show_in_tab]" value="N" />
	        <input type="checkbox" name="product_data[sb_show_in_tab]" id="show_sb_in_tab" value="Y" {if $product_data.sb_show_in_tab== "Y"}checked="checked"{/if} class="checkbox" />
	    </div>
    </div>
    <div class="control-group">
	    <label class="control-label" for="sb_price_insurance">{__("sb_price_insurance")}:</label>
	    <div class="controls">
	        <input type="text" name="product_data[price_insurance]" id="sb_price_insurance" value="{$product_data.price_insurance}" />
	    </div>
    </div>
</div>
