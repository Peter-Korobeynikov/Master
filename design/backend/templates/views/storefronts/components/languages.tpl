{*
array  $id                 Storefront ID
array  $all_languages      All languages
int[]  $selected_languages Storefront language IDs
string $input_name         Input name
*}

{$input_name = $input_name|default:"storefront_data[language_ids][]"}

<div class="control-group">
    <label for="languages_{$id}"
           class="control-label cm-required cm-multiple-checkboxes"
    >
        {__("languages")}
    </label>
    <div class="controls" id="languages_{$id}">
        <div class="cm-combo-checkbox-group">
            <input type="hidden"
                   name="{$input_name}"
                   value=""
            />

            <label class="checkbox"
                   for="language_all_{$id}"
            >
                <input type="checkbox"
                       class="cm-checkbox-group"
                       data-ca-checkbox-group-role="toggler"
                       data-ca-checkbox-group="languages_{$id}"
                       id="language_all_{$id}"
                        {if $selected_languages === []}
                            checked
                            disabled
                        {/if}
                />
                {__("all_languages")}
            </label>

            {foreach $all_languages as $language}
                <label class="checkbox"
                       for="language_{$language.lang_id}_{$id}"
                >
                    <input type="checkbox"
                           class="cm-checkbox-group"
                           data-ca-checkbox-group-role="togglee"
                           data-ca-checkbox-group="languages_{$id}"
                           name="{$input_name}"
                           value="{$language.lang_id}"
                           id="language_{$language.lang_id}_{$id}"
                           {if in_array($language.lang_id, $selected_languages)}checked{/if}
                    />

                    {$language.name}
                </label>
            {/foreach}
        </div>
    </div>
</div>
