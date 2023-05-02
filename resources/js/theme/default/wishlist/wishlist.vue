<template>
   <div>
      <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/">{{ lang.global.home }}</a></li>
               <li><span>{{ lang.global.wishlist.wishlist }}</span></li>
            </ul>
         </div>
   </div>
   <div class="holder">
      <div class="container">
         <!-- Two columns -->
         <!-- Page Title -->
         <div class="page-title text-center">
            <h1>{{ lang.global.wishlist.my_wishlist }}</h1>
         </div>
         <!-- /Page Title -->
         <!-- Filter Row -->
         <div class="filter-row" v-if="wishlists.length > 0">
            <div class="row">
               <!-- <div class="items-count">{{ currentPage == totalPages ? totalRecords : wishlists.length * currentPage}} out of {{totalRecords}} item(s)</div> -->
               <div class="items-count">{{ currentPage == totalPages ? totalRecords : wishlists.length}} out of {{totalRecords}} item(s)</div>
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
         <div class="row" id="wishlist-listing">
            <!-- Center column -->
            <div class="col-lg aside">
               <div class="prd-grid-wrap">
                  <!-- Products Grid -->
                  <div class="product-listing data-to-show-5 data-to-show-md-5 data-to-show-sm-1 js-category-grid" ref="product-listing" id="product-listing" :class="active_el" v-if="wishlists.length > 0">
                     <div class="prd prd--style2 prd-labels--max prd-labels-shadow prd-w-xxs" 
                     v-for="(wishlist,index) in wishlists">
                        <div class="prd-inside">
                           <div class="prd-img-area">
                              <a :href="'/product/detail/'+ wishlist.product.slug" class="prd-img image-hover-scale image-container">
                                 <img :src="wishlist.product.medias[0].image_src[3]" :alt="wishlist.product.title" class="js-prd-img fade-up ls-is-cached lazyloaded" v-if="wishlist.product.medias.length > 0">
                                 <img :src="noImage" @error="noImage" :alt="wishlist.product.title" class="js-prd-img fade-up ls-is-cached lazyloaded" v-else>
                                 <div class="foxic-loader"></div>
                                 <div class="prd-big-squared-labels"></div>
                              </a>
                              <div class="prd-circle-labels">
                                 <a class="circle-label-compare circle-label-wishlist--remove js-remove-wishlist mt-0" title="Remove From Wishlist" @click.prevent="removeFromWishlist(wishlist.product.id, index)"><i class="icon-heart-hover"></i></a>
                              </div>
                              <ul class="list-options color-swatch" v-if="wishlist.product.medias.length > 0">
                                <li :data-image="imageData.image_src[3]" :class="(imageindex == 0)?'active': ''" v-for="(imageData,imageindex) in wishlist.product.medias" v-if="imageindex < 3">
                                    <a href="javascript:void(0)" class="js-color-toggle">
                                       <img  class="fade-up ls-is-cached lazyloaded" :alt="wishlist.product.title" :src="imageData.image_src[1]" @error="noImage">
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
                                 <h2 class="prd-title"><a :href="'/product/detail/'+ wishlist.product.slug">{{wishlist.product.title}}</a></h2>
                                 <!-- <div class="prd-description" v-html="wishlist.product.description">
                                 </div> -->
                                 <div class="prd-action">
                                       <a :href="'/product/detail/'+ wishlist.product.slug" class="btn js-prd-addtocart">{{ lang.global.wishlist.view_detail }}</a>
                                 </div>
                              </div>
                              <div class="prd-hovers">
                                 <div class="prd-circle-labels">
                                    <div>
                                       <a class="circle-label-compare circle-label-wishlist--remove  js-remove-wishlist mt-0" title="Remove From Wishlist" @click.prevent="removeFromWishlist(wishlist.product.id, index)"><i class="icon-heart-hover"></i></a>
                                    </div>
                                  <!--   <div class="prd-hide-mobile"><a href="#" class="circle-label-qview js-prd-quickview" data-src="ajax/ajax-quickview.html"><i class="icon-eye"></i><span>{{ lang.global.wishlist.quick_view }}</span></a></div> -->
                                 </div>
                                 <div class="prd-price">
                                    <div class="price-old" v-if="wishlist.product.compare_at_price > 0">{{ $settings.CURRECNY_SYMBOL }} {{wishlist.product.compare_at_price}}</div>
                                    <div class="price-new">{{ $settings.CURRECNY_SYMBOL }} {{wishlist.product.price}}</div>
                                 </div>
                                 <div class="prd-action">
                                    <div class="prd-action-left">
                                         <a :href="'/product/detail/'+ wishlist.product.slug" class="btn js-prd-addtocart">{{ lang.global.wishlist.view_detail }}</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                 <!--  <div class="section-pagination" v-if="!isProductFound">
                     <sliding-pagination
                       :current="currentPage"
                       :total="totalPages"
                       @page-change="pageChangeHandler"
                     ></sliding-pagination>
                  </div> -->

                  <div v-if="isProductFound">
                       <div class="page404-bg">
                          <div class="page404-text">
                            <div class="txt2">{{ lang.global.wishlist.wishlist_empty }}</div>
                          </div>
                          <svg id="morphing" xmlns="" width="600" height="600" viewBox="0 0 600 600">
                            <g transform="translate(50,50)">
                              <path class="p" d="M93.5441 2.30824C127.414 -1.02781 167.142 -4.63212 188.625 21.7114C210.22 48.1931 199.088 86.5178 188.761 119.068C179.736 147.517 162.617 171.844 136.426 186.243C108.079 201.828 73.804 212.713 44.915 198.152C16.4428 183.802 6.66731 149.747 1.64848 118.312C-2.87856 89.9563 1.56309 60.9032 19.4066 38.3787C37.3451 15.7342 64.7587 5.14348 93.5441 2.30824Z"/>
                            </g>
                          </svg>
                        </div>
                        <div class="page404-info text-center">
                           <a href="/products/all" class="btn">{{ lang.global.wishlist.continue_browse_product }}</a>
                        </div>
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
    name: "Wishlist",
    props:['data'],
    data() {
      return {
         active_el: 'prd-grid',
         wishlists:[],
         client_id: CLIENT_ID,
         currentPage: 1,
         totalPages: 1,
         totalRecords: 0,
         perPage:2,
         isProductFound:false,
         queriedProductAPI:false,
      }
    },
    watch: {
       // whenever question changes, this function will run
      wishlists: function () {
         if(this.wishlists.length == 0){
            this.isProductFound = true;
         } else {
            this.isProductFound = false;
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
      this.$nextTick(function() {
        window.addEventListener('scroll', this.onScroll);
        this.onScroll(); // needed for initial loading on page
      });   
    },
    created(){
      this.wishlists = this.data.wishlist;
      this.totalPages = this.data.totalPages;
      this.totalRecords = this.data.totalRecords;
    },
    beforeDestroy() {
       window.removeEventListener('scroll', this.onScroll);
    }, 
    methods: {
      onScroll() {
         if(this.currentPage != this.totalPages){
            var usersHeading = this.$refs["product-listing"];
            if (usersHeading) {
               var marginTop = Math.abs(usersHeading.getBoundingClientRect().top) ;
               var marginHeight = Math.abs(usersHeading.getBoundingClientRect().height) ;
               var innerHeight = Math.abs(window.innerHeight);
               if (( parseInt(marginTop) + parseInt(innerHeight + 200) ) >= parseInt(marginHeight) && ( parseInt(marginTop) + parseInt(innerHeight + 200) ) <= parseInt(marginHeight+50)) { 
                  if(!this.queriedProductAPI){  
                     this.currentPage = this.currentPage + 1;  
                     this.getWishlist();
                  }      
               }                               
            }  
         }
      }, 
      changeView(type){
         this.active_el = type;
      },
      removeFromWishlist(productId, index){
         this.$store.dispatch("globalStore/DeleteWishlistIten", productId)
         .then((res) => {
              if (res.response.status_code == 2084) {
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
                  this.wishlists.splice(index, 1);
                  this.$store.commit('globalStore/removeWishlistItem');
              }
         })
         .catch((err) => {
            this.$toast.open({
              message: err,
              type: "error",
            });
         });
      },
      pageChangeHandler(selectedPage) {
        this.currentPage = selectedPage;
        this.getWishlist();
      },
      getWishlist(){
         if(!this.queriedProductAPI){
            this.queriedProductAPI = true;
            let section = $('#wishlist-listing');
            blockSection(section);
            this.$store.dispatch("globalStore/GetWishlist", this.currentPage)
            .then((res) => {
               if (res.response.status_code == 2093) {
                  // this.wishlists = [...res.response.data];
                  let vm = this;
                  res.response.data.forEach(product => {
                     vm.wishlists.push(product);
                  });
               }
               this.queriedProductAPI = false;
               // $('html, body').animate({
               //    scrollTop: $("#product-listing").offset().top
               // }, 2000);
               unblockSection(section);
            })
            .catch((err) => {
               this.queriedProductAPI = false;
               this.$toast.open({
                 message: err,
                 type: "error",
               });
               unblockSection(section);
            });
         }
      },
    },
    components: {
    }
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