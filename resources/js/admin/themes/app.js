require('./../main');

import store from './store/index';

const themeselection = require('./components/selectedtheme.vue').default;
const createtheme = require('./components/create.vue').default;

var app = new Vue({
   el: '#themeSelectionApp',
   store,
   components: {
       themeselection,
       createtheme
   }
});
