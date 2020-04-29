import { params } from './params';
import { actions } from './actions';
import { api } from './api';
import $ from "jquery";

export const sortable = {
    _sortable: function () {

        var sortable_params = {
            items: params.sortable_items_selector,
            update: function (event, ui) {
                var snapping = actions._snapBlocks($(ui.item));

                api.sendRequest('snapping', '', {
                    snappings: snapping
                });
            }
        };

        $.extend(params, sortable_params);

        $(params.grid_selector).sortable(params);
    }
};
