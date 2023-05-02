<template>
  <div id="input-sizing">
      <form method="POST" id="frmAddEditshippingdetails" @submit.prevent="submit()">
            <div class="col-12 mb-2">
              <a class="back-url" @click="backtolist()">
                  <i data-feather='arrow-left-circle'></i> {{ lang.cruds.shippingdetails.shipment_details }}
              </a>
            </div>
        <div v-for="(shipping , index) in data.shippingDetail">
            <div class="row"  v-if="shipping.name == 'ShipRocket'">
                <div class="col-md-4 col-12">
                    <div class="shipping-content">
                        <h6 class="store-title">{{ lang.cruds.shippingdetails.fields.shiprocket }}</h6>
                        <p>{{ lang.cruds.shippingdetails.fields.shiprocket_helper }}</p>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                      <!-- Basic details start -->
                      <div class="card">
                          <div class="card-body">
                             <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="required" for="email">{{ lang.cruds.shippingdetails.fields.email }}</label>
                                        <input class="form-control" type="text" v-model="shipping.email">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="required" for="password">{{ lang.cruds.shippingdetails.fields.password }}</label>
                                        <input class="form-control" type="text" v-model="shipping.password">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" id="shiprocket_test_mode" class="custom-control-input" v-model="shipping.test_mode" />
                                            <label class="custom-control-label" for="shiprocket_test_mode">{{ lang.cruds.shippingdetails.fields.enable_test_mode }}</label>
                                        </div>
                                    </div>
                                </div>
                             </div>
                          </div>
                      </div>
                      <!-- Basic details end -->
                </div>
            </div>
        
          <div class="row"  v-if="shipping.name == 'Ithinklogistics'">
              <div class="col-md-4 col-12">
                  <div class="store-content">
                      <h6 class="store-title">{{ lang.cruds.shippingdetails.fields.i_think_logistices }}</h6>
                      <p>{{ lang.cruds.shippingdetails.fields.i_think_logistices_helper }}</p>
                  </div>
              </div>
              <div class="col-md-8 col-12">
                    <!-- Basic details start -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                              <div class="col-12">
                                 <div class="form-group">
                                    <label class="required" for="access_token">{{ lang.cruds.shippingdetails.fields.access_token }}</label>
                                    <input class="form-control" type="text" v-model="shipping.access_token">
                                 </div>
                                  <div class="form-group">
                                      <label class="required" for="secret_key">{{ lang.cruds.shippingdetails.fields.secret_key }}</label>
                                      <input class="form-control" type="text" v-model="shipping.secret_key">
                                  </div>
                                  <div class="form-group">
                                    <div class="custom-control custom-checkbox mb-1">
                                        <input type="checkbox" id="logistics_test_mode" class="custom-control-input" v-model="shipping.test_mode" />
                                        <label class="custom-control-label" for="logistics_test_mode">{{ lang.cruds.shippingdetails.fields.enable_test_mode }}</label>
                                    </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                    </div>
              </div>
              <!-- Basic details end -->
          </div>
        </div>
          <div class="form-group">
              <button class="btn btn-primary waves-effect waves-float waves-light" type="submit">
                  {{ lang.global.save }}
              </button>
              <button class="btn btn-danger waves-effect waves-float waves-light" type="button" @click="backtolist()">
                  {{ lang.global.back_to_list }}
              </button>
          </div>
      </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'shippingdetails',
    data() {
      return {
       objShippingDetails:[],
      }
    },
    mounted(){
        
             this.objShippingDetails = this.data.shippingDetail;
    
    },
    components: {
     
    },
    computed: {
    },
    created() {
      
    },
    methods: {
      submit(){
        openLoader();
        this.$store.dispatch("shippingDetailsModule/SaveShippingDetails",{'shippingdetails' : this.objShippingDetails})
          .then((res) => {
              if (res.response.status_code == 3078) {
                  successModal(res.response.message);
                  setTimeout(function(){
                  window.location = res.response.data;
                  },2000);

              }
               else
              {
                errorModal(res.response.message);
              }
              closeLoader();
          })
          .catch((err) => {
             closeLoader();
             errorModal(err.response.message);
        });
      },
      backtolist(){
         window.history.back();
      },
    }
  }
</script>

<style scoped>
    .back-url{
    font-size: 18px;
    color: #5e5873;
}
.store-content p {
    font-size: 13px;
    text-align: justify;
}
.store-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}
hr{
  margin-bottom: 3rem;
}
</style>