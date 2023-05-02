require('./../main');

import store from './store/index';
const abandonecheckouts = require('./components/abandonecheckouts.vue').default;

var app = new Vue({
   el: '#abandoneCheckoutsApp',
   store,
   components: {
      abandonecheckouts
   }
});
