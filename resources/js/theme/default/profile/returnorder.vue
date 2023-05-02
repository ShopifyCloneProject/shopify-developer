<template>
<div>
   <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/">{{ lang.global.home }}</a></li>
               <li><a href="/orders">{{ lang.global.profile.returnorder.your_orders }}</a></li>
               <li><span>{{ returnProduct.id }}</span></li>
            </ul>
         </div>
   </div>
  <div class="holder" id="returnOrder" style="min-height: 500px;">
      <div class="container"  >
         <ValidationObserver ref="returnOrderForm" v-slot="{ handleSubmit }">
            <form method="POST" enctype="multipart/form-data" id="frmReturnOrder" @submit.prevent="handleSubmit(submit())">
               <div class="row">
                  <div class="col-18">
                     <div class="cart-table">
                        <div class="cart-table-prd cart-table-prd--head py-1 d-none d-md-flex">
                           <div class="cart-table-prd-image text-center">
                              {{ lang.global.profile.returnorder.image }}
                           </div>
                           <div class="cart-table-prd-content-wrap">
                              <div class="cart-table-prd-info">{{ lang.global.profile.returnorder.name }}</div>
                              <div class="cart-table-prd-qty">{{ lang.global.profile.returnorder.refund_qty }}</div>
                              <div class="cart-table-prd-price">{{ lang.global.profile.returnorder.refund_amount }}</div>
                           </div>
                        </div>
                        <div class="cart-table-prd">
                           <div class="cart-table-prd-image">
                              <a :href="'/product/detail/'+ returnProduct.slug" v-if="returnProduct.src">
                                 <img class="lazyload fade-up" :src="returnProduct.image_src[0]" 
                                 :data-src="returnProduct.image_src[2]" :alt="returnProduct.title" @error="setAltImg">
                              </a>
                           </div>
                           <div class="cart-table-prd-content-wrap">
                              <div class="cart-table-prd-info">
                                 <div class="cart-table-prd-price">
                                    <div class="price-new">{{ $settings.CURRECNY_SYMBOL }}{{ returnProduct.price }}</div>
                                 </div>
                                 <h2 class="cart-table-prd-name">
                                    <a :href="'/product/detail/'+ returnProduct.slug">{{ returnProduct.title }}</a>
                                 </h2>
                                 <span class="minicart-qty">{{ returnProduct.remainQuantity }}</span>
                              </div>
                              <div class="cart-table-prd-qty">
                                 <div class="qty qty-changer">
                                    <button class="decrease" v-show="decreaseShow" type="button" @click="decreaseQuantity()" ></button>
                                    <input type="number" class="qty-input" disabled="disabled" :value="returnProduct.currentReturnQuantity">
                                    <button class="increase" v-show="increaseShow" type="button" @click="increaseQuantity()" ></button>
                                 </div>
                              </div>
                              <div class="cart-table-prd-price-total">
                                {{ $settings.CURRECNY_SYMBOL }}{{ returnProduct.price * returnProduct.currentReturnQuantity }}
                              </div>
                           </div>
                        </div>
                        <div class="cart-table-prd pt-0" v-if="typeof returnProduct.descriptionData != 'undefined'">
                          <div class="collapse-default prd-description w-100">
                            <div class="card">
                              <a
                              href="#"
                              id="headingCollapse"
                              class="card-header text-primary text-center theme-bg-color justify-content-center text-light  font-weight-bold"
                              data-toggle="collapse"
                              role="button"
                              data-target="#collapse"
                              aria-expanded="false"
                              aria-controls="collapse"
                              >
                              {{ lang.global.show_details }}
                            </a>
                            <div id="collapse" role="tabpanel" aria-labelledby="headingCollapse" class="collapse theme-back-bg-color">
                              <div class="card-body">
                                 <div class="row">
                                    <div class="col-18 border-bottom pb-2">
                                       <div class="description-table d-flex">
                                          <div class="cart-table-prd-info">{{ lang.global.profile.returnorder.description }}</div>
                                          <div class="cart-table-prd-qty">{{ lang.global.profile.returnorder.request_qty }}</div>
                                          <div class="cart-table-prd-qty">{{ lang.global.profile.returnorder.approve_qty }}</div>
                                          <div class="cart-table-prd-qty">{{ lang.global.profile.returnorder.date }}</div>
                                       </div>
                                    </div>
                                    <div class="col-18 pb-2">
                                       <div class="return-orderodescription" v-for="(description, index) in returnProduct.descriptionData" :key="index">
                                          <div :class="description.hasOwnProperty('admin_approve') && index != 0 ? 'border-bottom my-1' : 'my-1' ">
                                          </div>
                                          <div class="d-flex"  v-if="description.hasOwnProperty('admin_approve')">
                                             <div class="cart-table-prd-info">
                                                <label>{{ lang.global.profile.returnorder.request }} :</label>
                                                <span class="cart-table-prd-info">{{description.description}}</span>
                                             </div>
                                              <div class="cart-table-prd-qty">
                                                <span class="cart-table-prd-info">{{description.quantity}}</span>
                                             </div>
                                             <div class="cart-table-prd-qty">
                                                <span class="cart-table-prd-info"></span>
                                             </div>
                                             <div class="cart-table-prd-qty">
                                                <span class="cart-table-prd-info">{{description.created_at}}</span>
                                             </div>
                                          </div>
                                          <div class="d-flex"  v-if="!description.hasOwnProperty('admin_approve')">
                                             <div class="cart-table-prd-info ml-1">
                                                <label>{{ lang.global.profile.returnorder.response }} :</label>
                                                <span class="cart-table-prd-info">{{description.description}}</span>
                                             </div>
                                             <div class="cart-table-prd-qty">
                                                <span class="cart-table-prd-info"></span>
                                             </div>
                                             <div class="cart-table-prd-qty">
                                                <span class="cart-table-prd-info">{{description.quantity}}</span>
                                             </div>
                                             <div class="cart-table-prd-qty"><span class="cart-table-prd-info">{{description.updated_at}}</span></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                     </div>
                  </div>
                  <div class="col-18 mt-2" v-if="returnProduct.returnQuantity <= returnProduct.quantity">
                     <div class="form-group green-border-focus"> 
                        <label class="text-uppercase label" for="productReturnDescription">Description:</label>
                        <textarea class="form-control" id="productReturnDescription" rows="3" v-model="description">
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
import { mapState } from 'vuex'

export default {
    name: "returnOrder",
    props:['data', 'auth'],
    data() {
      return {
        returnProduct:[],
        description:'',
        client_id: CLIENT_ID,
        increaseShow: true,
        decreaseShow: false,
      }
    },
    
    computed: {
      ...mapState(['globalStore']),
      noImage(){
         return this.globalStore.no_image;
      }
    },
    mounted(){
      this.returnProduct = this.data.orderproduct;
      this.returnProduct.currentReturnQuantity = 0;
      this.returnProduct.remainQuantity = this.returnProduct.quantity - this.returnProduct.returnQuantity;
      if(this.returnProduct.currentReturnQuantity >= this.returnProduct.remainQuantity){
                  this.increaseShow = false;
                  this.decreaseShow = false;
      }
      
    },
    methods: {
      
       
      decreaseQuantity(){
            this.returnProduct.returnQuantity--;
            this.returnProduct.currentReturnQuantity--;
            this.increaseShow = true;
            if(this.returnProduct.currentReturnQuantity == 0){
               this.decreaseShow = false;
            }
          
          },
         increaseQuantity(){
            this.returnProduct.returnQuantity++;
            this.returnProduct.currentReturnQuantity++;
            this.decreaseShow = true;
            if(this.returnProduct.currentReturnQuantity >= this.returnProduct.remainQuantity){
                  this.increaseShow = false;
            }
            
          },
      
      setAltImg(event){
         event.target.src = this.noImage;
      },
      cancel(){
         location.reload();
      },

      submit(){
            this.$refs.returnOrderForm.validate().then(success => {
                     if (!success) {
                          $("html, body").animate({ scrollTop: 50 }, 200);
                       return;
                     }
                    
               openLoader();
               const  payload = {
                           id: this.returnProduct.id,
                           order_id: this.returnProduct.order_id,
                           product_id: this.returnProduct.product_id,
                           product_variant_option_id: this.returnProduct.product_variant_options_id ,
                           quantity: this.returnProduct.currentReturnQuantity,
                           description: this.description,
                        };
               this.$store.dispatch("globalStore/ReturnOrderProduct", payload)
               .then((res) => {
                   if (res.response.status_code == 3055) {
                        successModal(res.response.message);
                        setTimeout(function(){
                           location.reload();
                        },2000);
                   }
                  else if(res.response.status_code == 7005)
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
        },
    },
  }
</script>
<style lang="scss" scoped>
   
    .table-dark
   {
      background-color: #283046;
   }
   .theme-bg-color{
      background-color: #283046;
   }
   .theme-back-bg-color{
      background-color: #f8f8f8;
   }
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