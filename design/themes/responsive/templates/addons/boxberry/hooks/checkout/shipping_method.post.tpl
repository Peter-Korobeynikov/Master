{if $cart.chosen_shipping.$group_key == $shipping.shipping_id && $shipping.module == 'boxberry'}
    {assign var="shipping_id" value="{$shipping.shipping_id}"}
    {if $shipping.service_code == 'boxberry_self' || $shipping.service_code == 'boxberry_self_prepaid'}
    <input type="hidden" name="boxberry_selected_point[{$group_key}][{$shipping_id}]" value="{$cart.shippings_extra.boxberry.$group_key.$shipping_id.point_id}">
    <input type="hidden" name="boxberry_selected_point_full_name[{$group_key}][{$shipping_id}]" value="{$cart.shippings_extra.boxberry.$group_key.$shipping_id.point_full_name}">
    Пункт выдачи: <a class="bxb_link" id="bxb_link_{$shipping_id}" data-boxberry-open="true"
       data-boxberry-token="{$cart.shippings_extra.boxberry.$group_key.$shipping_id.apiKeyWidget}"
       data-boxberry-city="{$group.package_info.location.city} {$group.package_info.location.state_descr}"
       data-boxberry-weight="{$cart.shippings_extra.boxberry.$group_key.$shipping_id.boxberry_weight}"
       data-api_url="{$cart.shippings_extra.boxberry.$group_key.$shipping_id.api_url}"
       data-widget_url="{$cart.shippings_extra.boxberry.$group_key.$shipping_id.widget_url}"
       data-sucrh="{$cart.shippings_extra.boxberry.$group_key.$shipping_id.sucrh}"
       data-paymentsum="{$cart.shippings_extra.boxberry.$group_key.$shipping_id.boxberry_paymentsum}"
       data-ordersum="{$cart.shippings_extra.boxberry.$group_key.$shipping_id.boxberry_ordersum}"
       data-boxberry-point-input="boxberry_selected_point[{$group_key}][{$shipping_id}]"
       data-boxberry-point-full-name-input="boxberry_selected_point_full_name[{$group_key}][{$shipping_id}]"
    >{$cart.shippings_extra.boxberry.$group_key.$shipping_id.point_full_name|default:'Выберите пункт выдачи'}</a>
    {script src="js/addons/boxberry/boxberry.js"}
    {/if}
{/if}