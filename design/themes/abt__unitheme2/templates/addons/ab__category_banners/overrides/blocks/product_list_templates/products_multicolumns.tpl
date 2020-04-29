{** template-description:tmpl_grid **}

{capture name="products_grid_html"}
    {$tmpl='products_multicolumns'}

    {include file="blocks/product_list_templates/default_params/`$tmpl`.tpl"}
    {include file="blocks/list_templates/grid_list.tpl"}
{/capture}

{if $ab__cb_banner_exists}
    {$smarty.capture.products_grid_html|fn_ab__cb_insert_category_banner:'products_multicolumns'}
{/if}

{$smarty.capture.products_grid_html nofilter}