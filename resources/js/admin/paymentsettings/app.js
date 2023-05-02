require('./../main');

import store from './store/index';
const paymentsettings = require('./components/index.vue').default;
const paymentmethods = require('./components/paymentmethods.vue').default;
const paymentdetails = require('./components/paymentdetails.vue').default;

var app = new Vue({
   el: '#paymentSettingsApp',
   store,
   components: {
       paymentsettings,
       paymentmethods,
       paymentdetails
   }
});
