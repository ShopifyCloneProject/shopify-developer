require('./../main');

import store from './store/index';
const taxessettings = require('./components/index.vue').default;
const statetaxes = require('./components/statetaxes.vue').default;

var app = new Vue({
   el: '#taxesSettingsApp',
   store,
   components: {
       taxessettings,
       statetaxes
   }
});
