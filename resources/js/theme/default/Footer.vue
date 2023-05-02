<template>
   <div>
      <footer class="page-footer footer-style-6 ">
         <div class="holder">
            <div class="footer-shop-info">
               <div class="container">
                  <div class="text-icn-blocks-bg-row">
                     <div class="text-icn-block-footer">
                        <div class="icn">
                           <i class="icon-trolley"></i>
                        </div>
                        <div class="text">
                           <h4>{{ lang.global.footer.extra_fast_delivery }}</h4>
                           <p>{{ lang.global.footer.extra_fast_delivery_helper }}</p>
                        </div>
                     </div>
                     <div class="text-icn-block-footer">
                        <div class="icn">
                           <i class="icon-currency"></i>
                        </div>
                        <div class="text">
                           <h4>{{ lang.global.footer.best_price }}</h4>
                           <p>{{ lang.global.footer.best_price_helper }}</p>
                        </div>
                     </div>
                     <div class="text-icn-block-footer">
                        <div class="icn">
                           <i class="icon-diplom"></i>
                        </div>
                        <div class="text">
                           <h4>{{ lang.global.footer.guarantee }}</h4>
                           <p>{{ lang.global.footer.guarantee_helper }}</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="footer-top">
            <div class="container">
               <div class="row mt-0">
                  <div class="col-lg col-xl last-mobile">
                     <div class="footer-block">
                        <div class="footer-logo">
                           <a href="/" class="logo"><img :src="logo"  @error="setAltLogo" /></a>
                        </div>
                        <div class="collapsed-content">
                           <ul >
                              <li  v-if="objThemeSettings">E-mail: <a href="mailto:objThemeSettings.email">{{objThemeSettings.email}}</a></li>
                              <li v-if="objTiming">Hours : {{startTime}} - {{endTime}} {{startDay}} - {{endDay}}   </li>
                           </ul>
                        </div>
                        <ul class="social-list" v-if="objThemeSettings">
                           <li  v-if="objThemeSettings.facebook != null">
                              <a :href="objThemeSettings.facebook" target="_blank" class="icon icon-facebook"></a>
                           </li>
                           <li  v-if="objThemeSettings.twitter != null">
                              <a :href="objThemeSettings.twitter" target="_blank" class="icon icon-twitter"></a>
                           </li>
                           <li  v-if="objThemeSettings.gplus != null">
                              <a :href="objThemeSettings.gplus" target="_blank" class="icon icon-google"></a>
                           </li>
                           <li  v-if="objThemeSettings.vimeo != null">
                              <a :href="objThemeSettings.vimeo" target="_blank" class="icon icon-vimeo"></a>
                           </li>
                           <li  v-if="objThemeSettings.youtube != null">
                              <a :href="objThemeSettings.youtube" target="_blank" class="icon icon-youtube"></a>
                           </li>
                           <li  v-if="objThemeSettings.pinterest != null">
                              <a :href="objThemeSettings.pinterest" target="_blank" class="icon icon-pinterest"></a>
                           </li>
                        </ul>
                        <div class="d-lg-none mt-3">
                           <div class="box-coupon">
                              <div class="box-coupon-icon"><i class="icon-scissors"></i></div>
                              <div class="box-coupon-text"><span class="custom-color">FOXIC</span> THEME</div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg col-xl">
                     <div class="footer-block collapsed-mobile">
                        <div class="title">
                           <h4>{{ lang.global.footer.information }}</h4>
                           <span class="toggle-arrow"><span></span><span></span></span>
                        </div>
                        <div class="collapsed-content">
                           <ul>
                              <li><a href="/privacy">{{ lang.global.footer.privacy_policy }}</a></li>
                              <li><a href="/termsandconditions">{{ lang.global.footer.terms_conditions }}</a></li>
                              <li><a href="/refund">{{ lang.global.footer.returns_exchanges }}</a></li>
                              <li><a href="/shippingpolicy">{{ lang.global.footer.shipping_delivery }}</a></li>
                              <!--  <li><a href="contact.html">{{ lang.global.footer.contact_us }}</a></li> -->
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg col-xl">
                     <div class="footer-block collapsed-mobile">
                        <div class="title">
                           <h4>{{ lang.global.footer.account_details }}</h4>
                           <span class="toggle-arrow"><span></span><span></span></span>
                        </div>
                        <div class="collapsed-content">
                           <ul>
                              <li><a href="/account">{{ lang.global.footer.my_account }}</a></li>
                              <li><a href="/cart">{{ lang.global.footer.view_cart }}</a></li>
                              <!-- <li><a href="account-history.html">{{ lang.global.footer.trackl_my_order }}</a></li> -->
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg col-xl">
                     <div class="footer-block collapsed-mobile">
                        <div class="title">
                           <h4>{{ lang.global.footer.safe_payments }}</h4>
                           <span class="toggle-arrow"><span></span><span></span></span>
                        </div>
                        <div class="collapsed-content">
                           <ul class="payment-link">
                              <li><i class="icon-google-pay-logo"></i></li>
                              <li><i class="icon-visa-pay-logo"></i></li>
                              <li><i class="icon-apple-pay-logo"></i></li>
                           </ul>
                        </div>
                        <!-- <div class="d-none d-lg-block">
                           <div class="box-coupon">
                              <div class="box-coupon-icon"><i class="icon-scissors"></i></div>
                              <div class="box-coupon-text"><span class="custom-color">FOXIC</span> THEME</div>
                           </div>
                        </div> -->
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="footer-bottom footer-bottom--bg">
            <div class="container">
               <div class="footer-copyright text-center">
                  <a href="#">{{sitename}}</a> ©{{currentyear}} copyright
               </div>
            </div>
         </div>
      </footer>
   </div>
</template>
<script>
   import { mapState } from 'vuex'
   export default {
      props: [],
      name: 'Footer',
      data() {
         return {
            cruds: [],
            days:[
            'Sun',
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat',
            ],
            logo: '',
            sitename: location.hostname,
            currentyear: new Date().getFullYear(),
            objThemeSettings:JSON.parse(objThemeSettings),
            objTiming:JSON.parse(objTiming),
            startDay:'',
            endDay :'',
            startTime :'',
            endTime :'',
         }
      },
      computed: {
         ...mapState(['globalStore']),
         noImage(){
            return this.globalStore.no_image;
         }
      },
      mounted(){
         this.setHours();
         this.logo = JSON.parse(globalsettings).strLogo;
      },
      methods: {
         setHours(){
            this.startDay = this.days[0];
            this.endDay =this.days[0];
            this.startTime =this.objTiming[0].open.slice(0,-3);
            this.endTime =this.objTiming[0].close.slice(0,-3);
            let startCounter = 0;
            for(let i=0;i<=6;i++){
               if(this.objTiming[i].open != '00:00:00' && startCounter == 0){
                  this.startDay = this.days[i];
                  this.startTime = this.objTiming[i].open.slice(0,-3);
                  startCounter = 1;
                  continue;
               }
               else if(this.objTiming[i].open == '00:00:00'){
                  continue;
               }
               else{
                  this.endDay = this.days[i];
                  this.endTime = this.objTiming[i].close.slice(0,-3);
               }
            }
         },
         setAltLogo(event)
         {
            event.target.src  = '/theme/default/images/skins/fashion/logo.png';
         },
      },
      components: {
      }
   }
</script>
