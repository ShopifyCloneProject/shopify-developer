import Vue from "vue";
window.Vue = require('vue');

import Vuex from 'vuex';
Vue.use(Vuex);

import shippingDetailsModule from  './store';
/*import globalStore          from  './../../../store/store';*/

export default new Vuex.Store({
    modules: {
       shippingDetailsModule
    },
});