import Vue from "vue";
window.Vue = require('vue');

import Vuex from 'vuex';
Vue.use(Vuex);

import locationSettingsModule from  './store';
/*import globalStore          from  './../../../store/store';*/

export default new Vuex.Store({
    modules: {
       locationSettingsModule
    },
});
