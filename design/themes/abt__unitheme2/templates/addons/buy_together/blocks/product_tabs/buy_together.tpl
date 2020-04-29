{** block-description:buy_together **}

{if $runtime.controller == 'promotions' || $runtime.controller == 'ab__dotd'}
    {include file="addons/abt__unitheme2/hooks/products/components/buy_together.tpl" show_scroll=false}
{elseif $settings.abt__ut2.products.addon_buy_together.view == 'as_tab_in_tabs' || ( $runtime.controller == 'products' && $runtime.mode == 'options' )}
    {include file="addons/abt__unitheme2/hooks/products/components/buy_together.tpl" show_scroll=true}
{/if}