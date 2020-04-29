{* block-description:abt__ut2_advanced_subcategories_menu *}
{if $abt__ut2_subcategories}
    <ul class="ut2-subcategories clearfix">
        {foreach from=$abt__ut2_subcategories item=category name="ssubcateg"}
            {if $category.category_id == $_REQUEST.category_id}
                <li class="ut2-item ut2-current-item">
                    <span {live_edit name="category:category:{$category.category_id}"}>{$category.category}</span>
                </li>
            {else if}
                <li class="ut2-item">
                    <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}">
                        <span {live_edit name="category:category:{$category.category_id}"}>{$category.category}</span>
                    </a>
                </li>
            {/if}
        {/foreach}
    </ul>
{/if}