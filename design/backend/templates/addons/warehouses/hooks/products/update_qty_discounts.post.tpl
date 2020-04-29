{if $warehouses}
<div class="hidden" id="content_warehouses_quantity">
    <div class="table-responsive-wrapper">
        <table class="table table-middle table--relative table-responsive" width="100%">
            <thead>
                <tr>
                    <th>{__("warehouses.name")}</th>
                    <th width="30%">{__("warehouses.city")}</th>
                    <th width="15%">{__("warehouses.store_type")}</th>
                    <th width="15%">{__("warehouses.quantity")}</th>
                </tr>
            </thead>
            <tbody>
                {foreach $warehouses as $warehouse}
                    <tr class="cm-row-item cm-row-status-{$warehouse.status|strtolower}">
                        <td data-th="{__("warehouses.name")}">
                            <a href="{"store_locator.update?store_location_id=`$warehouse.store_location_id`"|fn_url}"
                               class="row-status"
                               target="_blank"
                            >{$warehouse.name}</a>
                        </td>
                        <td data-th="{__("warehouses.city")}">
                            <span class="row-status">
                                {$warehouse.city}
                            </span>
                        </td>
                        <td data-th="{__("warehouses.store_type")}">
                            <span class="row-status">
                                {$store_types[$warehouse.store_type]}
                            </span>
                        </td>
                        <td data-th="{__("warehouses.quantity")}">
                            {$amount = ""}
                            {if $warehouses_amounts[$warehouse.warehouse_id]}
                                {$amount = $warehouses_amounts[$warehouse.warehouse_id]["amount"]}
                            {/if}
                            <input type="text" name="product_data[warehouses][{$warehouse.warehouse_id}]" value="{$amount}" class="input-small"/></td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{/if}
