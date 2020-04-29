{** block-description:ab__video_gallery_title_product **}
{$ab__vg_videos = $product.product_id|fn_ab__vg_get_videos}
{if $ab__vg_videos}
<div id="content_ab__video_gallery" class="ab__video_gallery-block">
<div class="ab__vg-videos">
{foreach $ab__vg_videos as $video}
<div class="ab__vg-video">
<a class="cm-dialog-opener" data-ca-target-id="ab__vg_video_{$video.video_id}" rel="nofollow">
<span class="ab__vg-video_thumb">
{include file="addons/ab__video_gallery/components/thumbnail.tpl" video=$video width=370 height=240}
</span>
</a>
<div class="ab__vg-video_title"><p><strong>{$video.title}</strong></p></div>
<div class="ab__vg-video_description ty-wysiwyg-content">{$video.description nofilter}</div>
</div>
{/foreach}
</div>
</div>
{/if}