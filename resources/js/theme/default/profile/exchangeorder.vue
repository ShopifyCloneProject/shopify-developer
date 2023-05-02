<template>
   <div>
      <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/"> {{ lang.global.home }}</a></li>
               <li><a href="/orders">{{ lang.global.profile.exchangeorder.your_orders }}</a></li>
               <li><span>{{ exchangeProduct.id }}</span></li>
            </ul>
         </div>
      </div>
      <div class="holder" id="exchangeOrder" style="min-height: 500px;">
         <div class="container"  >
            <ValidationObserver ref="exchangeOrderForm" v-slot="{ handleSubmit }">
               <form method="POST" enctype="multipart/form-data" id="frmExchangeOrder" @submit.prevent="handleSubmit(submit())">
                  <div class="row">
                     <div class="col-18">
                        <div class="cart-table">
                           <div class="cart-table-prd cart-table-prd--head py-1 d-none d-md-flex">
                              <div class="cart-table-prd-image text-center">
                                 {{ lang.global.profile.exchangeorder.image }}
                              </div>
                              <div class="cart-table-prd-content-wrap">
                                 <div class="cart-table-prd-info">{{ lang.global.profile.exchangeorder.name }}</div>
                                 <div class="cart-table-prd-qty">{{ lang.global.profile.exchangeorder.qty }}</div>
                                 <div class="cart-table-prd-price">{{ lang.global.profile.exchangeorder.amount }}</div>
                              </div>
                           </div>
                           <div class="cart-table-prd">
                              <div class="cart-table-prd-image">
                                 <a  v-if="exchangeProduct.src">
                                    <img class="lazyload fade-up" :src="exchangeProduct.image_src[2]"  :alt="exchangeProduct.title" @error="setAltImg">
                                 </a>
                              </div>
                              <div class="cart-table-prd-content-wrap">
                                 <div class="cart-table-prd-info">
                                    <div class="cart-table-prd-price">
                                       <div class="price-new">{{ $settings.CURRECNY_SYMBOL }}{{ exchangeProduct.price }}</div>
                                    </div>
                                    <h2 class="cart-table-prd-name">
                                       <a>{{ exchangeProduct.title }}</a>
                                    </h2>
                                    <span class="minicart-qty">{{ exchangeProduct.quantity }}</span>
                                 </div>
                                 <div class="cart-table-prd-qty">
                                    <div class="qty qty-changer">
                                       <button class="decrease" v-show="decreaseShow" type="button" @click="decreaseQuantity()" ></button>
                                       <input type="text" class="qty-input" v-model="oldExchangeQuantity">
                                       <button class="increase" v-show="increaseShow" type="button" @click="increaseQuantity()" ></button>
                                    </div>
                                 </div>
                                 <div class="cart-table-prd-price-total">
                                    {{ exchangeProduct.symbol }} {{ exchangeProduct.price * exchangeQuantity }}
                                 </div>
                              </div>
                           </div>

                           <ExchangeDescription :frompage="'exchangeorder'" :exchangeproductdes="exchangeProduct.descriptionData" v-if="exchangeProduct.descriptionData != null"></ExchangeDescription>

                        </div>
                     </div>
                     <!-- Media start -->
                     <ExchangeMedia :frompage="'/00'" :exchangemedia="mediaData"></ExchangeMedia>
                     <!-- Media end -->

                     <div class="col-18 mt-3">
                        <div class="form-group green-border-focus"> 
                           <h4 for="productReturnDescription">{{ lang.global.profile.exchangeorder.client_request }}</h4>
                           <textarea class="form-control" id="productReturnDescription" rows="3" v-model="exchangeProduct.client_request">
                           </textarea>
                        </div>
                        <div class="mt-2">
                           <button type="submit" class="btn mr-1">{{ lang.global.save }}</button>
                           <button type="reset" class="btn btn--alt" @click="cancel()">{{ lang.global.cancel }}</button>
                        </div>
                     </div>
                  </div>
               </form>
            </ValidationObserver>
         </div>
      </div>
   </div>
</template>

<script>
   import { mapState } from 'vuex';
   import ExchangeDescription from './exchangedescription';
   import ExchangeMedia from './exchangemedia';

   export default {
      name: "exchangeOrder",
      props:['data', 'auth'],
      components: {
         ExchangeDescription , ExchangeMedia
      },
      data() {
         return {
            exchangeQuantity: 0,
            oldExchangeQuantity : 0,
            client_request: '',
            client_id: CLIENT_ID,
            increaseShow: true,
            decreaseShow: false,
            showDeleteBtn: false,
            mediaData: [],
            exchangeProduct:[],
         }
      },
      computed: {
         ...mapState(['globalStore']),
         noImage(){
            return this.globalStore.no_image;
         }
      },
      mounted(){
         this.exchangeProduct = this.data.orderproduct;
         this.client_request = this.exchangeProduct.client_request;
         this.exchangeProduct.remainQuantity = this.exchangeProduct.quantity - this.exchangeProduct.exchangeQuantity;
         this.oldExchangeQuantity = this.exchangeProduct.exchangeQuantity;
         if(this.oldExchangeQuantity >= this.exchangeProduct.remainQuantity){
            this.increaseShow = true;
            this.decreaseShow = false;
         }
      },
      methods: {
         decreaseQuantity(){
            openLoader();
            this.exchangeQuantity--;
            this.oldExchangeQuantity--;
            this.increaseShow = true;
            if(this.oldExchangeQuantity == 0 || this.exchangeProduct.exchangeQuantity >= this.oldExchangeQuantity){
               this.decreaseShow = false;
            }
            closeLoader();
         },
         increaseQuantity(){
            openLoader();
            this.exchangeQuantity++;
            this.oldExchangeQuantity++;
            this.decreaseShow = true;
            if(this.oldExchangeQuantity >= this.exchangeProduct.quantity){
               this.increaseShow = false;
            }
            closeLoader();
         },
         setAltImg(event){
            event.target.src = this.noImage;
         },
         cancel(){
            location.reload();
         },
         submit(){
            if(this.oldExchangeQuantity > 0)
            {
               this.$refs.exchangeOrderForm.validate().then(success => {
                  if (!success) {
                     $("html, body").animate({ scrollTop: 50 }, 200);
                     return;
                  }

                  openLoader();
                  const  payload = {
                     id: this.exchangeProduct.id,
                     order_id: this.exchangeProduct.order_id,
                     product_id: this.exchangeProduct.product_id,
                     product_variant_option_id: this.exchangeProduct.product_variant_options_id ,
                     quantity: this.exchangeQuantity,
                     client_request: this.exchangeProduct.client_request,
                     media : this.mediaData
                  };
                  this.$store.dispatch("globalStore/ExchangeOrderProduct", payload)
                  .then((res) => {
                     if (res.response.status_code == 3096) {
                        successModal(res.response.message);
                        setTimeout(function(){
                           window.location = res.response.data.url;
                        },2000);
                     }
                     else if(res.response.status_code == 7005)
                     {
                        errorModal(res.response.message);
                     }
                     else
                     {
                        errorModal(res.response.message);
                     }
                     closeLoader();
                  })
                  .catch((err) => {
                     closeLoader();
                     errorModal(err.response.message);
                  });
               });
            }
            else
            {
               closeLoader();
               errorModal("Please set quantity first");
            }
         },
      },
      
   }
</script>
<style lang="scss" scoped>
   .card{
      border:none;
   }
   .card-body{
      border:1px solid #e1dfdf;
   }
   .cart-table-prd:nth-child(2){
      border-bottom:none;
   }
   .cart-table-prd{
      padding-left:10px !important;
      padding-right:10px !important;
   }
   .form-control{
      background-color:#fff;
      border:1px solid #e1dfdf;
   }
   .green-border-focus .form-control:focus {
      border: 1px solid #80bdff;
      box-shadow:0px 0px 0px 2px rgb(0,123,255,0.25);

   }
   .cart-table-prd-info{
      flex-basis: 40%;
      .minicart-qty{
         position: relative;
         color: #fff;
         border-color: #17c6aa;
         background-color: #17c6aa;
         padding: 13px;
         font-size: 14px;
      }
   }

</style>