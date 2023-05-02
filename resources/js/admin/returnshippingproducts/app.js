require('./../main');

import store from './store/index';
const returnshippingproducts = require('./components/returnshippingproducts.vue').default;

var app = new Vue({
   el: '#returnshippingproductsApp',
   store,
   components: {
      returnshippingproducts,
   }
});
