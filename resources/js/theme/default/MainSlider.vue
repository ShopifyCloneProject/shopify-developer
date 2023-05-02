<template>
  <div>
         <!-- Main Slider -->
         <div class="holder fullwidth full-nopad mt-0">
            <div class="container-fluid">
               <div class="bnslider-wrapper">
                  <div class="bnslider bnslider--lg keep-scale" id="bnslider-001" data-slick='{"arrows": true, "dots": true}' data-autoplay="false" data-speed="12000" data-start-width="1917" data-start-height="764" data-start-mwidth="1550" data-start-mheight="1000">
                     <template v-if="sliders.length > 0">
                        <template  v-for="(slider,index) in sliders">
                        <div class="bnslider-slide" v-if="slider.align==1">
                           <div class="bnslider-image-mobile lazyload" :data-bgset="slider.imagedata[0]['imageurl']"></div>
                           <div class="bnslider-image lazyload" :data-bgset="slider.imagedata[0]['imageurl']"></div>
                           <div class="bnslider-text-wrap bnslider-overlay ">
                              <div class="bnslider-text-content txt-middle txt-right txt-middle-m txt-center-m">
                                 <div class="bnslider-text-content-flex ">
                                    <div class="bnslider-vert w-s-60 w-ms-100" style="padding: 0px">
                                       <div class="bnslider-text order-1 mt-sm bnslider-text--md text-center data-ini" data-animation="fadeInUp" data-animation-delay="800" data-fontcolor="#282828" data-fontweight="700" data-fontline="1.5">{{ slider.text1 }}</div>
                                       <div class="bnslider-text order-2 mt-sm bnslider-text--xs text-center data-ini" data-animation="fadeInUp" data-animation-delay="1000" data-fontcolor="#7c7c7c" data-fontweight="400" data-fontline="1.5">{{ slider.text2 }}</div>
                                       <div class="btn-wrap text-center  order-4 mt-md" data-animation="fadeIn" data-animation-delay="2000" style="opacity: 1;" v-if="slider.url != '' && slider.url != null">
                                          <a :href="slider.url" target="_blank" class="btn">
                                          {{ lang.global.mainslider.shop_now }}
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        
                        <div class="bnslider-slide" v-else>
                           <div class="bnslider-image-mobile lazyload" :data-bgset="slider.imagedata[0]['imageurl']"></div>
                           <div class="bnslider-image lazyload" :data-bgset="slider.imagedata[0]['imageurl']"></div>
                           <div class="bnslider-text-wrap bnslider-overlay ">
                              <div class="bnslider-text-content txt-middle txt-left txt-middle-m txt-center-m">
                                 <div class="bnslider-text-content-flex ">
                                    <div class="bnslider-vert w-s-60 w-ms-100" style="padding: 0px">
                                       <div class="bnslider-text order-1 mt-0 bnslider-text--md text-center data-ini" data-animation="fadeInUp" data-animation-delay="800" data-fontcolor="#282828" data-fontweight="700" data-fontline="1.5">{{ slider.text1 }}</div>
                                       <div class="bnslider-text order-2 mt-sm bnslider-text--xs text-center data-ini" data-animation="fadeInUp" data-animation-delay="1000" data-fontcolor="#7c7c7c" data-fontweight="400" data-fontline="1.5">{{ slider.text2 }}</div>
                                       <div class="btn-wrap text-center  order-4 mt-md" data-animation="fadeIn" data-animation-delay="2000" style="opacity: 1;" v-if="slider.url != '' && slider.url != null">
                                          <a :href="slider.url" target="_blank" class="btn">
                                          {{ lang.global.mainslider.shop_now }}
                                          </a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </template>
                  </template>
                  <template v-else>
                     <div class="bnslider-slide">
                        <div class="bnslider-image-mobile lazyload" data-bgset="/theme/default/images/skins/fashion/slider/slide-fashion-02-m.png"></div>
                        <div class="bnslider-image lazyload" data-bgset="/theme/default/images/skins/fashion/slider/slide-fashion-02.png"></div>
                        <div class="bnslider-text-wrap bnslider-overlay ">
                           <div class="bnslider-text-content txt-middle txt-right txt-middle-m txt-center-m">
                              <div class="bnslider-text-content-flex ">
                                 <div class="bnslider-vert w-s-60 w-ms-100" style="padding: 0px">
                                    <div class="bnslider-text order-1 mt-sm bnslider-text--md text-center data-ini" data-animation="fadeInUp" data-animation-delay="800" data-fontcolor="#282828" data-fontweight="700" data-fontline="1.5"> {{ lang.global.mainslider.price_this_year }} </div>
                                    <div class="bnslider-text order-2 mt-sm bnslider-text--xs text-center data-ini" data-animation="fadeInUp" data-animation-delay="1000" data-fontcolor="#7c7c7c" data-fontweight="400" data-fontline="1.5"> {{ lang.global.trends.ecommerce_template }} </div>
                                    <div class="btn-wrap text-center  order-4 mt-md" data-animation="fadeIn" data-animation-delay="2000" style="opacity: 1;">
                                       <a href="https://bit.ly/3eJX5XE" target="_blank" class="btn">
                                       {{ lang.global.mainslider.shop_now }}
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="bnslider-slide">
                        <div class="bnslider-image-mobile lazyload" data-bgset="/theme/default/images/skins/fashion/slider/slide-fashion-01-m.png"></div>
                        <div class="bnslider-image lazyload" data-bgset="/theme/default/images/skins/fashion/slider/slide-fashion-01.png"></div>
                        <div class="bnslider-text-wrap bnslider-overlay ">
                           <div class="bnslider-text-content txt-middle txt-left txt-middle-m txt-center-m">
                              <div class="bnslider-text-content-flex ">
                                 <div class="bnslider-vert w-s-60 w-ms-100" style="padding: 0px">
                                    <div class="bnslider-text order-1 mt-0 bnslider-text--md text-center data-ini" data-animation="fadeInUp" data-animation-delay="800" data-fontcolor="#282828" data-fontweight="700" data-fontline="1.5"> {{ lang.global.mainslider.rated_theme }} </div>
                                    <div class="bnslider-text order-2 mt-sm bnslider-text--xs text-center data-ini" data-animation="fadeInUp" data-animation-delay="1000" data-fontcolor="#7c7c7c" data-fontweight="400" data-fontline="1.5"> {{ lang.global.trends.ecommerce_template }} </div>
                                    <div class="btn-wrap text-center  order-4 mt-md" data-animation="fadeIn" data-animation-delay="2000" style="opacity: 1;">
                                       <a href="https://bit.ly/3eJX5XE" target="_blank" class="btn">
                                       {{ lang.global.mainslider.shop_now }}
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </template>
                  </div>
                  <div class="bnslider-arrows container-fluid">
                     <div></div>
                  </div>
                  <div class="bnslider-dots container-fluid"></div>
               </div>
            </div>
         </div>
         <!-- //Main Slider -->


  </div>
</template>

<script>
import { mapState } from 'vuex'

  export default {
   props: ['sliders'],
   name: 'MainSlider',
    data() {
      return {
        cruds: [],
        client_id: CLIENT_ID,
      }
    },
    computed: {
      
    },
    methods: {
     
    },
    components: {
    }
  }
</script>