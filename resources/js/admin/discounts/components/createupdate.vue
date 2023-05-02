<template>
    <div id="input-sizing">
        <ValidationObserver ref="discountsForm" v-slot="{ handleSubmit }">
            <form method="POST" id="frmDiscounts" @submit.prevent="handleSubmit(submit())">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <ValidationProvider  name="code" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="discounts_code">{{ lang.cruds.discounts.fields.code }}</label>
                                                <input class="form-control" type="text" placeholder="Enter code" v-model="formData.code">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-4">
                                        <ValidationProvider  name="starting_date" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="starting_date">{{ lang.cruds.discounts.fields.starting_date }}</label>
                                                <div>
                                                    <date-picker 
                                                        v-model="formData.starting_date"
                                                        type="datetime"
                                                        value-type="format"
                                                        format="YYYY-MM-DD hh:mm"
                                                        placeholder="Select starting datetime">
                                                    </date-picker>
                                                </div>
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-4">
                                        <ValidationProvider  name="expiry_date" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="expiry_date">{{ lang.cruds.discounts.fields.expiry_date }}</label>
                                                <div>
                                                    <date-picker 
                                                        v-model="formData.expiry_date"
                                                        type="datetime"
                                                        value-type="format"
                                                        format="YYYY-MM-DD hh:mm"
                                                        placeholder="Select expiration datetime">
                                                    </date-picker>
                                                </div>
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-4">
                                         <div class="form-group">
                                            <label class="required mb-1" for="expiry_type">{{ lang.cruds.discounts.fields.expiry_type }}</label>
                                            <div class="d-flex">
                                                <div class="custom-control custom-radio mt-0 mr-2"  v-for="(expiry , index) in expirytype">
                                                    <input class="custom-control-input" type="radio" :id="`expiry_${index}`"  :value="index" v-model="formData.expiry_type" />
                                                    <label class="custom-control-label" :for="`expiry_${index}`">{{ expiry }}</label> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <ValidationProvider  name="currency" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="currency_id">{{ lang.cruds.discounts.fields.currency }}</label>
                                                <select class="custom-select" name="currency_id" id="currency_id" v-model="formData.currency_id">
                                                    <option :value="id" v-for="(item , id) in list.currencies" v-bind:key="id">{{ item }}</option>
                                                </select>
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-6">
                                         <ValidationProvider  name="initial_value" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                <label class="required" for="initial_value">{{ lang.cruds.discounts.fields.initial_value }}</label>
                                                <input class="form-control" type="text" placeholder="Enter initial valuecode" v-model="formData.initial_value">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                         <ValidationProvider  name="user_availability" rules="required" v-slot="{ errors }">
                                            <div class="form-group">
                                                 <label class="required" for="user_availability">{{ lang.cruds.discounts.fields.user_availability }}</label>
                                                <input class="form-control" type="number" placeholder="Enter user availability" v-model="formData.user_availability">
                                                <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="required" for="discount_note">{{ lang.cruds.discounts.fields.note }}</label>
                                            <textarea class="form-control" id="discount_note" rows="3" v-model="formData.note"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-4">
                                        <label class="required" for="currency_id">{{ lang.cruds.discounts.fields.status }}</label>
                                        <select class="custom-select" name="currency_id" id="currency_id" v-model="formData.status">
                                            <option value="select status" selected="selected">Select Status</option>
                                            <option :value="id" v-for="(item , id) in statustype" v-bind:key="id">{{ item }}</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="required mb-1" for="percentage_or_amount">{{ lang.cruds.discounts.fields.percentage_or_amount }}</label>
                                        <div class="d-flex">
                                            <div class="custom-control custom-radio mt-0 mr-2" v-for="(discount , index) in percentageAmount">
                                                <input class="custom-control-input" type="radio" :id="`discount_${index}`"  :value="index" v-model="formData.percentage_or_amount" />
                                                 <label class="custom-control-label" :for="`discount_${index}`">{{ discount }}</label> 
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-4">
                                        <ValidationProvider  name="amount" rules="required" v-slot="{ errors }">
                                            <div class="form-group" v-if="formData.percentage_or_amount == 0">
                                               <label class="required" for="discount_amount">{{ lang.cruds.discounts.fields.amount }}</label>
                                                <input class="form-control" type="text" placeholder="Enter discount amount" v-model="formData.amount">
                                                 <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                            <div class="form-group" v-else>
                                               <label class="required" for="discount_percentage">{{ lang.cruds.discounts.fields.percentage }}</label>
                                                <input class="form-control" type="text" placeholder="Enter discount percentage" v-model="formData.amount">
                                                 <p class="text-danger">{{ errors[0] }}</p>
                                            </div>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                    <div class="card-header">
                                             <h4 class="card-title">{{ lang.global.media }}</h4>
                                        </div>
                                        <div class="card-body">
                                            <section>
                                                 <button v-if="showDeleteBtn" @click.prevent="deleteMedia" type="button" class="btn btn-outline-warning waves-effect pull-right">{{ lang.global.deleteselectmedia }}</button>
                                                <div class="draggable-outer-line" v-if="mediaData.length == 0"> 
                                                    <div class="row no-item"  @click.prevent="callFileData">
                                                       <div class="col-md-12 icon">
                                                             <img width="40" src="data:image/svg+xml,%3csvg fill='none' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill-rule='evenodd' clip-rule='evenodd' d='M20 10a10 10 0 11-20 0 10 10 0 0120 0zM5.3 8.3l4-4a1 1 0 011.4 0l4 4a1 1 0 01-1.4 1.4L11 7.4V15a1 1 0 11-2 0V7.4L6.7 9.7a1 1 0 01-1.4-1.4z' fill='%235C5F62'/%3e%3c/svg%3e"  alt="" />
                                                        </div>
                                                        <div class="col-md-12 text">
                                                           <div class="add-file"> {{ lang.global.add_media }} </div>
                                                        </div> 
                                                    </div>   
                                                </div> 
                                                <div class="DropZone__Container hasitem" v-else>
                                                        <div class="dropzone-first">
                                                            <draggable tag="div" v-model="mediaData" class="dropzone-second-div" draggable=".item">
                                                            <div v-for="(imagedata,index) in mediaData" class="item">
                                                                <button class="btnImage" type="button" @mouseover="mouseOverDiv(index)" @mouseleave="mouseLeaveDiv(index)">
                                                                    <div class="checkbox-outer" v-show="imagedata.displaycheckbox">
                                                                        <div class="custom-control custom-checkbox">
                                                                          <input type="checkbox" class="custom-control-input" :id="'deletecheckbox'+imagedata.id" v-model="imagedata.checked" @change="checkAnySelect">
                                                                          <label class="custom-control-label" :for="'deletecheckbox'+imagedata.id"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="image-outer" :class="(imagedata.displaycheckbox)?'opacitityDown':''">
                                                                        <img class="main-image" :src="imagedata.imageurl" :alt="imagedata.src_alt_text">
                                                                    </div>
                                                                </button>
                                                            </div>
                                                            <div class="add-media-outer">
                                                                <button class="add-image" type="button" @click.prevent="callFileData">
                                                                        <span class="add-image-link">{{ lang.global.add_media }}</span>
                                                                </button>
                                                            </div>
                                                        </draggable>
                                                    </div>
                                                </div>
                                            </section>
                                            
                                            <template>
                                            <form ref="mediaform">
                                            <input type="file" name="media[]" multiple="" accept="image/jpeg, image/png" id="media" class="opacity-0" @change="passImageData($event)">
                                            </form>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="required mb-1" for="product_or_collection">{{ lang.cruds.discounts.fields.product_or_collection }}</label>
                                                <div class="d-flex">
                                                    <div class="custom-control custom-radio mt-0 mr-2"  v-for="(product , index) in productCollection">
                                                        <input class="custom-control-input" type="radio" :id="`product_${index}`"  :value="index" v-model="formData.product_or_collection" :disabled="disabled" @change="changeCategory()"/>
                                                        <label class="custom-control-label" :for="`product_${index}`">{{ product }}</label> 
                                                    </div>
                                                </div>
                                            </div>
                                             <div class="col-6" v-if="formData.product_or_collection == 1">
                                                <label class="required mb-1" for="productstatus">{{ lang.cruds.discounts.fields.product_status }}</label>
                                                <div class="d-flex">
                                                    <div class="custom-control custom-radio mt-0 mr-2"  v-for="(productstatus , index) in productStatus">
                                                        <input class="custom-control-input" type="radio" :id="`productstatus_${index}`"  :value="index" v-model="formData.product_status"/>
                                                        <label class="custom-control-label" :for="`productstatus_${index}`">{{ productstatus }}</label> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="row">
                                             <div class="col-6">
                                                <label class="required mb-1" for="userstatus">{{ lang.cruds.discounts.fields.user }}</label>
                                                <div class="d-flex">
                                                    <div class="custom-control custom-radio mt-0 mr-2"  v-for="(userstatus , index) in userStatus">
                                                        <input class="custom-control-input pointer" type="radio" :id="`userstatus_${index}`"  :value="index" name="user_status" v-model.number="user_status"/>
                                                        <label class="custom-control-label pointer" :for="`userstatus_${index}`">{{ userstatus }}</label> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="row" v-if="formData.product_or_collection == 1 && formData.product_status == 0">
                                            <div class="col-md-2">{{ lang.cruds.discounts.fields.product }}</div>
                                            <div class="col-md-10">
                                              <multiselect
                                                          v-model="selectedData"
                                                          :options="objProductsSelect"
                                                          :taggable="true"
                                                          tag-placeholder="Add this product"
                                                          placeholder="search or add product"
                                                          :custom-label="FilterTitleName"
                                                          track-by="id"
                                                          :close-on-select="true"
                                                          :clear-on-select="true"
                                                          @search-change="SearchProducts"
                                                          :loading="isLoading"
                                                          >
                                              </multiselect>
                                            </div>
                                            <button class="btn btn-primary waves-effect mt-2 ml-1" id="addproduct" type="button" @click="addNewProduct()">{{ lang.global.add }} {{ lang.global.new }}</button>
                                        </div>
                                        <div class="row"  v-else-if="formData.product_or_collection == 0">
                                            <div class="col-md-3">{{ lang.cruds.discounts.fields.collection }}</div>
                                            <div class="col-md-8">
                                                <div class="form-group sel-collection-section">
                                                    <multiselect
                                                          v-model="finalSelectCollection"
                                                          :options="list.collections"
                                                          placeholder="Add collections"
                                                          :custom-label="FilterTitleName"
                                                          track-by="id"
                                                          :multiple="true"
                                                          :close-on-select="false"
                                                          :clear-on-select="false"
                                                          :taggable="false"
                                                          @select=onSelectCollections($event)
                                                          @remove=onRemoveCollections($event)
                                                          >
                                                        <template slot="option" slot-scope="scope">
                                                            <div class="form-check form-check-inline"  @click.self="select(scope.option)">
                                                                <input class="form-check-input" type="checkbox" :id="'inlineCheckbox'+scope.option.id" v-model="scope.option.checked" @focus.prevent />
                                                                <label class="form-check-label"  
                                                                :for="'inlineCheckbox'+scope.option.id">{{ scope.option.title }}</label>
                                                            </div>
                                                        </template>
                                                        <template slot="selection" slot-scope="{ values, search }">
                                                            <span class="multiselect__single" v-if="values.length">{{ values.length }} collections selected</span>
                                                        </template>
                                                    </multiselect> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row" v-if="user_status == 0">
                                            <div class="col-md-2">{{ lang.cruds.discounts.fields.user }}</div>
                                            <div class="col-md-10">
                                              <multiselect
                                                          v-model="selectUserId"
                                                          :options="objUserSelect"
                                                          :taggable="true"
                                                          tag-placeholder="Add this user"
                                                          placeholder="search or add user"
                                                          :custom-label="FilterUserName"
                                                          track-by="email"
                                                          :close-on-select="true"
                                                          :clear-on-select="true"
                                                          @search-change="SearchUsers"
                                                          :loading="isUserLoading"
                                                          >
                                              </multiselect>
                                            </div>
                                            <button class="btn btn-primary waves-effect mt-2 ml-1" id="adduser" type="button" @click="addNewUser()">{{ lang.global.add }} {{ lang.global.new }}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="row" v-if="finalSelectionProduct.length > 0 && formData.product_status == 0">
                                            <div class="col-12">
                                                <div class="responsive-div-table table">
                                                    <div class="row header w-100">
                                                        <div class="cell w-20">Image</div>
                                                        <div class="cell w-50">Selected Products</div>
                                                        <div class="cell w-20 text-center">Status</div>
                                                        <div class="cell w-10 text-center">Action</div>
                                                    </div>
                                             
                                                    
                                                    <div class="menuitem row w-100 pl-1" v-for="(selectedProduct, index) in finalSelectionProduct" :key="index">
                                                        <div class="cell w-20  p-1">
                                                             <img class="lazyload fade-up" :src="selectedProduct.medias[0].image_src[2]"  :data-src="selectedProduct.medias[0].image_src[2]" :alt="selectedProduct.title"  @error="setAltImg" height="80" width="80" v-if="selectedProduct.medias.length > 0">
                                                             <img v-else :src="noImage" height="80" width="80">
                                                        </div>
                                                        <div class="cell w-50  p-1">
                                                            <div class="price-new">{{ selectedProduct.title }} </div>
                                                        </div>
                                                        <div class="cell w-20  p-1 text-center">
                                                          <VueToggles
                                                              height="25"
                                                              width="85"
                                                              fontWeight="bold"
                                                              checkedText="Active"
                                                              uncheckedText="InActive"
                                                              uncheckedColor="#000"
                                                              checkedBg="#2e5fe5"
                                                              uncheckedBg="lightgrey"
                                                              v-model="selectedProduct.productstatus"
                                                              @click="setWithProductStatus(index)"
                                                            ></VueToggles>
                                                        </div>
                                                        <div class="cell w-10 p-1 text-center">
                                                          <a class="cart-table-prd-remove pointer btn" data-tooltip="Remove Product" role="button" @click="removeProduct(index)"><i class="fa fa-trash text-danger"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="row" v-else-if="finalSelectCollection.length > 0">
                                            <div class="col-12">
                                                <div class="responsive-div-table table">
                                                      <div class="row header w-100">
                                                        <div class="cell w-50">Collection Name</div>
                                                        <div class="cell w-20 text-center">Action</div>
                                                      </div>
                                               
                                                  <div class="menuitem row w-100 pl-1" v-for="(selectedcollection, index) in finalSelectCollection" >
                                                    <div class="cell w-50  p-1">
                                                        <div class="price-new">{{ selectedcollection.title }}</div>
                                                    </div>
                                                    <div class="cell w-20 p-1 text-center">
                                                      <a class="cart-table-prd-remove pointer btn" data-tooltip="Remove Product" role="button" @click="onRemoveCollections(selectedcollection); removeCollection(index)"><i class="fa fa-trash text-danger"></i></a>
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-6">
                                        <div class="row" v-if="finalSelectUserId.length > 0 && user_status == 0">
                                            <div class="col-12">
                                                <div class="responsive-div-table table">
                                                    <div class="row header w-100">
                                                        <div class="cell w-50">Email</div>
                                                        <div class="cell w-30 text-center">Status</div>
                                                        <div class="cell w-20 text-center">Action</div>
                                                    </div>
                                               
                                                    <div class="menuitem row w-100 pl-1" v-for="(selecteduser, index) in finalSelectUserId" :key="index">
                                                        <div class="cell w-50  p-1">
                                                            <div class="price-new">{{ selecteduser.email }}</div>
                                                        </div>
                                                        <div class="cell w-30 p-1 text-center">
                                                        <VueToggles
                                                              height="25"
                                                              width="85"
                                                              fontWeight="bold"
                                                              checkedText="Active"
                                                              uncheckedText="InActive"
                                                              uncheckedColor="#000"
                                                              checkedBg="#2e5fe5"
                                                              uncheckedBg="lightgrey"
                                                              v-model="selecteduser.userstatus"
                                                              @click="setWithUserStatus(index)"
                                                            ></VueToggles>
                                                        </div>
                                                        <div class="cell w-20  p-1 text-center">
                                                          <a class="cart-table-prd-remove pointer btn" data-tooltip="Remove Product" role="button" @click="removeUser(index)"><i class="fa fa-trash text-danger"></i></a>
                                                        </div>
                                                    </div>
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                 </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-8 form-group float-left">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">
                            {{ lang.global.save }}
                        </button>
                        <button class="btn btn-danger waves-effect waves-light" type="button" @click="cancel()" >
                            {{ lang.global.cancel }}
                        </button>
                        <button class="btn btn-danger waves-effect waves-light" type="button" @click="backtolist()">
                            {{ lang.global.back_to_list }}
                        </button>
                    </div>
        </div>
        <br>
    </form>
</ValidationObserver>
</div>
</template>
<script>

    import { mapGetters, mapActions } from 'vuex';
    import draggable from 'vuedraggable';
    import DatePicker from 'vue2-datepicker';
    import VueToggles from 'vue-toggles';
    import 'vue2-datepicker/index.css';

    export default {
        props: ['list', 'data', 'type'],
        name:'discounts',
        data() {
            return {
                formData:{
                    code:'',
                    starting_date:'',
                    expiry_date:'',
                    expiry_type:0,
                    currency_id:'',
                    initial_value:'',
                    user_availability:'',
                    note:'',
                    status:1,
                    percentage_or_amount:1,
                    amount:'',
                    product_or_collection:1,
                    product_status:1,

                },
                user_status:1,
                showDeleteBtn: false,
                selectedData:[],
                userimage:null,
                imagedata:'',
                isLoading : false,
                isUserLoading : false,
                expirytype: ['No' , 'Yes'],
                percentageAmount: ['Amount' ,'Percentage'],
                productCollection: ['Collection' ,'Product'],
                productStatus: ['Particuler' ,'All'],
                userStatus: ['Particuler','All'],
                statustype: ['Disabled' ,'Enabled'],
                selectUserId: [],
                objProductsSelect: [],
                objUserSelect: [],
                finalSelectionProduct:[],
                finalSelectUserId:[],
                finalSelectCollection:[],
                mediaData:[],
                disabled:false,
            }
        },
        mounted(){
            this.objProductsSelect = this.list.objProducts;
            this.objUserSelect = this.list.objUsers;
            if(this.type == 'Edit'){
                    this.setFormData();
                    this.disabled = true;
            }
        },
        components: {
            VueToggles
        },
        computed: {
            noImage(){
                return '/assets/images/no-image.jpg';
            },
        },
        created() {
        },
        methods: {
            setFormData(){
                this.formData = this.data.objDiscount;
                this.finalSelectionProduct = this.data.objDiscountProducts;
                this.finalSelectCollection = this.data.collections;
                this.finalSelectUserId = this.data.objDiscountUsers;
                this.user_status = 1;
                if(this.finalSelectUserId.length > 0){
                        this.user_status = 0;
                }
                let objImage =[];
                let  tempObjImage= {};
                for (const [key, value] of Object.entries(this.data.objDiscountMediaSrc)) {
                    tempObjImage =  {
                                        checked: false,
                                        displaycheckbox: false,
                                        id: value.id,
                                        imageurl: value.src
                                    };
                    objImage.push(tempObjImage);
                }
                this.mediaData = objImage;
            },
            setWithProductStatus(index)
            {   
                let tempProductData = [...this.finalSelectionProduct];
                tempProductData[index].productstatus = !tempProductData[index].productstatus;
                this.finalSelectionProduct = tempProductData;    
            },
            setWithUserStatus(index)
            {   
                let tempUserData = [...this.finalSelectUserId];
                tempUserData[index].userstatus = !tempUserData[index].userstatus;
                this.finalSelectUserId = tempUserData;    
            },
            callFileData(){
                $("#media").trigger('click');
            },
            mouseOverDiv(index){
                this.mediaData[index].displaycheckbox = true;
            },
            mouseLeaveDiv(index){
                this.mediaData[index].displaycheckbox = false;
                this.checkAnySelect();
            },
            appendMedia(base64image){
                this.mediaData.push({id: Math.floor((Math.random() * -10000000)),  imageurl: base64image, checked: false, displaycheckbox: false, media: [] });
            },
            checkAnySelect(){
                let funCheckDisplayCheckbox = false;
                for (const [key, value] of Object.entries(this.mediaData)) {
                    if(value.checked)
                    {
                        funCheckDisplayCheckbox = true;
                        this.showDeleteBtn = true;
                        break;
                    }
                }

                if(funCheckDisplayCheckbox)
                {
                    for (const [key, value] of Object.entries(this.mediaData)) {
                        value.displaycheckbox = true;
                    }
                }
                else
                {
                    for (const [key, value] of Object.entries(this.mediaData)) {
                        value.displaycheckbox = false;
                    }
                    this.showDeleteBtn = false;
                }
            },
            deleteMedia(){
                for (const [key, value] of Object.entries(this.mediaData)) {
                    if(value.checked) 
                    {
                        this.deleteMediaIndex();
                    }
                }
                this.showDeleteBtn = false;
                this.checkAnySelect();
            },
            deleteMediaIndex(){
                for (const [key, value] of Object.entries(this.mediaData)) {
                    if(value.checked)
                    {
                        this.mediaData.splice(key, 1);
                        this.deleteMedia();
                    }
                }
            },
            passImageData(event){
                blockSection($("#mediaDataId"));
                let self = this;
                for (const [key, value] of Object.entries(event.target.files)) {
                    const  reader = new FileReader();
                    reader.readAsDataURL(value); 
                    reader.onload  = function() {
                        self.appendMedia(reader.result);
                    }
                }
                self.$refs.mediaform.reset();
                unblockSection($("#mediaDataId"));
            },
            setAltImg(event){
                event.target.src = this.noImage;
            },
            changeCategory(){
                if(this.formData.product_or_collection == 1){
                 this.finalSelectCollection = [];
                }
                else if(this.formData.product_or_collection == 0){
                 this.selectedData = [];
                 this.finalSelectionProduct = [];
                }
            },
            FilterTitleName(option) {
                return `${option.title}`;
            },
            FilterUserName({ email,mobile }) {
                if(mobile == ''){
                    return `${email}`;
                }
                return `${email} - (${mobile})`;
            },
            removeProduct(index){
            this.finalSelectionProduct.splice(index, 1);
            },
            removeCollection(index){
            this.finalSelectCollection.splice(index, 1);
            },
            removeUser(index){
                this.finalSelectUserId.splice(index, 1);
            },
            SearchProducts(query){
                if(query.length > 3){
                    this.isLoading = true;
                     this.$store.dispatch("discountsModule/GetSearchProduct", {'search': query})
                        .then((res) => {
                            this.isLoading = false;
                            if(res.response.status_code == 2044)
                            {
                                this.objProductsSelect = res.response.data;
                            }
                           else
                           {
                                errorModal(res.response.message);
                           }
                        })
                        .catch((err) => {
                          this.isLoading = false;
                          errorModal(err.response.message);
                        });
                    }
            },
            SearchUsers(searchUserKeyWord) {
                if(searchUserKeyWord.length > 3){
                 this.isUserLoading = true;
                 this.$store.dispatch("discountsModule/GetSearchUser", {'search': searchUserKeyWord})
                 .then((res) => {
                  if (res.response.status_code == 3038) {
                    this.objUserSelect = res.response.data;
                    this.isUserLoading = false;
                  }
                  else
                  {
                    errorModal(res.response.message);
                  }
                })
                 .catch((err) => {
                  errorModal(err.response.message);
                  this.isLoading = false;
                });
               }
            },
            onSelectCollections(options) {
             let index = this.list.collections.findIndex(item => item.id==options.id);
             this.list.collections[index].checked = true;
            },
             onRemoveCollections(options) {
              let optionIndex = this.list.collections.findIndex(item => item.id==options.id);
              this.list.collections[optionIndex].checked = false;
            },
            addNewProduct(){
                this.selectedData.productstatus = true;
                this.finalSelectionProduct.push(this.selectedData);
            },
            addNewUser(){
                this.selectUserId.userstatus = true;
                this.finalSelectUserId.push(this.selectUserId);
            },
             submit(){
              this.$refs.discountsForm.validate().then(success => {
                if (!success) {
                  $("html, body").animate({ scrollTop: 50 }, 200);
                  return;
                }
                this.formData.user_status = this.user_status;
                this.formData.media = this.mediaData;
                this.formData.finalSelectionProduct = this.finalSelectionProduct;
                this.formData.finalSelectCollection = this.finalSelectCollection;
                this.formData.finalSelectUserId = this.finalSelectUserId;
                openLoader();
                  this.$store.dispatch("discountsModule/AddEditDiscounts", this.formData)
                  .then((res) => {
                    if (res.response.status_code == 3107) {
                      successModal(res.response.message);
                      setTimeout(function(){
                        window.location = res.response.data.url;
                        },2000);
                    }
                    else if (res.response.status_code == 3109) {
                      successModal(res.response.message);
                      setTimeout(function(){
                        window.location.reload();
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
            
              });
        },
        cancel(){
           location.reload();
        },
         backtolist(){
            window.history.back();
          },

    }   

    }
</script>

<style lang="scss" scoped>
     .card{
        border:none;
    }
    .card-body{
        padding-top:25px !important;
        border:1px solid #e1dfdf;
    }
    .store-content p {
        font-size: 13px;
        text-align: justify;
    }
    .store-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .mx-datepicker{
        width: 100%;
    }
    .draggable-outer-line{
    width: 100%;
    border: 2px dashed #9f9f9f;
    border-radius: 15px;
        &:hover{
            background-color: #e3e2e2;
            cursor: pointer;
        }
        .no-item{
            margin: 5%;
            min-height: 100px;
            .icon{
                text-align: center;
                margin-bottom: 10px;
            }
            .text{
                text-align: center;
                .add-file{
                    display: inline-block;
                    padding: 10px 15px;
                    border: 2px solid #c1c1c1;
                    border-radius: 5px;
                    font-weight: 600;
                }
            }
        }

    }

    .DropZone__Container{
        min-height: 200px;
        position: relative;
        flex: 1 1;
        .dropzone-first {
            position: relative;
            height: 100%;
        }
        .dropzone-second-div {
            display: grid;
            grid-gap: .8rem;
            -webkit-user-select: none;
            user-select: none;
            .btnImage {
                width: 100%;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                border: .1rem solid #c9cccf;
                background: #ffffff;
                border-radius: 6px;
                padding: 0;
                margin: 0;
                .checkbox-outer{
                    position: absolute;
                    z-index: 99;
                    top: 2%;
                    left: 5%;
                }
                .image-outer{
                    width: 100%;
                    display: flex;
                    overflow: hidden;
                    flex-direction: column;
                    z-index: 5;
                    align-items: center;
                    justify-content: center;
                    border-radius: 5px;
                    transform: translateZ(0);
                    &:after{
                        content: "";
                        display: block;
                        width: 100%;
                        padding-bottom: 100%;
                    }
                    img {
                        position: absolute;
                        z-index: 1;
                        max-width: 100%;
                        max-height: 100%;
                    }
                }   
            }
        }
    }


    @media (min-width: 0px) and (max-width: 991.98px)
    {
        .dropzone-second-div{
        grid-template-columns: repeat(3,1fr);
        }
    }

    @media (min-width: 992px)
    {
        .dropzone-second-div{
        grid-template-columns: repeat(4,1fr);
        }
    }
    
    .add-image{
        height: 100%;
        border: 0.2rem dashed #c9cccf !important;
        min-height: 150px;
        .image-outer-media{
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            flex-direction: column;
            text-align: center;
            padding: .8rem;
            cursor: pointer;
            outline: none;
            background: none;
            border: none;
            .add-image-link{
                margin-top: 40%;
            }
        }
    }
    .opacitityDown{
        background-color: #969393;
        opacity: 0.6;
    }

    .add-media-outer{
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: calc(100% - .4rem);
        border-radius: 3px;
        .add-image{
                position: absolute;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                flex-direction: column;
                text-align: center;
                padding: .8rem;
                cursor: pointer;
                outline: none;
                background: none;
                .add-image-link{
                    margin-top: 40%;
                }
        }
    }
     .pull-right{
        display:block;
        margin-bottom:20px;
        margin-left: auto;
    }
    .float-right{
        float: right;
    }
    .opacity-0{
        opacity: 0;
    }
    </style>