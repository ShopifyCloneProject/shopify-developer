require('./../main');

import store from './store/index';
const legalsettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#legalSettingsApp',
   store,
   components: {
       legalsettings
   }
});
