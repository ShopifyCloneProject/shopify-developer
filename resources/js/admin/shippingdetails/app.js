require('./../main');

import store from './store/index';
const shippingdetails = require('./components/index.vue').default;

var app = new Vue({
   el: '#shippingDetailsApp',
   store,
   components: {
      shippingdetails,
   }
});
