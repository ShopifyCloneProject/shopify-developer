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
                <input type="checkbox" class="custom-control-input" id="is_local_pickpup" value="1" v-model="formData.isLocalDelivery"/>
                <label class="custom-control-label" for="is_local_pickpup">{{ lang.cruds.shippingSettings.offers_local_pickup }}</label>
                </div>
            </div>
          </div>
          <div class="card-footer text-muted">
              <div>
                  <p>{{ lang.cruds.shippingSettings.offers_local_delivery }}. <button class="btn p-0 text-primary" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#changeCurrency"> {{ lang.cruds.shippingSettings.change_currency }}</button></p>

              </div>
          </div>
      </div>
      <!-- card end -->
      <!-- card start -->
      <div class="card" v-if="formData.isLocalDelivery">
          <div class="card-header">
            <h4 class="card-title">{{ lang.cruds.shippingSettings.delivery_area }}</h4>
          </div>
          <div class="card-body border-bottom">
             <div><p>{{ lang.cruds.shippingSettings.delivery_area_helper }}</p></div>
            <div class="form-group">
                <div class="custom-control custom-radio mb-1">
                  <input type="radio" id="use_pin_code" class="custom-control-input" value="1" v-model.number="formData.deliveryArea" />
                  <label class="custom-control-label" for="use_pin_code">{{ lang.cruds.shippingSettings.use_pin_code }}</label>
                  <div class="helper-text">
                      <small>{{ lang.cruds.shippingSettings.use_pin_code_helper }}</small>
                  </div>
                </div>
            </div>
            <div class="form-group">
                <div class="custom-control custom-radio mb-1">
                  <input type="radio" id="set_radius" class="custom-control-input" value="2" v-model.number="formData.deliveryArea" />
                  <label class="custom-control-label" for="set_radius">{{ lang.cruds.shippingSettings.set_radius }}</label>
                  <div class="helper-text">
                      <small>{{ lang.cruds.shippingSettings.set_radius_helper }}</small>
                  </div>
                </div>
                <div class="radius-content mt-1 ml-2" v-if="formData.deliveryArea == 2">
                   <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="incluide_neighboring" value="1" v-model="formData.isIncludeNeighboring"/>
                        <label class="custom-control-label" for="incluide_neighboring">{{ lang.cruds.shippingSettings.incluide_neighboring }}</label>
                        </div>
                    </div>
                    <p>{{ lang.cruds.shippingSettings.measure_radius_in }}</p>
                    <div class="custom-control custom-radio mb-1">
                      <input type="radio" id="km" class="custom-control-input" value="km" v-model.number="formData.radius" />
                      <label class="custom-control-label" for="km">km</label>
                    </div>
                     <div class="custom-control custom-radio mb-1">
                        <input type="radio" id="mi" class="custom-control-input" value="mi" v-model.number="formData.radius" />
                        <label class="custom-control-label" for="mi">mi</label>
                    </div>
                </div>
            </div>
          </div>
          <div class="card-body border-bottom mt-1" v-for="(zone, index) in formData.zones">
            <div class="d-flex">
              <h6 class="mb-1"><strong>{{ lang.cruds.shippingSettings.delivery_zone }}</strong></h6>
              <button class="btn p-0 ml-auto" @click="removeDeliveryBlock(index)" v-if="getZoneLength > 1"><i class="fa fa-trash"></i></button>
            </div>

            <div class="row">
              <div class="col-md-12">
                  <div class="form-group">
                      <label for="src_alt_text">{{  lang.cruds.shippingSettings.zone_name }}</label>
                      <input class="form-control" type="text" v-model="zone.zoneName">
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="form-group">
                    <label for="src_alt_text">{{  lang.cruds.shippingSettings.delivery_radius_up_to }}</label>
                    <div class="input-group input-group-merge">
                       <input class="form-control input-sm" type="number" v-model="zone.deliveryRadius">
                       <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon5">km</span>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="src_alt_text">{{  lang.cruds.shippingSettings.minimum_order_price }}</label>
                    <div class="input-group input-group-merge">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon5">R</span>
                      </div>
                      <input class="form-control input-sm" type="number" step="0.01" placeholder="0.00" v-model="zone.maxOrderPrice">
                    </div>
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                    <label for="src_alt_text">{{  lang.cruds.shippingSettings.delivery_price }}</label>
                    <div class="input-group input-group-merge">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon5">R</span>
                      </div>
                      <input class="form-control input-sm" type="number" step="0.01" placeholder="0.00" v-model="zone.deliveryPrice">
                      <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon5">{{  lang.cruds.shippingSettings.free }}</span>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="col-md-12">
                 <div class="form-group">
                    <button class="btn p-0 text-primary" @click="addAdditionalPricing(index)" v-if="!zone.additionalPricing">{{  lang.cruds.shippingSettings.add_conditional_pricing }}</button>
                    <button class="btn p-0 text-primary" @click="removeAdditionalPricing(index)" v-if="zone.additionalPricing">{{  lang.cruds.shippingSettings.remove_conditional_pricing }}</button>
                 </div>
              </div>
              <div class="col-md-12"  v-if="zone.additionalPricing">
                <div class="row" v-for="(additionalPrice, pindex) in zone.additionalPrice" :step="count = pindex + 1">
                  <div class="col-md-6">
                      <div class="form-group">
                        <label for="src_alt_text"> {{ lang.cruds.shippingSettings.order_from }} ₹0.00 {{  lang.cruds.shippingSettings.up_to }}</label>
                        <div class="input-group input-group-merge">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon5">R</span>
                          </div>
                          <input @keyup.enter="addAdditionalPricingMore(index, pindex)" step="0.01" placeholder="0.00" class="form-control input-sm" type="number" v-model="additionalPrice.offerPrice">
                        </div>
                      </div>
                  </div>

                  <div :class="zone.additionalPrice.length > count ? 'col-md-5' : 'col-md-6'">
                      <div class="form-group">
                        <label for="src_alt_text">{{  lang.cruds.shippingSettings.delivery_price }}</label>
                        <div class="input-group input-group-merge">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon5">R</span>
                          </div>
                          <input class="form-control input-sm" step="0.01" placeholder="0.00" type="number" v-model="additionalPrice.deliveryPrice">
                          <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon5">{{  lang.cruds.shippingSettings.free }}</span>
                          </div>
                        </div>
                      </div>
                  </div>

                  <div class="col-md-1 text-right" v-if="zone.additionalPrice.length > count">
                    <div class="form-group">
                      <button class="btn btn-outline-danger text-nowrap px-1 mt-2" type="button" @click="removeBlock(index, pindex)">
                        <i class="fa fa-trash"></i>
                      </button>
                    </div>
                  </div>

                </div>
              </div>
              <div class="col-md-12">
                 <div class="form-group">
                      <label for="title">{{ lang.cruds.shippingSettings.delivery_information }}</label>
                      <textarea class="form-control" rows="5" v-model="zone.deliveryInformation"></textarea>
                      <small class="textarea-counter-value float-right"><span class="char-count">{{ zone.deliveryInformation.length }}</span> / 255 </small>
                      <div class="small">{{ lang.cruds.shippingSettings.delivery_information_helper }}</div> 
                  </div>
              </div>
            </div>
          </div>
          <div class="card-footer text-muted">
              <div class="form-group">
                  <button class="btn p-0 text-primary" @click="addDeliveryZone()" v-if="getZoneLength < 5"><i class="fa fa-plus"></i> {{  lang.cruds.shippingSettings.create_new_delivery_zone }}</button>
                  <button class="btn p-0" v-if="getZoneLength >= 5"><i class="fa fa-plus"></i> {{  lang.cruds.shippingSettings.create_new_delivery_zone }}</button>
              </div>
          </div>
      </div>
      <!-- card end -->
      <!-- Change currency Modals start -->
      <div class="modal fade" id="changeCurrency" tabindex="-1" role="dialog" aria-labelledby="changeCurrencyTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="changeCurrencyTitle">{{ lang.cruds.shippingSettings.change_currency }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <p class="ml-1">{{ lang.cruds.shippingSettings.change_currency_helper }}</P>
                  <div class="row mt-2 mb-1">
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                  <input type="radio" id="use_store_default" class="custom-control-input" value="default" v-model="currencyType" />
                                  <label class="custom-control-label" for="use_store_default">{{ lang.cruds.shippingSettings.use_store_default }}</label>
                                  <div class="helper-text">
                                      <small>{{ lang.cruds.shippingSettings.use_store_default_helper }}</small>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                  <input type="radio" id="choose_from_list" class="custom-control-input" value="custom" v-model="currencyType" />
                                  <label class="custom-control-label" for="choose_from_list">{{ lang.cruds.shippingSettings.choose_from_list }}</label>
                                  <select class="custom-select" v-model="currency" :disabled="disabled">
                                    <option :value="currency.id" v-for="(currency, index) in currencies">{{ currency.name }} ({{ currency.currency }})</option>
                                  </select>
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
        <!-- Change currency Modals End -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'localDelivery',
    data() {
      return {
        address:{},
        formData:{
          isLocalDelivery:false,
          deliveryArea: 1,
          radius: 'km',
          zones:[
            {
              zoneName: this.lang.cruds.shippingSettings.local_delivery,
              deliveryRadius:10,
              maxOrderPrice:'',
              deliveryPrice:'',
              deliveryInformation:'',
              additionalPricing:false,
              additionalPrice:[]
            }
          ]
        },
        currency:'45',
        currencyType:'default',
        currencies:[]
      }
    },
    mounted(){
    },
    components: {
    },
    computed: {
      getZoneLength(){
          return this.formData.zones.length;
      },
      disabled(){
          return this.currencyType == 'default' ? true : false;
      }
    },
    created() {
       this.address = this.data.address;
       this.currencies = this.data.currencies;
    },
    methods: {
      addDeliveryZone(){
        this.formData.zones.push(
          {
            zoneName: this.lang.cruds.shippingSettings.local_delivery,
            deliveryRadius:10,
            maxOrderPrice:'',
            deliveryPrice:'',
            deliveryInformation:'',
            additionalPricing:false,
            additionalPrice:[]
          }
        );
      },
      removeDeliveryBlock(index){
        this.formData.zones.splice(index, 1);
      },
      addAdditionalPricing(index){
          this.formData.zones[index].additionalPricing = true;
          this.formData.zones[index].additionalPrice.push({ offerPrice:'', deliveryPrice: ''});
      },
      removeAdditionalPricing(index){
          this.formData.zones[index].additionalPricing = false;
          this.formData.zones[index].additionalPrice = [];
      },
      addAdditionalPricingMore(index, pindex){
        let additionalPrice = this.formData.zones[index].additionalPrice;
        let offerPrice = additionalPrice[pindex].offerPrice;
        if(additionalPrice.length < 3){
            if(offerPrice != '' && offerPrice > 0){
             this.formData.zones[index].additionalPrice.push({ offerPrice:'', deliveryPrice: ''});
            }
        }
      },
      removeBlock(index, pindex){
        this.formData.zones[index].additionalPrice.splice(pindex, 1);
      }
    }
  }
</script>

<style scoped>
svg.feather.feather-map-pin {
    height: 2em;
    width: 2em;
}
</style>