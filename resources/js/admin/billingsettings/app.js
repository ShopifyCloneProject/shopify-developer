require('./../main');

import store from './store/index';
const billingsettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#billingSettingsApp',
   store,
   components: {
       billingsettings,
   }
});
