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
      <div class="holder" id="cancelexchangeorder" style="min-height: 500px;">
         <div class="container"  >
            <ValidationObserver ref="cancelexchangeorderForm" v-slot="{ handleSubmit }">
               <form method="POST" enctype="multipart/form-data" id="frmcancelexchangeorder" @submit.prevent="handleSubmit(submit())">
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
                                    <span class="minicart-qty">{{ exchangeProduct.mainQuantity }}</span>
                                 </div>
                                 <div class="cart-table-prd-qty">
                                    <div class="qty qty-changer">
                                       {{ exchangeProduct.quantity }}
                                    </div>
                                 </div>
                                 <div class="cart-table-prd-price-total">
                                    {{ exchangeProduct.symbol }} {{ exchangeProduct.price * exchangeProduct.quantity }}
                                 </div>
                              </div>
                           </div>

                           <ExchangeDescription :frompage="'cancelexchangeorder'" :exchangeproductdes="exchangeProduct.descriptionData" v-if="exchangeProduct.descriptionData != null"></ExchangeDescription>
                        </div>
                     </div>

                     <!-- Media start -->
                     <ExchangeMedia :frompage="'exchangeorder'" :exchangemedia="mediaData"></ExchangeMedia>
                     <!-- Media end -->

                     <div class="col-18 mt-3">
                        <div class="form-group green-border-focus"> 
                           <h4 for="productReturnDescription">{{ lang.global.profile.exchangeorder.client_request }}</h4>
                           <textarea class="form-control" id="productReturnDescription" rows="3" v-model="exchangeProduct.client_request">
                           </textarea>
                        </div>
                        <div class="mt-2">
                           <button type="button" class="btn btn--alt" @click="confirmCancelBox('cancel exchange request','CancelExchangeOrderRequest',data.orderproduct.order_id)">{{ lang.global.profile.exchangeorder.cancel_exchange_request }}</button>
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
   import alertMixin from './../../mixins/alertaction';
   import ExchangeDescription from './exchangedescription';
   import ExchangeMedia from './exchangemedia';

   export default {
      name: "cancelexchangeorder",
      mixins: [alertMixin],
      props:['data', 'auth'],
      data() {
         return {
            exchangeProduct:[],
            client_request: '',
            client_id: CLIENT_ID,
            increaseShow: true,
            decreaseShow: false,
            showDeleteBtn: false,
            mediaData: [],
         }
      },
      computed: {
         ...mapState(['globalStore']),
         noImage(){
            return this.globalStore.no_image;
         }
      },
      mounted(){
         if(Object.keys(this.data.orderproduct).length > 0){
            this.setExchangeProductData();
         }
      },
      methods: {
         setExchangeProductData(){
            let self = this;
            this.exchangeProduct = this.data.orderproduct;
            this.client_request = this.exchangeProduct.client_request;
            let objImage =[];
            let  tempObjImage= {};
            for (const [key, value] of Object.entries(self.exchangeProduct.exchange_media)) {
               tempObjImage = {
                  checked: false,
                  displaycheckbox: false,
                  id: value.id,
                  imageurl: value.img_src
               };
               objImage.push(tempObjImage);
            }
            self.mediaData = objImage;
         },
         setAltImg(event){
            event.target.src = this.noImage;
         },
         CancelExchangeOrderRequest(order_id){
            openLoader();
            this.$store.dispatch("globalStore/CancelExchangeOrderRequest", order_id)
            .then((res) => {
               closeLoader();
               if (res.response.status_code == 3101 || res.response.status_code == 3103 || res.response.status_code == 3105) {
                  this.$toast.open({
                     message: res.response.message,
                     type: 'success',
                  });
                  window.location = res.response.data.url;
               }
               else{
                   this.$toast.open({
                     message: res.response.message,
                     type: 'success',
                  });
               }
            })
            .catch((err) => {
               closeLoader();
               this.$toast.open({
                  message: err,
                  type: "error",
               });
            });
         },
      },
      components: {
         ExchangeDescription ,ExchangeMedia
      }
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