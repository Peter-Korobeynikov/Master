<label for="ccategories_{$rnd}_ids" class="control-label">{__("yml_export.param_exclude_categories")}:</label>
<div class="controls">
    {include file="pickers/categories/picker.tpl"
        input_name="pricelist_data[exclude_categories]"
        item_ids=$price.param_data.exclude_categories
        multiple=true
    }
</div>
