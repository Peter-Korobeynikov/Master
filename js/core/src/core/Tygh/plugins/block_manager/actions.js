import { params } from './params';
import { api } from './api';
import { animation } from './animation';
import $ from "jquery";

export const actions = {
    _snapBlocks: function (block) {
        var snapping = {};
        var blocks = block.parent().find(params.block_selector);

        blocks.each(function () {
            var _block = $(this);
            var index = _block.index();
            
            snapping[index] = {
                grid_id: _block.parent().data('caBlockManagerGridId'),
                order: index,
                snapping_id: _block.data('caBlockManagerSnappingId'),
                action: 'update',
            };
        });

        return snapping;
    },

    _executeAction: function (action) {
        var execute_result = false;

        if (action == 'switch') {
            execute_result = actions._blockSwitch();
        } else if (action == 'move') {
            execute_result = actions._blockMove();
        }

        return execute_result;
    },

    _blockSwitch: function () {
        var status = (params._self.data('caBlockManagerSwitch')) ? 'A' : 'D';
        var dynamic_object = 0;
        var switch_show_icon = params._self.find(params.switch_icon_show_selector);
        var switch_hide_icon = params._self.find(params.switch_icon_hide_selector);
        
        var data = {
            snapping_id: params._hover_element.data('caBlockManagerSnappingId'),
            object_id: dynamic_object,
            object_type: '',
            status: status,
            type: 'block'
        };

        api.sendRequest('update_status', '', data);

        if (status === 'A') {
            params._self.removeClass(params.block_disabled_class);
            params._hover_element.removeClass(params.block_disabled_class);
            params._self.data('caBlockManagerSwitch', false);
            switch_hide_icon.addClass(params.switch_icon_hidden_class);
            switch_show_icon.removeClass(params.switch_icon_hidden_class);
        } else {
            params._self.addClass(params.block_disabled_class);
            params._hover_element.addClass(params.block_disabled_class);
            params._self.data('caBlockManagerSwitch', true);
            switch_show_icon.addClass(params.switch_icon_hidden_class);
            switch_hide_icon.removeClass(params.switch_icon_hidden_class);
        }

        return true;
    },

    _blockMove: function () {
        var direction = params._self.data('caBlockManagerMove');
        var snapping = {};

        if (direction === 'up') {
            params._hover_element.prev().insertAfter(params._hover_element);
            animation.up();
        } else if (direction === 'down') {
            params._hover_element.next().insertBefore(params._hover_element);
            animation.down();
        }

        snapping = actions._snapBlocks(params._hover_element);

        api.sendRequest('snapping', '', {
            snappings: snapping
        });

        return true;
    },
};
