require('./../main');

import store from './store/index';
const product = require('./components/product.vue').default;


var app = new Vue({
   el: '#variantsSection',
   store,
   components: {
       product
   }
});
