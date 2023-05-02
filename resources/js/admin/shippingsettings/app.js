require('./../main');

import store from './store/index';
const shippingsettings = require('./components/index.vue').default;
const localdelivery = require('./components/localdelivery.vue').default;
const localpickup = require('./components/localpickup.vue').default;
const createrates = require('./components/createrates.vue').default;
const managerates = require('./components/managerates.vue').default;
const addeditrates = require('./components/addeditrates.vue').default;

var app = new Vue({
   el: '#shippingSettingsApp',
   store,
   components: {
       shippingsettings,
       localdelivery,
       localpickup,
       createrates,
       addeditrates,
       managerates
   }
});
