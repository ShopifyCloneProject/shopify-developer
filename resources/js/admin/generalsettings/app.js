require('./../main');

import store from './store/index';
const generalsettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#generalSettingsApp',
   store,
   components: {
       generalsettings
   }
});
