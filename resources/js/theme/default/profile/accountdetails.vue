<template>
  <div>
     <h1 class="mb-3 text-center">{{ lang.global.profile.account_details.account_details }}</h1>
     <div class="row vert-margin">
        <div class="col-sm-9">
           <div class="card">
              <div class="card-body">
                 <h3>{{ lang.global.profile.account_details.personal_info }}</h3>
                 <p v-if="Object.keys(userData).length > 0"><span> <b>First Name:</b>  {{ userData.name }}<br> </span>
                    <span v-if="userData.last_name != null"> <b>Last Name:</b> {{ userData.last_name }}<br></span>
                    <span v-if="userData.email != null">  <b>E-mail:</b> {{ userData.email }}<br></span>
                    <span  v-if="userData.mobile != null"> <b>Phone:</b> {{ userData.mobile }} </span>
                 </p>
                 <div class="mt-2 clearfix">
                    <a href="javascript:void(0)" @click="displayEditform = true" class="link-icn js-show-form" v-if="!displayEditform"><i class="icon-pencil"></i>{{ lang.global.edit }}</a>
                 </div>
              </div>
           </div>
        </div>
     </div>
     <div class="card mt-3" v-show="displayEditform">
        <div class="card-body">
           <h3>{{ lang.global.profile.account_details.update_account_details }}</h3>
             <ValidationObserver ref="updateuserform" v-slot="{ handleSubmit, invalid, reset }">
               <form class="loginform" @submit.prevent="handleSubmit(updateUser())" @reset.prevent="resetform">
                    <div class="row mt-2">
                       <div class="col-sm-9">
                          <label class="text-uppercase label">{{ lang.global.profile.account_details.first_name }}</label>
                          <div class="form-group">
                              <ValidationProvider name="Name"  rules="required" v-slot="{ errors }">
                                 <input type="text" class="form-control input form-control--sm" v-model="first_name">
                                 <span class="error text-danger">{{ errors[0] }}</span>
                              </ValidationProvider>
                          </div>
                       </div>
                       <div class="col-sm-9">
                          <label class="text-uppercase label">{{ lang.global.profile.account_details.last_name }}</label>
                          <div class="form-group">
                             <input type="text" class="form-control input form-control--sm" v-model="last_name">
                          </div>
                       </div>
                       <div class="col-sm-9 mt-1" v-if="userData.email == null">
                          <label class="text-uppercase">{{ lang.global.profile.account_details.email }}</label>
                          <div class="form-group">
                            <ValidationProvider name="E-mail"  rules="required|email" v-slot="{ errors }">
                             <input type="text" class="form-control  form-control--sm" v-model="email">
                             <span class="error text-danger">{{ errors[0] }}</span>
                              </ValidationProvider>
                          </div>
                       </div>
                       <div class="col-sm-9 mt-1" v-if="userData.mobile == null">
                          <label class="text-uppercase">{{ lang.global.profile.account_details.mobile }}</label>
                          <div class="form-group">
                              <ValidationProvider name="E-mail"  rules="required" v-slot="{ errors }">
                                <input type="text" class="form-control form-control--sm" v-model="mobile">
                                <span class="error text-danger">{{ errors[0] }}</span>
                              </ValidationProvider>
                          </div>
                       </div>
                    </div>
                    <div class="mt-2">
                       <button type="reset" id="resetform" class="btn btn--alt" @click="displayEditform = false">{{ lang.global.cancel }}</button>
                       <button type="submit" class="btn ml-1">{{ lang.global.update }}</button>
                    </div>
                 </form>
              </ValidationObserver>
        </div>
     </div>
  </div>
</template>

<script>

import { mapState } from 'vuex'

  export default {
    name: "detailssection",
    data() {
      return {
        cruds: [],
        first_name: null,
        last_name: null,
        email: null,
        mobile: null,
        displayEditform: false,
      }
    },
    computed: {
      ...mapState(['globalStore']),
      userData(){
         let data = this.globalStore.user
         this.first_name = data.name;
         this.last_name = data.last_name;
         this.email = data.email;
         this.mobile = data.mobile;
         return data;
      }
      
    },
    methods: {
     updateUser(){
      this.$refs.updateuserform.validate().then(success => {
          if (!success) {
            return;
          }
          openLoader();
          const  payload = {
            firstname: this.first_name,
            last_name: this.last_name,
            email: this.email,
            mobile: this.mobile
          };

          this.$store.dispatch("globalStore/updateUser", payload)
           .then((res) => {
                closeLoader();
                if (res.response.status_code == 1007) {
                     successModal(res.response.message);
                     this.resetform();
                     $("#resetform").trigger('click');
                }
                else if(res.response.status_code == 7005)
                {
                   errorModal(res.response.message);
                }
           })
            .catch((err) => {
              closeLoader();
              errorModal(err.response.message);
           });

       });
     },
     resetform(){
        this.$refs.updateuserform.reset();
      },
    },
    components: { 
      
    }
  }
</script>
