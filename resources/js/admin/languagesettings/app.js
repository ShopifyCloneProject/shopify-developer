require('./../main');

import store from './store/index';
const languagesettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#languageSettingsApp',
   store,
   components: {
       languagesettings
   }
});
