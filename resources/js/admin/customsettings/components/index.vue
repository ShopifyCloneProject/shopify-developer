<template>
  <div id="input-sizing">
      <form id="frmLegal">
          <div class="row">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h6 class="store-title">{{ lang.cruds.customSettings.title }}</h6>
                    <p class="small">{{ lang.cruds.customSettings.title_helper }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-header">
                         <h4 class="card-title">{{ lang.cruds.customSettings.head_script }}</h4>
                    </div>
                    <div class="card-body">
                        <p>Add content with "script" tag.</p>
                        <textarea
                          class="form-control"
                          id="headScript"
                          rows="10"
                          placeholder="Enter script"
                          v-model="headScript"
                        ></textarea>
                      <!--   <button class="btn btn-outline-secondary text-nowrap mt-1" @click.prevent="copyDefultText('refund')">
                            {{ lang.cruds.customSettings.create_from_template }}
                        </button> -->
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                         <h4 class="card-title">{{ lang.cruds.customSettings.body_script }}</h4>
                    </div>
                    <div class="card-body">
                        <p>Add content with "script" tag.</p>
                        <textarea
                          class="form-control"
                          id="bodyScript"
                          rows="10"
                          placeholder="Enter script"
                          v-model="bodyScript"
                        ></textarea>
                    </div>
                </div>
              
            </div>
          </div>
          <div class="form-group text-right">
              <button class="btn btn-primary waves-effect" type="buttom" @click.prevent="saveCustomSettings">{{ lang.global.save }}</button>
          </div>
      </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'customSettings',
    data() {
      return {
        headScript:'',
        bodyScript:'',
      }
    },
    created(){
        this.headScript = this.data.head_script;
        this.bodyScript = this.data.body_script;
    },
    methods: {
        saveCustomSettings(){
            openLoader();
            let payload = {
                'head_script': this.headScript,
                'body_script': this.bodyScript,
            };
            this.$store.dispatch("customSettingsModule/SaveCustomSettings", payload)
            .then((res) => {
                if(res.response.status_code == 3001)
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
</style>