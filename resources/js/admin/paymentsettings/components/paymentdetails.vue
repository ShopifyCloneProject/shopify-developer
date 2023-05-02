<template>
  <div id="input-sizing">
      <form id="paymentMethods" @submit.prevent="submit()">
          <div class="row">
            <div class="col-md-12 col-12">
                <!-- card start -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="font-weight-bold mb-2">{{ lang.cruds.paymentSettings.about }} {{ paymentMethods.title }}</h4>
                        <p>{{ lang.cruds.paymentSettings.learn_more_about }} <a href="#">{{ paymentMethods.title }}</a></p>
                    </div>
                </div>
                <!-- card end -->
                <!-- card start -->
                <div class="card" v-if="paymentMethods.id == 1">
                    <div class="card-body">
                        <h4 class="font-weight-bold mb-2">Account Information</h4>
                        <div class="form-group">
                            <label>{{ lang.cruds.paymentSettings.api_key_id }}</label>
                            <input class="form-control" type="text" v-model="apiKey">
                        </div>
                        <div class="form-group">
                            <label>{{ lang.cruds.paymentSettings.api_key_secret }}</label>
                            <!-- <input class="form-control" type="text" v-model="apiSecret" placeholder="••••••"> -->
                            <div class="input-group form-password-toggle mb-2">
                                <input
                                  :type="type"
                                  class="form-control"
                                  id="api-secret"
                                  placeholder="Your Password"
                                  aria-describedby="basic-default-password"
                                  v-model="apiSecret"
                                />
                                <div class="input-group-append" v-show="type == 'password'">
                                  <span class="input-group-text cursor-pointer" @click="type = 'text'"><i data-feather="eye"></i></span>
                                </div>
                                <div class="input-group-append" v-show="type == 'text'">
                                  <span class="input-group-text cursor-pointer" @click="type = 'password'"><i data-feather='eye-off'></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" v-else-if="paymentMethods.id == 4">
                    <div class="card-body">
                        <h4 class="font-weight-bold mb-2">{{ lang.cruds.paymentSettings.account_information }}</h4>
                        <div class="form-group">
                            <label>{{ lang.cruds.paymentSettings.api_key_id }}</label>
                            <input class="form-control" type="text" v-model="apiKey">
                        </div>
                        <div class="form-group">
                            <label>{{ lang.cruds.paymentSettings.api_key_secret }}</label>
                            <div class="input-group form-password-toggle mb-2">
                                <input
                                  :type="type"
                                  class="form-control"
                                  id="api-secret"
                                  placeholder="Your api secret"
                                  aria-describedby="basic-default-password"
                                  v-model="apiSecret"
                                />
                                <div class="input-group-append" v-show="type == 'password'">
                                  <span class="input-group-text cursor-pointer" @click="type = 'text'"><i data-feather="eye"></i></span>
                                </div>
                                <div class="input-group-append" v-show="type == 'text'">
                                  <span class="input-group-text cursor-pointer" @click="type = 'password'"><i data-feather='eye-off'></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="card" v-else-if="paymentMethods.id == 2">
                    <div class="card-body">
                        <h4 class="font-weight-bold mb-2">{{ lang.cruds.paymentSettings.account_information }}</h4>
                        <div class="form-group">
                            <label>MID</label>
                            <input class="form-control" type="text" v-model="apiKey">
                        </div>
                        <div class="form-group">
                            <label>Merchant Key</label>
                            <input class="form-control" type="text" v-model="apiSecret" placeholder="••••••">
                        </div>
                        <div class="form-group">
                            <label>Industry Type</label>
                            <input class="form-control" type="text" v-model="industryType">
                        </div>
                        <div class="form-group">
                            <label>Enviroment</label>
                             <select class="custom-select" v-model="website">
                                <option value="WEBSTAGING" >Testing</option>
                                <option value="DEFAULT" >Production</option>
                            </select>
                            <!-- <input class="form-control" type="text" v-model="website"> -->

                        </div>
                    </div>
                </div>
                <div class="card" v-else>
                    <div class="card-body">
                        <h4 class="font-weight-bold mb-2">{{ lang.cruds.paymentSettings.account_information }}</h4>
                        <div class="form-group">
                            <label>{{ lang.cruds.paymentSettings.api_key_id }}</label>
                            <input class="form-control" type="text" v-model="apiKey">
                        </div>
                        <div class="form-group">
                            <label>{{ lang.cruds.paymentSettings.api_key_secret }}</label>
                            <div class="input-group form-password-toggle mb-2">
                                <input
                                  :type="type"
                                  class="form-control"
                                  id="api-secret"
                                  placeholder="Your api key secret"
                                  aria-describedby="basic-default-password"
                                  v-model="apiSecret"
                                />
                                <div class="input-group-append" v-show="type == 'password'">
                                  <span class="input-group-text cursor-pointer" @click="type = 'text'"><i data-feather="eye"></i></span>
                                </div>
                                <div class="input-group-append" v-show="type == 'text'">
                                  <span class="input-group-text cursor-pointer" @click="type = 'password'"><i data-feather='eye-off'></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card end -->
                <!-- card start -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-group border-bottom" v-for="(type, index) in paymentMethods.types">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" :id="`payment_type_`+index" class="custom-control-input" :value="type.id" v-model="types" checked />
                                <label class="custom-control-label" :for="`payment_type_`+index">{{ type.name }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card end -->
                <!-- card start -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.paymentSettings.test_mode }}</h6>
                        <P>{{ lang.cruds.paymentSettings.test_mode_helper }} {{ paymentMethods.title }} {{ lang.cruds.paymentSettings.test_mode_helper1 }}</P>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" id="test_mode" class="custom-control-input" v-model="isTestMode" />
                                <label class="custom-control-label" for="test_mode">{{ lang.cruds.paymentSettings.enable_test_mode }}</label>
                            </div>
                        </div>
                        <div class="bg-warning colors-container rounded text-white align-items-center justify-content-center p-2" v-if="isTestMode">{{ lang.cruds.paymentSettings.enable_test_mode_helper }} {{ paymentMethods.title }} {{ lang.cruds.paymentSettings.enable_test_mode_helper1 }}</div>
                    </div>
                </div>
                <!-- card end -->
            </div>
          </div>
           <div class="form-group text-right" v-if="!activateStatus">
                <button class="btn btn-primary waves-effect" type="submit" >{{ lang.global.activate }} {{ paymentMethods.title }}</button>
           </div>
           <div class="form-group text-right" v-if="activateStatus && status == 1">
                <button class="btn btn-danger waves-effect" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#confirmDeactivate" type="button">{{ lang.global.deactivate }} {{ paymentMethods.title }}</button>
                <button class="btn btn-primary waves-effect" type="submit">{{ lang.global.save }}</button>
           </div>
            <div class="form-group text-right" v-else-if="activateStatus">
                <button class="btn btn-primary waves-effect" type="submit">{{ lang.global.reactivate }} {{ paymentMethods.title }}</button>
            </div>
      </form>

        <!-- Confirm Deactivate payment method Modals start -->
        <div class="modal fade" id="confirmDeactivate" tabindex="-1" role="dialog" aria-labelledby="confirmDeactivateTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="confirmDeactivateTitle">{{ lang.global.deactivate }} {{ paymentMethods.title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <div class="mt-2 mb-1">
                       {{ lang.cruds.paymentSettings.deactive_helper_1 }} {{ paymentMethods.title }} {{ lang.cruds.paymentSettings.deactive_helper_2 }}
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-danger waves-effect" @click="deactivatePaymentMethod()">{{ lang.global.deactivate }} {{ paymentMethods.title }}</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Confirm Deactivate payment method Modals End -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'paymentDetails',
    data() {
      return {
        paymentMethods:[],
        apiKey:'',
        apiSecret:'',
        types:[],
        isTestMode:false,
        industryType:'',
        website: 'WEBSTAGING',
        status:0,
        details:{},
        detailId:'',
        activateStatus:false,
        type:'password'
      }
    },
    mounted(){
    },
    components: {
     
    },
    computed: {
    },
    created() {
        this.paymentMethods = this.data.paymentMethods;
        let enabledPaymentType = [];
        $.each(this.paymentMethods.types, function(key, value) {
            if(value.pivot.is_enabled == 1){
                enabledPaymentType.push(value.id);
            }
        });
        this.types = enabledPaymentType;

        this.details = this.paymentMethods.details;
        if(this.details != '' && this.details != null){
            this.apiKey = this.details.app_key;
            this.apiSecret = this.details.app_secret;
            this.isTestMode = this.details.is_testmode == 1 ? true : false;
            this.industryType = this.details.industry_type;
            this.website = this.details.website;
            this.detailId = this.details.id;
            this.status = this.details.status;
            this.activateStatus = true;
        }
    },
    methods: {
        submit(){
            let data = {
                id:  this.paymentMethods.id,
                apiKey: this.apiKey,
                apiSecret: this.apiSecret,
                industryType: this.industryType,
                website: this.website,
                types: this.types,
                isTestMode: this.isTestMode
            };

            openLoader();
            this.$store.dispatch("paymentSettingsModule/ActivatePaymentMethod", data)
            .then((res) => {
                  if (res.response.status_code == 2056) {
                      successModal(res.response.message);
                      this.activateStatus = true;
                      this.status = 1;
                      setTimeout(function(){
                        window.location = res.response.data.url;
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
        deactivatePaymentMethod(){
            openLoader();
            this.$store.dispatch("paymentSettingsModule/deActivatePaymentMethod", this.detailId)
            .then((res) => {
                  if (res.response.status_code == 2057) {
                        successModal(res.response.message);
                        $('#confirmDeactivate').modal('hide');
                        this.status = 0;
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
        }
    }
  }
</script>

<style scoped>

</style>