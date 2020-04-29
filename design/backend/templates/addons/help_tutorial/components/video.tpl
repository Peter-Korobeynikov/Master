{strip}

<div class="help-tutorial-wrapper">
    <div class="help-tutorial-content clearfix {if $items|count > 1}help-tutorial-content_width_big{/if}{if $open} open{/if}" id="help_tutorial_content">
        {if $items|count > 1}
            {assign var="width" value="460"}
        {else}
            {assign var="width" value="640"}
        {/if}
        {assign var="align" value="left"}
        {foreach from=$items item=hash key="key"}
            {if $key == 1}
                {assign var="align" value="right"}
            {/if}
            <div class="help-tutorial-iframe-wrapper">
                <iframe width="{$width}" height="360" src="//www.youtube.com/embed/{$hash}?wmode=transparent&rel=0&html5=1" frameborder="0" allowfullscreen align="{$align}"></iframe>
            </div>
        {/foreach}
    </div>
</div>

{literal}
<script type="text/javascript">
    (function(_, $) {
        $(function() {
            $(_.doc).on('click', '#help_tutorial_link', function() {
                $(this).toggleClass('open');
                $('.help-tutorial-wrapper').toggleClass('open');
                $('#help_tutorial_content').toggleClass('open');
            });
        });
    }(Tygh, Tygh.$));
</script>
{/literal}
{/strip}
