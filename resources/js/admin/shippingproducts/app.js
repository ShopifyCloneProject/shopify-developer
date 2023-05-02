require('./../main');

import store from './store/index';
const shippingproducts = require('./components/shippingproducts.vue').default;

var app = new Vue({
   el: '#shippingProductApp',
   store,
   components: {
      shippingproducts,
   }
});
