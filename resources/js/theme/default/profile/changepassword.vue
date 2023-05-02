<template>
  <div>
    <div class="card">
         <div class="card-body">
            <h3> {{ lang.global.profile.change_password.change_password }}</h3>
             <ValidationObserver ref="chanegpasswordform" v-slot="{ handleSubmit, invalid, reset }">
               <form class="loginform" @submit.prevent="handleSubmit(changepassword())" @reset.prevent="resetform">
                  <div class="row" v-if="!userData.sociallogin">
                     <div class="col-sm-18">
                        <label class="text-uppercase label">{{ lang.global.profile.change_password.old_password }}</label>
                        <div class="form-group">
                          <ValidationProvider name="Password"  rules="required" v-slot="{ errors }">
                               <input type="password" placeholder="Enter password" class="form-control input" v-model="oldpassword" />
                                <span class="error text-danger">{{ errors[0] }}</span>
                           </ValidationProvider>
                        </div>
                     </div>
                  </div>
                  <div class="row mt-2">
                     <div class="col-sm-9">
                        <label class="text-uppercase label">{{ lang.global.profile.change_password.new_password }}</label>
                        <div class="form-group">
                           <ValidationProvider name="New Password"  rules="required" v-slot="{ errors }">
                               <input type="password" placeholder="Enter password" class="form-control input" v-model="newpassword" />
                                <span class="error text-danger">{{ errors[0] }}</span>
                           </ValidationProvider>
                        </div>
                     </div>
                     <div class="col-sm-9">
                        <label class="text-uppercase label">{{ lang.global.profile.change_password.confirm_password }}</label>
                        <div class="form-group">
                           <ValidationProvider name="Confirm Password"  rules="required|confirmed:New Password" v-slot="{ errors }">
                               <input type="password" placeholder="Enter confirm Password" class="form-control input" v-model="confirmpassword" />
                                <span class="error text-danger">{{ errors[0] }}</span>
                           </ValidationProvider>
                        </div>
                     </div>
                  </div>
                  <div class="mt-2">
                     <button type="reset" class="btn btn--alt">{{ lang.global.cancel }}</button>
                     <button type="submit" class="btn ml-1">{{ lang.global.profile.change_password.change_password }}</button>
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
    name: "changepassword",
    data() {
      return {
        cruds: [],
        opensection: 'detailssection',
        oldpassword: null,
        newpassword: null,
        confirmpassword: null,

      }
    },
    computed: {
      ...mapState(['globalStore']),
      userData(){
         return this.globalStore.user;
      }
    },
    mounted(){
      
    },
    methods: {
      changepassword(){
         this.$refs.chanegpasswordform.validate().then(success => {
          if (!success) {
            return;
          }
          openLoader();
          const  payload = {
            password: this.oldpassword,
            newpassword: this.newpassword,
          };
          this.$store.dispatch("globalStore/ChangePassword", payload)
           .then((res) => {
                closeLoader();
                if (res.response.status_code == 1000) {
                     successModal(res.response.message);
                     this.resetform();
                }
                else if(res.response.status_code == 7001)
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
        this.oldpassword = null;
        this.newpassword = null;
        this.confirmpassword = null;
        this.$refs.chanegpasswordform.reset();
      },
    },
    components: { 
      
    }
  }
</script>
<style lang="scss" scoped>
     .input:focus{
  box-shadow:0 0 0 2px rgba(0,119,255,0.2) !important;
  outline: none !important;
  background-color:transparent !important;
}

</style>