<div class="control-group cm-no-hide-input">
    <label class="control-label" for="elm_grid_animation_effect_{$id}">
        {__("animation_effect")}
    </label>
    <div class="controls">
        <select id="elm_grid_animation_effect_{$id}" name="animation_effect">
            <option value="none"
                {if $grid.animation_effect == "none"}selected="selected"{/if}
            >
                {__("none")}
            </option>
            <option value="sd-scale-up"
                {if $grid.animation_effect == "sd-scale-up"}selected="selected"{/if}
            >
                {__("scale-up")}
            </option>
            <option value="sd-scale-down"
                {if $grid.animation_effect == "sd-scale-down"}selected="selected"{/if}
            >
                {__("scale-down")}
            </option>
            <option value="sd-slide-left"
                {if $grid.animation_effect == "sd-slide-left"}selected="selected"{/if}
            >
                {__("slide-left")}
            </option>
            <option value="sd-slide-right"
                {if $grid.animation_effect == "sd-slide-right"}selected="selected"{/if}
            >
                {__("slide-right")}
            </option>
            <option value="sd-slide-up"
                {if $grid.animation_effect == "sd-slide-up"}selected="selected"{/if}
            >
                {__("slide-up")}
            </option>
            <option value="sd-slide-down"
                {if $grid.animation_effect == "sd-slide-down"}selected="selected"{/if}
            >
                {__("slide-down")}
            </option>
            <option value="sd-fade-in"
                {if $grid.animation_effect == "sd-fade-in"}selected="selected"{/if}
            >
                {__("fade-in")}
            </option>
        </select>
    </div>
</div>

<div class="control-group cm-no-hide-input">
    <label class="control-label" for="elm_grid_animation_duration_{$id}">
        {__("animation_duration")}
    </label>
    <div class="controls">
        <input id="elm_grid_animation_duration_{$id}"
            name="animation_duration"
            value="{$grid.animation_duration}"
            type="text"
        />
    </div>
</div>

<div class="control-group cm-no-hide-input">
    <label class="control-label" for="elm_grid_animation_delay_{$id}">
        {__("animation_delay")}
    </label>
    <div class="controls">
        <input id="elm_grid_animation_delay_{$id}"
            name="animation_delay"
            value="{$grid.animation_delay}"
            type="text"
        />
    </div>
</div>

<div class="control-group cm-no-hide-input">
    <label class="control-label" for="elm_grid_animation_speed_{$id}">
        {__("animation_speed")}
    </label>
    <div class="controls">
        <select id="elm_grid_animation_speed_{$id}" name="animation_speed">
            <option value="none"
                {if $grid.animation_speed == "none"}selected="selected"{/if}
            >
                {__("none")}
            </option>
            <option value="sd-ease-in"
                {if $grid.animation_speed == "sd-ease-in"}selected="selected"{/if}
            >
                {__("ease-in")}
            </option>
            <option value="sd-ease-in-out"
                {if $grid.animation_speed == "sd-ease-in-out"}selected="selected"{/if}
            >
                {__("ease-in-out")}
            </option>
            <option value="sd-linear"
                {if $grid.animation_speed == "sd-linear"}selected="selected"{/if}
            >
                {__("linear")}
            </option>
        </select>
    </div>
</div>

<div class="control-group cm-no-hide-input">
    <label class="control-label" for="elm_grid_number_of_impressions_{$id}">
        {__("number_of_impressions")}
    </label>
    <div class="controls">
        <input id="elm_grid_number_of_impressions_{$id}"
            name="number_of_impressions"
            value="{$grid.number_of_impressions}"
            type="text"
        />
    </div>
</div>
