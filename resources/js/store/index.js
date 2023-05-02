import Vuex from 'vuex';
window.Vue = require('vue').default;

import globalStore from  './store';
export default new Vuex.Store({
    modules: {
        globalStore
    },
});
