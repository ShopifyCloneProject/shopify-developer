require('./../main');

import store from './store/index';
const themesettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#themeSettingsApp',
   store,
   components: {
       themesettings,
   }
});
