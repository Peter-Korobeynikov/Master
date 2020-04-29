<div class="product-variations-convert-summary">
    {if !empty($errors)}
        <h4 class="text-error">{__("product_variations.converter.progress.errors")}</h4>
        <table width="100%" class="table table-no-hover table--relative">
            {foreach $errors as $error name="errors"}
                <tr {if $smarty.foreach.errors.first} class="no-border"{/if}>
                    <td class="text-error">{$error}</td>
                </tr>
            {/foreach}
        </table>
        <div style="padding: 10px">
            {__("product_variations.converter.result.problems_encountered")}
        </div>
    {/if}
    <div class="table-wrapper">
        <table width="100%" class="table table-no-hover table--relative">
            {if $by_variations}
                <tr class="no-border">
                    <td width="60%"><strong>{__("product_variations.converter.progress.result.configurable_products_count")}</strong></td>
                    <td align="right">{$counter.configurable_products}</td>
                </tr>
                <tr class="no-border">
                    <td width="60%"><strong>{__("product_variations.converter.progress.result.variations_count")}</strong></td>
                    <td align="right">{$counter.variations}</td>
                </tr>
            {/if}
            {if $by_combinations}
                <tr class="no-border">
                    <td width="60%"><strong>{__("product_variations.converter.progress.result.products_with_combinations_count")}</strong></td>
                    <td align="right">{$counter.products_with_combinations}</td>
                </tr>
                <tr class="no-border">
                    <td width="60%"><strong>{__("product_variations.converter.progress.result.combinations_count")}</strong></td>
                    <td align="right">{$counter.combinations}</td>
                </tr>
            {/if}
        </table>
        {if $counter.configurable_products || $counter.products_with_combinations}
            <div>
                <a href="{"products.manage?updated_in_hours=1&show_all_products=1"|fn_url}" target="_blank">{__("product_variations.converter.progress.result.go_on_products")}</a>
            </div>
        {/if}
    </div>
    <div>
        <a class="btn cm-notification-close pull-right">{__("close")}</a>
    </div>
</div>