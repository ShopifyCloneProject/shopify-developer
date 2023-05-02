require('./../main');

import store from './store/index';
const xmlfeed = require('./components/index.vue').default;
const editxmlfeed = require('./components/edit.vue').default;
const defaultxml = require('./components/defaultxml.vue').default;

var app = new Vue({
   el: '#xmlfeedApp',
   store,
   components: {
       xmlfeed, editxmlfeed, defaultxml
   }
});
