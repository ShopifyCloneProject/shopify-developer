<template>
  <div>
    
   <div class="holder breadcrumbs-wrap mt-0">
    <div class="container">
      <ul class="breadcrumbs">
        <li><a href="index.html"> {{ lang.global.home }} </a></li>
        <li><span>{{ setPageName(data.pageData.pages) }}</span></li>
      </ul>
    </div>
  </div>
  <div class="holder mt-2 mb-4 mb-lg-10">
    <div class="container">
       <h1 class="text-center">{{ setPageName(data.pageData.pages) }}</h1>
       <p v-html="data.pageData.data" v-if="data.pageData.data != ''"></p>
       <p v-else > {{ lang.global.static.there_no_any }} {{ setPageName(data.pageData.pages) }}</p>
    </div>
  </div>

    <!--  sticky add to cart -->

  </div>
</template>

<script>
import { mapState } from 'vuex'

  export default {
    name: 'static',
    props: ['data'],
    data() {
      return {
        cruds: []
      }
    },
    computed: {
      
    },
    mounted(){
    },
    methods: {
     setPageName(pagestatus)
     {
            if(pagestatus == 'refund')
            {
                return window.i18n.global.refund;
            }
            else if (pagestatus == 'privacy') {
                return window.i18n.global.privacy_policy;
            }
            else if (pagestatus == 'terms') {
                return window.i18n.global.termsandconditions;
            }
            else if (pagestatus == 'shippingpolicy') {
                return window.i18n.global.shippingpolicy;
            }
     }
    },
    components: {
    }
  }
</script>