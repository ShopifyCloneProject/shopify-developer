<template>
   <div class="holder">
      <div class="container">
         <div class="title-wrap text-center">
            <h2 class="h1-style"> {{ lang.global.productdetail.relatedsection.you_may_like }} </h2>
            <div class="carousel-arrows carousel-arrows--center"></div>
         </div>

          <div class="prd-grid-wrap position-relative">
                  <div class="prd-grid data-to-show-4 data-to-show-lg-4 data-to-show-md-3 data-to-show-sm-2 data-to-show-xs-2 js-category-grid" data-grid-tab-content>
                      <!-- Start Product section -->
                     <div class="prd prd--style2 prd-labels--max prd-labels-shadow " v-for="(newArriavlData, dataIndex) in relatedProducts">
                        <div class="prd-inside">
                           <div class="prd-img-area">
                              <a :href="'/product/detail/'+ newArriavlData.slug" class="prd-img image-hover-scale image-container">
                                 <img :src="newArriavlData.medias[0].image_src[3]"  :alt="newArriavlData.title" class="js-prd-img lazyload fade-up" v-if="newArriavlData.medias.length > 0" @error="setAltImg">
                                 <div class="foxic-loader"></div>
                              </a>
                              <div class="prd-circle-labels" v-if="auth">
                                 <a class="circle-label-compare circle-label-wishlist--add  mt-0" title="Add To Wishlist" @click="addToWishlist( newArriavlData.id, dataIndex )" v-if="!newArriavlData.is_wishlist"><i class="icon-heart-stroke" ></i></a>
                                 <a class="circle-label-compare circle-label-wishlist--remove mt-0" title="Remove From Wishlist" v-else><i class="icon-heart-hover" @click="removeFromWishlist( newArriavlData.id, dataIndex )"></i></a> 
                                 <!-- <a href="#" class="circle-label-qview js-prd-quickview prd-hide-mobile" data-src="ajax/ajax-quickview.html"><i class="icon-eye"></i><span>QUICK VIEW</span></a> -->
                              </div>
                              <ul class="list-options color-swatch" v-if="newArriavlData.medias.length > 0">
                                  <li :data-image="imageData.image_src[3]" :class="(imageindex == 0)?'active': ''" v-for="(imageData,imageindex) in newArriavlData.medias" v-if="imageindex < 3">
                                       <a href="javascript:void(0)" class="js-color-toggle">
                                          <img  class="fade-up ls-is-cached lazyloaded" alt="Color Name" :src="imageData.image_src[1]" @error="setAltImg">
                                       </a>
                                    </li> 
                                 </ul> 
                              </ul>
                           </div>
                           <div class="prd-info">
                              <div class="prd-info-wrap">
                                 <div class="prd-info-top">
                                    <div class="prd-rating"><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i></div>
                                 </div>
                                 <div class="prd-rating justify-content-center"><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i></div>
                                 <div class="prd-tag" v-if="newArriavlData.title != ''"><a  :href="'/product/detail/'+ newArriavlData.slug"></a></div>
                                 <h2 class="prd-title" v-if="newArriavlData.title != ''"><a  :href="'/product/detail/'+ newArriavlData.slug">{{ newArriavlData.title }}</a></h2>
                                 <div class="prd-description" v-html="newArriavlData.description">
                                    
                                 </div>
                                 <div class="prd-action">
                                    <!-- <button class="btn js-prd-addtocart">Add To Cart</button> -->
                                    <a :href="'/product/detail/'+ newArriavlData.slug" class="btn js-prd-addtocart"> {{ lang.global.view }} {{ lang.global.collectiondetail.detail }} </a>
                                 </div>
                              </div>
                              <div class="prd-hovers">
                                 <div class="prd-circle-labels" v-if="auth">
                                    <div>
                                       <a class="circle-label-compare circle-label-wishlist--add  mt-0" title="Add To Wishlist" @click="addToWishlist( newArriavlData.id, dataIndex )" v-if="!newArriavlData.is_wishlist"><i class="icon-heart-stroke" ></i></a>
                                       <a class="circle-label-compare circle-label-wishlist--remove mt-0" title="Remove From Wishlist" v-else><i class="icon-heart-hover" @click="removeFromWishlist( newArriavlData.id, dataIndex )"></i></a> 
                                    </div>
                                    <div class="prd-hide-mobile"><a href="#" class="circle-label-qview js-prd-quickview" data-src="ajax/ajax-quickview.html"><i class="icon-eye"></i><span>{{ lang.global.productdetail.relatedsection.quick }} {{ lang.global.view }} </span></a></div>
                                 </div>
                                 <div class="prd-price">
                                    <div class="price-old" v-if="newArriavlData.compare_at_price > 0">{{ $settings.CURRECNY_SYMBOL }} {{ newArriavlData.compare_at_price }}</div>
                                    <div class="price-new" v-if="newArriavlData.price > 0">{{ $settings.CURRECNY_SYMBOL }} {{ newArriavlData.price }}</div>
                                 </div>
                                 <div class="prd-action">
                                    <div class="prd-action-left">
                                       <a :href="'/product/detail/'+ newArriavlData.slug" class="btn js-prd-addtocart"> {{ lang.global.view }} {{ lang.global.collectiondetail.detail }} </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                    <!-- End Product section -->
                  </div>
               </div>
      </div>
   </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
   name: "RelatedSection",
   props:['products', 'auth'],
   data() {
      return {
         relatedProducts:[],
         client_id: CLIENT_ID,
      }
   },
   computed: {
      ...mapState(['globalStore']),
      noImage(){
         return this.globalStore.no_image;
      }
   },
   created(){
      this.relatedProducts = this.products;
   },
   methods: {
      addToWishlist(productId, index){
         this.$store.dispatch("globalStore/AddToWishlist", productId)
         .then((res) => {
              if (res.response.status_code == 2083) {
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
                  this.$store.commit('globalStore/addWishlistItem');
                  this.relatedProducts[index].is_wishlist = true;
              }
         })
         .catch((err) => {
            this.$toast.open({
              message: err,
              type: "error",
            });
         });
      },
      removeFromWishlist(productId, index){
         this.$store.dispatch("globalStore/DeleteWishlistIten", productId)
         .then((res) => {
              if (res.response.status_code == 2084) {
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
                  this.$store.commit('globalStore/removeWishlistItem');
                  this.relatedProducts[index].is_wishlist = false;
              }
         })
         .catch((err) => {
            this.$toast.open({
              message: err,
              type: "error",
            });
         });
      },
      setAltImg(event){
         event.target.src = this.noImage;
      },
   },
   components: {
   }
}
</script>