import { params } from './params';

export const animation = {
    up: function () {
        params._hover_element.addClass(params.block_got_up_class);

        setTimeout(function () {
            params._hover_element.removeClass(params.block_got_up_class);
        }, 300);
    },

    down: function () {
        params._hover_element.addClass(params.block_got_down_class);

        setTimeout(function () {
            params._hover_element.removeClass(params.block_got_down_class);
        }, 300);
    },
};
