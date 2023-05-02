require('./../main');

import store from './store/index';
const plansettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#planSettingsApp',
   store,
   components: {
       plansettings,
   }
});
