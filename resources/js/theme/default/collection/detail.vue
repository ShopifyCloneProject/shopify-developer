<template>
   <div>
      <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/"> {{ lang.global.home }} </a></li>
               <li><a href="/collections"> {{ lang.global.collectiondetail.collection }} </a></li>
               <li><span>{{title}}</span></li>
            </ul>
         </div>
   </div>
   <div class="holder">
      <div class="container" id="detailSection">
         <!-- Two columns -->
         <!-- Page Title -->
         <div class="page-title text-center">
            <h1>{{title}}</h1>
         </div>
         <!-- /Page Title -->
         <!-- Filter Row -->
         <div class="filter-row" v-if="products.length > 0">
            <div class="row">
               <!-- <div class="items-count">{{ currentPage == totalPages ? totalRecords : products.length * currentPage}} out of {{totalRecords}} item(s)</div> -->
               <div class="items-count">{{ currentPage == totalPages ? totalRecords : products.length }} out of {{totalRecords}} item(s)</div>
               <div class="select-wrap d-none d-md-flex">
                  <div class="select-label"> {{ lang.global.collectiondetail.sort }} </div>
                  <div class="select-wrapper select-wrapper-xxs" @change="getFilterProducts('filter')">
                     <select class="form-control input-sm" v-model="sortType">
                        <option value="new"> {{ lang.global.collectiondetail.new }} </option>
                        <option value="lowest"> {{ lang.global.collectiondetail.lowest_price }} </option>
                        <option value="highest"> {{ lang.global.collectiondetail.highest_price }} </option>
                        <option value="highest_sale"> {{ lang.global.collectiondetail.sale_product }} </option>
                     </select>
                  </div>
               </div>
               <div class="select-wrap d-none d-md-flex">
                  <div class="select-label"> {{ lang.global.view }} </div>
                  <div class="select-wrapper select-wrapper-xxs">
                     <select class="form-control input-sm" v-model="perPage" @change="getFilterProducts('filter')">
                        <option value="12">12</option>
                        <option value="36">36</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                     </select>
                  </div>
               </div>
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
         <div class="row">
            <!-- Left column -->
            <div class="col-lg-4 aside aside--left filter-col filter-mobile-col filter-col--sticky js-filter-col filter-col--opened-desktop" data-grid-tab-content>
               <div class="filter-col-content filter-mobile-content">
                  <div class="sidebar-block" v-if="getTotalSelection > 0">
                     <div class="sidebar-block_title">
                        <span> {{ lang.global.collectiondetail.current_selection }} </span>
                     </div>
                     <div class="sidebar-block_content">
                        <div class="selected-filters-wrap">
                           <ul class="selected-filters">
                              <template v-if="filter.size.length > 0">
                                 <li v-for="(size,index) in data.size">
                                    <a class="pointer" @click="removeFilter('size', size.id)" v-if="filter.size.includes(size.id)">{{size.options}}</a>
                                 </li>
                              </template>
                              <template v-if="filter.color.length > 0">
                                 <li v-for="(color,index) in data.color">
                                    <a class="pointer" @click="removeFilter('color', color.id)" v-if="filter.color.includes(color.id)">{{color.options}}</a>
                                 </li>
                              </template>
                              <template v-if="filter.material.length > 0">
                                 <li v-for="(material,index) in data.material">
                                    <a class="pointer" @click="removeFilter('material', material.id)" v-if="filter.material.includes(material.id)">{{material.options}}</a>
                                 </li>
                              </template>
                              <template v-if="filter.style.length > 0">
                                 <li v-for="(style,index) in data.style">
                                    <a class="pointer" @click="removeFilter('style', style.id)" v-if="filter.style.includes(style.id)">{{style.options}}</a>
                                 </li>
                              </template>
                              <template v-if="filter.titles.length > 0">
                                 <li v-for="(title,index) in data.title">
                                    <a class="pointer" @click="removeFilter('titles', title.id)" v-if="filter.titles.includes(title.id)">{{title.options}}</a>
                                 </li>
                              </template>
                              <template v-if="filter.brand.length > 0">
                                 <li v-for="(brand,index) in data.vendors">
                                    <a class="pointer" @click="removeFilter('brand', brand.id)" v-if="filter.brand.includes(brand.id)">{{brand.options}}</a>
                                 </li>
                              </template>
                              <template v-if="filter.tags.length > 0">
                                 <li v-for="(tags,index) in data.tags">
                                    <a class="pointer" @click="removeFilter('tags', tags.id)" v-if="filter.tags.includes(tags.id)">{{tags.options}}</a>
                                 </li>
                              </template>
                           </ul>
                           <div class="d-flex flex-wrap align-items-center">
                              <a class="clear-filters pointer" @click="clearSelection()"><span>Clear All</span></a>
                              <div class="selected-filters-count ml-auto d-none d-lg-block"> {{ lang.global.collectiondetail.selected }} <span>{{getTotalSelection}} {{ lang.global.collectiondetail.items }} </span></div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="sidebar-block d-filter-mobile">
                     <h3 class="mb-1"> {{ lang.global.collectiondetail.sort }} {{ lang.global.collectiondetail.by }} </h3>
                     <div class="select-wrapper select-wrapper-xs">
                        <select class="form-control">
                           <option value="featured"> {{ lang.global.collectiondetail.featured }} </option>
                           <option value="rating"> {{ lang.global.collectiondetail.rating }} </option>
                           <option value="price"> {{ lang.global.cart.price }} </option>
                        </select>
                     </div>
                  </div>
                  <div class="sidebar-block filter-group-block" :class="isOpenColor ? 'open' : 'collapsed'" v-if="data.color.length > 0">
                     <div class="sidebar-block_title" @click="isOpenColor = !isOpenColor">
                        <span> {{ lang.global.collectiondetail.colors }} </span>
                        <span class="toggle-arrow"><span></span><span></span></span>
                     </div>
                     <div class="sidebar-block_content">
                        <ul class="color-list two-column">
                           <li v-for="(color, index) in data.color">
                              <div class="clearfix">
                                 <input :id="'color'+index" :value="color.id" type="checkbox" v-model="filter.color" @change="getFilterProducts('filter')">
                                 <label :for="'color'+index">{{color.options}}</label>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="sidebar-block filter-group-block" :class="isOpenSize ? 'open' : 'collapsed'" v-if="data.size.length > 0">
                     <div class="sidebar-block_title" @click="isOpenSize = !isOpenSize">
                        <span> {{ lang.global.collectiondetail.size }} </span>
                        <span class="toggle-arrow"><span></span><span></span></span>
                     </div>
                     <div class="sidebar-block_content">
                        <ul class="category-list two-column size-list">
                           <li v-for="(size, index) in data.size">                              
                              <div class="clearfix">
                                 <input :id="'size'+index" :value="size.id" type="checkbox" v-model="filter.size" @change="getFilterProducts('filter')">
                                 <label :for="'size'+index">{{size.options}}</label>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="sidebar-block filter-group-block" :class="isOpenMaterial ? 'open' : 'collapsed'" v-if="data.material.length > 0">
                     <div class="sidebar-block_title" @click="isOpenMaterial = !isOpenMaterial">
                        <span> {{ lang.global.collectiondetail.material }} </span>
                        <span class="toggle-arrow"><span></span><span></span></span>
                     </div>
                     <div class="sidebar-block_content">
                        <ul class="category-list">
                            <li v-for="(material, index) in data.material">                              
                              <div class="clearfix">
                                 <input :id="'material'+index" :value="material.id" type="checkbox" v-model="filter.materials" @change="getFilterProducts('filter')">
                                 <label :for="'material'+index">{{material.options}}</label>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="sidebar-block filter-group-block" :class="isOpenStyle ? 'open' : 'collapsed'" v-if="data.style.length > 0">
                     <div class="sidebar-block_title" @click="isOpenStyle = !isOpenStyle">
                        <span> {{ lang.global.collectiondetail.style }} </span>
                        <span class="toggle-arrow"><span></span><span></span></span>
                     </div>
                     <div class="sidebar-block_content">
                        <ul class="category-list">
                            <li v-for="(style, index) in data.style">                              
                              <div class="clearfix">
                                 <input :id="'style'+index" :value="style.id" type="checkbox" v-model="filter.style" @change="getFilterProducts('filter')">
                                 <label :for="'style'+index">{{style.options}}</label>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="sidebar-block filter-group-block" :class="isOpenTitle ? 'open' : 'collapsed'" v-if="data.title.length > 0">
                     <div class="sidebar-block_title" @click="isOpenTitle = !isOpenTitle">
                        <span> {{ lang.global.collectiondetail.titles }} </span>
                        <span class="toggle-arrow"><span></span><span></span></span>
                     </div>
                     <div class="sidebar-block_content">
                        <ul class="category-list">
                            <li v-for="(title, index) in data.title">                              
                              <div class="clearfix">
                                 <input :id="'title'+index" :value="title.id" type="checkbox" v-model="filter.titles" @change="getFilterProducts('filter')">
                                 <label :for="'title'+index">{{title.options}}</label>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="sidebar-block filter-group-block" :class="isOpenBrand ? 'open' : 'collapsed'" v-if="data.vendors.length > 0">
                     <div class="sidebar-block_title" @click="isOpenBrand = !isOpenBrand">
                        <span> {{ lang.global.collectiondetail.brands }} </span>
                        <span class="toggle-arrow"><span></span><span></span></span>
                     </div>
                     <div class="sidebar-block_content">
                        <ul class="category-list">
                            <li v-for="(vendor, index) in data.vendors">                              
                              <div class="clearfix">
                                 <input :id="'vendor'+index" :value="vendor.id" type="checkbox" v-model="filter.brand" @change="getFilterProducts('filter')">
                                 <label :for="'vendor'+index">{{vendor.name}}</label>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="sidebar-block filter-group-block" :class="isOpenPrice ? 'open' : 'collapsed'">
                     <div class="sidebar-block_title" @click="isOpenPrice = !isOpenPrice">
                        <span> {{ lang.global.cart.price }} </span>
                        <span class="toggle-arrow"><span></span><span></span></span>
                     </div>
                     <div class="sidebar-block_content">
                        <vue-slider  :max="10000" :min="1" v-model="filter.price"></vue-slider>
                        <div class="preview-price p-1 text-center font-weight-bold">
                           <span class="min-price font-style-bold">{{ $settings.CURRECNY_SYMBOL }} {{filter.price[0]}}</span><span class="mx-1">-</span><span class="max-price">{{ $settings.CURRECNY_SYMBOL }} {{filter.price[1]}}</span>
                        </div>
                        <div class="text-center mt-1">
                           <button type="button" class="btn btn-primary" @click="getFilterProducts('filter')"> {{ lang.global.search }} </button>
                        </div>
                     </div>
                  </div>
                  <div class="sidebar-block filter-group-block" :class="isOpenTags ? 'open' : 'collapsed'"  v-if="data.tags.length > 0">
                     <div class="sidebar-block_title" @click="isOpenTags = !isOpenTags">
                        <span> {{ lang.global.collectiondetail.popular_tags }} </span>
                        <span class="toggle-arrow"><span></span><span></span></span>
                     </div>
                     <div class="sidebar-block_content populer-tags" id="scrollbar-style">
                        <ul class="tags-list">
                           <li v-for="(tag, index) in data.tags">                              
                              <div class="clearfix">
                                 <input :id="'tag'+index" :value="tag.id" type="checkbox" v-model="filter.tags" @change="getFilterProducts('filter')">
                                 <label :for="'tag'+index">{{tag.title}}</label>
                              </div>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <!-- filter toggle -->
            <div class="filter-toggle js-filter-toggle">
               <div class="loader-horizontal js-loader-horizontal">
                  <div class="progress">
                     <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                  </div>
               </div>
               <span class="filter-toggle-icons js-filter-btn"><i class="icon-filter"></i><i class="icon-filter-close"></i></span>
               <span class="filter-toggle-text"><a class="filter-btn-open js-filter-btn"> {{ lang.global.collectiondetail.refine_sort }} </a><a class="filter-btn-close js-filter-btn" @click="clearSelection()"> {{ lang.global.collectiondetail.reset }} </a><a class="filter-btn-apply js-filter-btn"> {{ lang.global.collectiondetail.close }} </a></span>
            </div>
            <!-- /Left column -->
            <!-- Center column -->
            <div class="col-lg aside">
               <div class="prd-grid-wrap">
                  <!-- Products Grid -->
                  <div class="product-listing data-to-show-5 data-to-show-md-5 data-to-show-sm-1 js-category-grid" ref="product-listing" id="product-listing" :class="active_el" v-if="products.length > 0">
                     <div class="prd prd--style2 prd-labels--max prd-labels-shadow prd-w-xxs" 
                     v-for="(product,index) in products">
                        <div class="prd-inside">
                           <div class="prd-img-area">
                              <a :href="'/product/detail/'+ product.slug" class="prd-img image-hover-scale image-container">
                                 <img :src="product.medias[0].image_src[3]" :alt="product.title" class="js-prd-img fade-up ls-is-cached lazyloaded" @error="setAltImg" v-if="product.medias.length > 0">
                                 <img src="" @error="setAltImg" :alt="product.title" class="js-prd-img fade-up ls-is-cached lazyloaded" v-else>
                                 <div class="foxic-loader"></div>
                                 <div class="prd-big-squared-labels">
                                 </div>
                              </a>
                              <div class="prd-circle-labels" v-if="auth">
                                <a class="circle-label-compare circle-label-wishlist--add  mt-0" title="Add To Wishlist" @click.prevent="addToWishlist( product, index)" v-if="!product.is_wishlist"><i class="icon-heart-stroke"></i></a>
                                 <a class="circle-label-compare circle-label-wishlist--remove mt-0" title="Remove From Wishlist" @click.prevent="removeFromWishlist( product.id, index)" v-else><i class="icon-heart-hover"></i></a> 
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
                                       <a class="btn js-prd-addtocart" :href="'/product/detail/'+ product.slug">{{ lang.global.view }}{{ lang.global.collectiondetail.detail }}</a>
                                 </div>
                              </div>
                              <div class="prd-hovers">
                                 <div class="prd-circle-labels" v-if="auth">
                                    <div>
                                      <a class="circle-label-compare circle-label-wishlist--add  mt-0" title="Add To Wishlist" @click.prevent="addToWishlist( product, index)" v-if="!product.is_wishlist"><i class="icon-heart-stroke"></i></a>
                                       <a class="circle-label-compare circle-label-wishlist--remove mt-0" title="Remove From Wishlist" @click.prevent="removeFromWishlist( product.id, index)" v-else><i class="icon-heart-hover"></i></a> 
                                    </div>
                                  <!--   <div class="prd-hide-mobile"><a href="#" class="circle-label-qview js-prd-quickview" data-src="ajax/ajax-quickview.html"><i class="icon-eye"></i><span>QUICK VIEW</span></a></div> -->
                                 </div>
                                 <div class="prd-price">
                                    <div class="price-old" v-if="product.compare_at_price > 0">{{ $settings.CURRECNY_SYMBOL }} {{product.compare_at_price}}</div>
                                    <div class="price-new">{{ $settings.CURRECNY_SYMBOL }} {{product.price}}</div>
                                 </div>
                                 <div class="prd-action">
                                    <div class="prd-action-left">
                                          <a class="btn js-prd-addtocart" :href="'/product/detail/'+ product.slug">{{ lang.global.view }} {{ lang.global.collectiondetail.detail }}</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- <div class="section-pagination" v-if="totalPages > 1">
                     <sliding-pagination
                       :current="currentPage"
                       :total="totalPages"
                       @page-change="pageChangeHandler"
                     ></sliding-pagination>
                  </div> -->
                  <div class="holder mt-0" v-if="isProductFound">
                       <div class="page404-bg">
                          <div class="page404-text">
                            <div class="txt2"> {{ lang.global.collectiondetail.product_not_found }} </div>
                          </div>
                          <svg id="morphing" xmlns="" width="600" height="600" viewBox="0 0 600 600">
                            <g transform="translate(50,50)">
                              <path class="p" d="M93.5441 2.30824C127.414 -1.02781 167.142 -4.63212 188.625 21.7114C210.22 48.1931 199.088 86.5178 188.761 119.068C179.736 147.517 162.617 171.844 136.426 186.243C108.079 201.828 73.804 212.713 44.915 198.152C16.4428 183.802 6.66731 149.747 1.64848 118.312C-2.87856 89.9563 1.56309 60.9032 19.4066 38.3787C37.3451 15.7342 64.7587 5.14348 93.5441 2.30824Z"/>
                            </g>
                          </svg>
                        </div>
                        <div class="page404-info text-center">
                           <a href="/products/all" class="btn"> {{ lang.global.continue }}{{ lang.global.cart.browse_products }} </a>
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
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/default.css'

export default {
    name: "Collection",
    props:['data', 'auth'],
    data() {
      return {
         active_el: 'prd-grid',
         id:'',
         title:'',
         slug:'',
         products:[],
         currentPage: 1,
         totalPages: 1,
         totalRecords: 0,
         perPage:12,
         client_id: CLIENT_ID,
         sortType:'highest_sale',
         isOpenSize:true,
         isOpenBrand:false,
         isOpenColor:false,
         isOpenTags:false,
         isOpenTitle:false,
         isOpenStyle:false,
         isOpenMaterial:false,
         isOpenPrice:true,
         isProductFound:false,
         queriedProductAPI:false,
         filter:{
            size:[],
            color:[],
            brand:[],
            price:[1,10000],
            tags:[],
            material:[],
            style:[],
            titles:[],
         }
      }
    },
    watch: {
       // whenever question changes, this function will run
      products: function () {
         if(this.products.length == 0){
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
      },
      getTotalSelection(){
         return parseInt(this.filter.size.length) + parseInt(this.filter.color.length) + parseInt(this.filter.brand.length) + parseInt(this.filter.tags.length) + parseInt(this.filter.material.length) + parseInt(this.filter.style.length) + parseInt(this.filter.titles.length);
      },
    },
    created(){
      this.id = this.data.collection.id;
      this.title = this.data.collection.title;
      this.slug = this.data.collection.slug;
    },
    mounted(){
      this.getFilterProducts();
      this.$nextTick(function() {
        window.addEventListener('scroll', this.onScroll);
        this.onScroll(); // needed for initial loading on page
      });   
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
                     this.getFilterProducts();
                  }      
               }                               
            }  
         }
      }, 
      addFilter(type, value){
         this.filter.size.push(value);
      },
      changeView(type){
         this.active_el = type;
      },
      pageChangeHandler(selectedPage) {
        this.currentPage = selectedPage;
        this.getFilterProducts();
      },
      getFilterProducts(type = null){
         if(!this.queriedProductAPI){
            this.queriedProductAPI = true;
            if(type != null){
               this.currentPage = 1;
               this.products = [];
            }
            let section = $('#detailSection');
            blockSection(section);
            var payload = {slug: this.slug, pid: this.currentPage, sortType:this.sortType, perPage:this.perPage, price:this.filter.price};
            if(this.filter.size.length > 0){ payload.size = this.filter.size; }
            if(this.filter.color.length > 0){ payload.color = this.filter.color; }
            if(this.filter.material.length > 0){ payload.material = this.filter.material; }
            if(this.filter.style.length > 0){ payload.style = this.filter.style; }
            if(this.filter.titles.length > 0){ payload.titles = this.filter.titles; }
            if(this.filter.brand.length > 0){ payload.brand = this.filter.brand; }
            if(this.filter.tags.length > 0){ payload.tags = this.filter.tags; }

            this.$store.dispatch("globalStore/getFilterProducts", payload)
            .then((res) => {
               if (res.response.status_code == 2044) {
                  // this.products = [...res.response.data.products];
                  let vm = this;
                  res.response.data.products.forEach(product => {
                     vm.products.push(product);
                  });
                  this.totalPages = res.response.data.totalPages;
                  // this.currentPage = res.response.data.currentPage;
                  this.totalRecords = res.response.data.totalRecords;
               }
               // $('html, body').animate({
               //    scrollTop: $("#detailSection").offset().top
               // }, 2000);
               this.queriedProductAPI = false;
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
      clearSelection(){
         this.filter.size = [];
         this.filter.color = [];
         this.filter.brand = [];
         this.filter.price = [1,10000];
         this.filter.tags = [];
         this.filter.material = [];
         this.filter.style = [];
         this.filter.titles = [];
         this.getFilterProducts('filter');
      },
      removeFilter(type, value){
         let index = this.filter[type].indexOf(value);
         this.filter[type].splice(index, 1);
         this.getFilterProducts('filter');
      },
      setAltImg(event){
         event.target.src = this.noImage;
      },
      addToWishlist(productData, index){
         //this.callFbPixelwhishlist(productData);
         this.$store.dispatch("globalStore/AddToWishlist", productData.id)
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
    components: {
      VueSlider
    }
  }
</script>

<style scoped>
   .prd-listview .prd .prd-info {
      align-items: baseline;
   }
   .prd-listview .prd .prd-img-area{
      max-width: 17%;
   }
   input[type='checkbox'] + label:before, input[type='radio'] + label:before {
      border: 1px solid #ddd;
      background-color: #ddd;
   }
   span.range-slider.slider {
      width: 100%;
   }
   .range-slider-fill {
      background-color: #17c6aa;
   }
   .tags-list li{
      display: block;
   }
</style>