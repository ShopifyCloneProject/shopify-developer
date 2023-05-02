require('./bootstrap');
import Vue from "vue";
window.Vue = require('vue').default;
import axios from "axios";
window.axios = require('axios');

window._ = require('lodash');
window.objectToFormData = require('object-to-formdata').objectToFormData

window.token = document.head.querySelector('meta[name="csrf-token"]');
  
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.prototype.lang = window.i18n;

const maindata = require('./theme/default/maindata.vue').default;
import store from "./store";

/** Vee validate */
import { ValidationObserver, extend, localize } from "vee-validate";
import { ValidationProvider } from "vee-validate/dist/vee-validate.full.esm";
import en from 'vee-validate/dist/locale/en.json';
import * as rules from 'vee-validate/dist/rules';

/** toast notification */
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
Vue.use(VueToast, {
    duration: 5000,
    dismissible: true,
    position:'top-right'
});

Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule]);
});

localize('en', en);
Vue.component("ValidationProvider", ValidationProvider);
Vue.component("ValidationObserver", ValidationObserver);

import SlidingPagination from 'vue-sliding-pagination'
Vue.component("SlidingPagination", SlidingPagination);

import StarRating from 'vue-star-rating'
Vue.component("StarRating", StarRating);

Vue.prototype.$settings = JSON.parse(globalsettings);

var app = new Vue({
   el: '#home',
   store,
   components: {
       maindata,
   }
});
 