<div class="ut2-banner ut2-settings-{$b.abt__ut2_device_settings} {$b.abt__ut2_class}">

    {if $b.abt__ut2_button_use == 'N' && $b.abt__ut2_url|trim}
    <a {if $b.abt__ut2_object == 'video' && $b.abt__ut2_youtube_id}data-content="video"{/if}
       href="{$b.abt__ut2_url|fn_url}"{if $b.abt__ut2_how_to_open == 'in_new_window'} target="_blank"{/if}>
        {/if}
        <div class="ut2-a__bg-banner{if $b.abt__ut2_background_color == '#ffffff' && $b.abt__ut2_background_color_use =="Y"} white-bg{/if} {$b.abt__ut2_color_scheme}"
             {strip}style="
             {if $b.abt__ut2_background_color_use == 'Y'} background-color:{$b.abt__ut2_background_color};{/if}
             {if $b.abt__ut2_background_image_size} background-size:{$b.abt__ut2_background_image_size};{/if}
                     margin:{$block.properties.margin};height:{$block.properties.height};"
                {if $b.abt__ut2_background_image.icon.image_path} data-background-url="{$b.abt__ut2_background_image.icon.image_path}"{/if}
                {/strip}>
            <div class="bg-opacity-{$b.abt__ut2_content_bg}" data-id="{$b.banner_id}">
                <div class="ut2-a__content valign-{$b.abt__ut2_content_valign} align-{$b.abt__ut2_content_align}{if $b.abt__ut2_main_image.icon.image_path} internal-image{/if}" style="height: {$block.properties.height};">
                    {if $b.abt__ut2_object == 'image' && $b.abt__ut2_main_image.icon.image_path}
                        <div class="ut2-a__img width{if $b.abt__ut2_content_full_width =="Y"}-full{else}-half{/if}" style="height: {$block.properties.height};">
	                        <img class="lazyOwl" src="{$smarty.const.ABT__UT2_LAZY_IMAGE}" data-src="{$b.abt__ut2_main_image.icon.image_path}" alt="{$b.abt__ut2_main_image.icon.alt}" title="{$b.abt__ut2_main_image.icon.alt}">
                        </div>
                    {elseif $b.abt__ut2_object == 'video' && $b.abt__ut2_youtube_id}
                        <div class="ut2-a__img ut2-a__video width{if $b.abt__ut2_content_full_width =="Y"}-full{else}-half{/if}" style="height: {$block.properties.height};" data-banner-youtube-id="{$b.abt__ut2_youtube_id}" data-banner-youtube-params="{$b|fn_abt__ut2_build_youtube_link:true}&enablejsapi=1&version=3">
	                        <img data-type="youtube-img" class="lazyOwl" src="{$smarty.const.ABT__UT2_LAZY_IMAGE}" data-src="https://img.youtube.com/vi/{$b.abt__ut2_youtube_id}/maxresdefault.jpg" alt="{$b.abt__ut2_title|strip_tags}">
                        </div>
                    {/if}

                    <div class="ut2-a__description width{if $b.abt__ut2_content_full_width =="Y"}-full{else}-half{/if}">
                        <div class="box" style="{if !empty($b.abt__ut2_padding)}padding:{$b.abt__ut2_padding};{/if}">
                            <{$b.abt__ut2_title_tag} class="ut2-a__title {if $b.abt__ut2_title_shadow =="Y"}shadow{/if}
                            weight-{$b.abt__ut2_title_font_weight}" style="font-size: {$b.abt__ut2_title_font_size}
                            ;{if $b.abt__ut2_title_color_use == 'Y'}color: {$b.abt__ut2_title_color};{/if}
                            ">{$b.abt__ut2_title nofilter}</{$b.abt__ut2_title_tag}>
                        <div class="ut2-a__descr" style="
	                        {if $b.abt__ut2_description_color_use == 'Y'}color: {$b.abt__ut2_description_color};{/if}
		                    {if $b.abt__ut2_description_bg_color_use == 'Y'}background-color:{$b.abt__ut2_description_bg_color};
			                {if $b.abt__ut2_description_bg_color}position: relative;left: 5px;display: inline;padding: 3px 0 3px;box-shadow: {$b.abt__ut2_description_bg_color} -5px 0 0 0, {$b.abt__ut2_description_bg_color} 5px 0 0 0;{/if}{/if}
					        {if $b.abt__ut2_description_font_size}font-size: {$b.abt__ut2_description_font_size};{/if}">
						    {$b.abt__ut2_description nofilter}
						</div>

						{if $b.abt__ut2_button_use == 'Y' && $b.abt__ut2_url|trim}
			                <div class="ut2-a__button">
			                    <a class="ty-btn ty-btn__primary" style="{if $b.abt__ut2_button_text_color_use == 'Y'}color: {$b.abt__ut2_button_text_color};{/if}{if $b.abt__ut2_button_color_use == 'Y'}background: {$b.abt__ut2_button_color};{/if}" href="{$b.abt__ut2_url|fn_url}"{if $b.abt__ut2_how_to_open == 'in_new_window'} target="_blank"{/if}>{$b.abt__ut2_button_text|default:"button"}</a>
			                </div>
		                {/if}
                    </div>
                </div>
            </div>
        </div>
</div>
{if $b.abt__ut2_button_use == 'N' && $b.abt__ut2_url|trim}
    </a>
{/if}
</div>