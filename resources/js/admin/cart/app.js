require('./../main');

import store from './store/index';
const createcartproduct = require('./components/createupdate.vue').default;

var app = new Vue({
   el: '#cartProductApp',
   store,
   components: {
      createcartproduct
   }
});
