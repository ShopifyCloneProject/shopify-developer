<template>
  <div id="input-sizing">
    <ValidationObserver ref="locationForm" v-slot="{ handleSubmit }">
      <form method="POST" enctype="multipart/form-data" id="frmAddEditLocations" @submit.prevent="handleSubmit(submit())">
          <div class="row">
            <div class="col-12 mb-2">
              <a :href="url" class="back-url" v-if="type == 'edit'">
                  <i data-feather='arrow-left-circle'></i> {{ beforeEdit.locationName }}
              </a>
              <a :href="url" class="back-url" v-else>
                  <i data-feather='arrow-left-circle'></i> {{ lang.cruds.address.add_location }}
              </a>
            </div>
            <div class="col-md-4 col-12">
                <div class="store-content">
                    <h6 class="store-title">{{ lang.cruds.address.detail }}</h6>
                    <p><small>{{ lang.cruds.address.detail_helper }}</small></p>
                </div>
            </div>
            <div class="col-md-8 col-12">
                  <!-- Basic details start -->
                  <div class="card">
                      <div class="card-body">
                         <div class="row">
                            <div class="col-12">
                              <ValidationProvider  name="Location name" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                    <label class="required" for="locationName">{{ lang.cruds.address.fields.location_name }}</label>
                                    <input class="form-control" type="text" v-model="formData.locationName">
                                    <p class="text-danger">{{ errors[0] }}</p>
                                </div>
                              </ValidationProvider>
                            </div>
                         </div>
                      </div>
                  </div>
                  <!-- Basic details end -->
            </div>
          </div>
          <hr />
          <div class="row">
              <div class="col-md-4 col-12">
                  <div class="store-content">
                      <h6 class="store-title">{{ lang.cruds.address.title_singular }}</h6>
                  </div>
              </div>
              <div class="col-md-8 col-12">
                    <!-- Basic details start -->
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                              <div class="col-12">
                                <div>
                                  <ValidationProvider  name="address" rules="required|min:10|max:80" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label class="required" for="address">{{ lang.cruds.address.fields.address }}</label>
                                        <input class="form-control" type="text" v-model="formData.address1">
                                        <p class="text-danger">{{ errors[0] }}</p>
                                    </div>
                                  </ValidationProvider>
                                </div>
                                <div>
                                  <ValidationProvider  name="Apartment, suite" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                      <label class="required" for="address_2">{{ lang.cruds.address.fields.address_2 }}</label>
                                      <input class="form-control" type="text" v-model="formData.address2">
                                      <p class="text-danger">{{ errors[0] }}</p>
                                    </div>
                                  </ValidationProvider>
                                </div>
                                <div>
                                  <div class="row">
                                    <div class="col-6">
                                      <ValidationProvider  name="City" rules="required" v-slot="{ errors }">
                                        <div class="form-group">
                                          <label class="required" for="city_name">{{ lang.cruds.address.fields.city_name }}</label>
                                          <input class="form-control" type="text" v-model="formData.city">
                                          <p class="text-danger">{{ errors[0] }}</p>
                                        </div>
                                      </ValidationProvider>
                                    </div>
                                    <div class="col-6">
                                      <ValidationProvider  name="Email" rules="required" v-slot="{ errors }">
                                        <div class="form-group">
                                          <label class="required" for="email">{{ lang.cruds.address.fields.email }}</label>
                                          <input class="form-control" type="text" v-model="formData.email">
                                          <p class="text-danger">{{ errors[0] }}</p>
                                        </div>
                                      </ValidationProvider>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                               <div class="col-md-4">
                                  <ValidationProvider  name="Country" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label class="required" for="country_id">{{ lang.cruds.address.fields.country }}</label>
                                        <select class="custom-select" v-model="formData.country" @change="getState()">
                                          <option :value="ldata.id" v-for="(ldata, index) in list.countries">{{ ldata.name }}</option>
                                        </select>
                                        <p class="text-danger">{{ errors[0] }}</p>
                                    </div>
                                  </ValidationProvider>
                                </div>
                                <div class="col-md-4" id="stateList">
                                <ValidationProvider  name="State" rules="required" v-slot="{ errors }">   
                                    <div class="form-group">
                                        <label class="required" for="state_id">{{ lang.cruds.address.fields.state }}</label>
                                        <select class="custom-select" v-model="formData.state">
                                            <option :value="index" v-for="(ldata, index) in states">{{ ldata }}</option>
                                        </select>
                                        <p class="text-danger">{{ errors[0] }}</p>
                                    </div>
                                  </ValidationProvider>
                                </div>
                                <div class="col-md-4">
                                  <ValidationProvider  name="Postal code" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label class="required" for="postal_code">{{ lang.cruds.address.fields.postal_code }}</label>
                                        <input class="form-control" type="text" v-model="formData.pincode">
                                        <p class="text-danger">{{ errors[0] }}</p>
                                    </div>
                                  </ValidationProvider>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-10">
                                  <ValidationProvider  name="Phone" rules="required|max:10" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label class="required" for="phone">{{ lang.cruds.address.fields.mobile }}</label>
                                          <div class="input-group">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text" v-if="formData.phoneCode">+{{ formData.phoneCode }}</span>
                                              </div>
                                              <input class="form-control" type="number" v-model="formData.phone" id="phone">
                                          </div>
                                          <p class="text-danger">{{ errors[0] }}</p>
                                      </div>
                                  </ValidationProvider>
                                </div>
                                <div class="col-md-2">
                                  <ValidationProvider  name="Mobile" rules="required" v-slot="{ errors }">
                                    <div class="form-group">
                                        <label class="required" for="mobile"></label>
                                        <select class="custom-select" v-model="formData.phoneCode">
                                          <option value="" disabled="disabled">{{ lang.cruds.address.fields.country }}</option>
                                          <option :value="ldata.phone_code" v-for="(ldata, phone_code) in list.countries">{{ ldata.name + '(+' + ldata.phone_code + ')' }}</option>
                                        </select>
                                        <p class="text-danger">{{ errors[0] }}</p>
                                    </div>
                                  </ValidationProvider>
                                </div>
                            </div>
                        </div>
                    </div>
              </div>
              <!-- Basic details end -->
          </div>
          <div class="form-group float-right">
              <button class="btn btn-primary waves-effect waves-light" type="submit">
                  {{ lang.global.save }}
              </button>
              <button class="btn btn-primary waves-effect waves-light"  type="button" @click="cancel()">
                  {{ lang.global.cancel }}
              </button>
              
          </div>
      </form>
      </ValidationObserver>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: ['list', 'data', 'type','globalsettings'],
    name:'AddLocation',
    data() {
      return {
        formData:{
          locationName:'',
          address1:'',
          address2:'',
          phone:'',
          country:'',
          city:'',
          email:'',
          state:'',
          pincode:'',
          phoneCode:'',
        },
        states:[],
        beforeEdit:{},
        url:locationUrl
      }
    },
    mounted(){
      this.formData.country = this.globalsettings.DEFAULT_COUNTRY;
    },
    computed: {
    },
    created() {
      this.setFormData();
      this.beforeEdit = { ...this.formData };
    },
    methods: {
      setFormData(){
          let address = this.data.address;
          this.beforeEdit = {...address };
          this.formData = {...address };
          this.getState();

      },
      submit(){
           this.$refs.locationForm.validate().then(success => {
            if (!success) {
              $("html, body").animate({ scrollTop: 50 }, 200);
              return;
            }
        openLoader();{
        this.$store.dispatch("locationSettingsModule/AddEditLocation", this.formData)
          .then((res) => {
              if (res.response.status_code == 1002) {
                  successModal(res.response.message);
                  this.formData = {...this.beforeEdit};
                  setTimeout(function(){
                      window.location = res.response.data.url;
                  },2000);
              }
              else if (res.response.status_code == 1003) {
                  successModal(res.response.message);
                  this.beforeEdit = {...this.formData };
                   setTimeout(function(){
                       window.location = res.response.data.url;
                  },2000);
                 
              }
              else if (res.response.status_code == 500) {
                errorModal(res.response.message);
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
        }
      });
      },
      getState(){
          let section = $('#stateList');
          blockSection(section);
          let countryId = this.formData.country;
          this.$store.dispatch("locationSettingsModule/GetStates", countryId)
          .then((res) => {
              if (res.response.status_code == 2046) {
                  this.states = res.response.data;
              }
              else
              {
                errorModal(res.response.message);
              }
              unblockSection(section);
          })
          .catch((err) => {
            unblockSection(section);
            errorModal(err.response.message);
          });
      },
      cancel(){
           location.reload();
        }
    }
  }
</script>

<style scoped>
.back-url{
    font-size: 18px;
    color: #5e5873;
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