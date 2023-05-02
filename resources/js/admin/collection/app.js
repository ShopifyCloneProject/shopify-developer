require('./../main');

import store from './store/index';
const collection = require('./components/collection.vue').default;



var app = new Vue({
   el: '#addCollectionApp',
   store,
   components: {
       collection
   }
});
