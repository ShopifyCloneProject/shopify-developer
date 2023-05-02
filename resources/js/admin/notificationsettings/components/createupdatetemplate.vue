<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                        <li class="nav-item">
                            <a
                            class="nav-link active"
                            id="home-tab-justified"
                            data-toggle="tab"
                            href="#home-just"
                            role="tab"
                            aria-controls="home-just"
                            aria-selected="true"
                            >Email</a
                            >
                        </li>
                        <li class="nav-item">
                            <a
                            class="nav-link"
                            id="profile-tab-justified"
                            data-toggle="tab"
                            href="#profile-just"
                            role="tab"
                            aria-controls="profile-just"
                            aria-selected="true"
                            >SMS</a
                            >
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content pt-1">
                        <div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
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
                                      <label>Status</label>
                                      <VueToggles
                                          height="25"
                                          width="90"
                                          fontWeight="bold"
                                          checkedText="Active"
                                          uncheckedText="InActive"
                                          checkedBg="#2e5fe5"
                                          uncheckedBg="lightgrey"
                                          uncheckedColor="#000"
                                          :value="formData.email"
                                          @click="formData.email = !formData.email"
                                        />
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-outline-secondary text-secondary" @click="revertNotificationDetailTemplate('email')">Revert to default</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-justified">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="required">{{ lang.cruds.notifications.fields.sms_template }}</label>
                                        <textarea class="form-control" rows="5" name="sms_template" id="sms_template" v-model="formData.sms_template" placeholder="Enter sms template"></textarea>
                                    </div>
                                    <div>
                                        <span>SMS template cannot be edited.Learn more about <a href="" class="notification-link">SMS notifications</a></span>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-12">
                                      <label>Status</label>
                                      <VueToggles
                                          height="25"
                                          width="90"
                                          fontWeight="bold"
                                          checkedText="Active"
                                          uncheckedText="InActive"
                                          checkedBg="#2e5fe5"
                                          uncheckedBg="lightgrey"
                                          uncheckedColor="#000"
                                          :value="formData.sms"
                                          @click="formData.sms = !formData.sms"
                                        />
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-outline-secondary text-secondary" @click="revertNotificationDetailTemplate('sms')">Revert to default</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" v-if="data.variable_description">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <h5>{{ lang.cruds.notifications.fields.variable_description }}</h5>
                                 <div v-html="data.variable_description"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-left mr-5" >
                <button class="btn btn-primary waves-effect" type="button" @click="saveNotificationData()">{{ lang.global.save }}</button>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex'
    import VueToggles from 'vue-toggles';
    export default {
        props: ['list', 'data', 'type'],
        name:'notifications',
        data() {
            return {
                formData:{
                    email_subject:'',
                    email_template:'',
                    sms_template:'',
                    email: false,
                    sms: false,
                }
            }
        },
        mounted(){
            this.setNotificationData();
        },
        components: {
            VueToggles
        },
        computed: {
        },
        created() {

        },
        methods: {
            revertNotificationDetailTemplate(statusType){
                openLoader();
                this.$store.dispatch("notificationSettingsModule/RevertNotification",{'notifications_id' : this.data.notificationDetail.notifications_id,'statusType': statusType})
                .then((res) => {
                    if (res.response.status_code == 3041) {
                        successModal(res.response.message);
                        setTimeout(function(){
                            location.reload();
                        },2000);
                        
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
            setNotificationData(){
                let notificationdata = this.data.notification;
                let data = this.data.notificationDetail;
                this.formData.email_subject = data.email_subject;
                this.formData.email_template = data.email_template;
                this.formData.sms_template = data.sms_template;
                this.formData.email = notificationdata.email;
                this.formData.sms = notificationdata.sms;
            },
            saveNotificationData(){
                openLoader();
                this.formData.notifications_id = this.data.notificationId;
                this.$store.dispatch("notificationSettingsModule/SaveNotification",this.formData)
                .then((res) => {
                    if (res.response.status_code == 3040) {

                        successModal(res.response.message);
                        setTimeout(function(){
                             window.location = res.response.data.url;
                        },2000);
                       
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
    
    .store-content p {
        font-size: 13px;
        text-align: justify;
    }
    .store-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .notification-link{
        text-decoration: underline;
        font-weight: bold;
    }
    

</style>