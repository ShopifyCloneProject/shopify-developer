import Vue from "vue";
window.Vue = require('vue').default;
import axios from "axios";
window.axios = require('axios');
window._ = require('lodash');
window.objectToFormData = require('object-to-formdata').objectToFormData

Vue.prototype.lang = window.i18n;

/** wysiwyg text editor */
import wysiwyg from "vue-wysiwyg";
Vue.component("wysiwyg", wysiwyg);
import "vue-wysiwyg/dist/vueWysiwyg.css";
Vue.use(wysiwyg);

/** Vee validate */
import { ValidationObserver, extend, localize } from "vee-validate";
import { ValidationProvider } from "vee-validate/dist/vee-validate.full.esm";
import en from 'vee-validate/dist/locale/en.json';
import * as rules from 'vee-validate/dist/rules';


Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule]);
});

localize('en', en);
Vue.component("ValidationProvider", ValidationProvider);
Vue.component("ValidationObserver", ValidationObserver);

import InputTag from 'vue-input-tag';
Vue.component("input-tag", InputTag);

import CKEditor from 'ckeditor4-vue';
Vue.use( CKEditor );

import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';
Vue.component("multiselect", Multiselect);

import Datanotfound from './components/Datanotfound.vue';
Vue.component("Datanotfound", Datanotfound);

import Orderdetails from './components/Orderdetails.vue';
Vue.component("Orderdetails", Orderdetails);


window.token = document.head.querySelector('meta[name="csrf-token"]');
  
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';