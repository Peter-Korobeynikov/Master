{if $addons.ab__motivation_block.description_type == 'smarty'}
<div class="sidebar-row ab-mb-sidebar-row">
<h6>{__('ab__mb.update_help.title')}</h6>
{foreach [1] as $tip}
<p>{__("ab__mb.update_help.tip_{$tip}")}</p>
{/foreach}
</div>
{/if}