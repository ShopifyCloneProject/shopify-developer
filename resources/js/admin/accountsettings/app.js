require('./../main');

import store from './store/index';
const accountsettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#accountSettingsApp',
   store,
   components: {
       accountsettings,
   }
});
