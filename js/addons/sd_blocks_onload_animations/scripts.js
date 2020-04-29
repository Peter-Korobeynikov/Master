$(function() {
    $('.sd-animated').each(function() {
        var delay = $(this).attr('class').match(/sd-animation-delay-([\d]+)/);
        delay = delay ? 300 * parseInt(delay[1]) : 0;
        
        var obj = this;
        setTimeout(function() {
            $(obj).waypoint({
                handler: function(direction) {
                    $(this.element).addClass('in');
                },
                offset: '95%',
                triggerOnce: true,
            });
        },delay);
    });
});