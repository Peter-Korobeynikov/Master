{*
array  $id                  Storefront ID
array  $all_currencies      All currencies
int[]  $selected_currencies Storefront currency IDs
string $input_name          Input name
*}

{$input_name = $input_name|default:"storefront_data[currency_ids][]"}

<div class="control-group">
    <label for="currencies_{$id}"
           class="control-label cm-required cm-multiple-checkboxes"
    >
        {__("currencies")}
    </label>
    <div class="controls" id="currencies_{$id}">
        <div class="cm-combo-checkbox-group">
            <input type="hidden"
                   name="{$input_name}"
                   value=""
            />

            <label class="checkbox"
                   for="currency_all_{$id}"
            >
                <input type="checkbox"
                       class="cm-checkbox-group"
                       data-ca-checkbox-group-role="toggler"
                       data-ca-checkbox-group="currencies_{$id}"
                       id="currency_all_{$id}"
                       {if $selected_currencies === []}
                           checked
                           disabled
                       {/if}
                />
                {__("all_currencies")}
            </label>

            {foreach $all_currencies as $currency}
                <label class="checkbox"
                       for="currency_{$currency.currency_id}_{$id}"
                >
                    <input type="checkbox"
                           class="cm-checkbox-group"
                           data-ca-checkbox-group-role="togglee"
                           data-ca-checkbox-group="currencies_{$id}"
                           name="{$input_name}"
                           value="{$currency.currency_id}"
                           id="currency_{$currency.currency_id}_{$id}"
                           {if in_array($currency.currency_id, $selected_currencies)}
                               checked
                           {/if}
                    />

                    {$currency.description}
                </label>
            {/foreach}
        </div>
    </div>
</div>
