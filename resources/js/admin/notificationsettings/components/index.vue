<template>
    <div id="input-sizing">
        <ValidationObserver ref="notificationForm" v-slot="{ handleSubmit }">
            <form method="POST" enctype="multipart/form-data" id="frmNotification" @submit.prevent="handleSubmit(submit())">
                <div class="row">
                    <div class="col-12">
                        <!-- Basic details start -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <ValidationProvider name="title" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="notifisation_title">{{ lang.cruds.notifications.fields.title }}</label>
                                                <input class="form-control" type="text" placeholder="Enter title" v-model="formData.title">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="required" for="notifisation_description">{{ lang.cruds.notifications.fields.description }}</label>
                                            <textarea class="form-control" type="text" placeholder="Enter description" rows="4" v-model="formData.description"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <ValidationProvider name="category" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="status">{{ lang.cruds.notifications.fields.category }}</label>
                                                <select class="custom-select" value="Select category" name="category" id="select_category" v-model="formData.category">
                                                    <option value="" disabled selected>Select Category</option>
                                                    <option :value="index" v-for="(item, index) in list.categories">{{ item }}</option>
                                                </select>
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <ValidationProvider name="Options" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="status">{{ lang.cruds.notifications.fields.options }}</label>
                                                <select class="custom-select" value="Select option" name="options" id="select_options" v-model="formData.options">
                                                    <option value="" disabled selected>Select Options</option>
                                                    <option :value="index" v-for="(item, index) in list.options">{{ item }}</option>
                                                </select>
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <ValidationProvider name="Status" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="status">{{ lang.cruds.notifications.fields.status }}</label>
                                                <select class="custom-select" value="select status" name="status" id="selectstatus" v-model="formData.status">
                                                    <option value="" disabled selected>Select Status</option>
                                                    <option :value="index" v-for="(status, index) in statustype">{{ status }}</option>
                                                </select>
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" name="email" id="email" v-model="formData.email" value="true">
                                                <label class="custom-control-label" for="email">{{ lang.cruds.notifications.fields.email }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="required" for="email_subject">{{ lang.cruds.notifications.fields.email_subject }}</label>
                                            <input class="form-control" type="text" placeholder="Enter email subject" v-model="formData.email_subject">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                           <label class="required">{{ lang.cruds.notifications.fields.email_template }}</label>
                                           <textarea class="form-control" rows="10" placeholder="Enter email template" name="email_template" id="email_template" v-model="formData.email_template"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" name="sms" id="sms" v-model="formData.sms" value="true">
                                                <label class="custom-control-label" for="sms">{{ lang.cruds.notifications.fields.sms }}</label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                           <label class="required">{{ lang.cruds.notifications.fields.sms_template }}</label>
                                           <textarea class="form-control" rows="5" name="sms_template" id="sms_template" v-model="formData.sms_template" placeholder="Enter sms template"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="largeInput">{{ lang.cruds.notifications.fields.variable_description }}</label>
                                            <textarea class="form-control" rows="5" name="variable_description" id="variable_description"  v-model="formData.variable_description" placeholder="Enter variable description"></textarea>
                                        </div>
                                    </div>
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
                <br>
            </form>
        </ValidationObserver>
    </div>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['list', 'data', 'type'],
        name:'notification',
        data() {
            return {
                formData:{
                    title:'',
                    description:'',
                    category:'',
                    options:'',
                    status:'',
                    email:true,
                    sms:true,
                    email_subject:'',
                    email_template:'',
                    sms_template:'',
                    variable_description:''
                },
                statustype:{'0':'InActive','1':'Active' },
            }
        },
        mounted(){
            if(this.type == 'Edit'){
                this.setFormData();
            }
            
        },
        components: {

        },
        computed: {
        },
        created() {
        },
        methods: {
            setFormData(){
                let data = this.data;
                this.formData = data.notifications;

            },
            submit(){
                this.$refs.notificationForm.validate().then(success => {
                    if (!success) {
                        $("html, body").animate({ scrollTop: 50 }, 200);
                        return;
                    }
                    openLoader();
                    if(this.type == 'Add')
                    {
                        this.$store.dispatch("notificationSettingsModule/AddNotification", this.formData)
                        .then((res) => {

                            if (res.response.status_code == 3021) {
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
                            errorModal(err.response.message);
                        });
                    }
                    else
                    {
                        this.formData.id = this.data.notifications.id;
                        this.$store.dispatch("notificationSettingsModule/EditNotification", this.formData)
                        .then((res) => {
                            if (res.response.status_code == 3023) {
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
                            errorModal(err.response.message);
                        });

                    }
                });
            },
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