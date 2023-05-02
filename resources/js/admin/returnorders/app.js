require('./../main');

import store from './store/index';
const showreturnorders = require('./components/showreturnorders.vue').default;

var app = new Vue({
   el: '#showreturnordersApp',
   store,
   components: {
      showreturnorders
   }
});
