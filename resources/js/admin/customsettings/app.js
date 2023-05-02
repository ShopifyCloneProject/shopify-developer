require('./../main');

import store from './store/index';
const customsettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#customSettingsApp',
   store,
   components: {
       customsettings,
   }
});
