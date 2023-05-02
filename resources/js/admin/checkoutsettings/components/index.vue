<template>
  <div id="input-sizing">
      <form id="frmCheckout">
          <!-- Customer account section start -->
          <div class="row border-bottom">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h4 class="store-title mb-2 font-weight-bolder">{{ lang.cruds.checkoutSettings.customer_accounts }}</h4>
                    <p class="small text-justify">{{ lang.cruds.checkoutSettings.customer_accounts_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-body border-bottom">
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="account_disabled" class="custom-control-input" value="1" v-model.number="formData.customerAccounts" />
                              <label class="custom-control-label" for="account_disabled">{{ lang.cruds.checkoutSettings.account_disabled }}</label>
                              <div class="helper-text small">
                                  {{ lang.cruds.checkoutSettings.account_disabled_helper }}
                              </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="account_optional" class="custom-control-input" value="2" v-model.number="formData.customerAccounts" />
                              <label class="custom-control-label" for="account_optional">{{ lang.cruds.checkoutSettings.account_optional }}</label>
                              <div class="helper-text small">
                                  {{ lang.cruds.checkoutSettings.account_optional_helper }}
                              </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="account_required" class="custom-control-input" value="3" v-model.number="formData.customerAccounts" />
                              <label class="custom-control-label" for="account_required">{{ lang.cruds.checkoutSettings.account_required }}</label>
                              <div class="helper-text small">
                                  {{ lang.cruds.checkoutSettings.account_required_helper }}
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <!-- Customer account section end -->
          <!-- Customer contact section start -->
          <div class="row mt-2 border-bottom">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h4 class="store-title mb-2 font-weight-bolder">{{ lang.cruds.checkoutSettings.customer_contact }}</h4>
                    <p class="small text-justify">{{ lang.cruds.checkoutSettings.customer_contact_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                      <div class="card-body border-bottom">
                          <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.to_check_out }}</h6>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="check_out_option_1" class="custom-control-input" value="1" v-model.number="formData.toCheckOut" />
                                <label class="custom-control-label" for="check_out_option_1">{{ lang.cruds.checkoutSettings.check_out_option_1 }}</label>
                                <div class="helper-text small">
                                    {{ lang.cruds.checkoutSettings.check_out_option_1_helper }}
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="check_out_option_2" class="custom-control-input" value="2" v-model.number="formData.toCheckOut" />
                                <label class="custom-control-label" for="check_out_option_2">{{ lang.cruds.checkoutSettings.check_out_option_2 }}</label>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="check_out_option_3" class="custom-control-input" value="3" v-model.number="formData.toCheckOut" />
                                <label class="custom-control-label" for="check_out_option_3">{{ lang.cruds.checkoutSettings.check_out_option_3 }}</label>
                              </div>
                          </div>
                           <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="check_out_option_4" class="custom-control-input" value="4" v-model.number="formData.toCheckOut" />
                                <label class="custom-control-label" for="check_out_option_4">{{ lang.cruds.checkoutSettings.check_out_option_4 }}</label>
                              </div>
                          </div>
                      </div>
                      <div class="card-body">
                          <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.shipping_updates }}</h6>
                          <div class="form-group">
                              <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" id="shipping_updates_option_1" class="custom-control-input"  v-model="formData.shoppingUpdate1" />
                                <label class="custom-control-label" for="shipping_updates_option_1">{{ lang.cruds.checkoutSettings.shipping_updates_option_1 }}</label>
                                <div class="helper-text small">
                                    {{ lang.cruds.checkoutSettings.check_out_option_1_helper }}
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" id="shipping_updates_option_2" class="custom-control-input"  v-model="formData.shoppingUpdate2" />
                                <label class="custom-control-label" for="shipping_updates_option_2">{{ lang.cruds.checkoutSettings.shipping_updates_option_2 }}</label>
                              </div>
                          </div>
                      </div>
                </div>
            </div>
          </div>
          <!-- Customer contact section end -->
          <!-- form options section start -->
          <div class="row mt-2 border-bottom">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h4 class="store-title mb-2 font-weight-bolder">{{ lang.cruds.checkoutSettings.form_options }}</h4>
                    <p class="small text-justify">{{ lang.cruds.checkoutSettings.form_options_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <!-- full name ction start -->
                    <div class="card-body border-bottom">
                        <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.full_name }}</h6>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="reuired_fisrt_name" class="custom-control-input" value="1" v-model.number="formData.fullNameOption" />
                              <label class="custom-control-label" for="reuired_fisrt_name">{{ lang.cruds.checkoutSettings.reuired_fisrt_name }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="reuired_fisrt_last_name" class="custom-control-input" value="2" v-model.number="formData.fullNameOption" />
                              <label class="custom-control-label" for="reuired_fisrt_last_name">{{ lang.cruds.checkoutSettings.reuired_fisrt_last_name }}</label>
                            </div>
                        </div>
                    </div>
                    <!-- full name ction end -->
                    <!-- company section start -->
                    <div class="card-body border-bottom">
                        <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.company_name }}</h6>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="hidden" class="custom-control-input" value="1" v-model.number="formData.companyNameOption" />
                              <label class="custom-control-label" for="hidden">{{ lang.cruds.checkoutSettings.hidden }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="optional" class="custom-control-input" value="2" v-model.number="formData.companyNameOption" />
                              <label class="custom-control-label" for="optional">{{ lang.cruds.checkoutSettings.optional }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="required" class="custom-control-input" value="3" v-model.number="formData.companyNameOption" />
                              <label class="custom-control-label" for="required">{{ lang.cruds.checkoutSettings.required }}</label>
                            </div>
                        </div>
                    </div>
                    <!-- company section end -->
                    <!-- address 2 section start -->
                    <div class="card-body border-bottom">
                        <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.address_2 }}</h6>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="hidden1" class="custom-control-input" value="1" v-model.number="formData.address2Option" />
                              <label class="custom-control-label" for="hidden1">{{ lang.cruds.checkoutSettings.hidden }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="optional1" class="custom-control-input" value="2" v-model.number="formData.address2Option" />
                              <label class="custom-control-label" for="optional1">{{ lang.cruds.checkoutSettings.optional }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="required1" class="custom-control-input" value="3" v-model.number="formData.address2Option" />
                              <label class="custom-control-label" for="required1">{{ lang.cruds.checkoutSettings.required }}</label>
                            </div>
                        </div>
                    </div>
                    <!-- address 2 section end -->
                    <!-- shipping address section start -->
                    <div class="card-body mt-2">
                        <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.shipping_address_phone }}</h6>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="hidden2" class="custom-control-input" value="1" v-model.number="formData.shippingAddressOption" />
                              <label class="custom-control-label" for="hidden2">{{ lang.cruds.checkoutSettings.hidden }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="optional2" class="custom-control-input" value="2" v-model.number="formData.shippingAddressOption" />
                              <label class="custom-control-label" for="optional2">{{ lang.cruds.checkoutSettings.optional }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-1">
                              <input type="radio" id="required2" class="custom-control-input" value="3" v-model.number="formData.shippingAddressOption" />
                              <label class="custom-control-label" for="required2">{{ lang.cruds.checkoutSettings.required }}</label>
                            </div>
                        </div>
                    </div>
                    <!-- shipping address section end -->
                </div>
            </div>
          </div>
          <!-- form options section end -->
          <!-- tipping section start -->
          <div class="row mt-2 border-bottom">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h4 class="store-title mb-2 font-weight-bolder">{{ lang.cruds.checkoutSettings.tipping }}</h4>
                    <p class="small text-justify">{{ lang.cruds.checkoutSettings.tipping_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.tipping }}</h6>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                              <input type="checkbox" id="tipping_option" class="custom-control-input"  v-model="formData.tippingOption" />
                              <label class="custom-control-label" for="tipping_option">{{ lang.cruds.checkoutSettings.tipping_option }}</label>
                              <div class="helper-text small">
                                  {{ lang.cruds.checkoutSettings.tipping_option_helper }}
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <!-- tipping section end -->
          <!-- Order processing section start -->
          <div class="row mt-2 border-bottom">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h4 class="store-title mb-2 font-weight-bolder">{{ lang.cruds.checkoutSettings.order_processing }}</h4>
                    <p class="small text-justify">{{ lang.cruds.checkoutSettings.order_processing_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                      <div class="card-body border-bottom">
                          <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.order_processing_title }}</h6>
                          <div class="form-group">
                              <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" id="order_checkout_option_1" class="custom-control-input"  v-model.number="formData.orderProcessing1" />
                                <label class="custom-control-label" for="order_checkout_option_1">{{ lang.cruds.checkoutSettings.order_checkout_option_1 }}</label>
                                <div class="helper-text small">
                                    {{ lang.cruds.checkoutSettings.order_checkout_option_1_helper }}
                                </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" id="order_checkout_option_2" class="custom-control-input"  v-model.number="formData.orderProcessing2" />
                                <label class="custom-control-label" for="order_checkout_option_2">{{ lang.cruds.checkoutSettings.order_checkout_option_2 }}</label>
                                <div class="helper-text small">
                                    {{ lang.cruds.checkoutSettings.order_checkout_option_2_helper }}
                                </div>
                              </div>
                          </div>
                      </div>
                      <div class="card-body border-bottom">
                          <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.after_order_paid }}</h6>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="after_order_paid_option_1" class="custom-control-input" value="1" v-model.number="formData.afterOrderPaid" />
                                <label class="custom-control-label" for="after_order_paid_option_1">{{ lang.cruds.checkoutSettings.after_order_paid_option_1 }}</label>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="after_order_paid_option_2" class="custom-control-input" value="2" v-model.number="formData.afterOrderPaid" />
                                <label class="custom-control-label" for="after_order_paid_option_2">{{ lang.cruds.checkoutSettings.after_order_paid_option_2 }}</label>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="after_order_paid_option_3" class="custom-control-input" value="3" v-model.number="formData.afterOrderPaid" />
                                <label class="custom-control-label" for="after_order_paid_option_3">{{ lang.cruds.checkoutSettings.after_order_paid_option_3 }}</label>
                              </div>
                          </div>
                      </div>
                      <div class="card-body border-bottom">
                          <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.after_order_fullfilled }}</h6>
                          <div class="form-group">
                              <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" id="after_order_fullfilled_option" class="custom-control-input"  v-model="formData.orderFullfilled" />
                                <label class="custom-control-label" for="after_order_fullfilled_option">{{ lang.cruds.checkoutSettings.after_order_fullfilled_option }}</label>
                                <div class="helper-text small">
                                    {{ lang.cruds.checkoutSettings.after_order_fullfilled_option_helper }}
                                </div>
                              </div>
                          </div>
                      </div>
                      <div class="card-body">
                          <div class="form-group">
                              <label>{{ lang.cruds.checkoutSettings.after_order_fullfilled_option }}</label>
                              <textarea  class="form-control" rows="3" v-model="formData.additionalScript"></textarea>
                          </div>
                      </div>
                </div>
            </div>
          </div>
          <!-- Order processing section end -->
          <!-- email marketing section start -->
          <div class="row mt-2 border-bottom">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h4 class="store-title mb-2 font-weight-bolder">{{ lang.cruds.checkoutSettings.email_marketing }}</h4>
                    <p class="small text-justify">{{ lang.cruds.checkoutSettings.email_marketing_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox mb-1">
                              <input type="checkbox" id="email_marketing_option_1" class="custom-control-input"  v-model="formData.emailOption1" />
                              <label class="custom-control-label" for="email_marketing_option_1">{{ lang.cruds.checkoutSettings.email_marketing_option_1 }}</label>
                            </div>
                            <div class="custom-control custom-checkbox ml-2">
                              <input type="checkbox" id="email_marketing_option_2" class="custom-control-input"  v-model="formData.emailOption2" />
                              <label class="custom-control-label" for="email_marketing_option_2">{{ lang.cruds.checkoutSettings.email_marketing_option_2 }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <!-- email marketing section end -->
          <!-- Abandoned checkouts section start -->
          <div class="row mt-2">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h4 class="store-title mb-2 font-weight-bolder">{{ lang.cruds.checkoutSettings.abandoned_checkouts }}</h4>
                    <p class="small text-justify">{{ lang.cruds.checkoutSettings.abandoned_checkouts_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                      <div class="card-body border-bottom">
                          <div class="form-group">
                              <div class="custom-control custom-checkbox mb-1">
                                <input type="checkbox" id="abandoned_checkouts_option" class="custom-control-input"  v-model="formData.abandonCheckout" />
                                <label class="custom-control-label" for="abandoned_checkouts_option">{{ lang.cruds.checkoutSettings.abandoned_checkouts_option }}</label>
                              </div>
                          </div>
                      </div>
                      <div class="card-body border-bottom">
                          <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.send_to }}</h6>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="send_to_option_1" class="custom-control-input" value="1" v-model.number="formData.sendToOption" />
                                <label class="custom-control-label" for="send_to_option_1">{{ lang.cruds.checkoutSettings.send_to_option_1 }}</label>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="send_to_option_2" class="custom-control-input" value="2" v-model.number="formData.sendToOption" />
                                <label class="custom-control-label" for="send_to_option_2">{{ lang.cruds.checkoutSettings.send_to_option_2 }}</label>
                              </div>
                          </div>
                      </div>
                      <div class="card-body border-bottom">
                          <h6 class="mb-1 font-weight-bolder">{{ lang.cruds.checkoutSettings.send_after }}</h6>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="send_after_option_1" class="custom-control-input" value="1" v-model.number="formData.sendAfterOption" />
                                <label class="custom-control-label" for="send_after_option_1">{{ lang.cruds.checkoutSettings.send_after_option_1 }}</label>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="send_after_option_2" class="custom-control-input" value="2" v-model.number="formData.sendAfterOption" />
                                <label class="custom-control-label" for="send_after_option_2">{{ lang.cruds.checkoutSettings.send_after_option_2 }}</label>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="send_after_option_3" class="custom-control-input" value="3" v-model.number="formData.sendAfterOption" />
                                <label class="custom-control-label" for="send_after_option_3">{{ lang.cruds.checkoutSettings.send_after_option_3 }}</label>
                              </div>
                          </div>
                          <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="send_after_option_4" class="custom-control-input" value="4" v-model.number="formData.sendAfterOption" />
                                <label class="custom-control-label" for="send_after_option_4">{{ lang.cruds.checkoutSettings.send_after_option_4 }}</label>
                              </div>
                          </div>
                      </div>
                </div>
            </div>
          </div>
          <!-- Abandoned checkouts section end -->
          <div class="form-group text-right">
              <button class="btn btn-primary waves-effect" type="buttom" @click.prevent="saveSettings">{{ lang.global.save }}</button>
          </div>
      </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'checkout',
    data() {
      return {
        formData:{
          customerAccounts:'3',
          toCheckOut: '1',
          shoppingUpdate1: false,
          shoppingUpdate2: false,
          fullNameOption:'1',
          companyNameOption:'1',
          address2Option:'2',
          shippingAddressOption:'1',
          tippingOption: false,
          orderProcessing1: false,
          orderProcessing2: false,
          afterOrderPaid:'2',
          orderFullfilled: true,
          additionalScript:'',
          emailOption1: true,
          emailOption2: false,
          abandonCheckout: true,
          sendToOption:'2',
          sendAfterOption:'3'
        }
      }
    },
    mounted(){
        this.formData = this.data.settings;
    },
    components: {
     
    },
    computed: {
    },
    methods: {

        saveSettings(){
                openLoader();
                this.$store.dispatch("checkoutSettingsModule/saveSettings", this.formData)
                .then((res) => {
                    if(res.response.status_code == 2090)
                    {
                        successModal(res.response.message);
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
        handlecheckbox(status)
        {
            if(status=="0")
            {
                return false;
            }
            return true;
        }
    }
  }
</script>
<style scoped>
</style>