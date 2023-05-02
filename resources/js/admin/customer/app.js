require('./../main');

import store from './store/index';
const addeditcustomer = require('./components/index.vue').default;
const showcustomer = require('./components/show.vue').default;
const editcustomer = require('./components/edit.vue').default;

var app = new Vue({
   el: '#customerApp',
   store,
   components: {
       addeditcustomer,
       showcustomer,
       editcustomer
   }
});
