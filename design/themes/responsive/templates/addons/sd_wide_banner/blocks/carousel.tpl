{** block-description:carousel **}
{if $items}
    {foreach $items as $banner}
        <style>
            .sd-wide-banner__content--color--{$banner.banner_id} .sd-wide-banner__content-title {
                color: {$banner.font_color};
            }

            .sd-wide-banner__content--color--{$banner.banner_id} .sd-wide-banner__content-subtitle {
                color: {$banner.font_color};
            }

            .sd-wide-banner__content--color--{$banner.banner_id} .sd-wide-banner__content-button .button{
                background-color: {$banner.button_color};
            }

            .sd-wide-banner__content--color--{$banner.banner_id} .sd-wide-banner__content-button .button:hover,
            .sd-wide-banner__content--color--{$banner.banner_id} .sd-wide-banner__content-button .button:active {
                filter: brightness(90%);
            }
        </style>
    {/foreach}
    <div class="sd-wide-banner__container">
        <div id="sd_banner_slider_{$block.snapping_id}" class="banners owl-carousel">
            {foreach from=$items item="banner" key="key"}
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
                                            <a href="{$banner.url|fn_url}" class="button graphic-btn">{$banner.button_text}</a>
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
    </div>
{/if}

<script type="text/javascript">
-(function(_, $) {
    $.ceEvent('on', 'ce.commoninit', function(context) {
        var slider = context.find('#sd_banner_slider_{$block.snapping_id}');
        if (slider.length) {
            slider.owlCarousel({
                direction: '{$language_direction|escape:"javascript"}',
                items: 1,
                singleItem : true,
                slideSpeed: 400,
                autoPlay: '{$block.properties.delay * 1000|default:false}',
                stopOnHover: true,
                {if $block.properties.navigation == "N"}
                    pagination: false
                {/if}
                {if $block.properties.navigation == "D"}
                    pagination: true
                {/if}
                {if $block.properties.navigation == "P"}
                    pagination: true,
                    paginationNumbers: true
                {/if}
                {if $block.properties.navigation == "A"}
                    pagination: false,
                    navigation: true,
                    navigationText: ['{__("prev_page")|escape:"javascript"}', '{__("next")|escape:"javascript"}']
                {/if}
                {if $block.properties.navigation == "DA"}
                    pagination: true,
                    navigation: true,
                    navigationText: ['{__("prev_page")|escape:"javascript"}', '{__("next")|escape:"javascript"}']
                {/if}
                {if $block.properties.navigation == "PA"}
                    pagination: true,
                    paginationNumbers: true,
                    navigation: true,
                    navigationText: ['{__("prev_page")|escape:"javascript"}', '{__("next")|escape:"javascript"}']
                {/if}
            });
        }
    });
}(Tygh, Tygh.$));
</script>