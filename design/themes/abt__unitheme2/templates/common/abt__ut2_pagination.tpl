{$p=$search|fn_generate_pagination}
{$id="`$type`_pagination_contents"}

{if $position == 'top'}
    <div id="{$id}">
{elseif $position == 'bottom'}
    <!--{$id}--></div>

    {if $p.next_page > $p.current_page}
        <div class="ut2-load-more-container" id="load_more_{$id}">
            {$show_more=$p.items_per_page}
            {$left_products=$p.total_items-($p.items_per_page*$p.current_page)}
            {if $left_products < $p.items_per_page}{$show_more=$left_products}{/if}
            {assign var="c_url" value=$config.current_url|fn_query_remove:"page"}
            <span class="ut2-load-more" data-ut2-load-more-url="{"`$c_url`&page=`$p.next_page``$extra_url`"|fn_url}" data-ut2-load-more-result-ids="{"`$type`_pagination_contents"},load_more_{$type}_pagination_contents,pagination_block,pagination_block_bottom"><i class="loader"></i>{__("abt__ut2.load_more.show_more.`$object`", [$show_more])}</span>
        <!--load_more_{$id}--></div>
    {/if}
{/if}
