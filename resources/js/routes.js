import Vue from "vue";
import VueRouter from 'vue-router';

import details from "./theme/default/profile/details";
import address from "./theme/default/profile/address";
import order from "./theme/default/profile/order";

export const routes = [
        { path: "/details", name: "deatils", component: details },
        { path: '/address', name: 'address', component: address },
        { path: '/order', name: 'order', component: order },
        { path: '*', redirect: '/' }
    ];


export const router = new VueRouter({
    base: '/',
    mode: 'history',
    routes
});
