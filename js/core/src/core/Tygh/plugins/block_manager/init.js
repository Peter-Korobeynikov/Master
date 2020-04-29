import { Tygh } from '../..';
import { params } from './params';
import { actions } from './actions';
import { sortable } from './sortable';
import $ from "jquery";

let isInit;

export const init = {
    init: function () {
        if (isInit) {
            return;
        }

        sortable._sortable();

        $(Tygh.doc).on('click', params.action_selector, function (e) {
            params._self = $(this);
            var jelm = params._self.parents(params.menu_selector).parent().parent();

            params._hover_element = jelm;
            var action = params._self.data('caBlockManagerAction');

            return actions._executeAction(action);
        });

        isInit = true;
    }
};
