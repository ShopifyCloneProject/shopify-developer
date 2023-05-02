<template>
   <div>
      <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/"> {{ lang.global.home }} </a></li>
               <li><span> {{ lang.global.collectiondetail.collection }} </span></li>
            </ul>
         </div>
   </div>
   <div class="holder">
      <div class="container collection-page-content">
         <div class="page-title text-center">
            <div class="title">
               <h1> {{ lang.global.collectiondetail.collection }} </h1>
            </div>
         </div>
         <div class="row collection-grid-2 mobile-sm-pad custom-grid data-to-show-4 data-to-show-lg-3 data-to-show-md-2 data-to-show-sm-2" v-if="collections.length > 0">
            <div class="collection-grid-2-item w-100 text-center" v-for="(collection, index) in collections">
               <a :href="'/collections/' + collection.slug" class="bnr-wrap collection-grid-2-item-inside">
                  <div class="collection-grid-2-img image-container image-hover-scale" style="padding-bottom: 80.0%">
                     <img :src="collection.src.url" :data-src="collection.src.url" class="lazyload fade-up" :alt="collection.src_alt_text" v-if="collection.src != null && collection.src != ''" @error="setAltImg">
                     <img :src="noImage" :data-src="noImage" class="lazyload fade-up" :alt="collection.src_alt_text" v-else>
                  </div>
               </a>
               <h3 class="collection-grid-2-title"><a :href="'/collections/' + collection.slug">{{collection.title}}</a></h3>
               <h5 class="collection-item-qty">{{collection.products_count}} {{ lang.global.collectiondetail.items }} </h5>
            </div>
         </div>
         <div class="holder mt-0" v-if="isCollectionFound">
           <div class="page404-bg">
              <div class="page404-text">
                <div class="txt2"> {{ lang.global.collectiondetail.collection }}{{ lang.global.collection.not_found }} </div>
              </div>
              <svg id="morphing" width="600" height="600" viewBox="0 0 600 600">
                <g transform="translate(50,50)">
                  <path class="p" d="M93.5441 2.30824C127.414 -1.02781 167.142 -4.63212 188.625 21.7114C210.22 48.1931 199.088 86.5178 188.761 119.068C179.736 147.517 162.617 171.844 136.426 186.243C108.079 201.828 73.804 212.713 44.915 198.152C16.4428 183.802 6.66731 149.747 1.64848 118.312C-2.87856 89.9563 1.56309 60.9032 19.4066 38.3787C37.3451 15.7342 64.7587 5.14348 93.5441 2.30824Z"/>
                </g>
              </svg>
            </div>
            <div class="page404-info text-center">
               <a href="/products/all" class="btn">{{ lang.global.continue }}{{ lang.global.cart.browse_products }}</a>
            </div>
         </div>
      </div>
      <div class="more-link-wrapper text-center" v-if="collections.length > 0"><a href="/products/all" class="btn"> {{ lang.global.collection.all }}{{ lang.global.collection.products }}</a></div>
   </div>
</div>
</template>

<script>
import { mapState } from 'vuex'

export default {
    name: "Collection",
    props:['data'],
    data() {
      return {
         collections:[],
         isCollectionFound:false,
      }
    },
    computed: {
      ...mapState(['globalStore']),
      noImage(){
         return this.globalStore.no_image;
      }
    },
    watch: {
       // whenever question changes, this function will run
      collections: function () {
         if(this.collections.length == 0){
            this.isCollectionFound = true;
         } else {
            this.isCollectionFound = false;
         }
      }
    },
    created(){
      this.collections = this.data.collections;
    },
    methods: {
      setAltImg(event){
         event.target.src = this.noImage;
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
      max-width: 17%;
   }
</style>