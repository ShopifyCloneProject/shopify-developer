<template>
<div>
  <div class="holder breadcrumbs-wrap mt-0">
      <div class="container">
         <ul class="breadcrumbs">
            <li><a href="/">{{ lang.global.home }}</a></li>
            <li><span>{{ lang.global.thankyou.thankyou }}</span></li>
         </ul>
      </div>
  </div>

  <div class="holder">
    <div class="container">
      <div class="row">
            <div class="jumbotron">
                <h2 class="text-center">{{ lang.global.thankyou.your_order_received }}</h2>
              <h3 class="text-center">{{ lang.global.thankyou.thankyou_description }}</h3>
              
              <p class="text-center">{{ lang.global.thankyou.your_order }} {{ data.order.order_nr }}</p>
              <p class="text-center">{{ lang.global.thankyou.order_confirmation_description }}</p>
                <center><div class="btn-group mt-5">
                    <a href="/" class="btn btn-lg btn-warning">{{ lang.global.thankyou.continue }}</a>
                </div></center>
            </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>

import { mapState } from 'vuex'

  export default {
    name: "thankyou",
    props: ['data'],
    data() {
      return {
        cruds: []
      }
    },
    created(){
      this.removeLocalStorage('step');
      this.removeLocalStorage('uniqueId');
      this.removeLocalStorage('shippingAddressId');
      this.removeLocalStorage('billingAddressId');
      this.removeLocalStorage('selectPaymentMethod');
    },
    methods: {
      removeLocalStorage(key){
        if(localStorage.getItem(key) != null)
        {
            localStorage.removeItem(key);
        }
      }
    }
  }
</script>
<style lang="scss" >
  .jumbotron{
    width: 100%;
    padding: 20px;
    border-radius: 15px;
  }
</style>