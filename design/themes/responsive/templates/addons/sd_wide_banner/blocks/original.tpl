{** block-description:original **}
{if $items}
    <div class="sd-wide-banner__container">
            {foreach from=$items item="banner" key="key"}
                <style>
                    .sd-wide-banner__content--color--{$banner.banner_id} .sd-wide-banner__content-title {
                        color: {$banner.font_color};
                    }

                    .sd-wide-banner__content--color--{$banner.banner_id} .sd-wide-banner__content-subtitle {
                        color: {$banner.font_color};
                    }

                    .sd-wide-banner__content--color--{$banner.banner_id} .sd-wide-banner__content-button {
                        background-color: {$banner.button_color};
                    }

                    .sd-wide-banner__content--color--{$banner.banner_id} .sd-wide-banner__content-button:hover,
                    .sd-wide-banner__content--color--{$banner.banner_id} .sd-wide-banner__content-button:active {
                        filter: brightness(90%);
                    }
                </style>
                <div class="sd-wide-banner__image-item sd-wide-banner__banner--{$banner.banner_id}">
                    {if $banner.type == "G" && $banner.main_pair.image_id}
                        {if $banner.url != "" && !$banner.button_text}<a class="banner__link" href="{$banner.url|fn_url}" {if $banner.target == "B"}target="_blank"{/if}>{/if}
                            {include file="common/image.tpl" images=$banner.main_pair class="sd-wide-banner__image"}

                            <div class="sd-wide-banner__wrap">
                                <div class="sd-wide-banner__content sd-wide-banner__content--color--{$banner.banner_id}">
                                    {if $banner.banner_title}
                                        <div class="sd-wide-banner__content-title align-center">
                                            {$banner.banner_title}
                                        </div>
                                    {/if}
                                    {if $banner.banner_subtitle}
                                        <div class="sd-wide-banner__content-subtitle align-center">
                                            {$banner.banner_subtitle}
                                        </div>
                                    {/if}
                                    {if $banner.button_text}
                                        <div class="sd-wide-banner__content-button align-center">
                                            <a href="{$banner.url|fn_url}" class="button">{$banner.button_text}</a>
                                        </div>
                                    {/if}
                                </div>
                            </div>
                        {if $banner.url != "" && !$banner.button_text}</a>{/if}
                    {else}
                        <div class="ty-wysiwyg-content sd-wide-banner__text-item">
                            {$banner.description nofilter}
                        </div>
                    {/if}
                </div>
            {/foreach}
    </div>
{/if}
