require('./../main');

import store from './store/index';
const createorder = require('./components/createupdate.vue').default;
const ordersummary = require('./components/ordersummary.vue').default;
const refundorders = require('./components/refundorders.vue').default;
const refundparticularorder = require('./components/refundparticularorder.vue').default;

var app = new Vue({
   el: '#orderProductApp',
   store,
   components: {
      createorder,
      refundorders,
      refundparticularorder,
      ordersummary,
   }
});
