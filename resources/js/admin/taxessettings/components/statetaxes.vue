<template>
  <div id="input-sizing">
    <ValidationObserver ref="StateTaxesForm" v-slot="{ handleSubmit }">
        <form method="POST" enctype="multipart/form-data" id="frmAddEditTaxes" @submit.prevent="handleSubmit(submit())">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div  class="back-url">
                            <i data-feather='arrow-left-circle'></i> {{ lang.cruds.taxes.title }}
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div  class="base-taxes">
                            <h3>{{ lang.cruds.taxes.base_taxes }}</h3>
                            <p>Use base taxes if you have a tax obligation in {{ data.objCountry.name }}. These tax rates will be used unless overrides are specifed.</p>
                            <button class="btn btn-light waves-effect waves-light" type="button" @click="cancel()" >
                                    {{ lang.cruds.taxes.reset_to_default_tax_rates }}
                            </button>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-2 border-bottom">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <h5>{{ lang.cruds.taxes.country_tax }}</h5>
                                            <div class="input-group input-group-merge form-password-toggle mb-2">
                                                <input
                                                    v-model="formData.tax_percentage"
                                                    type="number"
                                                    class="form-control"
                                                    aria-describedby="basic-default-password1"
                                                />
                                                <div class="input-group-append">
                                                  <span class="input-group-text cursor-pointer">%</i></span>
                                                </div>
                                            </div>
                                            <!-- <input class="form-control" type="text" v-model="formData.themeurl"> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-1">
                                        <h5>{{ lang.cruds.taxes.regions }}</h5>
                                    </div>
                                    <div class="col-md-12" v-for="(states , index) in formData.newStateTax">
                                        <p>{{ states.name }}</p>  
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="input-group input-group-merge form-password-toggle mb-2">
                                                    <input
                                                        v-model="states.state_tax.state_tax_percentage"
                                                        type="number"
                                                        class="form-control"
                                                        aria-describedby="basic-default-password1"
                                                    />
                                                    <div class="input-group-append">
                                                      <span class="input-group-text cursor-pointer">%</i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <div class="form-group">
                                                    <input class="form-control" type="text" v-model="states.state_tax.text">
                                                </div>
                                            </div>
                                             <div class="col-6">

                                                <div class="form-group">
                                                    <select class="form-control" name="states" v-model="states.state_tax.tax_additional">
                                                        <option :value="id" v-for="(item , id) in list.taxAdditional" v-bind:key="id">{{ item | replace(oldPercentage , formData.tax_percentage) }}</option>
                                                    </select>
                                                  </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group">
                            <button class="btn btn-primary waves-effect waves-light fixed-right-bottom" type="button" @click="saveStateTaxes()">
                               {{ lang.global.save }}
                            </button>
                            <button class="btn btn-danger waves-effect waves-float waves-light" type="button" @click="backtolist()">
                                    {{ lang.global.back_to_list }}
                             </button>
                        </div>
                    </div>
                    
                </div>
                
              <br>
        </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import draggable from 'vuedraggable'

export default {
    props: ['list', 'data'],
    name:'StateTaxes',
    data() {
      return {
        formData: {
            tax_percentage:'',
            newStateTax: [],
            country_id: '',

        },
        oldPercentage:0,
      }
    },
    mounted(){
      this.formData.tax_percentage = this.data.objCountry.country_tax.tax_percentage; 
      this.formData.country_id = this.data.objCountry.id;
      this.formData.newStateTax = [...this.list.objStates];
      this.oldPercentage = this.formData.tax_percentage;
    },
    computed: {
    },
    created() {
      
    },
    filters: {
        replace: function (st, rep, repWith) {
        const result = st.split(rep).join(repWith)
        return result;
        }
    },
    methods: {
      
        saveStateTaxes(){
            openLoader();
            this.$store.dispatch("taxesSettingsModule/SaveStateTaxes", this.formData)
              .then((res) => {
                  if (res.response.status_code == 3113) {
                      successModal(res.response.message);
                       setTimeout(function(){
                              window.location.reload();
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
        cancel(){
            window.location.reload();
        },
        backtolist(){
            window.history.back();
          },

    }
    
  }
</script>

<style lang="scss" scoped>
    .back-url{
        font-size: 20px;
        color: #5e5873;
        svg{
            height:20px;
            width:20px; 
        }
    }
    .btn-light {
        color: #2a2e30;
        background-color: #ffffff;
        border-color: #2a2e30;
    }

</style>