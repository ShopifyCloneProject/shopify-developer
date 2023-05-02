<template>
  <div id="input-sizing">
      <form id="frmLegal">
          <div class="row">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h6 class="store-title">{{ lang.cruds.legalSettings.legal_pages }}</h6>
                    <p class="small">{{ lang.cruds.legalSettings.legal_pages_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header">
                         <h4 class="card-title">{{ lang.cruds.legalSettings.refund_policy }}</h4>
                    </div>
                    <div class="card-body">
                        <ckeditor v-model="refundPolicy" />
                        <button class="btn btn-outline-secondary text-nowrap mt-1" @click.prevent="copyDefultText('refund')">
                            {{ lang.cruds.legalSettings.create_from_template }}
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                         <h4 class="card-title">{{ lang.cruds.legalSettings.privacy_policy }}</h4>
                    </div>
                    <div class="card-body">
                        <ckeditor v-model="privacyPolicy" />
                        <button class="btn btn-outline-secondary text-nowrap mt-1" @click.prevent="copyDefultText('privacy')">
                            {{ lang.cruds.legalSettings.create_from_template }}
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                         <h4 class="card-title">{{ lang.cruds.legalSettings.terms_of_services }}</h4>
                    </div>
                    <div class="card-body">
                        <ckeditor v-model="termsOfServices" />
                        <button class="btn btn-outline-secondary text-nowrap mt-1" @click.prevent="copyDefultText('terms')">
                            {{ lang.cruds.legalSettings.create_from_template }}
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                         <h4 class="card-title">{{ lang.cruds.legalSettings.shipping_policy }}</h4>
                    </div>
                    <div class="card-body">
                         <ckeditor v-model="shippingPolicy" />
                    </div>
                </div>
            </div>
          </div>
          <div class="form-group text-right">
              <button class="btn btn-primary waves-effect" type="buttom" @click.prevent="savePages">{{ lang.global.save }}</button>
          </div>
      </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'legal',
    data() {
      return {
        refundPolicy:'',
        privacyPolicy:'',
        termsOfServices:'',
        shippingPolicy:'',
      }
    },
    mounted(){
        this.refundPolicy = this.data.pages.refund;
        this.privacyPolicy = this.data.pages.privacy;
        this.termsOfServices = this.data.pages.terms;
        this.shippingPolicy = this.data.pages.shippingpolicy;
    },
    methods: {
        savePages(){
                openLoader();
                let formData = {
                    'refund': this.refundPolicy,
                    'privacy': this.privacyPolicy,
                    'terms': this.termsOfServices,
                    'shippingpolicy': this.shippingPolicy,
                };
                this.$store.dispatch("legalSettingsModule/savePages", formData)
                .then((res) => {
                    if(res.response.status_code == 2091)
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
        copyDefultText(pagestatus){
            if(pagestatus == 'refund')
            {
                this.refundPolicy = this.data.pages.refund;
            }
            else if (pagestatus == 'privacy') {
                this.privacyPolicy = this.data.pages.privacy;
            }
            else if (pagestatus == 'terms') {
                this.termsOfServices = this.data.pages.terms;
            }
        }
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
</style>