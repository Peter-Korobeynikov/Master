{if $category_banner}
    <div class="{$item_class} category-banner"{if $layout != 'products_without_options'} style="height: {$smarty.capture.abt__ut2_gl_item_height-1}px"{/if}>
        {if $category_banner.url}
        <a {if $category_banner.target_blank == 'Y'}target="_blank"{/if} href="{$category_banner.url|fn_url}">
            {/if}
            {if $layout == 'products_multicolumns'}
                {include file="common/image.tpl" images=$category_banner.main_pair lazy_load=true}
            {else}
                {include file="common/image.tpl" images=$category_banner.list_pair lazy_load=true}
            {/if}
            {if $category_banner.url}
        </a>
        {/if}
    </div>
{/if}