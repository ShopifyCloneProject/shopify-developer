import Vue from "vue";
window.Vue = require('vue');

import Vuex from 'vuex';
Vue.use(Vuex);

import orderModule		from  './store';
export default new Vuex.Store({
    modules: {
        orderModule
    },
});
