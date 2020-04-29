{if $category_banner}
    
    {if $layout == 'products_without_options' && $move_hr_to_the_top}
        <hr>
    {/if}

    <div class="{$item_class} category-banner">
        {if $category_banner.url}
        <a {if $category_banner.target_blank == 'Y'}target="_blank"{/if} href="{$category_banner.url|fn_url}">
            {/if}
            {if $layout == 'products_multicolumns'}
                {include file="common/image.tpl" images=$category_banner.main_pair}
            {else}
                {include file="common/image.tpl" images=$category_banner.list_pair}
            {/if}
            {if $category_banner.url}
        </a>
        {/if}
    </div>

    {if $layout == 'products_without_options' && !$move_hr_to_the_top}
        <hr>
    {/if}
{/if}