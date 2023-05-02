require('./../main');

import store from './store/index';
const exchangeorders = require('./components/exchangeorders.vue').default;
const showexchangeorders = require('./components/showexchangeorders.vue').default;

var app = new Vue({
   el: '#exchangeordersApp',
   store,
   components: {
      exchangeorders,
      showexchangeorders,
   }
});
