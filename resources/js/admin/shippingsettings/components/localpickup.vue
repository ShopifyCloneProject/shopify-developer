<template>
  <div id="input-sizing">
      <!-- card start -->
      <div class="card">
          <div class="card-header">
            <h4 class="card-title">{{ lang.cruds.shippingSettings.location_status }}</h4>
          </div>
          <div class="card-body">
            <div class="address-deails">
                <div class="d-flex align-items-start mb-1">
                    <div class="mt-25 mr-75">
                        <i data-feather='map-pin' width="24" height="24"></i>
                    </div>
                    <div>
                        <div v-if="address.locationName" class="mb-25 font-weight-bold">{{address.locationName}}</div>
                        <div class="mb-25" style="margin-bottom: 5px;">
                          <span v-if="address.address1">{{address.address1 + ' ,'}}</span>
                          <span v-if="address.address2">{{address.address2 + ' ,'}}</span>
                          <span v-if="address.city">{{address.city + ' ,'}}</span>
                          <span v-if="address.stateName">{{address.stateName + ' ,'}}</span>
                          <span v-if="address.shortCode">{{address.shortCode + ' ,'}}</span>
                          <span v-if="address.pincode">{{address.pincode}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="is_local_pickpup" value="1" v-model="formData.isLocalpickpup"/>
                <label class="custom-control-label" for="is_local_pickpup">{{ lang.cruds.shippingSettings.offers_local_pickup }}</label>
                </div>
            </div>
          </div>
      </div>
      <!-- card end -->
      <!-- card start -->
      <div class="card" v-if="formData.isLocalpickpup">
          <div class="card-header">
            <h4 class="card-title">{{ lang.cruds.shippingSettings.information_at_checkout }}</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
                <label for="title">{{ lang.cruds.shippingSettings.expected_pickup_time }}</label>
                <select class="custom-select" v-model="formData.expectedPickupTime">
                  <option value="oneHour">{{ lang.cruds.shippingSettings.usually_ready_in }} 1 {{ lang.cruds.shippingSettings.hours }}</option>
                  <option value="twoHours">{{ lang.cruds.shippingSettings.usually_ready_in }} 2 {{ lang.cruds.shippingSettings.hours }}</option>
                  <option value="fourHours">{{ lang.cruds.shippingSettings.usually_ready_in }} 3 {{ lang.cruds.shippingSettings.hours }}</option>
                  <option value="twentyFourHours">{{ lang.cruds.shippingSettings.usually_ready_in }} 24 {{ lang.cruds.shippingSettings.hours }}</option>
                  <option value="twoToFourDays">{{ lang.cruds.shippingSettings.usually_ready_in }} 2-4  {{ lang.cruds.shippingSettings.days }}</option>
                  <option value="fiveOrMoreDays">{{ lang.cruds.shippingSettings.usually_ready_in }} 5+  {{ lang.cruds.shippingSettings.days }}</option>
                </select>
            </div>
            <div class="address-deails mt-1">
                <h6 class="font-weight-bold text-uppercase">{{ lang.cruds.shippingSettings.checkout_preview }}</h6>
                <div class="d-flex align-items-start border rounded p-1">
                    <div class="d-flex align-items-start">
                        <div class="mt-25 mr-75">
                            <i data-feather='map-pin' width="24" height="24"></i>
                        </div>
                        <div>
                            <div v-if="address.locationName" class="mb-25 font-weight-bold">{{address.locationName}}</div>
                            <div class="mb-25" style="margin-bottom: 5px;">
                              <span v-if="address.address1">{{address.address1 + ' ,'}}</span>
                              <span v-if="address.address2">{{address.address2 + ' ,'}}</span>
                              <span v-if="address.city">{{address.city + ' ,'}}</span>
                              <span v-if="address.stateName">{{address.stateName + ' ,'}}</span>
                              <span v-if="address.shortCode">{{address.shortCode + ' ,'}}</span>
                              <span v-if="address.pincode">{{address.pincode}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="ml-auto text-right">
                        <div class="font-weight-bold">{{ lang.cruds.shippingSettings.free }}</div>
                        <div class="small" v-if="formData.expectedPickupTime == 'oneHour'">{{ lang.cruds.shippingSettings.usually_ready_in }} 1 {{ lang.cruds.shippingSettings.hours }}</div>
                        <div class="small" v-if="formData.expectedPickupTime == 'twoHours'">{{ lang.cruds.shippingSettings.usually_ready_in }} 2 {{ lang.cruds.shippingSettings.hours }}</div>
                        <div class="small" v-if="formData.expectedPickupTime == 'fourHours'">{{ lang.cruds.shippingSettings.usually_ready_in }} 3 {{ lang.cruds.shippingSettings.hours }}</div>
                        <div class="small" v-if="formData.expectedPickupTime == 'twentyFourHours'">{{ lang.cruds.shippingSettings.usually_ready_in }} 24 {{ lang.cruds.shippingSettings.hours }}</div>
                        <div class="small" v-if="formData.expectedPickupTime == 'twoToFourDays'">{{ lang.cruds.shippingSettings.usually_ready_in }} 2-4  {{ lang.cruds.shippingSettings.days }}</div>
                        <div class="small" v-if="formData.expectedPickupTime == 'fiveOrMoreDays'">{{ lang.cruds.shippingSettings.usually_ready_in }} 5+  {{ lang.cruds.shippingSettings.days }}</div>
                          </div>
                </div>
                <div class="small">{{ lang.cruds.shippingSettings.checkout_preview_helper }}</div>
            </div>
          </div>
      </div>
      <!-- card end -->
       <!-- card start -->
      <div class="card" v-if="formData.isLocalpickpup">
          <div class="card-header">
            <h4 class="card-title">{{ lang.cruds.shippingSettings.order_ready }}</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
                <label for="title">{{ lang.cruds.shippingSettings.pickup_instructions }}</label>
                <textarea class="form-control" :placeholder="lang.cruds.shippingSettings.order_ready_placeholder" rows="5" v-model="formData.pickupInstructions"></textarea>
                 <small class="textarea-counter-value float-right"><span class="char-count">{{ formData.pickupInstructions.length }}</span> / 255 </small>
                <div class="small">{{ lang.cruds.shippingSettings.order_ready_helper }}</div> 
            </div>
          </div>
      </div>
      <!-- card end -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'localPickup',
    data() {
      return {
        address:{},
        formData:{
          isLocalpickpup:false,
          expectedPickupTime:'twentyFourHours',
          pickupInstructions:''
        }
      }
    },
    mounted(){
    },
    components: {
    },
    computed: {
    },
    created() {
       this.address = this.data.address;
    },
    methods: {
    }
  }
</script>

<style scoped>
svg.feather {
    height: 2em;
    width: 2em;
}
</style>