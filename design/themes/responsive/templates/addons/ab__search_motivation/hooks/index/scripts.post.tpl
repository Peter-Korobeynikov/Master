{$search_phrases = ""|fn_ab__search_motivation_get_phrases}
{if $search_phrases}
    {script src="js/addons/ab__search_motivation/theater.js"}
    <script>
        (function (_, $) {
            $.ceEvent('on', 'ce.commoninit', function(context) {
                var input = $('#search_input:not(.initialized)',context);
                if (input.length) {
                    var theaterForSearchBox = new TheaterJS();
                    theaterForSearchBox
                        .describe("SearchBox", .8, "#search_input")
                        {foreach $search_phrases as $search_phrase}
                        .write("SearchBox:{$search_phrase|trim}").write({
                            name: 'wait',
                            args: [2000]
                        })
                        {/foreach}
                        .write(function () {
                            theaterForSearchBox.play(true);
                        });

                    input.addClass("initialized").removeClass('cm-hint').val('').attr('name', 'q');
                }
            });
        })(Tygh, Tygh.$);
    </script>
{/if}