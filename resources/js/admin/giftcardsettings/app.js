require('./../main');

import store from './store/index';
const giftcardsettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#giftcardSettingsApp',
   store,
   components: {
       giftcardsettings,
   }
});
