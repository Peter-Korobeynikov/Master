{if $video.icon_type == 'icon' && $video.icon}
    {include file="common/image.tpl" images=$video.icon image_width=$width image_height=$height show_detailed_link=false}
{else}
    <img class="lazyOwl" src="{$smarty.const.AB__VG_LAZY_IMAGE}" data-src="https://img.youtube.com/vi/{$video.youtube_id}/hqdefault.jpg" {if $width}width="{$width}"{/if} {if $height}height="{$height}"{/if} alt="{$video.title|strip_tags}" />
{/if}