<template>
  <div id="input-sizing">
      <form id="frmAddEditLocations">
          <div class="row">
            <div class="col-12 mb-2 text-right" id="btn_create_location">
              <a :href="url" class="btn btn-primary waves-effect waves-light">
                  {{ lang.cruds.address.add_location }}
              </a>
            </div>
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h6 class="store-title">{{ lang.cruds.address.locations }}</h6>
                    <p>{{ lang.cruds.address.locations_helper1 }}</p>
                    <p>{{ lang.cruds.address.locations_helper2 }}</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                  <!-- Basic details start -->
                <div class="card">
                    <div class="card-body border-bottom" v-for="(address, index) in addresses">
                         <div class="address-deails">
                              <div class="d-flex align-items-start mb-1">
                                <div class="mt-25 mr-75">
                                    <i data-feather='map-pin'></i>
                                </div>
                                <div class="defaul-address mb-1">
                                  <div v-if="address.locationName" class="mb-1 font-weight-bold">{{address.locationName}}</div>
                                  <div class="mb-0.5" style="margin-bottom: 5px;">
                                    <span v-if="address.address1">{{address.address1 + ' ,'}}</span>
                                    <span v-if="address.address2">{{address.address2 + ' ,'}}</span>
                                    <span v-if="address.city">{{address.city + ' ,'}}</span>
                                    <span v-if="address.stateName">{{address.stateName + ' ,'}}</span>
                                    <span v-if="address.shortCode">{{address.shortCode + ' ,'}}</span>
                                    <span v-if="address.pincode">{{address.pincode}}</span>
                                  </div>
                                  <div>
                                    <span>{{address.email}}</span>
                                  </div>
                                  <div v-if="address.phone">
                                    <span v-if="address.phoneCode">+{{ address.phoneCode }}</span>
                                    <span>{{address.phone}}</span>
                                  </div>
                                  <div class="default-badge" v-if="address.is_default == 1">
                                    <div class="badge badge-secondary">{{ lang.global.default }}</div>
                                  </div>
                                </div>
                              </div>
                              <div class="float-right">
                                <a :href="`/admin/settings/locations/` + address.id" class="btn btn-outline-secondary">{{ lang.global.change }}</a>
                                <button class="btn btn-outline-primary" v-if="address.is_default != 1" @click.prevent="makeDefaultAddress(address.id)">{{ lang.global.make }} {{ lang.global.default }}</button>
                              </div>
                          </div>
                    </div>
                    <div class="card-body" v-if="getAddressLength == 0">
                        <p class="text-center mt-1">{{ lang.global.data_not_found }}</p>
                    </div>
                </div>
            </div>
              <!-- Basic details end -->
          </div>
      </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['list', 'data','globalsettings'],
    name:'Locations',
    data() {
      return {
        addresses:[],
        states:[],
        url:createUrl
      }
    },
    mounted() {
        if($('#address_create').length != 1)
                {
                    $('#btn_create_location').remove();
                }
    },
    components: {
     
    },
    computed: {
      getAddressLength(){
        return this.addresses.length;
      }
    },
    created() {
      this.setAddress();
    },
    methods: {
      setAddress(){
        this.addresses = this.data.addresses;
      },
      makeDefaultAddress(id){
         openLoader();
          this.$store.dispatch("locationSettingsModule/MakeDefaultAddress", id)
          .then((res) => {
              if (res.response.status_code == 2053) {
                  successModal(res.response.message);
                  $.each(this.addresses, function(key, value) {
                    if(value.id == id){
                      value.is_default = 1
                    } else {
                      value.is_default = 0;
                    }
                  }); 
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

<style scoped>
.defaul-address{
  position: relative;
}
.default-badge{
  position: absolute;
  top: 0;
  right:0;
}
.border-bottom{
  border-bottom: 1px solid #ddd;
}
.store-content p {
    font-size: 13px;
    text-align: justify;
}
.store-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}
svg.feather.feather-map-pin {
    height: 2em;
    width: 2em;
}
</style>