{** block-description:tmpl_abt__ut2__recent_posts_advanced **}

{assign var="parent_id" value=$block.content.items.parent_page_id}

{if $items}
{assign var="obj_prefix" value="`$block.block_id`000"}

<div class="ut2-blog__recent-posts">
    {foreach from=$items item="page" name="fp"}
    	{if $smarty.foreach.fp.iteration <=7}
        <div class="ut2-blog__recent-posts--item {if $smarty.foreach.fp.first}first{/if}">
        	<a href="{"pages.view?page_id=`$page.page_id`"|fn_url}" >
                {if $smarty.foreach.fp.first}
                {$image_data=$page.main_pair|fn_image_to_display:550:366}
                <div class="ut2-blog__recent-posts--img cover {if !$page.main_pair}no-image{/if}" {if $page.main_pair}data-background-url="{$image_data.image_path}"{/if}><div class="ut2-blog__date">{$page.timestamp|date_format:"`$settings.Appearance.date_format`"}</div></div>
                {else}
                {$image_data=$page.main_pair|fn_image_to_display:268:179}
                <div class="ut2-blog__recent-posts--img cover {if !$page.main_pair}no-image{/if}" {if $page.main_pair}data-background-url="{$image_data.image_path}"{/if}><div class="ut2-blog__date">{$page.timestamp|date_format:"`$settings.Appearance.date_format`"}</div></div>
                {/if}
                <div class="ut2-blog__recent-posts--title">{$page.page|truncate:70:"...":true}</div>
            </a>
        </div>
        {/if}
    {/foreach}
</div>
{if $settings.abt__ut2.general.blog_page_id|intval > 0}
	<div class="ty-left">
	    {include file="buttons/button.tpl" but_href="pages.view?page_id={$settings.abt__ut2.general.blog_page_id|intval}"|fn_url but_text=__("abt__ut2.settings.general.blog_page_id.button") but_icon="ut2-icon-right" but_meta="ty-btn__primary ut2-blog__button"}
	</div>
{/if}

{/if}