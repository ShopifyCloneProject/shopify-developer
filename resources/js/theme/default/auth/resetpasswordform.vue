<template>
   <div>
      <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/"> {{ lang.global.home }} </a></li>
               <li><span> {{ lang.global.resetpassword.reset_password }} </span></li>
            </ul>
         </div>
      </div>
      <!-- Reset password start-->
      <div class="holder content  pt-4" id="resetPassword">
         <div class="container">
            <div class="row">
               <div class="col-md-9 order-md-2">
                  <img src="/theme/default/images/login-banner.svg" alt="Image" class="img-fluid">
               </div>
               <div class="col-md-9 contents">
                  <div class="row justify-content-center">
                     <div class="col-md-12" v-if="!isChangeSuccess">
                        <div class="mb-4">
                           <h2> {{ lang.global.resetpassword.reset_password }} </h2>
                           <p class="mb-2"> {{ lang.global.resetpasswordform.new_password_different_from_previous }} </p>
                        </div>
                        <!-- <p class="text-danger mb-2 text-center login-error" style="display:none;"></p> -->
                        <ValidationObserver ref="resetPasswordForm" v-slot="{ handleSubmit, invalid, reset }">
                           <form class="resetPasswordForm" @submit.prevent="handleSubmit(resetPassword())" @reset.prevent="resetlogin">
                              <div class="form-group">
                                 <ValidationProvider name="Email"  rules="required|email" v-slot="{ errors }">
                                    <input type="text"  class="form-control input" placeholder="Enter Email" v-model="formData.email" />
                                    <span class="error text-danger">{{ errors[0] }}</span>
                                 </ValidationProvider>
                              </div>
                              <div class="form-group">
                                 <ValidationProvider name="Password" rules="required|min:8|max:16|regex:(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*[^A-Za-z0-9])" vid="password"v-slot="{ errors, failedRules }"
                                    >
                                    <div class="mb-30">
                                       <div class="form-group">
                                          <label> {{ lang.global.login.password }} </label>
                                          <div class="input-with-icon">
                                             <input type="password"  class="form-control input" v-model="formData.password" placeholder="Enter Your Password" />
                                             <i class="theme-cl ti-lock"></i>
                                          </div>
                                       </div>
                                       <p class="error text-danger" v-if="failedRules.required"> {{ lang.global.resetpasswordform.password_required }} </p>
                                       <!-- <p class="error" v-if="failedRules.min">Must be at least 8 characters</p> -->
                                       <p class="error text-danger" v-if="failedRules.max"> {{ lang.global.resetpasswordform.password_size_limit }} </p>
                                       <p class="error lregex text-danger" v-if="(failedRules.min >= '1' || failedRules.regex || failedRules.max >= '16')"> {{ lang.global.resetpasswordform.password_invalid }} <br> {{ lang.global.resetpasswordform.password_contain_letters }} <br> {{ lang.global.resetpasswordform.password_contain_case }} <br> {{ lang.global.resetpasswordform.password_contain_digit }} <br> {{ lang.global.resetpasswordform.password_size }} </p>
                                       <!-- <p class="error">{{ errors[0] }}</p> -->
                                    </div>
                                 </ValidationProvider>
                              </div>
                              <div class="form-group mb-2">
                                 <ValidationProvider name="Confirm Password" rules="required|confirmed:password" v-slot="{ errors }" >
                                    <div class="mb-30">
                                       <div class="form-group">
                                          <label> {{ lang.global.login.confirm_password }} </label>
                                          <div class="input-with-icon">
                                             <input type="password"  class="form-control input" v-model="formData.password_confirmation" placeholder="Enter Your Password" />
                                             <i class="theme-cl ti-lock"></i>
                                          </div>
                                       </div>
                                       <p class="error text-danger">{{ errors[0] }}</p>
                                    </div>
                                 </ValidationProvider>
                              </div>
                              <button type="submit" class="btn text-white btn-block btn-primary social-login signin" id="btnResetPassword"> {{ lang.global.resetpassword.reset_password }} </button>
                              <input type="reset" id="loginreset" class="opacity-0" >
                              <div class="text-light-custom"> {{ lang.global.resetpassword.already_account }} <span class="pointer font-weight-bold" @click="gotoForm('login')"> {{ lang.global.login.click_here }} </span></div>
                           </form>
                        </ValidationObserver>
                     </div>

                     <div class="col-md-12" v-else>
                       <div class="mb-4">
                           <h2> {{ lang.global.resetpasswordform.password_successfully_reset }} </h2>
                           <p class="mb-2"> {{ lang.global.resetpasswordform.password_changed_successfully }} <a href="/login"> {{ lang.global.login.click_here }} </a> {{ lang.global.resetpasswordform.tologin }} </p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Reset password end-->
   </div>
</template>
<script>
   import { mapState } from 'vuex'
   
     export default {
      props: ['data'],
      name: 'ResetPasswordForm',
       data() {
         return {
            isChangeSuccess:false,
            formData:{
               token: '',
               email: '',
               password: '',
               password_confirmation: '',
            }
         }
       },
       created(){
         this.formData.token = this.data.token;
         this.formData.email = this.data.email;
       },
       methods: {
         resetPassword() {
            this.$refs.resetPasswordForm.validate().then(success => {
               if (!success) {
                  return;
               }
      
               let section = $('#btnResetPassword');
               blockSection(section);
               this.$store.dispatch("globalStore/ResetPasswordForm", this.formData)
                  .then((res) => {
                    if (res.response.status_code == 1010) {
                        this.$toast.open({
                           message: res.response.message,
                           type: 'success',
                        });
                       this.isChangeSuccess = true;
                     } else if(res.response.status_code == 1011) {
                        this.$toast.open({
                           message: res.response.message,
                           type: 'error',
                        });
                     } else {
                        this.$toast.open({
                           message: 'Something went wrong.',
                           type: 'error',
                        });
                     }
                     unblockSection(section);
                  })
                  .catch((err) => {
                     this.$toast.open({
                        message: err,
                        type: 'error',
                     });
                     unblockSection(section);
                  });
            });
         }
       }
     }
</script>
<style lang="scss" scoped>

   
   .input:focus{
  box-shadow:0 0 0 2px rgba(0,119,255,0.2) !important;
  outline: none !important;
  background-color:transparent !important;
}
   @media (max-width: 991.98px) {
   .content .bg {
   height: 500px;
   }
   }
   .content .contents,
   .content .bg {
   width: 50%;
   }
   @media (max-width: 1199.98px) {
   .content .contents,
   .content .bg {
   width: 100%;
   }
   }
   .content .contents .form-group,
   .content .bg .form-group {
   position: relative;
   }
   .content .contents .form-group input,
   .content .bg .form-group input {
   background: transparent;
   border-bottom: 1px solid #ccc !important;
   }
   .content .contents .form-group span,
   .content .bg .form-group span {
   font-size: 14px;
   display: block;
   margin-bottom: 0;
   color: #b3b3b3;
   }
   .content .contents .form-group span input,
   .content .bg .form-group span input{
   width: 100%;
   border: none;
   padding: 10px 0;
   }
   .content .contents .form-group.focus,
   .content .bg .form-group.focus {
   background: #fff;
   }
   .content .contents .form-group.field--not-empty label,
   .content .bg .form-group.field--not-empty label {
   margin-top: -25px;
   }
   .content .contents .form-control:active,
   .content .contents .form-control:focus,
   .content .bg .form-control:active,
   .content .bg .form-control:focus {
   outline: none;
   -webkit-box-shadow: none;
   box-shadow: none;
   }
   .content .bg {
   background-size: cover;
   background-position: center;
   }
   .content a {
   color: #888;
   text-decoration: underline;
   }
   .content .btn {
   height: 54px;
   padding-left: 30px;
   padding-right: 30px;
   }
   .content .forgot-pass {
   position: relative;
   top: -4px;
   font-size: 14px;
   }
   .control {
   display: block;
   position: relative;
   padding-left: 30px;
   margin-bottom: 15px;
   cursor: pointer;
   font-size: 14px;
   }
   .control .caption {
   position: relative;
   top: 0.2rem;
   color: #888;
   }
   .control input {
   position: absolute;
   z-index: -1;
   opacity: 0;
   }
   .text-light-custom{
   color:#888;
   }
   input[type='checkbox'] + label:before, input[type='radio'] + label:before{
   border-color: #bbb;
   background-color: #ddd;
   }
   .content .contents{
   border-right:2px solid #ddd;
   }
   @media (max-width: 767px) {
   h2{
   text-align:center;
   margin-top:30px;
   }
   .content .contents{
   border-right:none;
   }
   }
</style>