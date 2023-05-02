require('./../main');

import store from './store/index';
const dashboard = require('./components/index.vue').default;

var app = new Vue({
   el: '#dashboard-ecommerce',
   store,
   components: {
       dashboard,
   }
});
