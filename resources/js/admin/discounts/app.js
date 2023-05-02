require('./../main');

import store from './store/index';
const createupdatediscounts = require('./components/createupdate.vue').default;

var app = new Vue({
   el: '#discountsApp',
   store,
   components: {
       createupdatediscounts,
   }
});
