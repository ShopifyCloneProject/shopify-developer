<template>
  <div id="input-sizing">
      <!-- card start -->
      <div class="card">
          <div class="card-header">
            <h4 class="card-title">{{ lang.cruds.shippingSettings.name }}</h4>
          </div>
          <div class="card-body">
              <div class="form-group">
                  <input class="form-control" type="text">
                  <div class="helper-text small">
                       {{ lang.cruds.shippingSettings.rate_name_helper }}
                  </div>
              </div>
          </div>
      </div>
      <!-- card end -->
      <!-- card start -->
      <div class="card">
          <div class="card-header d-flex">
              <h4 class="font-weight-bold">
                   {{ lang.cruds.shippingSettings.products }}
              </h4>
              <div class="ml-auto"><button class="btn p-0 text-primary" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#products">{{ lang.global.add }} {{ lang.cruds.shippingSettings.products }}</button></div>
          </div>
          <div class="card-body">
              <div class="text-center p-2" v-if="zoneProducts.length <= 0">
                    <div>{{ lang.cruds.shippingSettings.no_products }}</div>
                    <p>{{ lang.cruds.shippingSettings.edit_profile_items_helper }}</p>
              </div>
          </div>
      </div>
      <!-- card end -->
      <!-- card start -->
      <div class="card">
          <div class="card-header d-flex">
              <h4 class="font-weight-bold">
                   {{ lang.cruds.shippingSettings.shipping_from }}
              </h4>
              <div class="ml-auto"><button class="btn p-0 text-primary">{{ lang.cruds.shippingSettings.show_details }}</button></div>
          </div>
          <div class="card-body border-bottom">
              <div class="address-deails d-flex" v-for="(address, index) in addresses">
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
                <div class="ml-auto">
                    <button class="btn p-0 text-primary" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#manageRate" @click="manageRateForLocation(index)">{{ lang.cruds.shippingSettings.manage }}</button>
                </div>
            </div>
          </div>
          <div class="card-header d-flex mt-1">
              <h4 class="font-weight-bold">
                   {{ lang.cruds.shippingSettings.shipping_to }}
              </h4>
              <div class="ml-auto"><button class="btn p-0 text-primary" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#createZone">{{ lang.cruds.shippingSettings.create_shipping_zone }}</button></div>
          </div>
          <div class="card-body border-bottom">
              <div class="text-center p-2" v-if="zones.length <= 0">
                    <div>{{ lang.cruds.shippingSettings.no_zones }}</div>
                    <p>{{ lang.cruds.shippingSettings.no_zones_helper }}</p>
              </div>
          </div>
      </div>
      <!-- card end -->

      <!-- modal start -->
      <!-- manage rate Modals start -->
      <div class="modal fade" id="manageRate" tabindex="-1" role="dialog" aria-labelledby="manageRateTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="manageRateTitle">{{ lang.cruds.shippingSettings.namage }} {{ currentLocationName }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <div class="row mt-2 mb-1">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label>{{ lang.global.create }} New Rates</label>
                              <div class="custom-control custom-radio mb-1">
                                  <input type="radio" id="create_rates" class="custom-control-input" value="add" v-model="manageRates" />
                                  <label class="custom-control-label" for="create_rates">New Rates for {{ currentLocationName }}</label>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <label>Remove Rates</label>
                              <div class="custom-control custom-radio mb-1">
                                  <input type="radio" id="remove_rates" class="custom-control-input" value="remove" v-model="manageRates" />
                                  <label class="custom-control-label" for="remove_rates">Remove Rates for {{ currentLocationName }}</label>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary">{{ lang.global.save }}</button>
              </div>
            </div>
          </div>
      </div>
      <!-- manage rate Modals End -->

      <!--createZone Modals start -->
      <div class="modal fade" id="createZone" tabindex="-1" role="dialog" aria-labelledby="createZoneTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="createZoneTitle">{{  lang.global.create }} Zone</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <div class="row mt-2 mb-1">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label for="src_alt_text">{{  lang.cruds.shippingSettings.zone_name }}</label>
                              <input class="form-control" type="text" v-model="zoneName">
                              <div class="helper-text">
                                  <small>{{ lang.cruds.shippingSettings.customer_not_See }}</small>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <div>
                                  <DxTreeView
                                    id="countrytreeview"
                                    :items="countries"
                                    :search-enabled="true"
                                    :search-mode="searchMode"
                                     show-check-boxes-mode="normal"
                                  />
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary">{{ lang.global.save }}</button>
              </div>
            </div>
          </div>
      </div>
      <!-- createZone Modals End -->

      <!--products Modals start -->
      <div class="modal fade" id="products" tabindex="-1" role="dialog" aria-labelledby="productsTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="productsTitle">{{ lang.cruds.shippingSettings.edit_profile_items }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <div class="row mt-2 mb-1">
                      <div class="col-md-12">
                          <div class="form-group">
                              <div>
                                  <DxTreeView
                                    id="producttreeview"
                                    :items="products"
                                    :search-enabled="true"
                                    :search-mode="searchMode"
                                    :data-source="products"
                                    show-check-boxes-mode="normal"
                                    v-model:selected-row-keys="zoneProducts"
                                    key-expr="id"
                                    @selection-changed="onSelectionChanged"
                                  />
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary">{{ lang.global.save }}</button>
              </div>
            </div>
          </div>
      </div>
      <!-- products Modals End -->

  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { DxTreeView, DxSelection } from 'devextreme-vue/tree-view';
import DxSelectBox from 'devextreme-vue/select-box';
import 'devextreme/dist/css/dx.greenmist.css';

export default {
    props: ['data'],
    name:'createRates',
    data() {
      return {
        addresses:[],
        products:[],
        zones:[],
        manageRates:'add',
        currentLocationName:'',
        searchMode: 'contains',
        zoneName:'',
        countries:[],
        zoneProducts:[],
      }
    },
    mounted(){
    },
    components: {
      DxSelectBox,
      DxTreeView
    },
    computed: {
     
    },
    created() {
       this.addresses = this.data.addresses;
       this.countries = this.data.countries;
       this.products = this.data.products;
    },
    methods: {
        manageRateForLocation(index){
          this.currentLocationName = this.addresses[index].locationName;
        },
        onSelectionChanged({ component }){
          console.log( component )
        }
    }
  }
</script>

<style scoped>
svg.feather.feather-map-pin {
    height: 2em;
    width: 2em;
}
#treeview {
  height: 400px;
}

.options {
  padding: 20px;
  position: absolute;
  bottom: 0;
  right: 0;
  width: 260px;
  top: 0;
  background-color: #f5f5f5;
}

.caption {
  font-size: 18px;
  font-weight: 500;
}

.option {
  margin-top: 10px;
}

.option > .dx-selectbox {
  display: inline-block;
  vertical-align: middle;
  max-width: 350px;
  width: 100%;
  margin-top: 5px;
}
.dx-treeview-search {
    margin-bottom: 15px;
}
</style>