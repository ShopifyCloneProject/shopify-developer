<template>
    <div id="input-sizing">
        <ValidationObserver ref="GeneralSettingsForm" v-slot="{ handleSubmit }">
            <form method="POST" enctype="multipart/form-data" id="frmAddEditGeneralSettings" @submit.prevent="submit()">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="store-content">
                            <h6 class="store-title">{{ lang.cruds.userStore.store_detail }}</h6>
                            <p>{{ lang.cruds.userStore.store_detail_helper1 }}</p>
                        </div>
                    </div>
                    <div class="col-md-8 col-12">
                        <!-- Basic details start -->
                        <div class="card">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <ValidationProvider name="storeName" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="store_contact_email">{{ lang.cruds.userStore.fields.store_name }}</label>
                                                <input class="form-control" type="text" v-model="formData.storeName">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-6">
                                        <ValidationProvider name="contactEmail" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="store_contact_email">{{ lang.cruds.userStore.fields.store_contact_email }}</label>
                                                <input class="form-control" type="email" v-model="formData.contactEmail">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-6">
                                        <ValidationProvider name="senderEmail" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="sender_email">{{ lang.cruds.userStore.fields.sender_email }}</label>
                                                <input class="form-control" type="email" v-model="formData.senderEmail">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-12">
                                        <ValidationProvider name="industries" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="country_id">{{ lang.cruds.userStore.fields.sender_industry }}</label>
                                                <select class="custom-select" v-model="formData.industry">
                                                    <option :value="ldata.id" v-for="(ldata, index) in list.industries">{{ ldata.title }}</option>
                                                </select>
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
                            <h6 class="store-title">{{ lang.cruds.userStore.store_address }}</h6>
                            <p>{{ lang.cruds.userStore.store_address_helper1 }}</p>
                            <p>{{ lang.cruds.userStore.store_address_helper2 }}</p>
                        </div>
                    </div>
                    <div class="col-md-8 col-12">
                        <!-- Basic details start -->
                        <div class="card">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <ValidationProvider name="company" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="company">{{ lang.cruds.userStore.fields.company }}</label>
                                                <input class="form-control" type="text" v-model="formData.company">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-12">
                                        <ValidationProvider name="address1" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="address">{{ lang.cruds.address.fields.address }}</label>
                                                <input class="form-control" type="text" v-model="formData.address1">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-12">
                                        <ValidationProvider name="address2" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="address_2">{{ lang.cruds.address.fields.address_2 }}</label>
                                                <input class="form-control" type="text" v-model="formData.address2">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-12">
                                        <ValidationProvider name="phone" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="mobile">{{ lang.cruds.address.fields.mobile }}</label>
                                                <input class="form-control" type="number" v-model="formData.phone">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-12">
                                        <ValidationProvider name="city" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="city_name">{{ lang.cruds.address.fields.city_name }}</label>
                                                <input class="form-control" type="text" v-model="formData.city">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <ValidationProvider name="countries" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label for="country_id">{{ lang.cruds.address.fields.country }}</label>
                                                <select class="custom-select" v-model="formData.country" @change="getState()">
                                                    <option :value="index" v-for="(ldata, index) in list.countries">{{ ldata }}</option>
                                                </select>
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-md-4" id="stateList"> 
                                        <ValidationProvider name="state" rules="required" v-slot="{ errors }">  
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
                                        <ValidationProvider name="pincode" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="postal_code">{{ lang.cruds.address.fields.postal_code }}</label>
                                                <input class="form-control" type="text" v-model="formData.pincode">
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
                <hr />
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="store-content">
                            <h6 class="store-title">{{ lang.cruds.userStore.standards_fromat }}</h6>
                            <p>{{ lang.cruds.userStore.standards_fromat_helper }}</p>
                        </div>
                    </div>
                    <div class="col-md-8 col-12">
                        <!-- Basic details start -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="required" for="store_contact_email">TimeZone</label>
                                            <select class="custom-select" v-model="formData.timezone">
                                                <option :value="index" v-for="(ldata, index) in list.timezones">{{ ldata }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required">{{ lang.cruds.userStore.fields.unit_system }}</label>
                                            <select class="custom-select" v-model="formData.unitSyatem">
                                                <option :value="index" v-for="(ldata, index) in list.unit_system">{{ ldata }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required">{{ lang.cruds.userStore.fields.unit_weight }}</label>
                                            <select class="custom-select" v-model="formData.unitWeight">
                                                <option :value="ldata.id" v-for="(ldata, index) in list.weight_units">{{ ldata.title }} ({{ ldata.short_code }})</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="prefix">{{ lang.cruds.userStore.fields.prefix }}</label>
                                            <input class="form-control" type="text" v-model="formData.prefix">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="suffix">{{ lang.cruds.userStore.fields.suffix }}</label>
                                            <input class="form-control" type="text" v-model="formData.suffix">
                                        </div>
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
                            <h6 class="store-title">{{ lang.cruds.userStore.store_currency }}</h6>
                            <p>{{ lang.cruds.userStore.store_currency_helper }}</p>
                        </div>
                    </div>
                    <div class="col-md-8 col-12">
                        <!-- Basic details start -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <ValidationProvider name="currency" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="title">{{ lang.cruds.userStore.store_currency }}</label>
                                                <a class="ml-auto float-right d-none" @click="changeFormat = true" v-if="!changeFormat">{{ lang.cruds.userStore.fields.change_format }}</a>
                                                <a class="ml-auto float-right d-none" @click="changeFormat = false" v-if="changeFormat">{{ lang.cruds.userStore.fields.change_format }}</a >
                                                <select class="custom-select mb-1" v-model="formData.currency">
                                                    <option :value="ldata.id" v-for="(ldata, index) in list.currencies">{{ ldata.name }} ({{ ldata.currency }})</option>
                                                </select>
                                                <p class="text-danger">{{ errors[0] }}</p>
                                                <span class="help-block h6 mt-1 d-block">{{ lang.cruds.userStore.store_currency_helper1 }}</span>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <hr v-if="changeFormat" />
                                <div class="row" v-if="changeFormat">
                                    <div class="col-12">
                                        <ValidationProvider name="sysmbol" rules="required" v-slot="{ errors }">
                                            <label class="required" for="title">{{ lang.cruds.userStore.fields.user_store_format }}</label>
                                            <input class="form-control" type="text" v-model="formData.sysmbol">
                                            <p class="text-danger">{{ errors[0] }}</p>
                                        </ValidationProvider>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Basic details end -->
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="store-content">
                            <h6 class="store-title">{{ lang.cruds.userStore.store_gst_details }}</h6>
                            <p>{{ lang.cruds.userStore.store_gst_details }}</p>
                        </div>
                    </div>
                    <div class="col-md-8 col-12">
                        <!-- Basic details start -->
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="prefix">{{ lang.cruds.userStore.fields.gst }}</label>
                                            <input class="form-control" type="text" maxlength="15"ax v-model="formData.gst">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Basic details end -->
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary waves-effect waves-float waves-light" type="submit">
                        {{ lang.global.save }}
                    </button>
                </div>
            </form>
        </ValidationObserver>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: ['list', 'data'],
        name:'GeneralSettings',
        data() {
            return {
                changeFormat:false,
                formData:{
                    storeName:'',
                    contactEmail:'',
                    senderEmail:'',
                    industry:16,
                    company:'',
                    address1:'',
                    address2:'',
                    phone:'',
                    city:'',
                    country:101,
                    state:'',
                    pincode:'',
                    timezone:'',
                    unitSyatem:'',
                    unitWeight:'',
                    prefix:'',
                    suffix:'',
                    currency:'',
                    sysmbol:'',
                    gst:'',
                },
                states:[]
            }
        },
        mounted(){

        },
        components: {

        },
        computed: {
        },
        created() {
            this.getState();
            if(this.data != null && this.data != ''){
                this.setFormDate();
            }
        },
        methods: {
            submit(){
                this.$refs.GeneralSettingsForm.validate().then(success => {
                    if (!success) {
                        $("html, body").animate({ scrollTop: 50 }, 200);
                        return;
                    }
                    openLoader();
                    this.$store.dispatch("generalSettingsModule/SaveStoreDetails", this.formData)
                    .then((res) => {
                        if (res.response.status_code == 2055) {
                            successModal(res.response.message);
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
            setFormDate(){
                this.formData.storeName = this.data.store_name;
                this.formData.contactEmail = this.data.store_contact_email;
                this.formData.senderEmail = this.data.sender_email;
                this.formData.industry = this.data.user_store_industry_id;
                this.formData.company = this.data.company;
                this.formData.address1 = this.data.address;
                this.formData.address2 = this.data.address_2;
                this.formData.phone = this.data.mobile;
                this.formData.city = this.data.city;
                this.formData.country = this.data.country_id;
                this.formData.state = this.data.state_id;
                this.formData.pincode = this.data.postal_code;
                this.formData.timezone = this.data.timezone_id;
                this.formData.unitSyatem = this.data.unit_system;
                this.formData.unitWeight = this.data.unit_weight;
                this.formData.prefix = this.data.prefix;
                this.formData.suffix = this.data.suffix;
                this.formData.currency = this.data.currency_id;
                this.formData.sysmbol = this.data.sysmbol;
                this.formData.gst = this.data.gst;
            },
            getState(){
                let section = $('#stateList');
                blockSection(section);
                let countryId = this.formData.country;
                this.$store.dispatch("generalSettingsModule/GetStates", countryId)
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
    hr{
        margin-bottom: 3rem;
    }
</style>