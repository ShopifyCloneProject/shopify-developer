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
                     <div class="col-md-12" v-if="!isMailSent">
                        <div class="mb-4">
                           <h2> {{ lang.global.resetpassword.forgot_your_password }} </h2>
                           <p class="mb-2">Lorem ipsum dolor sit amet elit. Sapiente sit aut eos consectetur adipisicing.</p>
                        </div>
                        <p class="text-danger mb-2 text-center login-error" style="display:none;"></p>
                        <ValidationObserver ref="resetPasswordForm" v-slot="{ handleSubmit, invalid, reset }">
                           <form class="resetPasswordForm" @submit.prevent="handleSubmit(resetPassword())" @reset.prevent="resetlogin">
                                  <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                                    <label> {{ lang.global.login.username }} <span class="text-danger">*</span></label>
                                    <div class="form-group mb-2">
                                       <input type="text" class="form-control input" placeholder="Enter Email" v-model="email">
                                       <span class="error text-danger">{{ errors[0] }}</span>
                                    </div>
                                 </ValidationProvider>
                               
                             
                              <button type="submit" class="btn text-white btn-block btn-primary social-login signin" id="btnResetPassword"> {{ lang.global.resetpassword.reset_password }} </button>
                              <input type="reset" id="loginreset" class="opacity-0" >
                              <div class="text-light-custom"> {{ lang.global.resetpassword.already_account }} <span class="pointer font-weight-bold" @click="gotoForm('login')"> {{ lang.global.login.click_here }} </span></div>
                           </form>
                        </ValidationObserver>
                     </div>
                     <div class="col-md-12" v-else>
                           <div class="mb-4">
                               <h2> {{ lang.global.resetpassword.email_sent }} </h2>
                               <p> {{ lang.global.resetpassword.reset_password_instructions }} </p>
                               <a href="/login" class="mt-1 font-weight-bold d-block"> {{ lang.global.resetpassword.click_to }} {{ lang.global.login.login }} </a>
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
      name: 'ResetPassword',
       data() {
         return {
            isMailSent:false,
            email:''
         }
       },
       methods: {
         resetPassword() {
            this.$refs.resetPasswordForm.validate().then(success => {
               if (!success) {
                  return;
               }
               let section = $('#btnResetPassword');
               blockSection(section);
               this.$store.dispatch("globalStore/ResetPassword", this.email)
                  .then((res) => {
                    if (res.response.status_code == 1008) {
                        this.$toast.open({
                           message: res.response.message,
                           type: 'success',
                        });
                      this.isMailSent = true;
                    } else if (res.response.status_code == 1009 || res.response.status_code == 1007) {
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