<template>
    <div id="input-sizing">
        <ValidationObserver ref="themeSettingsForm" v-slot="{ handleSubmit }">
            <form method="POST" enctype="multipart/form-data" id="frmThemeSettings" @submit.prevent="handleSubmit(submit())">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="facebook">{{ lang.cruds.themesettings.fields.facebook }}</label>
                                            <input class="form-control" type="text" placeholder="Enter Facebook link" name="facebook" v-model="formData.facebook">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="twitter">{{ lang.cruds.themesettings.fields.twitter }}</label>
                                            <input class="form-control" type="text" placeholder="Enter twitter link" name="twitter" v-model="formData.twitter">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="gplus">{{ lang.cruds.themesettings.fields.gplus }}</label>
                                            <input class="form-control" type="text" placeholder="Enter google+ link" name="gplus" v-model="formData.gplus">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="instagram">{{ lang.cruds.themesettings.fields.instagram }}</label>
                                            <input class="form-control" type="text" placeholder="Enter instagram link" name="instagram" v-model="formData.instagram">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="vimeo">{{ lang.cruds.themesettings.fields.vimeo }}</label>
                                            <input class="form-control" type="text" placeholder="Enter vimeo link" name="vimeo" v-model="formData.vimeo">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="linkedin">{{ lang.cruds.themesettings.fields.linkedin }}</label>
                                            <input class="form-control" type="text" placeholder="Enter liknedin link" name="liknedin" v-model="formData.linkedin">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="pinterest">{{ lang.cruds.themesettings.fields.pinterest }}</label>
                                            <input class="form-control" type="text" placeholder="Enter pinterest link" name="pinterest" v-model="formData.pinterest">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="youtube">{{ lang.cruds.themesettings.fields.youtube }}</label>
                                            <input class="form-control" type="text" placeholder="Enter youtube link" name="youtube" v-model="formData.youtube">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="email">{{ lang.cruds.themesettings.fields.email }}</label>
                                            <input class="form-control" type="text" placeholder="Enter company email" name="email" v-model="formData.email">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="sitename">{{ lang.cruds.themesettings.fields.sitename }}</label>
                                            <input class="form-control" type="text" placeholder="Enter sitename" name="sitename" v-model="formData.sitename">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="card-title">{{ lang.cruds.themesettings.title_timing }}</h4>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <h6>{{ lang.cruds.themesettings.fields.daysname }}</h6>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <h6>{{ lang.cruds.themesettings.fields.open_time }}</h6>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <h6>{{ lang.cruds.themesettings.fields.close_time }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row mb-1" v-for="(item, index) in finalTiming">
                                            <div class="col-md-4 col-12">
                                                <label for="select_days">{{item.daysName }}</label>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <select class="custom-select"  v-model="item.open">
                                                        <option :value="index" v-for="(item , index) in openTiming">{{ item }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <select class="custom-select" v-model="item.close">
                                                        <option :value="index" v-for="(item , index) in closeTiming">{{ item }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    import moment from 'moment';
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['data'],
        name:'themesettings',
        data() {
            return {
                formData:{
                    facebook:'',
                    twitter:'',
                    gplus:'',
                    vimeo:'',
                    linkedin:'',
                    pinterest:'',
                    youtube:'',
                    email:'',
                    sitename:'',
                    instagram:'',
                },
                days:[
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                ],
                openTiming:[],
                closeTiming:[],
                finalTiming:[],

            }
        },
        mounted(){
            if(this.data.objThemesetting != null){
                this.formData = this.data.objThemesetting;
            }
            this.data.objTiming.forEach((value,index) =>{
                let selectOpenIndex = 0;
                let selectCloseIndex = 0;
                if(value.open == "00:00:00")
                {
                    selectOpenIndex = 0;
                }
                else
                {
                    selectOpenIndex = this.openTiming.indexOf(value.open.slice(0,-3))
                }

                if(value.close == "00:00:00")
                {
                    selectCloseIndex = 0;
                }
                else{
                    selectCloseIndex = this.closeTiming.indexOf(value.close.slice(0,-3))
                }
                this.finalTiming[index] = {'days': index ,  'daysName' : this.days[value.days] , 'open' : selectOpenIndex , 'close' : selectCloseIndex};
            });
        },
        components: {
        },
        computed: {
        },
        created() {
            for(let i=0;i<=6;i++){
                this.finalTiming[i] = {'days': i ,  'daysName' : this.days[i] , 'open' : 0 , 'close' : 0}
            }
            this.openTiming[0] = "Holiday";
            this.closeTiming[0] = "Holiday";
            let startTime = '01:00';
            let durationInMinutes = 30;
            for(let i=1;i<=45;i++){
                this.openTiming[i] = startTime;
                this.closeTiming[i] = startTime;
                startTime = moment(startTime, "HH:mm").add(30, 'minutes').format('HH:mm');
            }
        },
        methods: {
            submit(){
                this.$refs.themeSettingsForm.validate().then(success => {
                    if (!success) {
                        $("html, body").animate({ scrollTop: 50 }, 200);
                        return;
                    }
                    openLoader();
                    this.formData.finalTiming = this.finalTiming;
                    this.formData.timing = this.openTiming;
                    this.$store.dispatch("themesettingsModule/AddEditThemeSettings", this.formData )
                    .then((res) => {
                        if (res.response.status_code == 3042) {
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
                });
            },
        },
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