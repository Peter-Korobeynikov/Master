import React from 'react';
import ReactDOM from 'react-dom';
import $ from 'jquery';

import { createStore } from "redux";
import { Provider } from 'react-redux';

import { reducer, actions } from "./reducer";
import { getNotifications } from "./api";
import { NotificationsCenter, NotificationsCenterCounter } from "./component";

const NotificationsCenterStore = createStore(reducer);

const initNotificationsCenter = async function() {
  const payload = await getNotifications({
    items_per_page: NotificationsCenterStore.getState().fetchPerPage,
    page: NotificationsCenterStore.getState().fetchPage
  });

  try {
    NotificationsCenterStore.dispatch({ type: actions.START_LOAD });
    NotificationsCenterStore.dispatch({ type: actions.APPLY_DATA, payload });
    NotificationsCenterStore.dispatch({ type: actions.END_LOAD });
    NotificationsCenterStore.dispatch({ type: actions.SELECT_FIRST_SECTION });
  } catch (err) {
    NotificationsCenterStore.dispatch({ type: actions.END_LOAD });
  }

  ReactDOM.render(
    (
      <Provider store={NotificationsCenterStore}>
        <NotificationsCenterCounter />
      </Provider>
    ),
    document.querySelector('[data-ca-notifications-center-counter]')
  );
}

$.ceEvent('one', 'ce.commoninit', initNotificationsCenter);

$.ceEvent('on', 'ce.notifications_center.reloaded', function (callback) {
    initNotificationsCenter().then(function() {
        if (callback) {
            callback();
        }
    });
});

$.ceEvent('on', 'ce.notifications_center.enabled', async function (langVars) {
  ReactDOM.render(
    (
      <Provider store={NotificationsCenterStore}>
        <NotificationsCenter langVars={langVars} />
      </Provider>
    ),
    document.querySelector('[data-ca-notifications-center-root]')
  );
});
