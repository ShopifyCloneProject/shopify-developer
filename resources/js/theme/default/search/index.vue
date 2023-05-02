<template>
   <div>
      <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/"> {{ lang.global.home }} </a></li>
               <li><span> {{ lang.global.searchview.search_for }} {{title}}</span></li>
            </ul>
         </div>
   </div>
   <div class="holder">
      <div class="container">
         <!-- Two columns -->
         <!-- Page Title -->
         <div class="page-title text-center">
            <h1>({{totalRecords}}) {{ lang.global.searchview.records_found }} </h1>
         </div>
         <!-- /Page Title -->
          <!-- Filter Row -->
         <div class="filter-row" v-if="totalRecords > 0">
            <div class="row">
               <div class="items-count">{{ currentPage == totalPages ? totalRecords : products.length * currentPage}} out of {{totalRecords}} item(s)</div>
               <div class="viewmode-wrap">
                  <div class="view-mode">
                     <span class="js-horview d-none d-lg-inline-flex" @click="changeView('prd-horgrid')" :class="{ active : active_el == 'prd-horgrid' }"><i class="icon-grid"></i></span>
                     <span class="js-gridview" @click="changeView('prd-grid')" :class="{ active : active_el == 'prd-grid' }"><i class="icon-grid"></i></span>
                     <span class="js-listview" @click="changeView('prd-listview')" :class="{ active : active_el == 'prd-listview' }"><i class="icon-list"></i></span>
                  </div>
               </div>
            </div>
         </div>
         <!-- /Filter Row -->
         <div class="row" id="product-listing">
            <!-- Center column -->
            <div class="col-lg aside">
               <div class="prd-grid-wrap">
                  <!-- Products Grid -->
                  <div class="product-listing data-to-show-5 data-to-show-md-5 data-to-show-sm-1 js-category-grid" :class="active_el" v-if="totalRecords > 0">
                     <div class="prd prd--style2 prd-labels--max prd-labels-shadow prd-w-xxs" 
                     v-for="(product,index) in products">
                        <div class="prd-inside">
                           <div class="prd-img-area">
                              <a :href="'/product/detail/'+ product.slug" class="prd-img image-hover-scale image-container">
                                 <img :src="product.medias[0].image_src[2]"  class="js-prd-img fade-up ls-is-cached lazyloaded" @error="noImage">
                                 <div class="foxic-loader"></div>
                                 <div class="prd-big-squared-labels"></div>
                              </a>
                              <div class="prd-circle-labels" v-if="auth">
                                 <a class="circle-label-compare circle-label-wishlist--add  mt-0" title="Add To Wishlist" @click="addToWishlist( product.id, index )" v-if="!product.is_wishlist"><i class="icon-heart-stroke" ></i></a>
                                 <a class="circle-label-compare circle-label-wishlist--remove mt-0" title="Remove From Wishlist" v-else><i class="icon-heart-hover" @click="removeFromWishlist( product.id, index )"></i></a>
                              </div>
                              <ul class="list-options color-swatch" v-if="product.medias.length > 0">
                                <li :data-image="imageData.image_src[3]" :class="(imageindex == 0)?'active': ''" v-for="(imageData,imageindex) in product.medias" v-if="imageindex < 3">
                                    <a href="javascript:void(0)" class="js-color-toggle">
                                       <img  class="fade-up ls-is-cached lazyloaded" :alt="product.title" :src="imageData.image_src[1]" @error="noImage">
                                    </a>
                                 </li> 
                              </ul> 
                           </div>
                           <div class="prd-info">
                              <div class="prd-info-wrap">
                                 <div class="prd-info-top">
                                    <div class="prd-rating"><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i></div>
                                 </div>
                                 <div class="prd-rating justify-content-center"><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i><i class="icon-star-fill fill"></i></div>
                                 <h2 class="prd-title"><a :href="'/product/detail/'+ product.slug">{{product.title}}</a></h2>
                                 <!-- <div class="prd-description" v-html="product.description">
                                 </div> -->
                                 <div class="prd-action">
                                    <form>
                                       <a :href="'/product/detail/'+ product.slug" class="btn js-prd-addtocart"> {{ lang.global.view }} {{ lang.global.collectiondetail.detail }} </a>
                                    </form>
                                 </div>
                              </div>
                              <div class="prd-hovers">
                                 <div class="prd-circle-labels" v-if="auth">
                                    <div>
                                       <a class="circle-label-compare circle-label-wishlist--add  mt-0" title="Add To Wishlist" @click="addToWishlist( product.id, index )" v-if="!product.is_wishlist"><i class="icon-heart-stroke" ></i></a>
                                       <a class="circle-label-compare circle-label-wishlist--remove mt-0" title="Remove From Wishlist" v-else><i class="icon-heart-hover" @click="removeFromWishlist( product.id, index )"></i></a>
                                    </div>
                                 </div>
                                 <div class="prd-price">
                                    <div class="price-old" v-if="product.compare_at_price > 0">{{ $settings.CURRECNY_SYMBOL }} {{product.compare_at_price}}</div>
                                    <div class="price-new">{{ $settings.CURRECNY_SYMBOL }} {{product.price}}</div>
                                 </div>
                                 <div class="prd-action">
                                    <div class="prd-action-left">
                                       <form>
                                          <button class="btn js-prd-addtocart"> {{ lang.global.view }} {{ lang.global.collectiondetail.detail }} </button>
                                       </form>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="section-pagination">
                     <sliding-pagination
                       :current="currentPage"
                       :total="totalPages"
                       @page-change="pageChangeHandler"
                     ></sliding-pagination>
                  </div>
                  <!-- /Products Grid -->
               </div>
            </div>
            <!-- /Center column -->
         </div>
         <!-- /Two columns -->
      </div>
   </div>
</div>
</template>

<script>
import { mapState } from 'vuex'

export default {
    name: "Search",
    props:['data', 'auth'],
    data() {
      return {
         active_el: 'prd-grid',
         products:[],
         currentPage: 1,
         totalPages: 1,
         totalRecords: 0,
         isProductFound:false,
         client_id: CLIENT_ID,
         title:''
      }
    },
    computed: {
      ...mapState(['globalStore']),
      noImage(){
         return this.globalStore.no_image;
      }
    },
    created(){
      this.products = this.data.products;
      this.totalPages = this.data.totalPages;
      this.totalRecords = this.data.totalRecords;
      this.title = this.data.title;
    },
    methods: {
      changeView(type){
         this.active_el = type;
      },
      pageChangeHandler(selectedPage) {
        this.currentPage = selectedPage;
        this.getSearchProducts();
      },
      getSearchProducts(){
         let section = $('#product-listing');
         blockSection(section);
         let payload = {
            page: this.currentPage,
            title: this.title
         }
         this.$store.dispatch("globalStore/GetSearchProducts", payload)
         .then((res) => {
            if (res.response.status_code == 2044) {
               this.products = [...res.response.data.data];
            }
            // $('html, body').animate({
            //    scrollTop: $("#product-listing").offset().top
            // }, 2000);
            unblockSection(section);
         })
         .catch((err) => {
            this.$toast.open({
              message: err,
              type: "error",
            });
            unblockSection(section);
         });
      },
      addToWishlist(productId, index){
         this.$store.dispatch("globalStore/AddToWishlist", productId)
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
    },
  }
</script>

<style scoped>
   .prd-listview .prd .prd-info {
      align-items: baseline;
   }
   .prd-listview .prd .prd-img-area{
      max-width: 12%;
   }
</style>