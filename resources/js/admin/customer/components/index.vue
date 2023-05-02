<template>
    <div id="input-sizing">
        <ValidationObserver ref="customerForm" v-slot="{ handleSubmit }">
            <form method="POST" enctype="multipart/form-data" id="frmCustomer" @submit.prevent="handleSubmit(submit())">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="required" for="costomer_name">{{ lang.cruds.user.fields.name }}</label>
                                            <input class="form-control" type="text" placeholder="Enter name" v-model="formData.name">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="required" for="last_name">{{ lang.cruds.user.fields.last_name }}</label>
                                            <input class="form-control" type="text" placeholder="Enter last name" v-model="formData.last_name">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <ValidationProvider name="mobile" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="last_name">{{ lang.cruds.user.fields.mobile }}</label>
                                                <input class="form-control" type="number" placeholder="Enter mobile" v-model="formData.mobile">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="required" for="username">{{ lang.cruds.user.fields.username }}</label>
                                            <input class="form-control" type="text" placeholder="Enter username" v-model="formData.username">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <ValidationProvider name="email" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="email">{{ lang.cruds.user.fields.email }}</label>
                                                <input class="form-control" type="email" placeholder="Enter email" v-model="formData.email">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="required" for="password">{{ lang.cruds.user.fields.password }}</label>
                                            <input class="form-control" type="password" placeholder="Enter password" v-model="formData.password">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label>{{ lang.cruds.user.fields.gender }}</label>
                                        <div class="row">
                                        <div class="col-md-4 gender" v-for="(gender, index) in gendertype">
                                            <input type="radio" :id="`gender_${index}`"  :value="index" v-model="formData.gender" />
                                            <label :for="`gender_${index}`">{{ gender }}</label>
                                          </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="required" for="email_status">{{ lang.cruds.user.fields.email_notification_status }}</label>
                                                <select class="custom-select" value="Select status" name="email_status" id="select_email_status" v-model="formData.email_notification_status">
                                                    <option value="" disabled selected>Select Status</option>
                                                    <option :value="index" v-for="(item , index) in list.emailStatus">{{ item }}</option>
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="required" for="sms_status">{{ lang.cruds.user.fields.sms_notification_status }}</label>
                                                <select class="custom-select" value="select status" name="sms_status" id="select_sms_status" v-model="formData.sms_notification_status">
                                                    <option value="" disabled selected>Select Status</option>
                                                    <option :value="index" v-for="(item , index) in list.smsStatus">{{ item }}</option>
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="required" for="blocked_status">{{ lang.cruds.user.fields.blocked }}</label>
                                                <select class="custom-select" value="select status" name="blocked_status" id="select_blocked_status" v-model="formData.blocked">
                                                    <option value="" disabled selected>Select Status</option>
                                                    <option :value="index" v-for="(item , index) in list.blocked">{{ item }}</option>
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="required" for="role_id">{{ lang.cruds.user.fields.roles }}</label>
                                                <select class="custom-select" value="select roles" name="role_id" id="role_id" v-model="formData.role_id">
                                                    <option value="" disabled selected>Select Roles</option>
                                                    <option :value="index" v-for="(item , index) in list.roles">{{ item }}</option>
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                      <ValidationProvider  name="image" rules="size:10" v-slot="{ errors }">
                                        <div class="form-group">
                                          <label class="required" for="addimage">{{ lang.cruds.user.fields.image }}</label>
                                          <div class="addimage">
                                            <input type="file" id="addimage"  name="image" @change="handleFileObject()" ref="file" />
                                          </div>
                                            <img :src="imagedata" height="70" width="70" @error="setAltImg" id="displayImage">
                                          <p class="text-danger">{{ errors[0] }}</p>
                                        </div>
                                      </ValidationProvider>
                                    </div> 
                                </div>

                            </div>

                            <!-- Basic details end -->
                        </div>
                    </div>
                    <div class="form-group float-left">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            {{ lang.global.save }}
                        </button>
                        <button class="btn btn-danger waves-effect waves-light" type="button" @click="cancel()" >
                            {{ lang.global.cancel }}
                        </button>
                    </div>
                </div>
                <br>
            </form>
        </ValidationObserver>
    </div>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['list', 'data', 'type'],
        name:'customers',
        data() {
            return {
                formData:{
                    name:'',
                    last_name:'',
                    mobile:'',
                    username:'',
                    email:'',
                    password:'',
                    gender:'M',
                    email_notification_status:'1',
                    sms_notification_status:'1',
                    blocked:'1',
                    role_id:'3'
                },
                userimage:null,
                imagedata:'',
                gendertype: {'M':'Male','F':'Female','T':'Transgender'}

            }
        },
        mounted(){

           
            if(this.type == 'edit'){
                    this.formData = this.data.user;
                    if(typeof this.formData.image != 'undefined'){
                        this.imagedata = this.formData.image;
                    }
            }
            
        },
        components: {

        },
        computed: {
            noImage(){
                return '/assets/images/no-image.jpg';
            },
        },
        created() {
        },
        methods: {
            
            setAltImg(event){
                event.target.src = this.noImage;
            },
            handleFileObject() {
                this.userimage = this.$refs.file.files[0];
                let input = this.$refs.file;
                let file = input.files;
                if (file && file[0]) {
                  let reader = new FileReader;
                  reader.onload = e => {
                   $('#displayImage').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(file[0]);
                }
              },
            
            submit(){
                this.$refs.customerForm.validate().then(success => {
                    if (!success) {
                        $("html, body").animate({ scrollTop: 50 }, 200);
                        return;
                    }
                    openLoader();
                     let formData = new FormData();
                        formData.append('userimage', this.userimage);

                        _.each(this.formData, (value, key) => {
                        formData.append(key, value)
                        })
                        
                        this.$store.dispatch("customerModule/AddEditCustomer", formData)
                        .then((res) => {
                            if (res.response.status_code == 2047) {
                                successModal(res.response.message);

                                setTimeout(function(){
                                    window.location = res.response.data.url;
                                },2000);
                            }
                         
                         else if(res.response.status_code == 2048){
                                successModal(res.response.message);
                                 setTimeout(function(){
                                    window.location = res.response.data.url;
                                },2000);
                                
                            }
                        else
                        {
                            errorModal(res.response.message);
                        }
                        closeLoader();
                        })
                        .catch((err) => {
                            closeLoader();
                            let message = "";
                            if(typeof err.response.data.errors.mobile != 'undefined'){
                                message += err.response.data.errors.mobile[0] + '\n';
                            }
                            if(typeof err.response.data.errors.email != 'undefined'){
                                message += err.response.data.errors.email[0] + '\n';
                            }
                            errorModal(err.response.message);
                        });

                    
                });
            }
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