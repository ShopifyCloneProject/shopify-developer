<template>
  <div id="input-sizing">
      <div id="paymentMethods">
          <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="method-listings pt-2 pb-1 px-1 border-bottom pointer">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="input-group input-group-merge mb-2">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon-search2"><i data-feather="search"></i></span>
                                    </div>
                                    <input
                                      type="text"
                                      class="form-control"
                                      placeholder="Search..."
                                      aria-label="Search..."
                                      aria-describedby="basic-addon-search2"
                                      @keyup="searchPaymentMethods()"
                                      v-model="search"
                                    />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="custom-select" v-model="searchType" @change="searchPaymentTypes()">
                                    <option value="">Payment methods</option>
                                    <option v-for="(type, index) in paymentTypes" :value="type.name"> {{ type.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="method-listings pt-2 pb-25 px-1 border-bottom pointer" v-for="(method, index) in paymentMethods"  @click="openDetailPage(method.id)">
                        <div class="row">
                            <div class="col-md-7">
                                <div>
                                    <p class="font-weight-bold">{{ method.title }}</p>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div v-if="method.types.length > 0">
                                    <div class="badge badge-primary mr-1 mb-1" v-for="( type, index ) in method.types">
                                        {{ type.name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="paymentMethods.length <= 0">
                        <div class="text-center p-3">
                            <h3>Payment Method Not Found</h3>
                            <p class="small">Try changing the filters or search term</p>
                        </div>
                    </div>
                </div>
            </div>
          </div>
      </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['data'],
    name:'paymentMethods',
    data() {
      return {
        paymentMethods:[],
        paymentMethodsBefore:[],
        types:[],
        search:'',
        searchType:''
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
        this.paymentTypes = this.data.paymentTypes;
        this.paymentMethodsBefore =  this.data.paymentMethods;
    },
    methods: {
        openDetailPage(id){
            var currentUrl = window.location.pathname;
            let detailPageUrl = currentUrl + "/" + id;
            window.location = detailPageUrl;
        },
        searchPaymentMethods(){
            if(this.search.trim() != '' && this.search.trim() != null){
                let filtered  = this.paymentMethodsBefore.filter((el) => {
                    return el.title.toLowerCase().includes(this.search.toLowerCase());
                });

                this.paymentMethods = filtered
            } else {
                this.paymentMethods = this.paymentMethodsBefore;
            }
        },
        searchPaymentTypes(){
            if(this.searchType.trim() != '' && this.searchType.trim() != null){
                let searchType = this.searchType;
                //main methods loop
                let filtered  = this.paymentMethodsBefore.filter((el) => {
                    let result = false;
                    //each types loop for every method
                    $.each(el.types, function(key, value) {
                        if(value.name == searchType){
                            result = true;
                        }
                    });
                    return result;
                });

                this.paymentMethods = filtered
            } else {
                this.paymentMethods = this.paymentMethodsBefore;
            }
        }

    }
  }
</script>

<style scoped>

</style>