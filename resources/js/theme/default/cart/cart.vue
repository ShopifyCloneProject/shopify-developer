<template>
<div>
   <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/"> {{ lang.global.home }} </a></li>
               <li><span> {{ lang.global.cart.cart }} </span></li>
            </ul>
         </div>
   </div>
  <div class="holder" id="userCart" style="min-height: 500px;">
      <div class="container"  >
         <div class="page-title text-center">
            <h1> {{ lang.global.cart.shopping_cart }} </h1>
         </div>
         <div class="row" v-if="carts.length > 0">
            <div class="col-lg-11 col-xl-13">
               <div class="cart-table">
                  <div class="cart-table-prd cart-table-prd--head py-1 d-none d-md-flex">
                     <div class="cart-table-prd-image text-center">
                        {{ lang.global.cart.image }}
                     </div>
                     <div class="cart-table-prd-content-wrap">
                        <div class="cart-table-prd-info"> {{ lang.global.login.name }} </div>
                        <div class="cart-table-prd-qty"> {{ lang.global.cart.qty }} </div>
                        <div class="cart-table-prd-price"> {{ lang.global.cart.price }} </div>
                        <div class="cart-table-prd-action">&nbsp;</div>
                     </div>
                  </div>
                  <div class="cart-table-prd" v-for="(product, index) in carts">
                     <div class="cart-table-prd-image">
                        <a :href="'/product/detail/'+ product.slug" v-if="product.productImage"><img class="lazyload fade-up" :src="product.productImageSrc[2]" :data-src="product.productImageSrc[2]" :alt="product.productName" @error="setAltImg"></a>
                     </div>
                     <div class="cart-table-prd-content-wrap">
                        <div class="cart-table-prd-info">
                           <div class="cart-table-prd-price">
                              <div class="price-old" v-if="product.productComparePrice > 0">{{ $settings.CURRECNY_SYMBOL }}{{ product.productComparePrice }}</div>
                              <div class="price-new">{{ $settings.CURRECNY_SYMBOL }}{{ product.productPrice }}</div>
                           </div>
                           <h2 class="cart-table-prd-name"><a :href="'/product/detail/'+ product.slug">{{ product.productName }}</a></h2>
                        </div>
                        <div class="cart-table-prd-qty"  v-if="product.stock_status">
                           <div class="qty qty-changer">
                              <button class="decrease" @click="decreaseQuantity(product.id)" :disabled="product.quantity == 0"></button>
                              <input type="number" class="qty-input" disabled="disabled" :value="product.quantity">
                              <button class="increase" @click="increaseQuantity(product.id)" :disabled="increaseDisable(product.isContinueSelling,product.maxOrderLimit,product.quantity)"></button>
                           </div>
                        </div>
                        <div class="cart-table-prd-qty text-danger" v-else> {{ lang.global.cart.out_of_stock }} </div>
                        <div class="cart-table-prd-price-total">
                          {{ $settings.CURRECNY_SYMBOL }}{{ product.productPrice * product.quantity }}
                        </div>
                     </div>
                     
                     <div class="cart-table-prd-action">
                        <a class="cart-table-prd-remove pointer" data-tooltip="Remove Product" @click.prevent="removeCartProduct(product.id)"><i class="icon-recycle"></i></a>
                     </div>
                  </div>
               </div>
               <div class="text-center mt-1" id="clearAll"><button class="btn btn--grey" @click="clearAll()"> {{ lang.global.cart.clearall }}  </button></div>
               <div class="d-none d-lg-block">
                  <div class="mt-4"></div>
                  <!-- <RelatedSection :products="data.relatedProducts" :auth="auth"></RelatedSection> -->
               </div>
            </div>
            <div class="col-lg-7 col-xl-5 mt-3 mt-md-0">
               <!-- <div class="cart-promo-banner">
                  <div class="cart-promo-banner-inside">
                     <div class="txt1">Save 50%</div>
                     <div class="txt2">Only Today!</div>
                  </div>
               </div> -->
               <div class="card-total">
                  <!-- <div class="text-right">
                     <button class="btn btn--grey"><span>UPDATE CART</span><i class="icon-refresh"></i></button>
                  </div> -->
                  <div class="row d-flex">
                     <div class="col card-total-txt">Total</div>
                     <div class="col-auto card-total-price text-right">{{ $settings.CURRECNY_SYMBOL }} {{ cartTotal }}</div>
                  </div>
                  <a class="btn btn--full btn--lg" href="/checkout"><span> {{ lang.global.cart.check_out }}</span></a>
                  <!-- <div class="card-text-info text-right">
                     <h5>Standart shipping</h5>
                     <p><b>10 - 11 business days</b><br>1 item ships from the U.S. and will be delivered in 10 - 11 business days</p>
                  </div> -->
               </div>
               <div class="mt-2"></div>
               <div class="panel-group panel-group--style1 prd-block_accordion" id="productAccordion">
               
                  <div class="panel">
                     <div class="panel-heading active">
                        <h4 class="panel-title">
                           <a data-toggle="collapse" data-parent="#productAccordion" href="#collapse3"> {{ lang.global.cart.note_for_seller }} </a>
                           <span class="toggle-arrow"><span></span><span></span></span>
                        </h4>
                     </div>
                     <div id="collapse3" class="panel-collapse collapse show">
                        <div class="panel-body">
                           <textarea class="form-control form-control--sm textarea--height-100" placeholder="Text here"></textarea>
                           <div class="card-text-info mt-2">
                              <p> {{ lang.global.cart.savings }} </p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="holder mt-0" v-if="isCartEmpty">
         <div class="container">
            <div class="page404-bg">
              <div class="page404-text">
                <div class="txt2"> {{ lang.global.cart.shopping_cart_empty }} </div>
              </div>
              <svg id="morphing" xmlns="" width="600" height="600" viewBox="0 0 600 600">
                <g transform="translate(50,50)">
                  <path class="p" d="M93.5441 2.30824C127.414 -1.02781 167.142 -4.63212 188.625 21.7114C210.22 48.1931 199.088 86.5178 188.761 119.068C179.736 147.517 162.617 171.844 136.426 186.243C108.079 201.828 73.804 212.713 44.915 198.152C16.4428 183.802 6.66731 149.747 1.64848 118.312C-2.87856 89.9563 1.56309 60.9032 19.4066 38.3787C37.3451 15.7342 64.7587 5.14348 93.5441 2.30824Z"/>
                </g>
              </svg>
            </div>
            <div class="page404-info text-center"><a href="/products/all" class="btn"> {{ lang.global.cart.browse_products }} </a>
            </div>
         </div>
      </div>
   </div>
</div>
</template>

<script>
import { mapState } from 'vuex'
import RelatedSection from './../productdetail/RelatedSection';

export default {
    name: "Cart",
    props:['carts', 'cartTotal', 'data', 'auth', 'cartcalulatedata'],
    data() {
      return {
        cruds: [],
        client_id: CLIENT_ID,
        isCartEmpty: false,
      }
    },
    watch: {
       // whenever question changes, this function will run
      carts: function () {
         if(this.carts.length == 0){
            this.isCartEmpty = true;
         }
      }
    },
    computed: {
      ...mapState(['globalStore']),
      noImage(){
         return this.globalStore.no_image;
      }
    },
    mounted(){
      let self = this;
      setTimeout(function() {
        self.callCart();
         if(self.carts.length == 0)
         {
            self.IsCartEmpty = true;
         }
      }, 1000);
      document.title = "Cart";
    },
    methods: {
      removeCartProduct(cartProductId){
         let section = $('.cart-table');
         blockSection(section);
         this.$store.dispatch("globalStore/RemoveCartProduct", cartProductId)
         .then((res) => {
              unblockSection(section);
              this.$emit('callcart');
              if (res.response.status_code == 2062) {
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
              }
         })
         .catch((err) => {
            unblockSection(section);
            this.$toast.open({
              message: err,
              type: "error",
            });
         });
      },
      clearAll(){
         let section = $('#clearAll');
         blockSection(section);
         this.$store.dispatch('globalStore/ClearCart').
         then((res) => {
            if (res.response.status_code == 2062) {
               this.$toast.open({
                   message: res.response.message,
                   type: 'success',
               });
            }
            unblockSection(section);
         }).catch((err) => {
            this.$toast.open({
              message: err,
              type: "error",
            });
            unblockSection(section);
         });
      },
      decreaseQuantity(cartProductId){
         let section = $('.cart-table');
         blockSection(section);
         this.$store.dispatch("globalStore/DecreaseQuantity", cartProductId)
         .then((res) => {
              if (res.response.status_code == 2063) {
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
                  this.$store.commit('globalStore/decreaseQuantity', cartProductId);
                  this.callCart();
                  this.$emit('callcart');
              } else if(res.response.status_code == 7004){
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
              }
              unblockSection(section);
         })
         .catch((err) => {
            unblockSection(section);
            this.$toast.open({
              message: err,
              type: "error",
            });
         });
      },
      increaseQuantity(cartProductId){
         let section = $('.cart-table');
         blockSection(section);
         this.$store.dispatch("globalStore/IncreaseQuantity", cartProductId)
         .then((res) => {
               if (res.response.status_code == 2063) {
                  this.$toast.open({
                    message:  res.response.message,
                    type: "success",
                  })
                  this.$store.commit('globalStore/increaseQuantity', cartProductId);
                  this.callCart();
                  this.$emit('callcart');
               } else if(res.response.status_code == 7004){
                  this.$toast.open({
                    message:  res.response.message,
                    type: "success",
                  })
               }
               unblockSection(section);
         })
         .catch((err) => {
            unblockSection(section);
            this.$toast.open({
              message: err,
              type: "error",
            })
         });
      },
      increaseDisable(isContinueSelling,max_order_limit,buy_quantity){
         if(isContinueSelling == 1){
            return false;
         }
         else if(max_order_limit <= buy_quantity){
            return true;
         }
         return false;
      },
      callCart(){
          this.$store.dispatch("globalStore/countCart").then((res) => {}).catch((err) => {});
      },
      setAltImg(event){
         event.target.src = this.noImage;
      },
    },
    components: {
      RelatedSection
    }
  }
</script>