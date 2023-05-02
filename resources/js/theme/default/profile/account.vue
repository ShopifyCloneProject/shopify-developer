<template>
  <div>
    <div class="holder breadcrumbs-wrap mt-0">
        <div class="container">
           <ul class="breadcrumbs">
              <li><a href="/">{{ lang.global.home }}</a></li>
              <li><span>{{ lang.global.profile.account.account }}</span></li>
           </ul>
        </div>
     </div>
   
        <div class="holder">
           <div class="container">
              <div class="row">
                 <div class="col-md-4 aside aside--left">
                    <div class="list-group">
                    <a @click="setSection('details')" :class="(opensection == 'details')?'active':''" class="list-group-item pointer">{{ lang.global.profile.account.account_details }}</a>
                    <a @click="setSection('addresses')" :class="(opensection == 'addresses')?'active':''" class="list-group-item pointer">{{ lang.global.profile.account.my_addresses }}</a>
                    <a @click="setSection('orders')" :class="(opensection == 'orders')?'active':''" class="list-group-item pointer">{{ lang.global.profile.account.my_order_history }}</a>
                    <a @click="setSection('changepass')" :class="(opensection == 'changepass')?'active':''" class="list-group-item pointer">{{ lang.global.profile.account.change_password }}</a> 
                    <a href="/logout" class="list-group-item">{{ lang.global.profile.account.logout }}</a>
                    </div>
                 </div>
                 <div class="col-md-14 aside">
                    <AccountDetails  v-if="opensection == 'details'" />
                    <Address  v-if="opensection == 'addresses'" />
                    <Order  v-if="opensection == 'orders'" />
                    <ChangePassword  v-if="opensection == 'changepass'" />
                 </div>
              </div>
           </div>
        </div>
  </div>
</template>

<script>
import AccountDetails from "./accountdetails";
import Address from "./address";
import Order from "./orders";
import ChangePassword from "./changepassword";

import { mapState } from 'vuex'

  export default {
    name: "account",
    props: ['data'],
    data() {
      return {
        cruds: [],
        opensection: 'details',
      }
    },
    created(){
      this.opensection = this.data.section
    },
    methods: {
     setSection(status){
        this.opensection = status;
        if(status == 'details')
        {
             window.history.pushState({}, null, '/account');
        }
        else if(status == 'orders')
        {
            window.history.pushState({}, null, '/orders');
        }
        else if(status == 'changepass')
        {
            window.history.pushState({}, null, '/changepassword');
        }
        else if(status == 'addresses')
        {
            window.history.pushState({}, null, '/addresses');
        }
     },
    },
    components: { 
      AccountDetails, Address, Order, ChangePassword
    }
  }
</script>
