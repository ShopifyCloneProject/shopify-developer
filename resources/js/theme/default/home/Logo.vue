<template>
  <div>
         <div class="holder holder-mt-medium">
            <div class="container">
               <ul class="brand-grid flex-wrap justify-content- js-color-hover-brand-grid">
                  <template v-if="companylogos.length > 0">
                         <li v-for="(companylogo,index) in companylogos">
                           <a :href="companylogo.url" target="_self" class="d-block image-container" title="Brand">
                           <img class="lazyload fade-up" :src="companylogo.imagedata[0]['imageurl']" :data-src="companylogo.imagedata[0]['imageurl']" @error="setAltImg">
                           </a>
                         </li>
                  </template>
                  <template v-else>
                  <li>
                     <a href="#" target="_self" class="d-block image-container" title="Brand">
                     <img class="lazyload fade-up" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/brands/brand-fashion-06.png" alt="Brand">
                     </a>
                  </li>
                  <li>
                     <a href="#" target="_self" class="d-block image-container" title="Brand">
                     <img class="lazyload fade-up" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/brands/brand-fashion-05.png" alt="Brand">
                     </a>
                  </li>
                  <li>
                     <a href="#" target="_self" class="d-block image-container" title="Brand">
                     <img class="lazyload fade-up" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/brands/brand-fashion-01.png" alt="Brand">
                     </a>
                  </li>
                  <li>
                     <a href="#" target="_self" class="d-block image-container" title="Brand">
                     <img class="lazyload fade-up" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/brands/brand-fashion-02.png" alt="Brand">
                     </a>
                  </li>
                  <li class="visuallyhidden js-hidden">
                     <a href="#" target="_self" class="d-block image-container" title="Brand">
                     <img class="lazyload fade-up" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/brands/brand-fashion-03.png" alt="Brand">
                     </a>
                  </li>
                  <li class="visuallyhidden js-hidden">
                     <a href="#" target="_self" class="d-block image-container" title="Brand">
                     <img class="lazyload fade-up" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/skins/fashion/brands/brand-fashion-04.png" alt="Brand">
                     </a>
                  </li>
                  </template>
               </ul>
               <div class="text-center mt-3 d-md-none">
                  <a href="#" class="btn btn--grey brands-show-more js-brands-show-more"><span> {{ lang.global.show }} {{ lang.global.homelogo.more }} </span><span> {{ lang.global.show }} {{ lang.global.homelogo.less }} </span></a>
               </div>
            </div>
         </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

  export default {
   props: ['data'],
    name: "accessoriesfashion",
    data() {
      return {
        companylogos: [],
        client_id: CLIENT_ID,
      }
    },
    computed: {
      
    },
     mounted(){
      this.companylogos = this.data.companylogos;
    },
    methods: {
     setAltImg(event){
      event.target.src = '/theme/default/images/skins/fashion/brands/brand-fashion-01.png';
     },
    },
    components: {
    }
  }
</script>