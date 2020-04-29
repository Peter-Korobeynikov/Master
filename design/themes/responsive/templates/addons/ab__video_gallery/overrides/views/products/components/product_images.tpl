{assign var="th_size" value=$addons.ab__video_gallery.th_size|default:35}

{if $product.main_pair.icon || $product.main_pair.detailed}
    {assign var="image_pair_var" value=$product.main_pair}
{elseif $product.option_image_pairs}
    {assign var="image_pair_var" value=$product.option_image_pairs|reset}
{/if}

{$is_vertical = (($runtime.mode != 'quick_view') && ($addons.ab__video_gallery.vertical == 'Y'))}

{if $image_pair_var.image_id}
    {assign var="image_id" value=$image_pair_var.image_id}
{else}
    {assign var="image_id" value=$image_pair_var.detailed_id}
{/if}

{if !$preview_id}
    {assign var="preview_id" value=$product.product_id|uniqid}
{/if}

{$ab__vg_videos = $product.product_id|fn_ab__vg_get_videos}
{$ab__vg_settings = $product.product_id|fn_ab__vg_get_setting}

{$_count = $product.image_pairs|count}
{$total_count = ($_count + $ab__vg_videos|count)}
{if $total_count == 0}{$v_gal_width = 0}{elseif $total_count > 6 && $settings.Appearance.thumbnails_gallery == "N"}{$v_gal_width = ($th_size * 2 + 18)}{else}{$v_gal_width = ($th_size + 12)}{/if}

<div class="ab_vg-images-wrapper" data-ca-previewer="true">
<div id="product_images_{$preview_id}" class="ty-product-img cm-preview-wrapper{if $is_vertical} ab-vertical" style="width: -webkit-calc(100% - {$v_gal_width}px);width: calc(100% - {$v_gal_width}px);"{else}"{/if}>

    {* print video_tumbs to var *}
    {capture name="ab__vg_videos"}
        {if $ab__vg_videos}
            {$ab__video_width = $settings.Thumbnails.product_details_thumbnail_width}
            {$ab__video_height = $settings.Thumbnails.product_details_thumbnail_height}

            {foreach $ab__vg_videos as $video}
                {if $product.details_layout == 'bigpicture_template' && $video.icon === "snapshot"}
                    {$image_width = 800}
                    {$image_height = 600}
                {/if}

                {if $addons.ab__video_gallery.on_thumbnail_click == 'image_replace' || $quick_view}
                    <div id="det_img_link_{$preview_id}_{$video.video_id}" class="ab__vg_loading ab__vg-image_gallery_video cm-image-previewer{if $ab__vg_settings.replace_image != 'Y' || $video@iteration > 1} hidden{/if}" data-src="{include file="addons/ab__video_gallery/components/build_url.tpl" youtube_id=$video.youtube_id}" data-frameborder="0" data-allowfullscreen="1">
                        {include file="addons/ab__video_gallery/components/thumbnail.tpl" video=$video width=$image_width height=$image_height}
                    </div>
                {else}
                    <a id="det_img_link_{$preview_id}_{$video.video_id}" class="ab__vg-image_gallery_video cm-image-previewer cm-dialog-opener{if $ab__vg_settings.replace_image != 'Y' || $video@iteration > 1} hidden{/if}" data-ca-target-id="ab__vg_video_{$video.video_id}" rel="nofollow">
                        {include file="addons/ab__video_gallery/components/thumbnail.tpl" video=$video width=$image_width height=$image_height}
                    </a>
                {/if}
            {/foreach}
        {/if}
    {/capture}

    {* output videos before images *}
    {if $addons.ab__video_gallery.position == 'pre'}
        {$smarty.capture.ab__vg_videos nofilter}
    {/if}

    {include file="common/image.tpl" obj_id="`$preview_id`_`$image_id`" images=$image_pair_var link_class="cm-image-previewer{if $ab__vg_videos && $ab__vg_settings.replace_image == 'Y'} hidden{/if}" image_width=$image_width image_height=$image_height image_id="preview[product_images_`$preview_id`]"}

    {foreach from=$product.image_pairs item="image_pair"}
        {if $image_pair}
            {if $image_pair.image_id}
                {assign var="img_id" value=$image_pair.image_id}
            {else}
                {assign var="img_id" value=$image_pair.detailed_id}
            {/if}
            {include file="common/image.tpl" images=$image_pair link_class="cm-image-previewer hidden" obj_id="`$preview_id`_`$img_id`" image_width=$image_width image_height=$image_height image_id="preview[product_images_`$preview_id`]"}
        {/if}
    {/foreach}

    {* output videos after images *}
    {if $addons.ab__video_gallery.position == 'post'}
        {$smarty.capture.ab__vg_videos nofilter}
    {/if}
</div>


{* Video popups content. For tab with videos or for popup onclick *}
{foreach $ab__vg_videos as $video}
    <div id="ab__vg_video_{$video.video_id}" class="ab__vg_video_popup cm-popup-box hidden" data-ca-keep-in-place="true" title="{$video.title}">
        <div class="ab__vg_loading" data-src="{include file="addons/ab__video_gallery/components/build_url.tpl" youtube_id=$video.youtube_id}" data-frameborder="0" data-allowfullscreen="1">
            {include file="addons/ab__video_gallery/components/thumbnail.tpl" video=$video width=$ab__vg_default_video_width height=$ab__vg_default_video_height}
        </div>
    </div>
{/foreach}

{if $product.image_pairs || $ab__vg_videos}
    {$image_counter = -1}
    {if $settings.Appearance.thumbnails_gallery == "Y"}
        <input type="hidden" name="no_cache" value="1" />
        {strip}
            <div class="ty-center ty-product-bigpicture-thumbnails_gallery{if $is_vertical} ab-vg-vertical-thumbnails" style="width: {$v_gal_width - 2}px"{else}"{/if}>
                <div class="cm-image-gallery-wrapper ty-thumbnails_gallery ty-inline-block">
                    {strip}
                        <div class="ab__vg-product-thumbnails cm-ab__vg-gallery" id="images_preview_{$preview_id}" data-ca-cycle="{$addons.ab__video_gallery.cycle}" data-ca-vertical="{if $is_vertical}Y{else}N{/if}">
                            {if $ab__vg_videos && $addons.ab__video_gallery.position == 'pre'}
                                {foreach $ab__vg_videos as $video}
                                    {$image_counter = $image_counter + 1}
                                    <div class="cm-item-gallery">
                                        <a data-ca-gallery-large-id="det_img_link_{$preview_id}_{$video.video_id}" class="ab__vg-image_gallery_item cm-gallery-item cm-thumbnails-mini ty-product-thumbnails__item gallery {if $ab__vg_settings.replace_image == 'Y' && $video@iteration == 1} active{/if}" style="width: {$th_size}px" data-ca-image-order="{$image_counter}" data-ca-parent="#product_images_{$preview_id}">
                                            <img src="{$images_dir}/addons/ab__video_gallery/youtube_logo.png" width="{$th_size}" height="{$th_size}" alt="{$video.title|strip_tags}" />
                                        </a>
                                    </div>
                                {/foreach}
                            {/if}

                            {if $image_pair_var}
                                {$image_counter = $image_counter + 1}
                                <div class="cm-item-gallery">
                                    <a data-ca-gallery-large-id="det_img_link_{$preview_id}_{$image_id}" class="cm-gallery-item cm-thumbnails-mini ty-product-thumbnails__item gallery {if !$ab__vg_videos || $ab__vg_settings.replace_image != 'Y'} active{/if}" style="width: {$th_size}px" data-ca-image-order="{$image_counter}" data-ca-parent="#product_images_{$preview_id}">
                                        {include file="common/image.tpl" ab__vg_gallery_image=true images=$image_pair_var image_width=$th_size image_height=$th_size show_detailed_link=false obj_id="`$preview_id`_`$image_id`_mini"}
                                    </a>
                                </div>
                            {/if}
                            {if $product.image_pairs}
                                {foreach from=$product.image_pairs item="image_pair"}
                                    {$image_counter = $image_counter + 1}
                                    {if $image_pair}
                                        <div class="cm-item-gallery">
                                            {if $image_pair.image_id}
                                                {assign var="img_id" value=$image_pair.image_id}
                                            {else}
                                                {assign var="img_id" value=$image_pair.detailed_id}
                                            {/if}
                                            <a data-ca-gallery-large-id="det_img_link_{$preview_id}_{$img_id}" class="cm-gallery-item cm-thumbnails-mini ty-product-thumbnails__item gallery" data-ca-image-order="{$image_counter}" data-ca-parent="#product_images_{$preview_id}">
                                                {include file="common/image.tpl" ab__vg_gallery_image=true images=$image_pair image_width=$th_size image_height=$th_size show_detailed_link=false obj_id="`$preview_id`_`$img_id`_mini"}
                                            </a>
                                        </div>
                                    {/if}
                                {/foreach}
                            {/if}

                            {if $ab__vg_videos && $addons.ab__video_gallery.position == 'post'}
                                {foreach $ab__vg_videos as $video}
                                    {$image_counter = $image_counter + 1}
                                    <div class="cm-item-gallery">
                                        <a data-ca-gallery-large-id="det_img_link_{$preview_id}_{$video.video_id}" class="ab__vg-image_gallery_item cm-gallery-item cm-thumbnails-mini ty-product-thumbnails__item gallery {if $ab__vg_settings.replace_image == 'Y' && $video@iteration == 1} active{/if}" style="width: {$th_size}px" data-ca-image-order="{$image_counter}" data-ca-parent="#product_images_{$preview_id}">
                                            <img src="{$images_dir}/addons/ab__video_gallery/youtube_logo.png" width="{$th_size}" height="{$th_size}" alt="{$video.title|strip_tags}" />
                                        </a>
                                    </div>
                                {/foreach}
                            {/if}

                        </div>
                    {/strip}
                </div>
            </div>
        {/strip}
    {else}
        <div class="ab__vg-product-thumbnails ty-center{if $is_vertical} ab-vg-vertical-thumbnails" style="width: {$v_gal_width-4}px"{else}"{/if} id="images_preview_{$preview_id}">
            {strip}

                {if $ab__vg_videos && $addons.ab__video_gallery.position == 'pre'}
                    {foreach $ab__vg_videos as $video}
                        {$image_counter = $image_counter + 1}
                        <a data-ca-gallery-large-id="det_img_link_{$preview_id}_{$video.video_id}" class="ab__vg-image_gallery_item ty-product-thumbnails__item cm-thumbnails-mini{if $ab__vg_settings.replace_image == 'Y' && $video@iteration == 1} active{/if}" data-ca-image-order="{$image_counter}" data-ca-parent="#product_images_{$preview_id}">
                            <img src="{$images_dir}/addons/ab__video_gallery/youtube_logo.png" width="{$th_size}" height="{$th_size}" alt="{$video.title|strip_tags}" />
                        </a>
                    {/foreach}
                {/if}

                {if $image_pair_var}
                    {$image_counter = $image_counter + 1}
                    <a data-ca-gallery-large-id="det_img_link_{$preview_id}_{$image_id}" class="cm-thumbnails-mini ty-product-thumbnails__item{if !$ab__vg_videos || $ab__vg_settings.replace_image != 'Y'} active{/if}" data-ca-image-order="{$image_counter}" data-ca-parent="#product_images_{$preview_id}">
                        {include file="common/image.tpl" ab__vg_gallery_image=true images=$image_pair_var image_width=$th_size image_height=$th_size show_detailed_link=false obj_id="`$preview_id`_`$image_id`_mini"}
                    </a>
                {/if}

                {if $product.image_pairs}
                    {foreach from=$product.image_pairs item="image_pair"}
                        {$image_counter = $image_counter + 1}
                        {if $image_pair}
                            {if $image_pair.image_id == 0}
                                {assign var="img_id" value=$image_pair.detailed_id}
                            {else}
                                {assign var="img_id" value=$image_pair.image_id}
                            {/if}
                            <a data-ca-gallery-large-id="det_img_link_{$preview_id}_{$img_id}" class="cm-thumbnails-mini ty-product-thumbnails__item" data-ca-image-order="{$image_counter}" data-ca-parent="#product_images_{$preview_id}">
                                {include file="common/image.tpl" ab__vg_gallery_image=true images=$image_pair image_width=$th_size image_height=$th_size show_detailed_link=false obj_id="`$preview_id`_`$img_id`_mini"}
                            </a>
                        {/if}
                    {/foreach}
                {/if}

                {if $ab__vg_videos && $addons.ab__video_gallery.position == 'post'}
                    {foreach $ab__vg_videos as $video}
                        {$image_counter = $image_counter + 1}
                        <a data-ca-gallery-large-id="det_img_link_{$preview_id}_{$video.video_id}" class="ab__vg-image_gallery_item ty-product-thumbnails__item cm-thumbnails-mini{if $ab__vg_settings.replace_image == 'Y' && $video@iteration == 1} active{/if}" data-ca-image-order="{$image_counter}" data-ca-parent="#product_images_{$preview_id}">
                            <img src="{$images_dir}/addons/ab__video_gallery/youtube_logo.png" width="{$th_size}" height="{$th_size}" alt="{$video.title|strip_tags}" />
                        </a>
                    {/foreach}
                {/if}

            {/strip}
        </div>
    {/if}
{/if}
</div>


{include file="common/previewer.tpl"}

{script src="js/addons/ab__video_gallery/product_image_gallery.js"}

{hook name="products:product_images"}{/hook}