<template>
  <div>
    
    <div id="popupNewsletter" class="modal-info-content js-newslettermodal newslettermodal--classic p-0" style="display: none;" data-pause="12000" data-expires="0" data-only-index="false">
     <div class="row align-items-center">
        <div class="col-sm-8 d-none d-sm-block">
           <div class="popup-newsletter-image image-container" style="padding-bottom: 160.0%">
              <img class="lazyload w-100" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="/theme/default/images/popup-image.png" alt="">
           </div>
        </div>
        <div class="col-sm-10">
           <div class="popup-newsletter-content">
              <form method="post" class="newslettermodal-content-form">
                 <h3 class="newslettermodal-content-title"> {{ lang.global.modal.first_to_know }} </h3>
                 <div class="newslettermodal-content-text">About our newest arrivals, special offers plus 10% off on your first order.</div>
                 <div class="form-group mt-3">
                    <input type="hidden" name="contact[tags]" value="newsletter">
                    <div class="form-label"> {{ lang.global.modal.email_address }} </div>
                    <input type="email" name="contact[email]" class="form-control form-control--sm" value="" placeholder="Email address">
                 </div>
                 <button type="submit" class="btn w-100"> {{ lang.global.modal.subscribe }} </button>
                 <div class="popup-newsletter-info-sm mt-2"> {{ lang.global.modal.accept }} <a href="#" class="modal-info-link" data-src="#agreementInfo"> {{ lang.global.modal.terms_of_use }} </a></div>
              </form>
           </div>
        </div>
     </div>
     <div data-fancybox-close class="fancybox-close-small modal-close"><i class="icon-close"></i></div>
</div>

  </div>
</template>

<script>

import { mapState } from 'vuex'

  export default {
    props: ['hello'],
    data() {
      return {
        cruds: []
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