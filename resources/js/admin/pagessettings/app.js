require('./../main');

import store from './store/index';
const mainpage = require('./components/Home/mainpage.vue').default;
const menubar = require('./components/common/menubar.vue').default;
const childpage = require('./components/common/levelpage.vue').default;
const productdetail = require('./components/Detail/index.vue').default;


var app = new Vue({
   el: '#pagesettings',
   store,
   components: {
       mainpage, menubar, childpage, productdetail
   }
});
