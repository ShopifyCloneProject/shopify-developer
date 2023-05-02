<template>
  <div id="input-sizing">
      <form id="frmGiftCards">
          <div class="row">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h6 class="store-title">{{ lang.cruds.planSettings.plan_details }}</h6>
                    <p><small>{{ lang.cruds.planSettings.plan_details_helper }}</small></p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    <div class="card-body border-bottom">
                      <div class="row">
                          <div class="col-12">
                             <h6 class="store-title">{{ lang.cruds.planSettings.custom_plan }}</h6>
                             <p><small>{{ lang.cruds.planSettings.custom_plan_helper }}</small></p>
                          </div>
                      </div>
                      <hr class="mt-2 mb-2" />
                      <div class="row ">
                          <div class="col-12">
                              <h6 class="store-title">{{ lang.cruds.planSettings.paymeny_method }}</h6>
                              <p><small>{{ lang.cruds.planSettings.not_found }}</small></p>
                          </div>
                      </div>
                    </div>
                </div>
            </div>
          </div>
      </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'Plan',
    data() {
      return {
      }
    },
    mounted(){
    },
    components: {
     
    },
    computed: {
    },
    created() {
    },
    methods: {
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