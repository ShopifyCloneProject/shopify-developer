<template>
  <div id="input-sizing">
      <form id="frmGiftCards">
          <div class="row">
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h6 class="store-title">billing</h6>
                </div>
            </div>
            <div class="col-md-8 col-12">
                <div class="card">
                    
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
    name:'billing',
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