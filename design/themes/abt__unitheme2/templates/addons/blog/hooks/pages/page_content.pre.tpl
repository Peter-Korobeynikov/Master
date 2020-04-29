{if $page.description && $page.page_type == $smarty.const.PAGE_TYPE_BLOG}
    <div class="ut2-blog__date">{$page.timestamp|date_format:"`$settings.Appearance.date_format`"}</div>
    {if $page.main_pair}
        <div class="ut2-blog__img-block">
            {include file="common/image.tpl" obj_id=$page.page_id images=$page.main_pair}
        </div>
    {/if}
{/if}
