<template>
  <div>
         <div class="holder holder-mt-xsmall">
            <div class="container">
               <div class="row vert-margin-small">
                  <div class="col-sm" v-for="(accessrory,index in accessrories" v-if="accessrories.length > 0">
                     <a :href="accessrory.url" class="collection-grid-3-item image-hover-scale">
                        <div class="collection-grid-3-item-img image-container" style="padding-bottom: 93.68%">
                           <img :src="accessrory.imagedata[0]['imageurl']" :data-src="accessrory.imagedata[0]['imageurl']" class="lazyload fade-up" @error="setAltImg">
                           <div class="foxic-loader"></div>
                        </div>
                        <div class="collection-grid-3-caption-bg" v-if="accessrory.text1 != null || accessrory.text2 != null">
                           <h3 class="collection-grid-3-title">{{ accessrory.text1 }}</h3>
                           <h4 class="collection-grid-3-subtitle">{{ accessrory.text2 }}</h4>
                        </div>
                     </a>
                  </div>
                  <div class="col-sm" v-if="accessrories.length == 0">
                     <a href="#" class="collection-grid-3-item image-hover-scale">
                        <div class="collection-grid-3-item-img image-container" style="padding-bottom: 93.68%">
                           <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/banner-fashion-2-02.png" class="lazyload fade-up" alt="Banner">
                           <div class="foxic-loader"></div>
                        </div>
                        <div class="collection-grid-3-caption-bg">
                           <h3 class="collection-grid-3-title"> {{ lang.global.accesoriesfashion.fashion }} </h3>
                           <h4 class="collection-grid-3-subtitle">Live&nbsp;According&nbsp;to&nbsp;Fashion</h4>
                        </div>
                     </a>
                  </div>
                  <div class="col-sm" v-if="accessrories.length == 0">
                     <a href="#" class="collection-grid-3-item image-hover-scale">
                        <div class="collection-grid-3-item-img image-container" style="padding-bottom: 93.68%">
                           <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/banner-fashion-2-02.png" class="lazyload fade-up" alt="Banner">
                           <div class="foxic-loader"></div>
                        </div>
                        <div class="collection-grid-3-caption-bg">
                           <h3 class="collection-grid-3-title"> {{ lang.global.accesoriesfashion.fashion }}  </h3>
                           <h4 class="collection-grid-3-subtitle">Live&nbsp;According&nbsp;to&nbsp;Fashion</h4>
                        </div>
                     </a>
                  </div>

               </div>
            </div>
         </div>
  </div>
</template>

<script>

  export default {
    props: ['data'],
    name: "AccessoriesFashion",
    data() {
      return {
         accessrories: [],
         client_id: CLIENT_ID,
      }
    },
    computed: {
      
    },
    mounted(){
      this.accessrories = this.data.accessories;
    },
    methods: {
     setAltImg(event){
      event.target.src = '/theme/default/images/skins/fashion/banner-fashion-2-02.png';
     },
    },
    components: {
    }
  }
</script>