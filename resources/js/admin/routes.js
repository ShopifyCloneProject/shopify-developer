import Vue from "vue/dist/vue";
window.Vue = require('vue').default;

import VueRouter from "vue-router";
Vue.use(VueRouter)

const AddAddress = require('./location/components/create').default;

const routes = [
	{
	  path: '/admin/settings',
      children: [
        {
          path: '/new',
          component: AddAddress
        },
        {
          path: 'location/:id',
          component: UserPosts
        }
      ]
  }
]	

export default new VueRouter({
    mode: 'history',
    base: __dirname,
    routes: routes
});