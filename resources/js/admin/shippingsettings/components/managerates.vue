<template>
  <div id="input-sizing">
      <!-- card start -->
      <div class="card">
          <div class="card-header">
            <h4 class="card-title">{{ lang.cruds.shippingSettings.location_status }}</h4>
          </div>
          <div class="card-body">
            
          </div>
          <div class="card-footer text-muted">
             
          </div>
      </div>
      <!-- card end -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'manageRates',
    data() {
      return {
        addresses:{},
      }
    },
    mounted(){
    },
    components: {
    },
    computed: {
     
    },
    created() {
       this.addresses = this.data.addresses;
    },
    methods: {
     
    }
  }
</script>

<style scoped>
svg.feather.feather-map-pin {
    height: 2em;
    width: 2em;
}
</style>