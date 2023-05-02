<template>
  <div id="input-sizing">
      <form post="POST" id="frmAddRates">
           <div class="row">
                <div class="col-md-8 col-12">
                    <div class="card"> 
                        <div class="card-header border-bottom" v-if="data.shipping_rates.length == 0">
                            <h4 class="card-title">{{ lang.cruds.shippingSettings.add_rate }}</h4>
                        </div>
                        <div class="card-header border-bottom" v-else>
                            <h4 class="card-title">{{ lang.cruds.shippingSettings.edit_rate }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row mt-2">
                                <div class="col-md-12 col-12" v-if="data.length == 0">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="owe_rates" name="rate_status" value="1" v-model="formData.rate_status"/>
                                            <label class="custom-control-label" for="owe_rates">{{ lang.cruds.shippingSettings.rate_option_1 }}</label> 
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="carrier_app_rates" name="rate_status" value="0" v-model="formData.rate_status" />
                                            <label class="custom-control-label" for="carrier_app_rates">{{ lang.cruds.shippingSettings.rate_option_2 }}</label> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12 mb-1">
                                    <div class="form-group rate_name">
                                        <label class="required" for="rate_name">{{ lang.cruds.shippingSettings.rate_name }}</label>
                                        <input class="form-control" type="text" placeholder="Enter rate name" v-model="formData.name">
                                        <span>{{ lang.cruds.shippingSettings.rate_name_helper }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12 mb-1">
                                    <div class="form-group">
                                        <label class="required" for="rate_price">{{ lang.cruds.shippingSettings.price }}</label>
                                        <div class="input-group input-group-merge mb-2">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text">{{globalsettings.CURRECNY_SYMBOL}}</span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="0.00" v-model="formData.price"/>
                                            <div class="input-group-append" v-if="formData.price <= 0">
                                              <span class="input-group-text" >Free</span>
                                            </div>
                                        </div>
                                        <div v-if="formData.conditions == 1">
                                            <a href="javascript:void(0)" @click="removeConditions()">{{ lang.cruds.shippingSettings.remove_conditions }}</a>
                                        </div>
                                        <div v-else>
                                            <a href="javascript:void(0)" @click="addConditions()" >{{ lang.cruds.shippingSettings.add_conditions }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-if="formData.conditions == 1">
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="base_weight" name="rate_base" value="0" v-model="formData.weight_or_price"/>
                                            <label class="custom-control-label" for="base_weight">{{ lang.cruds.shippingSettings.based_on_weight }}</label> 
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="base_price" name="rate_base" value="1" v-model="formData.weight_or_price"/>
                                            <label class="custom-control-label" for="base_price">{{ lang.cruds.shippingSettings.based_on_price }}</label> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12" v-if="formData.weight_or_price == 0">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="required">{{ lang.cruds.shippingSettings.minimum_weight }}</label>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input
                                                      type="text"
                                                      class="form-control"
                                                      placeholder="0"
                                                      v-model="formData.min"
                                                    />
                                                    <div class="input-group-append">
                                                      <span class="input-group-text" id="basic-addon6">Kg</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="required">{{ lang.cruds.shippingSettings.maximum_weight }}</label>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input
                                                      type="text"
                                                      class="form-control"
                                                      placeholder="No limit"
                                                      v-model="formData.max"
                                                    />
                                                    <div class="input-group-append">
                                                      <span class="input-group-text" id="basic-addon6">Kg</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12" v-if="formData.weight_or_price == 1">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="required">{{ lang.cruds.shippingSettings.minimum_price }}</label>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input
                                                      type="text"
                                                      class="form-control"
                                                      placeholder="0"
                                                      v-model="formData.min"
                                                    />
                                                    <div class="input-group-append">
                                                      <span class="input-group-text" id="basic-addon6">{{globalsettings.CURRECNY_SYMBOL}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label class="required">{{ lang.cruds.shippingSettings.maximum_price }}</label>
                                                <div class="input-group input-group-merge mb-2">
                                                    <input
                                                      type="text"
                                                      class="form-control"
                                                      placeholder="No limit"
                                                      v-model="formData.max"
                                                    />
                                                    <div class="input-group-append">
                                                      <span class="input-group-text" id="basic-addon6">{{globalsettings.CURRECNY_SYMBOL}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                               
                        </div>
                      
                    </div>
                    <div class="form-group float-left">
                        <button class="btn btn-primary waves-effect waves-light" type="button" @click="saveRates()">
                            {{ lang.global.save }}
                        </button>
                        <button class="btn btn-danger waves-effect waves-light" type="button" @click="cancel()" >
                            {{ lang.global.cancel }}
                        </button>
                    </div>
                </div>
                    
            </div>
        </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data' , 'globalsettings'],
    name:'addeditrates',
    data() {
      return {
        formData: {
            rate_status:1,
            name:'',
            price:'',
            conditions:0,
            weight_or_price:0,
            min:'',
            max:''
        }
      }
    },
    mounted(){
        document.title = this.lang.cruds.shippingSettings.add_rates;
        if(Object.keys(this.data.shipping_rates).length > 0){
            this.formData = this.data.shipping_rates;
            document.title = this.lang.cruds.shippingSettings.edit_rates;
        }
    },
    components: {
     
    },
    computed: {
        
    },
    created() {
        
    },
    methods: {
        removeConditions(){
            this.formData.conditions = 0;
        },
        addConditions(){
            this.formData.conditions = 1;
        },
        saveRates(){
            openLoader();
            this.$store.dispatch("shippingSettingsModule/SaveRates",this.formData)
              .then((res) => {
                  if (res.response.status_code == 3114) {
                      successModal(res.response.message);
                      window.location = res.response.data.url;
                  }
                  else if (res.response.status_code == 3115) {
                      successModal(res.response.message);
                      setTimeout(function(){
                            window.location = res.response.data.url;
                     },2000);
                  }
                  else{
                    errorModal(res.response.message);
                  }
                  closeLoader();
              })
              .catch((err) => {
                 closeLoader();
                 errorModal(err.response.message);
            });
          },
        
    }
  }
</script>

<style scoped>
.store-content p {
    font-size: 13px;
    text-align: justify;
}
.store-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}
svg.feather {
    height: 2em;
    width: 2em;
}
.custom-radio{
    margin-bottom: 10px;
}
.rate_name input{
    margin-bottom: 10px;
}
</style>