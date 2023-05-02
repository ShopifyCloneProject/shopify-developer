require('./../main');

import store from './store/index';
const checkoutsettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#checkoutSettingsApp',
   store,
   components: {
       checkoutsettings,
   }
});
