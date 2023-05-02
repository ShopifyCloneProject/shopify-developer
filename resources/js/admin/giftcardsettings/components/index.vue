<template>
  <div id="input-sizing">
      <form id="frmGiftCards">
          <div class="row">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h6 class="store-title">{{ lang.cruds.giftCardSettings.auto_expiration }}</h6>
                    <p>{{ lang.cruds.giftCardSettings.auto_expiration_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-body border-bottom">
                      <div class="row">
                          <div class="col-12">
                              <div class="form-group">
                                  <div class="custom-control custom-radio mb-1">
                                    <input type="radio" id="gift_type_1" class="custom-control-input" value="1" v-model.number="formData.giftType" @click="disabled = true"/>
                                    <label class="custom-control-label" for="gift_type_1">{{ lang.cruds.giftCardSettings.expire }}</label>
                                  </div>
                              </div>
                              <div class="form-group">
                                  <div class="custom-control custom-radio mb-1">
                                    <input type="radio" id="gift_type_2" class="custom-control-input" value="2" v-model.number="formData.giftType" @click="disabled = false"/>
                                    <label class="custom-control-label" for="gift_type_2">{{ lang.cruds.giftCardSettings.never_expire }}</label>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="row mt-1">
                          <div class="col-2">
                              <div class="form-group">
                                  <input class="form-control" type="number" step="1" placeholder="5" v-model="formData.days" :disabled="disabled">
                              </div>
                          </div>
                           <div class="col-10">
                              <select class="custom-select" v-model="formData.option" :disabled="disabled">
                                <option value="1">{{ lang.cruds.giftCardSettings.option_1 }}</option>
                                <option value="2">{{ lang.cruds.giftCardSettings.option_2 }}</option>
                                <option value="3">{{ lang.cruds.giftCardSettings.option_3 }}</option>
                              </select>
                          </div>
                          <div class="col-12">
                            <div><small>{{ lang.cruds.giftCardSettings.expire_helper }}</small></div>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="form-group">
              <button class="btn btn-secondary" type="button" @click.prevent="resetData()">{{ lang.global.cancel }}</button>
              <button class="btn btn-primary" type="button" @click.prevent="SaveGiftCardSettings()">{{ lang.global.save }}</button>
          </div>
      </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'GiftCards',
    data() {
      return {
        formData: {
          giftType: 1,
          days: 5,
          option: 3
        },
        disabled:true,
        beforeEdit:{}
      }
    },
    mounted(){
      this.beforeEdit = {...this.formData}
    },
    components: {
     
    },
    computed: {
    },
    created() {
      this.setFormData();
    },
    methods: {
      setFormData(){
        if(this.data.giftType == 2){ //if type is 1 then set default valur for days and option
          this.formData = {...this.data}
          this.disabled = false;
        } 
      },
      SaveGiftCardSettings(){
         openLoader();
          this.$store.dispatch("giftcardSettingsModule/SaveGiftCardSettings", this.formData)
          .then((res) => {
              if (res.response.status_code == 1002) {
                  this.beforeEdit = {...this.formData}
                  successModal(res.response.message);
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
      resetData(){
        this.formData = {...this.beforeEdit}
        if(this.beforeEdit.giftType == 1){
          this.disabled = true;
        } else {
          this.disabled = false;
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