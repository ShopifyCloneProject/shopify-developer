require('./../main');

import store from './store/index';
const notificationsettings = require('./components/index.vue').default;
const notifications = require('./components/notifications.vue').default;
const createupdatetemplate = require('./components/createupdatetemplate.vue').default;

var app = new Vue({
   el: '#notificationSettingsApp',
   store,
   components: {
       notificationsettings,
       notifications,
       createupdatetemplate
   }
});
