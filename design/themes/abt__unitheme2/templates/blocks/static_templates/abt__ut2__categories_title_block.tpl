{** block-description:tmpl_abt__ut2__categories_title_block **}

<div class="ut2-extra-block-title">
    {hook name="wrapper:categories_title_wrapper"}
        <h1 class="ty-mainbox-title">
            {hook name="wrapper:categories_title"}
				<span>{$category_data.category}</span>
            {/hook}
        </h1>
    {/hook}   
	{include file="common/breadcrumbs.tpl"}
</div>

