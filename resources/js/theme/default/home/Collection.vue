<template>
  <div>
    <div class="holder container holder-mt-medium section-name-products-grid" id="productsGrid01">
            <div class="container-fluid collection-container">
               <div class="title-wrap text-center">
                  <h2 class="h1-style">{{ lang.global.collections }}</h2>
                  <div class="title-wrap title-tabs-wrap text-center js-title-tabs">
                     <div class="title-tabs">
                        <h2 class="h3-style" v-for="(collection, index) in collections" :class="(activeCollection == index) ? 'active-tab' : ''">
                           <a href="javascript:void(0)" data-total="8" data-loaded="4" data-grid-tab-title @click.prevent="setCollectionProduct(index)">
                              <span class="title-tabs-text theme-font" v-if="collection.title != ''">{{ collection.title }}</span>
                           </a>
                        </h2>
                     </div>
                  </div>
               </div>
               <div class="prd-grid-wrap">
                  <div class="prd-grid data-to-show-4 data-to-show-md-3 data-to-show-sm-2 data-to-show-xs-2" data-grid-tab-content="" style="opacity: 1;">
                     <div class="prd prd--style2 prd-labels--max prd-labels-shadow prd-w-lg" v-for="(productData,index) in products" v-if="isVisible">
                        <div class="prd-inside">
                           <div class="prd-img-area">
                              <a :href="'/product/detail/'+ productData.slug" class="prd-img image-hover-scale image-container" style="padding-bottom: 128.48%">
                                 <img :src="productData.medias[0].image_src[3]" @error="setAltImg" :alt="productData.title" class="js-prd-img fade-up ls-is-cached lazyloaded" v-if="productData.medias.length > 0">
                                  <img src="" @error="setAltImg" :alt="productData.title" class="js-prd-img fade-up ls-is-cached lazyloaded" v-else>
                                 <div class="foxic-loader"></div>
                                 <!-- <div class="prd-big-squared-labels">
                                    <div class="label-sale">
                                       <span>-10% <span class="sale-text">Sale</span></span>
                                       <div class="countdown-circle"></div>
                                    </div>
                                 </div> -->
                              </a>
                              <div class="prd-circle-labels" v-if="auth">
                                 <a class="circle-label-compare circle-label-wishlist--add  mt-0" title="Add To Wishlist" @click.prevent="addToWishlist( productData, index)" v-if="!productData.is_wishlist"><i class="icon-heart-stroke"></i></a>
                                 <a class="circle-label-compare circle-label-wishlist--remove mt-0" title="Remove From Wishlist" @click.prevent="removeFromWishlist( productData.id, index)" v-else><i class="icon-heart-hover"></i></a> 
                                 <!-- <a class="circle-label-qview js-prd-quickview prd-hide-mobile" data-src="ajax/ajax-quickview.html"><i class="icon-eye"></i><span>QUICK VIEW</span></a> -->
                              </div>
                              <ul class="list-options color-swatch" v-if="productData.medias.length > 0">
                                 <li :data-image="imageData.image_src[3]" :class="(imageindex == 0)?'active': ''" v-for="(imageData,imageindex) in productData.medias" v-if="imageindex < 3">
                                    <a :href="'/product/detail/'+ productData.slug"  class="js-color-toggle">
                                       <img  class="fade-up ls-is-cached lazyloaded" :src="imageData.image_src[1]" @error="setAltImg">
                                    </a>
                                 </li> 
                              </ul> 
                           </div>
                           <div class="prd-info">
                              <div class="prd-info-wrap">
                                 <div class="prd-info-top">
                                    <div class="prd-rating"><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i></div>
                                    <div class="prd-tag"><a href="javascript:void(0)"></a></div>
                                 </div>
                                 <div class="prd-rating justify-content-center"><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i></div>
                                 <div class="prd-tag"><a href="javascript:void(0)"></a></div>
                                 <h2 class="prd-title" v-if="productData.title != ''"><a :href="'/product/detail/'+ productData.slug">{{ productData.title }}</a></h2>
                                 <div class="prd-description" v-html="productData.description"></div>
                              </div>
                              <div class="prd-hovers">
                                 <div class="prd-circle-labels" v-if="auth">
                                    <div>
                                       <a class="circle-label-compare circle-label-wishlist--add  mt-0" title="Add To Wishlist" @click="addToWishlist( productData, index )" v-if="!productData.is_wishlist"><i class="icon-heart-stroke" ></i></a>
                                       <a class="circle-label-compare circle-label-wishlist--remove mt-0" title="Remove From Wishlist" v-else><i class="icon-heart-hover" @click="removeFromWishlist( productData.id, index )"></i></a> 
                                    </div>
                                    <!-- <div>
                                       <a href="javascript:void(0)" class="circle-label-qview prd-hide-mobile js-prd-quickview" data-src="ajax/ajax-quickview.html"><i class="icon-eye"></i><span>QUICK VIEW</span></a>
                                    </div -->>
                                 </div>
                                 <div class="prd-price">
                                    <div class="price-old" v-if="productData.compare_at_price > 0">{{ $settings.CURRECNY_SYMBOL }} {{ productData.compare_at_price }}</div>
                                    <div class="price-new" v-if="productData.price > 0">{{ $settings.CURRECNY_SYMBOL }} {{ productData.price }}</div>
                                 </div>
                                 <div class="prd-action">
                                    <div class="prd-action-left">
                                      <a :href="'/product/detail/'+ productData.slug" class="btn js-prd-addtocart">{{ lang.global.view }} {{ lang.global.collectiondetail.detail }}</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="text-center w-100" v-if="isVisible">
                        <a :href="collectionUrl" class="btn btn-primary mt-2">{{ lang.global.view }} {{ lang.global.homecollection.collection }}</a>
                     </div>
                     <div class="holder mt-0" v-if="!isVisible">
                        <div class="txt2 h4 font-weight-bold p-5">{{ lang.global.homecollection.collection_empty }}</div>
                     </div>
                  </div>
                  <div class="prd-grid data-to-show-4 data-to-show-md-3 data-to-show-sm-2 data-to-show-xs-2" data-grid-tab-content></div>
                     <div class="loader-horizontal-sm js-loader-horizontal-sm d-none" data-loader-horizontal style="opacity: 0;"><span></span>
                     </div>
                     <!-- <div class="circle-loader-wrap d-none">
                        <div class="circle-loader">
                           <a href="" data-load="4" class="js-circle-loader">
                              <svg id="svg_d" version="1.1" xmlns="">
                                 <circle cx="50%" cy="50%" r="63" fill="transparent"></circle>
                                 <circle class="js-circle-bar" cx="50%" cy="50%" r="63" fill="transparent"></circle>
                              </svg>
                              <svg id="svg_m" version="1.1" xmlns="">
                                 <circle cx="50%" cy="50%" r="50" fill="transparent"></circle>
                                 <circle class="js-circle-bar" cx="50%" cy="50%" r="50" fill="transparent"></circle>
                              </svg>
                              <div class="circle-loader-text">Load More</div>
                              <div class="circle-loader-text-alt">
                                 <span class="js-circle-loader-start"></span>&nbsp;out of&nbsp;<span class="js-circle-loader-end"></span>
                              </div>
                           </a>
                        </div>
                     </div> -->
               </div>
            </div>
         </div>
  </div>
</template>

<script>
import { mapState } from 'vuex';

  export default {
    name: "Collection",
    props: ['collections', 'auth'],
    data() {
      return {
        cruds: [],
        products: [],
        client_id: CLIENT_ID,
        selectCollectionName : '',
        activeCollection: 0,
        collectionUrl:'',
        isVisible:true
      }
    },
    computed: {
      ...mapState(['globalStore']),
      noImage(){
         return this.globalStore.no_image;
      }
    },
    created(){
    },
    mounted(){
      this.products = this.collections[0].products;
      this.collectionUrl = '/collections/' + this.collections[0].slug;
      this.selectCollectionName = this.collections[0].title;
      this.isVisible = ( this.products.length > 0 ) ? true : false;
    },
    methods: {
      setCollectionProduct(index){
          this.activeCollection = index;
          this.selectCollectionName = this.collections[index].title;
          this.products = this.collections[index].products;
          this.collectionUrl = '/collections/' + this.collections[index].slug;
          this.isVisible = ( this.products.length > 0 ) ? true : false;
      },
      setAltImg(event){
         event.target.src = this.noImage;
      },
      addToWishlist(productdata, index){
         this.callFbPixelwhishlist(productdata);
         this.$store.dispatch("globalStore/AddToWishlist", productdata.id)
         .then((res) => {
              if (res.response.status_code == 2083) {
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
                  this.$store.commit('globalStore/addWishlistItem');
                  this.products[index].is_wishlist = true;
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
                  this.products[index].is_wishlist = false;
              }
         })
         .catch((err) => {
            this.$toast.open({
              message: err,
              type: "error",
            });
         });
      },
      callFbPixelwhishlist(productdata){
         fbq('track', 'AddToWishlist', {
            content_name: productdata.title,
            content_category: this.selectCollectionName,
            content_ids: [productdata.id.toString()],
            contents: [{id: productdata.id, quantity: 1}],
            content_type : 'product',
            value: productdata.price,
            currency: 'INR'
           });
      },
    },
    components: {
    }
  }
</script>
<style lang="scss" scopped>
.js-prd-img
{
   background: #f8f8f8;
}

.h3-style{
   &:hover{
      .title-tabs-text{
         color: #17c6aa !important;   
     }
  }
}

.active-tab{
   .title-tabs-text{
      color: #17c6aa !important;   
   }
}
</style>