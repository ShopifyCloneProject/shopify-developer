<template>
  <div id="input-sizing">
      <form id="frmAddEditLocations">
          <div class="row">
            <div class="col-12 mb-2 text-right">
              <button class="btn btn-primary waves-effect waves-light" @click.prevent="changeComponent()">
                  Add location
              </button>
            </div>
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h6 class="store-title">Locations</h6>
                    <p>Manage the places you stock inventory, fulfill orders, and sell products.</p>
                    <p>You’re using 2 of 8 locations available on your plan.</p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                  <!-- Basic details start -->
                <div class="card">
                    <div class="card-body border-bottom" v-for="(address, index) in addresses">
                         <div class="address-deails">
                             <div class="defaul-address mb-1">
                                <div v-if="address.locationName"><b>{{address.locationName}}</b></div>
                                <div>
                                  <span v-if="address.address1">{{address.address1 + ' ,'}}</span>
                                  <span v-if="address.address2">{{address.address2 + ' ,'}}</span>
                                  <span v-if="address.city">{{address.city + ' ,'}}</span>
                                  <span v-if="address.stateName">{{address.stateName + ' ,'}}</span>
                                  <span v-if="address.shortCode">{{address.shortCode + ' ,'}}</span>
                                  <span v-if="address.pincode">{{address.pincode}}</span>
                                </div>
                                <div v-if="address.phone">
                                  <span v-if="address.phoneCode">+{{ address.phoneCode }}</span>
                                  <span>{{address.phone}}</span>
                                </div>
                                <div class="default-badge" v-if="address.is_default == 1">
                                  <div class="badge badge-secondary">Default</div>
                                </div>
                              </div>
                              <div class="float-right">
                                <a href="" class="btn btn-outline-secondary" @click.prevent="changeComponent()">Change</a>
                                <button class="btn btn-outline-secondary" v-if="address.is_default != 1" @click.prevent="makeDefaultAddress(address.id)">Make Default</button>
                              </div>
                          </div>
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
    props: ['list', 'addresses','globalsettings'],
    name:'LocationsList',
    data() {
      return {
      }
    },
    computed: {

    },
    created() {
    },
    methods: {
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
      changeComponent(){
        this.$emit('change-component', {name:'Create'})
      }
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
hr{
  margin-bottom: 3rem;
}
</style>