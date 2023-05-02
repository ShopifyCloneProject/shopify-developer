require('./../main');

import store from './store/index';
const locationsettings = require('./components/index.vue').default;
const createlocation = require('./components/create.vue').default;

var app = new Vue({
   el: '#locationSettingsApp',
   store,
   components: {
       locationsettings,
       createlocation
   }
});
