{** block-description:blog.recent_posts_scroller **}

{if $items}

{assign var="obj_prefix" value="`$block.block_id`000"}

{if $block.properties.outside_navigation == "Y"}
    <div class="owl-theme ty-owl-controls">
        <div class="owl-controls clickable owl-controls-outside" id="owl_outside_nav_{$block.block_id}">
            <div class="owl-buttons">
                <div id="owl_prev_{$obj_prefix}" class="owl-prev"><i class="ty-icon-left-open-thin"></i></div>
                <div id="owl_next_{$obj_prefix}" class="owl-next"><i class="ty-icon-right-open-thin"></i></div>
            </div>
        </div>
    </div>
{/if}

<div class="ut2-blog__recent-posts scroller">
    <div id="scroll_list_{$block.block_id}" class="owl-carousel ty-scroller-list">

    {foreach from=$items item="page"}
        <div class="ut2-blog__recent-posts--item">

            <div class="ut2-blog__recent-posts--img">
                <a href="{"pages.view?page_id=`$page.page_id`"|fn_url}">
				{$image_data=$page.main_pair|fn_image_to_display:377:251}
                <div class="ut2-blog__recent-posts--img cover {if !$page.main_pair}no-image{/if}" {if $page.main_pair}style="background-image: url('{$image_data.image_path}');"{/if}><div class="ut2-blog__date">{$page.timestamp|date_format:"`$settings.Appearance.date_format`"}</div></div>
                </a>
            </div>

            <a href="{"pages.view?page_id=`$page.page_id`"|fn_url}" class="ut2-blog__recent-posts--title">{$page.page}</a>
        </div>
    {/foreach}

    </div>
</div>


{include file="common/scroller_init_with_quantity.tpl" prev_selector="#owl_prev_`$obj_prefix`" next_selector="#owl_next_`$obj_prefix`"}

{/if}