<template>
   <div>
      <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/"> {{ lang.global.home }} </a></li>
               <li v-if="displayLoginData"><span> {{ lang.global.login.login }} </span></li>
               <li v-if="!displayLoginData"><span>{{ lang.global.login.register }} </span></li>
            </ul>
         </div>
      </div>
      <div class="holder content  pt-4" id="login"  v-show="displayLoginData">
         <div class="container">
            <div class="row">
               <div class="col-md-9 order-md-2">
                  <img src="/theme/default/images/login-banner.svg" alt="Image" class="img-fluid">
               </div>
               <div class="col-md-9 contents">
                  <div class="row justify-content-center">
                     <div class="col-md-12">
                        <div class="mb-4">
                           <h2>{{ lang.global.login.signin }} </h2>
                           <p class="mb-2 d-none">Lorem ipsum dolor sit amet elit. Sapiente sit aut eos consectetur adipisicing.</p>
                        </div>
                        <p class="text-danger mb-2 text-center login-error" style="display:none;"></p>
                        <ValidationObserver ref="loginform" v-slot="{ handleSubmit, invalid, reset }">
                           <form class="loginform" @submit.prevent="handleSubmit(login())" @reset.prevent="resetlogin">
                              <div class="username mb-3">
                                 <ValidationProvider name="Email or Mobile"  rules="required" v-slot="{ errors }">
                                    <label>{{ lang.global.login.username }} <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                       <input type="text" class="form-control input" placeholder="Enter Email or mobile" v-model="loginusername">
                                       <span class="error text-danger">{{ errors[0] }}</span>
                                    </div>
                                 </ValidationProvider>
                              </div>
                              <div class="password mb-3">
                                 <ValidationProvider name="Password"  rules="required" v-slot="{ errors }">
                                    <label>{{ lang.global.login.password }} <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                       <input type="password" class="form-control input" placeholder="Enter password" v-model="loginpassword" />
                                       <span class="error text-danger">{{ errors[0] }}</span>
                                    </div>
                                 </ValidationProvider>
                              </div>
                              <div class="d-flex mb-5 align-items-center">
                                 <span class="mb-0 remember-checkbox">
                                 <input id="rememberMe" name="rememberMe" type="checkbox">
                                 <label for="rememberMe" class="text-light-custom"> {{ lang.global.login.rememberme }} </label>
                                 </span>
                                 <span class="ml-auto"><a href="/password/reset" class="forgot-pass"> {{ lang.global.login.forgot_password }} </a></span> 
                              </div>
                              <button type="submit" class="btn text-white btn-block btn-primary social-login signin"> {{ lang.global.login.signin }} </button>
                              <input type="reset" id="loginreset" class="opacity-0" >
                              <div class="text-light-custom"> {{ lang.global.login.have_not_account }} <span class="pointer font-weight-bold" @click="gotoForm('register')"> {{ lang.global.login.click_here }} </span></div>
                              <span class="text-left my-4 text-muted d-none"> {{ lang.global.login.sign_in_with }} </span>
                              <div class="social-login d-none">
                                 <a href="#" class="facebook">
                                 <span class="icon-facebook mr-3"></span> 
                                 </a>
                                 <a href="#" class="twitter">
                                 <span class="icon-twitter mr-3"></span> 
                                 </a>
                                 <a href="#" class="google">
                                 <span class="icon-google mr-3"></span> 
                                 </a>
                              </div>
                           </form>
                        </ValidationObserver>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Register start -->
      <div class="holder content  pt-4" id="register" v-show="!displayLoginData">
         <div class="container">
            <div class="row">
               <div class="col-md-9 order-md-2">
                  <img src="/theme/default/images/login-banner.svg" alt="Image" class="img-fluid">
               </div>
               <div class="col-md-9 contents">
                  <div class="row justify-content-center">
                     <div class="col-md-12">
                        <div class="mb-4">
                           <h2> {{ lang.global.login.signup }} </h2>
                           <p class="mb-2 d-none">Lorem ipsum dolor sit amet elit. Sapiente sit aut eos consectetur adipisicing.</p>
                        </div>
                        <ValidationObserver ref="registerform" v-slot="{ handleSubmit, invalid, reset }">
                           <form class="loginform" @submit.prevent="handleSubmit(register())" @reset.prevent="resetregister">
                              <div class="firstname  mb-2">
                                 <ValidationProvider name="Name"  rules="required" v-slot="{ errors }">
                                    <label> {{ lang.global.login.name }} <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                       <input type="text" class="form-control input" placeholder="Enter name" v-model="firstname">
                                       <span class="error text-danger">{{ errors[0] }}</span>
                                    </div>
                                 </ValidationProvider>
                              </div>
                              <div class="email mb-2" v-if="data.askEmail">
                                 <ValidationProvider name="Email"  rules="required" v-slot="{ errors }">
                                    <label> {{ lang.global.login.email }} <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                       <input type="email" class="form-control input" placeholder="Enter email" v-model="email">
                                       <span class="error text-danger">{{ errors[0] }}</span>
                                    </div>
                                 </ValidationProvider>
                              </div>
                              <div class="mobile mb-2" v-if="data.askMobile">
                                 <ValidationProvider name="Mobile"  rules="required" v-slot="{ errors }">
                                    <label> {{ lang.global.login.mobile }} <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                       <input type="number" class="form-control input" placeholder="Enter mobile" v-model="mobile" />
                                       <span class="error text-danger">{{ errors[0] }}</span>
                                    </div>
                                 </ValidationProvider>
                              </div>
                              <div class="password mb-2">
                                 <ValidationProvider name="RePassword"  rules="required" v-slot="{ errors }">
                                    <label> {{ lang.global.login.password }} <span class="text-danger">*</span></label>
                                       <div class="form-group">
                                          <input type="password" class="form-control input" placeholder="Enter password" v-model="password" />
                                          <span class="error text-danger">{{ errors[0] }}</span>
                                       </div>
                                 </ValidationProvider>
                              </div>
                              <div class="confirmpassword mb-4">
                                 <ValidationProvider name="Confirm Password"  rules="required|confirmed:RePassword" v-slot="{ errors }">
                                    <label> {{ lang.global.login.confirm_password }} <span class="text-danger">*</span></label>
                                       <div class="form-group">
                                    <input type="password" class="form-control input" placeholder="Enter confirm Password" v-model="confirmpassword" />
                                    <span class="error text-danger">{{ errors[0] }}</span>
                                 </div>
                                 </ValidationProvider>
                              </div>
                              <button type="submit" class="btn text-white btn-block btn-primary social-login register" > {{ lang.global.login.signup }} </button>
                              <input type="reset" id="registerreset" class="opacity-0" >
                              <div class="text-light-custom"> {{ lang.global.login.already_have_account }} <span class="pointer font-weight-bold" @click="gotoForm('login')"> {{ lang.global.login.sign_in_here }} </span></div>
                              <span class="text-left my-4 text-muted d-none"> {{ lang.global.login.or_register_with }} </span>
                              <div class="social-login d-none">
                                 <a href="#" class="facebook">
                                 <span class="icon-facebook mr-3"></span> 
                                 </a>
                                 <a href="#" class="twitter">
                                 <span class="icon-twitter mr-3"></span> 
                                 </a>
                                 <a href="#" class="google">
                                 <span class="icon-google mr-3"></span> 
                                 </a>
                              </div>
                           </form>
                        </ValidationObserver>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Register end -->
   </div>
</template>
<script>
   import { mapState } from 'vuex'
   
     export default {
      props: ['data'],
      name: 'Login',
       data() {
         return {
           cruds: [],
           loginusername: null,
           loginpassword: null,
           displayLoginData: true,
           firstname: null,
           email: null,
           mobile: null,
           password: null,
           confirmpassword: null,
   
         }
       },
       computed: {
         
       },
       created(){
       },
       mounted(){
         
       },
       methods: {
        gotoForm(status)
        {
         if(status == 'login')
         {
            this.resetlogin();
            this.displayLoginData = true;
         }
         else
         {
            this.resetregister();
            this.displayLoginData = false;
         }
        },
        login(){
         this.$refs.loginform.validate().then(success => {
             if (!success) {
               return;
             }
            let section = $(".signin");
             blockSection(section);
             const  payload = {
               username: this.loginusername,
               password: this.loginpassword
             };
              this.$store.dispatch("globalStore/Login", payload)
              .then((res) => {
                   unblockSection(section);
                   if (res.response.status_code == 1006) {
                        this.$toast.open({
                           message: res.response.message,
                           type: 'success',
                        });
                       
                        this.resetlogin();
                        location.replace("/");
                   }
                   else if(res.response.status_code == 7005)
                   {
                      this.$toast.open({
                         message: res.response.message,
                         type: 'error',
                      });

                      $('.login-error').html(res.response.message).show();
                      setTimeout(function(){
                         $('.login-error').html('').hide();
                      },2000);
                   }
              })
               .catch((err) => {
                 unblockSection(section);
                  this.$toast.open({
                           message: err.response.message,
                           type: 'error',
                       });
              });
   
           });
        },
         register(){
           this.$refs.registerform.validate().then(success => {
             if (!success) {
               return;
             }
             let section = $(".register");
             blockSection(section);
             const  payload = {
               firstname: this.firstname,
               username: this.username,
               email: this.email,
               mobile: this.mobile,
               password: this.password
             };
             this.$store.dispatch("globalStore/Register", payload)
              .then((res) => {
                   unblockSection(section);
                   if (res.response.status_code == 1004) {
                        this.resetregister();
                        this.displayLoginData = true;
                       this.$toast.open({
                           message: res.response.message,
                           type: 'success',
                       });
                   }
                   else if(res.response.status_code == 1005)
                   {
                       this.$toast.open({
                           message: res.response.message,
                           type: 'error',
                       });
                   }
                   
              })
               .catch((err) => {
                 unblockSection(section);
                 this.$toast.open({
                     message: err.response.message,
                     type: 'error',
                 });
              });
             
             
           });
         },
         resetregister(){
           this.firstname = null,
           this.username = null,
           this.email = null,
           this.mobile = null,
           this.password = null,
           this.confirmpassword = null,
           this.$refs.registerform.reset();
         },
         resetlogin(){
           this.loginusername = null;
           this.loginpassword = null;
           this.$refs.loginform.reset();
         }
       },
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
   .social-login a {
   text-decoration: none;
   position: relative;
   text-align: center;
   color: #fff;
   margin-bottom: 10px;
   width: 50px;
   height: 50px;
   border-radius: 50%;
   display: inline-block;
   }
   .social-login a span {
   position: absolute;
   top: 50%;
   left: 50%;
   -webkit-transform: translate(-50%, -50%);
   -ms-transform: translate(-50%, -50%);
   transform: translate(-50%, -50%);
   }
   .social-login a:hover {
   color: #fff;
   }
   .social-login a.facebook {
   background: #3b5998;
   }
   .social-login a.facebook:hover {
   background: #344e86;
   }
   .social-login a.twitter {
   background: #1da1f2;
   }
   .social-login a.twitter:hover {
   background: #0d95e8;
   }
   .social-login a.google {
   background: #ea4335;
   }
   .social-login a.google:hover {
   background: #e82e1e;
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