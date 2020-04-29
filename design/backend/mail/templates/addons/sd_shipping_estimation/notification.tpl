{include file="common/letter_header.tpl"}

<h2>{__("addons.sd_shipping_estimation.email_header")}</h2>
<b>{__("addons.sd_shipping_estimation.email_body")}</b>

<table>
    {foreach from=$shippings item=shipping}
        <tr>{$shipping.shipping}</tr>
    {/foreach}
</table>

{include file="common/letter_footer.tpl"}