import Vue from "vue";
window.Vue = require('vue');

import Vuex from 'vuex';
Vue.use(Vuex);

import cartModule		from  './store';
export default new Vuex.Store({
    modules: {
        cartModule
    },
});
