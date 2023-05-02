require('./../main');

import store from './store/index';
const channelsettings = require('./components/index.vue').default;

var app = new Vue({
   el: '#channelSettingsApp',
   store,
   components: {
       channelsettings,
   }
});
