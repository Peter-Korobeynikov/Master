{$payment_methods = fn_get_payments(['status' => 'A'])}

{if $payment_methods}
    <ul>
        {foreach $payment_methods as $payment}
            <li>
                — {$payment.payment}{if $payment.description}<span{if !$runtime.customization_mode.live_editor} class="cm-tooltip"{/if} title="{$payment.description}">{include file="addons/ab__motivation_block/blocks/components/info_icon.tpl"}</span>{/if}
            </li>
        {/foreach}
    </ul>
{/if}