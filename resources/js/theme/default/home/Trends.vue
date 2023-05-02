<template>
  <div>
     <div class="holder holder-mt-medium ">
            <div class="container">
               <template v-if="trends.length > 0">
                  <template v-for="(trend,index) in trends">
                     <a :href="trend.url" target="_blank" class="bnr-wrap bnr-" >
                        <div class="bnr custom-caption image-hover-scale bnr--middle bnr--right bnr--fullwidth">
                           <div class="bnr-img d-none d-sm-block image-container" style="padding-bottom: 41.36752136752137%">
                              <img :src="trend.imagedata[0]['imageurl']" :data-src="trend.imagedata[0]['imageurl']" class="lazyload fade-up" @error="setAltImg">
                           </div>
                           <div class="bnr-img d-sm-none image-container" style="padding-bottom: 74.3139407244786%">
                              <img :src="trend.imagedata[0]['imageurl']" :data-src="trend.imagedata[0]['imageurl']" class="lazyload fade-up" @error="setAltImgMobile">
                           </div>
                           <div class="bnr-caption text-center" style="padding: 4% 4%; ">
                              <div class="bnr-caption-inside w-s-50 w-ms-100 title-wrap">
                                 <h2 class="h1-style">{{ trend.text1 }}</h2>
                                 <div class="h-sub mt-0">{{ trend.text2 }}</div>
                                 <div class="bnr-btn inherit mt-sm order-3" v-if="trend.url != '' && trend.url != null">
                                     <a :href="trend.url" target="_blank" class="btn"> {{ lang.global.trends.buy_now }} </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </a>
                  </template>
               </template>
               <template v-else>
               <a href="#" target="_blank" class="bnr-wrap bnr-">
                  <div class="bnr custom-caption image-hover-scale bnr--middle bnr--right bnr--fullwidth">
                     <div class="bnr-img d-none d-sm-block image-container" style="padding-bottom: 41.36752136752137%">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/banner-fashion2-full.png" class="lazyload fade-up" alt="">
                     </div>
                     <div class="bnr-img d-sm-none image-container" style="padding-bottom: 74.3139407244786%">
                        <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/banner-fashion2-full-m.png" class="lazyload fade-up" alt="">
                     </div>
                     <div class="bnr-caption text-center" style="padding: 4% 4%; ">
                        <div class="bnr-caption-inside w-s-50 w-ms-100 title-wrap">
                           <h2 class="h1-style"> {{ lang.global.trends.best_trends }} <br class="d-sm-none"> {{ lang.global.trends.summer }} </h2>
                           <div class="h-sub mt-0"> {{ lang.global.trends.ecommerce_template }} </div>
                           <div class="bnr-btn inherit mt-sm order-3">
                             <a href="/" class="btn"> {{ lang.global.trends.buy_now }} </a>
                           </div>
                        </div>
                     </div>
                  </div>
               </a>
               </template>
            </div>
         </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

  export default {
   props: ['data'],
    name: "Trends",
    data() {
      return {
         client_id: CLIENT_ID,
         trends: [],
        
      }
    },
   mounted(){
      this.trends = this.data.trends;
    },
    computed: {
      
    },
    methods: {
      setAltImg(event){
      event.target.src = '/theme/default/images/skins/fashion/banner-fashion2-full-m.png';
      },
      setAltImgMobile(event)
      {
      event.target.src = '/theme/default/images/skins/fashion/banner-fashion2-full.png';

      }
    },
    components: {
    }
  }
</script>