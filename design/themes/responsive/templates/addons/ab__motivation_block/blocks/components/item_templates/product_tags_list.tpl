{if $addons.tags.status == 'A'}
    {$tags = fn_ab__mb_get_tags(['object_id' => $product.product_id,
                                'items_per_page' => $ab__mb_item.template_settings.tags_items_per_page,
                                'sort_by' => 'popularity',
                                'sort_order' => 'desc'])}

    {if $tags}
        <ul class="ty-tags-list">
            {foreach $tags as $tag}
                <li class="ty-tags-list__item">
                    <a class="ty-tags-list__a" href="{"tags.view?tag=`$tag.tag`"|fn_url}" target="_blank" rel="nofollow">{$tag.tag}</a>
                </li>
            {/foreach}
        </ul>
    {/if}
{/if}