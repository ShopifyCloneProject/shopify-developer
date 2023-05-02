require('./../main');

import store from './store/index';
const filessettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#filesSettingsApp',
   store,
   components: {
       filessettings,
   }
});
