<template>
  <div id="input-sizing">
      <div id="frmGiftCards">
          <div class="row">
            <div class="col-md-4 col-12">
                <div class="store-content">
                     <h4 class="store-title mb-2 font-weight-bolder">{{ lang.cruds.paymentSettings.payment_provider }}</h4>
                    <p class="small text-justify">{{ lang.cruds.paymentSettings.payment_provider_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <!-- card start-->
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-1 font-weight-bold">{{ lang.cruds.paymentSettings.payment_methods }}</h6>
                        <p class="small">{{ lang.cruds.paymentSettings.payment_methods_helper }}</p>
                        <div class="text-right">
                            <a class="btn btn-outline-secondary" id="btn_choose_payment_meethods" href="payments/alternate-providers">{{ lang.cruds.paymentSettings.choose_payment_methods }}</a>
                        </div>
                        <div v-if="activatedPaymentMethods.length > 0">
                            <div class="border-top pt-1 mt-1" v-for="(peymentMethod, index) in activatedPaymentMethods">
                                <div class="row align-items-center">
                                    <div class="col-md-10">
                                        <div class="font-weight-bolder">{{peymentMethod.title}} is activated.</div>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <a class="btn btn-outline-secondary" :href="`payments/alternate-providers/` + peymentMethod.id">{{ lang.global.edit }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card end-->
                <!-- card start-->
              <!--   <div class="card">
                    <div class="card-body">
                        <h6 class="mb-1 font-weight-bold">{{ lang.cruds.paymentSettings.manual_payment_methods }}</h6>
                        <p class="small">{{ lang.cruds.paymentSettings.manual_payment_methods_helper }}</p>
                        <div class="text-right">
                            <div class="btn-group">
                              <button
                                class="btn  btn-outline-secondary dropdown-toggle"
                                type="button"
                                id="dropdownMenuButton701"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                    {{ lang.cruds.paymentSettings.manual_payment_methods }}
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton701">
                                <a class="dropdown-item"
                                data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#manualPayment"  @click="addCustomPaymentMethod(method.text, method.type)" v-for="(method,index) in manualMethods">
                                    {{ method.text }}
                                </a>
                              </div>
                            </div>
                        </div>
                        <div v-if="manualPaymentMethods.length > 0">
                            <div class="border-top pt-1 mt-1" v-for="(peymentMethod, index) in manualPaymentMethods">
                                <div class="row align-items-center">
                                    <div class="col-md-10">
                                        <div class="font-weight-bolder" v-if="peymentMethod.type == 'custom'">{{peymentMethod.name}} is activated.</div>
                                        <div class="font-weight-bolder" v-else-if="peymentMethod.type == 'bank_deposite'">{{ lang.cruds.paymentSettings.bank_deposite }} is activated.</div>
                                        <div class="font-weight-bolder" v-else-if="peymentMethod.type == 'money_order'">{{lang.cruds.paymentSettings.money_order}} is activated.</div>
                                        <div class="font-weight-bolder" v-else-if="peymentMethod.type == 'cod'">{{lang.cruds.paymentSettings.cash_on_delivery}} is activated.</div>
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#manualPayment" @click="editCustomPaymentMethod(index)">{{ lang.global.edit }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- card end-->
            </div>
          </div>
      </div>

        <!-- Manual payment methods Modals start -->
       <!--  <div class="modal fade" id="manualPayment" tabindex="-1" role="dialog" aria-labelledby="manualPaymentTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="manualPaymentTitle">{{ lang.cruds.paymentSettings.set_up }} {{ currentPaymentMethod }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="resetData()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <div class="row mt-2 mb-1">
                       <div class="col-md-12">
                          <div class="form-group">
                              <label>{{ lang.cruds.paymentSettings.custom_payment_method_name }}</label>
                              <input class="form-control" type="text" v-model="manualPaymentData.paymentMethodName">
                          </div>
                       </div>
                       <div class="col-md-12">
                          <div class="form-group">
                                <label>{{ lang.cruds.paymentSettings.additional_details }}</label>
                                <textarea  class="form-control" rows="3" v-model="manualPaymentData.additionalDetails"></textarea>
                                <div class="helper-text small">
                                   {{ lang.cruds.paymentSettings.additional_details_helper }}
                                </div>    
                          </div>
                       </div>
                       <div class="col-md-12">
                          <div class="form-group">
                                <label>{{ lang.cruds.paymentSettings.payment_instructions }}</label>
                                <textarea  class="form-control" rows="3" v-model="manualPaymentData.paymentInstructions"></textarea>
                                <div class="helper-text small">
                                   {{ lang.cruds.paymentSettings.payment_instructions_helper }}
                                </div>    
                          </div>
                       </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger mr-auto" v-if="manualType == 'edit'" @click="deActivateCustomPaymentMethod()">{{ lang.global.deactivate }}</button>

                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" @click="resetData()">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary" @click="CreateCustomPaymentMethod()" v-if="manualType == 'add'">{{ lang.global.activate }} {{ currentPaymentMethod }}</button>
                <button type="button" class="btn btn-primary" @click="CreateCustomPaymentMethod()" v-if="manualType == 'edit'">{{ lang.global.save }}</button>
                <button type="button" class="btn btn-primary" @click="CreateCustomPaymentMethod()" v-if="manualType == 'reactive'">{{ lang.global.reactivate }} {{ currentPaymentMethod }}</button>
              </div>
            </div>
          </div>
        </div> -->
        <!-- Manual payment methods Modals End -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'payment',
    data() {
      return {
        manualPaymentData:{
            currentPaymentType:'',
            paymentMethodName:'',
            additionalDetails:'',
            paymentInstructions:'',
        },
        currentPaymentMethod:'',
        activatedPaymentMethods: [],
        manualType: "add",
        manualMethods:[
            { 'type':'custom', 'text' : this.lang.cruds.paymentSettings.create_custom_payment_method},
            { 'type':'bank_deposite', 'text' : this.lang.cruds.paymentSettings.bank_deposite},
            { 'type':'money_order', 'text' : this.lang.cruds.paymentSettings.money_order},
            { 'type':'cod', 'text' : this.lang.cruds.paymentSettings.cash_on_delivery}
        ],
        manualPaymentMethods:[],
        manualPaymentMethodsOriginal:[],
        beforeChange:{},
        index:''
      }
    },
    mounted(){
        if($('#payment_meethods_create').length != 1)
                {
                    $('#btn_choose_payment_meethods').remove();
                }
    },
    components: {
     
    },
    computed: {
    },
    created() {
        this.activatedPaymentMethods = this.data.activatedPaymentMethods;
        this.manualPaymentMethods = this.data.manualPaymentMethods;
        this.manualPaymentMethodsOriginal = this.data.manualPaymentMethodsOriginal;
        this.beforeChange = {...this.manualPaymentData};

        let filtered = [...this.manualMethods];
        $.each(this.manualPaymentMethods, function(key, value){
            if(value.type != 'custom'){
                let index = filtered.findIndex(item => item.type == value.type)
                filtered.splice(index, 1);
            }
        });
        this.manualMethods = [...filtered];
    },
    methods: {
        CreateCustomPaymentMethod(){
            openLoader();
            this.$store.dispatch("paymentSettingsModule/CreateCustomPaymentMethod", this.manualPaymentData)
            .then((res) => {
                  if (res.response.status_code == 2056) {
                    successModal(res.response.message);
                    closeLoader();
                    $('#manualPayment').modal('hide');
                    this.manualPaymentMethods.push(res.response.data.manualPaymentMethods);
                    this.manualPaymentMethodsOriginal = res.response.data.manualPaymentMethodsOriginal;
                    if(this.manualPaymentData.currentPaymentType != 'custom')
                    {
                      let index = this.manualMethods.findIndex(item => item.type == this.manualPaymentData.currentPaymentType)
                      this.manualMethods.splice(index, 1);
                    }
                    this.manualPaymentData = {...this.beforeChange};
                  } 
                  else  if (res.response.status_code == 1002) {
                    this.manualPaymentMethods = res.response.data.manualPaymentMethods;
                    this.manualPaymentMethodsOriginal = res.response.data.manualPaymentMethodsOriginal;
                    successModal(res.response.message);
                    closeLoader();
                    this.resetData();
                  }
                  else
                    {
                        errorModal(res.response.message);
                    }
            })
            .catch((err) => {
                closeLoader();
                errorModal(err.response.message);
            });
        },
        addCustomPaymentMethod(text, type){
            this.currentPaymentMethod =  text; 
            this.manualPaymentData.currentPaymentType = type; 
            let checkAlreadyAdded = {};
            $.each(this.manualPaymentMethodsOriginal, function(key, value){
                if(value.type == type){
                    checkAlreadyAdded = {...value};
                }
            });

            if(Object.keys(checkAlreadyAdded).length > 0){
                this.manualType = 'reactive';
                this.manualPaymentData.currentPaymentType = checkAlreadyAdded.type;
                this.manualPaymentData.paymentMethodName = checkAlreadyAdded.name;
                this.manualPaymentData.additionalDetails = checkAlreadyAdded.additional_details;
                this.manualPaymentData.paymentInstructions = checkAlreadyAdded.additional_instruction;
                this.manualPaymentData.id = checkAlreadyAdded.id;
            }
           
        },
        editCustomPaymentMethod(index){
            let data = this.manualPaymentMethods[index];
            this.manualType = 'edit';
            this.index = index;
            
            if( data.type == 'bank_deposite'){
                this.currentPaymentMethod = this.lang.cruds.paymentSettings.bank_deposite;
            } else if( data.type == 'money_order'){
                this.currentPaymentMethod = this.lang.cruds.paymentSettings.money_order;
            }else if( data.type == 'cod'){
                this.currentPaymentMethod = this.lang.cruds.paymentSettings.cash_on_delivery;
            }else if( data.type == 'custom'){
                this.currentPaymentMethod = this.lang.cruds.paymentSettings.create_custom_payment_method;
            }

            this.manualPaymentData.currentPaymentType = data.type;
            this.manualPaymentData.paymentMethodName = data.name;
            this.manualPaymentData.additionalDetails = data.additional_details;
            this.manualPaymentData.paymentInstructions = data.additional_instruction;
            this.manualPaymentData.id = data.id;
        },
        deActivateCustomPaymentMethod(){
            openLoader();
            this.$store.dispatch("paymentSettingsModule/deActivateCustomPaymentMethod", this.manualPaymentData.id)
            .then((res) => {
                if (res.response.status_code == 2057) {
                    successModal(res.response.message);
                    closeLoader();
                    $('#manualPayment').modal('hide');
                    this.manualPaymentMethods.splice(this.index, 1);
                    if(this.manualPaymentData.currentPaymentType == 'bank_deposite'){
                        this.manualMethods.push( { 'type':'bank_deposite', 'text' : this.lang.cruds.paymentSettings.bank_deposite} );
                    } else if(this.manualPaymentData.currentPaymentType == 'money_order'){
                         this.manualMethods.push( { 'type':'money_order', 'text' : this.lang.cruds.paymentSettings.money_order} );
                    }else if(this.manualPaymentData.currentPaymentType == 'cod'){
                         this.manualMethods.push( { 'type':'cod', 'text' : this.lang.cruds.paymentSettings.cash_on_delivery} );
                    }
                }
                else
                 {
                    errorModal(res.response.message);
                }
            })
            .catch((err) => {
                closeLoader();
                errorModal(err.response.message);
            });
        },
        resetData(){
            $('#manualPayment').modal('hide');
            this.manualPaymentData = {...this.beforeChange};
            this.manualType = 'add';
        }

    }
  }
</script>

<style scoped>

</style>