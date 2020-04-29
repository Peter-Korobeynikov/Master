<div class="hidden" id="content_required_products">
    {include file="views/products/components/picker/picker.tpl"
        input_name="required_product_ids[]"
        item_ids=$required_products
        multiple=true
        view_mode="external"
        select_group_class="btn-toolbar"
    }
</div>