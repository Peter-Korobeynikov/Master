{strip}
<script data-no-defer>
    (function(_, $) {
        {* Init abt__ut2 - settings, data and functions *}
        $.extend(_, {
            abt__ut2: {
                settings: {$settings.abt__ut2|json_encode nofilter},
                controller : '{$runtime.controller}',
                mode : '{$runtime.mode}',
                device : '{$settings.abt__device}'
            }
        });
    }(Tygh, Tygh.$));
</script>
{/strip}
{script src="js/addons/abt__unitheme2/abt__ut2.js"}
{script src="js/addons/abt__unitheme2/abt__ut2_ajax_blocks.js"}
{script src="js/addons/abt__unitheme2/abt__ut2_grid_tabs.js"}
{script src="js/addons/abt__unitheme2/abt__ut2_swipe_menu.js"}
{script src="js/addons/abt__unitheme2/abt__ut2_light_menu.js"}
{script src="js/addons/abt__unitheme2/abt__ut2_youtube.js"}
{script src="js/addons/abt__unitheme2/abt__ut2_load_more.js"}
{script src="js/addons/abt__unitheme2/abt__ut2_custom_combination.js"}