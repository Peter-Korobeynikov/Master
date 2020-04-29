{script src="js/lib/owlcarousel/owl.carousel.min.js"}

{strip}

<div class="help-tutorial-wrapper {if !$open}close-content{/if} {if $items|count > 1}help-tutorial-video{/if}" id="help_tutorial_video">
    <div class="help-tutorial-content clearfix {if $items|count > 1}owl-carousel help-tutorial-slider{/if} {if $open} open{/if}" id="help_tutorial_content">
        {foreach from=$items item=hash}
            <div class="help-tutorial-content_width_big">
            <iframe width="640" height="360" src="//www.youtube.com/embed/{$hash}?enablejsapi=1&wmode=transparent&rel=0&html5=1{$params}" frameborder="0" allowfullscreen></iframe>
            </div>
        {/foreach}
    </div>

    <div class="help-tutorial-all-video">
        <a href="https://www.cs-cart.ru/videos/admin/" target="_blank">{__("help_tutorial.videos_show")}</a>
    </div>
</div>

<script type="text/javascript">
    (function(_, $) {
        $(function() {
            $('#help_tutorial_link').on('click', function() {
                $(this).toggleClass('open');
                $('#help_tutorial_content').toggleClass('open');
                $('#help_tutorial_video').toggleClass('close-content');
            });

            if ($('#help_tutorial_video').length) {
                $('#header').addClass('help-tutorial-video-header');
            }
        });

        $(document).on('click', '.help-tutorial-video .owl-controls', function() {
            $('.help-tutorial-video').find('iframe').each(function() {
                $(this)[0].contentWindow.postMessage(JSON.stringify({
                    "event": "command",
                    "func": "pauseVideo",
                    "args": ""
                }), "*");
            });
        });

        $(document).on('click', '.help-tutorial-close', function() {
            $('.help-tutorial-video').find('iframe').each(function() {
                $(this)[0].contentWindow.postMessage(JSON.stringify({
                    "event": "command",
                    "func": "pauseVideo",
                    "args": ""
                }), "*");
            });
        });

        $.ceEvent('on', 'ce.commoninit', function(context) {
            var slider = context.find('#help_tutorial_content.help-tutorial-slider');
            if (slider.length) {
                slider.owlCarousel({
                    direction: '{$language_direction}',
                    items: 1,
                    singleItem : true,
                    autoPlay: false,
                    stopOnHover: true,
                    pagination: true,
                    paginationNumbers: true,
                    navigation: true,
                    navigationText: ['', '']
                });
            }
        });
    }(Tygh, Tygh.$));
</script>
{/strip}
