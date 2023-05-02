<template>
  <div id="input-sizing">
    <form method="POST"  id="frmSelectCountry">
        <div class="row mb-2">
            <div class="col-md-12 col-12">
                <div class="card"> 
                    <div class="card-header">
                        <h4 class="card-title">{{ lang.cruds.taxes.title }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="required" for="country">{{ lang.cruds.taxes.select_country }}</label>
                                    <select class="custom-select" name="country" v-model="countryTaxes.country_id">
                                         <option :value="id" v-for="(item , id) in list.countries" v-bind:key="id">{{ item }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group float-left mt-2" v-if="list.btn_select_taxes">
                                    <button class="btn btn-primary waves-effect waves-light" type="button" @click="SelectCountryTaxes()">
                                        Show Select Taxes
                                    </button>
                                </div>
                            </div>
                        </div>
                  
                    </div>
                </div>
              
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card"> 
                    <div class="card-header border-bottom d-block">
                        <h4 class="card-title">Decide how tax is changed</h4>
                        <p>Manage how to taxes are charged and shown in your store. For a summary of the taxes you've collected, <a href="#">view your report.</a></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-md-12 mt-2 d-flex align-items-start border-bottom">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="include_tax" v-model="countryTaxes.include_tax"/>
                                </div>
                                <div class="description">
                                    <h5>Include tax in prices</h5>
                                    <p>Product price will include tax.</p>
                                </div>
                            </div>
                            <input type="hidden"  value="0" v-model="countryTaxes.exclude_tax">
                            <!-- <div class="col-md-12 mt-2 d-flex align-items-start border-bottom">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="exclude_tax" v-model="countryTaxes.exclude_tax"/>
                                </div>
                                <div class="description">
                                    <h5>Include or exclude tax based on your customer's country</h5>
                                    <p> <a href="#">Go to Markets preferences</a> to turn this setting on.</p>
                                </div>
                            </div> -->
                            <div class="col-md-12 mt-2 d-flex align-items-start border-bottom">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="charge_on_shipping" v-model="countryTaxes.charge_on_shipping"/>
                                </div>
                                <div class="description">
                                    <h5>Charge tax on shipping rates</h5>
                                    <p>Include shipping rates in the tax calculation.</p>
                                </div>
                            </div>
                            <input type="hidden"  value="0" v-model="countryTaxes.charge_vat_digital_goods">
                            <!-- <div class="col-md-12 mt-2 d-flex align-items-start border-bottom">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="charge_vat_digital_goods" />
                                </div>
                                <div class="description">
                                    <h5>Charge VAT on digital goods</h5>
                                    <p>This will create a collection that you can add digital products to. VAT will be applied to products in the collection at checkout (for European customers).   <a href="#">Learn more</a></p>
                                </div>
                            </div> -->
                             <div class="col-md-12 mt-2 d-flex align-items-start">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="round_figure_tax" v-model="countryTaxes.round_value"/>
                                </div>
                                <div class="description">
                                    <h5>Round Figure Tax</h5>
                                    <p>Choose where you tax and round charge for shipping at checkout.</p>
                                </div>
                            </div>
                        </div>
                  
                    </div>
                </div>

                <div class="form-group float-left">
                    <button class="btn btn-primary waves-effect waves-light" type="button" @click="saveCountryTaxes()">
                       {{ lang.global.save }}
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
    props: ['data','list'],
    name:'taxes',
    data() {
      return {
        countryTaxes:[],
      }
    },
    mounted(){
        this.countryTaxes = this.data.countryTax;
        
    },
    methods: {
            SelectCountryTaxes() {
                window.location.href = this.data.stateTaxUrl;
            },

          saveCountryTaxes(){
            openLoader();
            this.$store.dispatch("taxesSettingsModule/SaveCountryTaxes",this.countryTaxes)
              .then((res) => {
                  if (res.response.status_code == 3110) {
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
    }
  }
</script>

<style lang="scss" scoped>
.store-content p {
    font-size: 13px;
    text-align: justify;
}
.store-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}
.card-header{
    .card-title{
        margin-bottom: 5px !important;
    } 
    P{
        margin-bottom: 0PX !important;
    }
}
.form-check input{
    height:18px;
    width:18px;
    margin-top:5px;
}
</style>