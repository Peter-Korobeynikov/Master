{script src="js/addons/sd_blocks_onload_animations/lib/jquery.waypoints.min.js"}
{script src="js/addons/sd_blocks_onload_animations/scripts.js"}

<script type="text/javascript">
(function(_, $) {

    {foreach from=$smarty.session.disabled_animations_blocks item=item}
        $(".block_{$item}").each(function(){
            $(this).attr('class',$(this).attr('class').replace(/sd-.+?(\s|$)/g,''));
        })
    {/foreach}

    {foreach from=$smarty.session.disabled_animations_grids item=item}
        $(".grid_{$item}").each(function(){
            $(this).attr('class',$(this).attr('class').replace(/sd-.+?(\s|$)/g,''));
        })
    {/foreach}

}(Tygh, Tygh.$));
</script>