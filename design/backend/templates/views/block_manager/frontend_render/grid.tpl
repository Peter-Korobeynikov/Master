{if $layout_data.layout_width != "fixed"}
    {if $parent_grid.width > 0}
        {$fluid_width = fn_get_grid_fluid_width($layout_data.width, $parent_grid.width, $grid.width)}
    {else}
        {$fluid_width = $grid.width}
    {/if}
{/if}



{if $grid.status == "A" && $content}
    {if $grid.alpha}<div class="{if $layout_data.layout_width != "fixed"}row-fluid {else}row{/if}">{/if}
        {$width = $fluid_width|default:$grid.width}
        <div class="span{$width}{if $grid.offset} offset{$grid.offset}{/if} {$grid.user_class}"
            data-ca-block-manager-grid-id="{$grid.grid_id}">
            {if $grid.wrapper}
                {include file=$grid.wrapper content=$content}
            {else}
                {$content nofilter}
            {/if}
        </div>
    {if $grid.omega}</div>{/if}
{/if}