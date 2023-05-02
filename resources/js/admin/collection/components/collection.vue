<template>
  <div id="input-sizing">
    <ValidationObserver ref="collectionForm" v-slot="{ handleSubmit }">
      <form method="POST" enctype="multipart/form-data" id="frmAddEditCollection" @submit.prevent="handleSubmit(submit())" >
         <div class="row">
            <div class="col-md-8 col-12">
                  <!-- Basic details start -->
                  <div class="card">
                      <div class="card-body">
                         <div class="row">
                            <div class="col-12">
                              <ValidationProvider  name="Title" rules="required" v-slot="{ errors }">
                                <div class="form-group">
                                      <label class="required" for="title">{{ lang.cruds.collection.fields.title }}</label>
                                      <input class="form-control" type="text" placeholder="enter title" v-model="formData.title">
                                      <p class="text-danger">{{ errors[0] }}</p>
                                  </div>
                                </ValidationProvider>
                                  <div class="form-group">
                                      <label for="description">{{ lang.cruds.collection.fields.description }}</label>
                                      <ckeditor v-model="formData.description" />
                                  </div>
                                  <div class="demo-inline-spacing">
                                      <label class="required">{{ lang.cruds.collection.fields.description_position }}</label>

                                      <div class="custom-control custom-radio" v-for="(ldata, index) in list.description_position">
                                        <input type="radio" :id="`position_${index}`" class="custom-control-input" :value="index" v-model="formData.descriptionPosition" />
                                        <label class="custom-control-label" :for="`position_${index}`">{{ ldata }}</label>
                                      </div>
                                  </div>
                            </div>
                         </div>
                      </div>
                  </div>
                  <!-- Basic details end -->
                  <!-- Collection type start -->
                  <div class="card" v-if="checkVisibility">
                      <div class="card-header" v-if="type == 'add'">
                         <h4 class="card-title">{{ lang.cruds.collection.fields.collection_type }}</h4>
                      </div>
                      <div class="card-body">
                            <div class="row" v-if="type == 'add'">
                              <div class="col-12">
                                  <div class="form-group">
                                      <div class="custom-control custom-radio mb-1"  v-for="(ldata, index) in list.collection_type">
                                        <input type="radio" :id="`collection_type_${index}`" class="custom-control-input" :value="index" v-model="formData.collectionType" @change="initFeatherReplace()" />
                                        <label class="custom-control-label" :for="`collection_type_${index}`">{{ ldata }}</label>
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <hr v-if="type == 'add'" />
                            <div class="row" v-if="formData.collectionType">
                                <div class="col-12">
                                  <h4>Conditions</h4>
                                  <div class="demo-inline-spacing">
                                      <label>Products must match:</label>
                                      <div class="custom-control custom-radio mb-1" v-for="(ldata, index) in list.conditions_type">
                                        <input type="radio" :id="`conditions_type_${ index }`" class="custom-control-input"  :value="index" v-model="formData.conditionsType" />
                                        <label class="custom-control-label" :for="`conditions_type_${ index }`">{{ ldata }}</label>
                                      </div>
                                  </div>
                                </div>
                            </div>
                            <!-- repeater start -->
                            <div action="#" class="df-repeater"  v-if="formData.collectionType">
                              <ValidationObserver ref="conditionsForm" v-slot="{ invalid }">
                                 <form class="conditionsForm">
                                    <div v-for="(condition, index) in conditions">
                                      <div class="data-repeater-item">
                                        <div class="row d-flex align-items-end">
                                          <div class="col-md-4 col-12">
                                            <div class="form-group">
                                              <div class="form-group">
                                                  <select class="custom-select" v-model="condition.typeId" @change="changeConditions(condition.typeId,index)">
                                                    <option :value="ldata.id" v-for="(ldata, tindex) in list.condition_titles">{{ ldata.title }}</option>
                                                  </select>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <select class="custom-select" v-model="condition.conditionId"  @change="checkCondition(condition.typeId, condition.conditionId, index)">
                                                  <option :value="ldata.id" v-for="(ldata, cindex) in list.conditions" :disabled="!condition.enabledId.includes( ldata.id )">{{ldata.title }}</option>
                                                </select>
                                            </div>
                                          </div>
                                          <div class="col-12" :class="conditions.length > 1 ? 'col-md-3' : 'col-md-4' " >
                                            <div v-if="condition.isVisibled">
                                              <div class="form-group" v-if="condition.typeId == 1">
                                                 <ValidationProvider name="value"  rules="required" v-slot="{ errors }">
                                                    <span class="error text-danger small">{{ errors[0] }}</span>
                                                    <input class="form-control" type="text" placeholder="enter conditions" v-model="condition.value">
                                                  </ValidationProvider>
                                              </div>
                                              <div class="form-group" v-else-if="condition.typeId == 2">
                                              <ValidationProvider name="type"  rules="required" v-slot="{ errors }">
                                                <span class="error text-danger small">{{ errors[0] }}</span>
                                                <select class="custom-select sel-ptype" v-model="condition.value">
                                                  <option :value="index" v-for="(ldata, index) in list.product_types">{{ ldata }}</option>
                                                </select>
                                              </ValidationProvider>
                                              </div>
                                              <div class="form-group" v-else-if="condition.typeId == 3">
                                               <ValidationProvider name="vendor"  rules="required" v-slot="{ errors }">
                                                <span class="error text-danger small">{{ errors[0] }}</span>
                                                  <select class="custom-select sel-vendor" v-model="condition.value">
                                                   <option :value="index" v-for="(ldata, index) in list.vendors">{{ ldata }}</option>
                                                  </select>
                                                </ValidationProvider>
                                              </div>
                                              <div class="form-group" v-else-if="condition.typeId == 4 || condition.typeId == 6">
                                               <ValidationProvider name="numbers"  rules="required" v-slot="{ errors }">
                                                <span class="error text-danger small">{{ errors[0] }}</span>
                                                  <div class="input-group input-group-merge">
                                                    <div class="input-group-prepend">
                                                      <span class="input-group-text" id="basic-addon5">₹</span>
                                                    </div>
                                                     <input class="form-control" type="number" step="0.01" placeholder="0.00" v-model="condition.value">
                                                  </div>
                                                </ValidationProvider>
                                              </div>
                                              <div class="form-group" v-else-if="condition.typeId == 5">
                                               <ValidationProvider name="tag"  rules="required" v-slot="{ errors }">
                                                <span class="error text-danger small">{{ errors[0] }}</span>
                                                 <select class="custom-select sel-tags" v-model="condition.value">
                                                    <option :value="index" v-for="(ldata, index) in list.tags">{{ ldata }}</option>
                                                 </select>
                                               </ValidationProvider>
                                              </div>
                                              <div class="form-group" v-else-if="condition.typeId == 7">
                                              <ValidationProvider name="weight"  rules="required" v-slot="{ errors }">
                                              <span class="error text-danger small">{{ errors[0] }}</span>
                                                <div class="input-group input-group-merge">
                                                   <input class="form-control" type="number" v-model="condition.value">
                                                   <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon5">kg</span>
                                                  </div>
                                                </div>
                                              </ValidationProvider>
                                              </div>
                                              <div class="form-group" v-else-if="condition.typeId == 8">
                                              <ValidationProvider name="number"  rules="required" v-slot="{ errors }">
                                              <span class="error text-danger small">{{ errors[0] }}</span>
                                                <input class="form-control" type="number" v-model="condition.value">
                                              </ValidationProvider>
                                              </div>
                                              <div class="form-group" v-else-if="condition.typeId == 9">
                                              <ValidationProvider name="text"  rules="required" v-slot="{ errors }">
                                              <span class="error text-danger small">{{ errors[0] }}</span>
                                                <input class="form-control" type="text" placeholder="enter condition">
                                              </ValidationProvider>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="col-md-1 col-12 mb-30" v-if="conditions.length > 1">
                                            <div class="form-group">
                                              <button class="btn btn-outline-danger text-nowrap px-1" type="button" @click="removeConditionBlock(index)">
                                                <i class="fa fa-trash"></i>
                                              </button>
                                            </div>
                                          </div>
                                        </div>
                                        <hr />
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-12">
                                        <button class="btn btn-icon btn-primary" type="button" @click="addConditionBlock()">
                                          <i data-feather="plus" class="mr-25"></i>
                                          <span>{{ lang.global.add }} {{ lang.global.new }}</span>
                                        </button>
                                         <button class="btn btn-icon btn-primary" type="button" @click="validateCondition()" v-if="type == 'edit'">
                                          <span>Load Products</span>
                                        </button>
                                      </div>
                                    </div>
                                </form>
                              </ValidationObserver> 
                            </div>
                            <!-- repeater end -->
                      </div>
                  </div> 
                  <!-- Collection type end -->
                  <!-- Products sections start -->
                  <div class="card"  v-if="type == 'edit'">
                      <div class="card-header">
                         <h4 class="card-title">{{ lang.cruds.product.title }}</h4>
                      </div>
                      <div class="card-body">
                            <div class="row">
                              <div class="col-6" v-if="formData.collectionType == 0">
                                  <div class="form-group">
                                      <button type="button" class="btn btn-primary btn-block waves-effect waves-float waves-light"  data-toggle="modal" data-target="#browseProducts">Browse</button>
                                  </div>
                              </div>
                              <div :class="formData.collectionType == 0 ? 'col-6' : 'col-12'">
                                  <div class="form-group">
                                     <select class="custom-select" id="sortType" v-model="formData.sortType" @change="getSortProducts()">
                                       <option v-for="(sortType, index) in list.product_sort_types" :value="index">{{sortType}}</option>
                                     </select>
                                  </div>
                              </div>
                            </div>
                            <hr />
                            <!-- repeater start -->
                            <div id="productList">
                              <div class="product-repeater" id="productRepeater" v-if="products.length > 0">
                                  <draggable v-model="products" :disabled="!enabled" @end="changeOrder">
                                    <div v-for="(product, index) in products" :step="count = index+1">
                                      <div class="product-repeater-item">
                                        <div class="d-flex product-list">
                                            <div class="product-index mr-2 ml-2">{{ count }}.</div>
                                            <div class="product-image mr-2">
                                                <img :src="product.medias[0].image_src[1]" :alt="product.title" v-if="product.medias.length > 0" @error="setAltImg" height="50" width="50">
                                                <img src="" :alt="product.title"  height="50" width="50" @error="setAltImg" v-else>
                                            </div>
                                            <div class="product-detail d-flex mr-2">
                                               <div class="product-title">
                                                    <a :href="'/admin/products/'+product.id+'/edit'">{{product.title}}</a>
                                               </div>
                                               <div class="product-status ml-auto">
                                                 <button
                                                    type="button"
                                                    class="btn"
                                                    data-toggle="popover"
                                                    data-content="Online"
                                                    data-original-title="SALES CHANNELS STATUS"
                                                    data-trigger="click"
                                                    data-placement="bottom"
                                                  >
                                                  <div class="badge " :class="product.status == 1 ? 'badge-success' : 'badge-danger' ">
                                                     {{ list.online_store[product.status] }}
                                                  </div>
                                                  </button>
                                              </div>
                                             <!--  <div class="remove-product">
                                                <a class="pointer">X</a>
                                              </div> -->
                                            </div>
                                        </div>
                                        <hr />
                                      </div>
                                    </div>
                                  </draggable>
                              </div>
                              <div class="product-repeater text-center p-2" v-else>
                                  <!-- <p class="not-found">{{ lang.global.data_not_found }}</p> -->
                                  <div class="h1 mb-2"><i class="fa fa-tag"></i></div>
                                  <p class="not-found" style="line-height: 20px;font-size: 12px;">There are no products in this collection.
                                  </br> Add or change conditions to add products.</p>
                              </div>
                              <div class="text-center py-1" v-if="products.length != addProductList.length" id="showAllProducts">
                                  <a class="pointer" @click.prevent="showAllProducts">Show more products</a>
                              </div>
                            </div>
                            <!-- repeater end -->
                      </div>
                  </div> 
                  <!-- Products sections end -->
                  <!-- SEO start -->
                  <div class="card">
                      <div class="card-header">
                         <h4 class="card-title">Search engine listing preview</h4>
                      </div>
                      <div class="card-body">
                         <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                  <label for="seo_keywords">{{ lang.cruds.collection.fields.seo_keywords }}</label>
                                  <input class="form-control" type="text" placeholder="enter seo keywords" v-model="formData.seoKeywords">
                              </div>
                              <div class="form-group">
                                  <label for="seo_description">{{ lang.cruds.collection.fields.seo_description }}</label>
                                  <textarea class="form-control" placeholder="enter seo description" name="seo_description" id="seo_description" v-model="formData.seoDescription"></textarea>
                              </div>
                            </div>
                         </div>
                      </div>
                  </div>
                  <!-- SEO end -->
            </div>
            <div class="col-md-4 col-12">
               <div class="card">
                  <div class="card-header">
                     <h4 class="card-title">Collection availability</h4>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-12">
                          <!--  <p class="card-text mb-2">
                              Be sure to use <code>.col-form-label-sm</code> or <code>.col-form-label-lg</code> to your
                              <code>&lt;label&gt;</code>s or <code>&lt;legend&gt;</code>s to correctly follow the size of
                              <code>.form-control-lg</code> and <code>.form-control-sm</code>.
                           </p> -->
                         <!--   <div class="demo-inline-spacing">
                              <label class="required">{{ lang.cruds.collection.fields.status }}</label>
                               <div class="custom-control custom-radio" v-for="(ldata, index) in list.status">
                                  <input type="radio" :id="`status_${index}`" name="status" class="custom-control-input" :value="index" v-model="formData.status" />
                                  <label class="custom-control-label" :for="`status_${index}`">{{ ldata }}</label>
                                </div>
                           </div> -->
                           <div class="form-group">
                              <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_track" value="1" v-model="formData.onlineStore"/>
                                <label class="custom-control-label" for="is_track">{{ lang.cruds.collection.fields.online_store }}</label>
                              </div>
                              <div class="mt-1" v-if="formData.onlineStore && scheduleinfo == ''">
                                <a href="#" data-toggle="modal" data-target="#scheduleModal"><small>{{ lang.global.schedule }} {{ lang.global.availibility }}</small></a>
                              </div>
                              <div class="mt-1" v-if="scheduleinfo != ''">
                                  <p>{{scheduleinfo}}</p>
                                  <a href="#" data-toggle="modal" data-target="#scheduleModal" class="mr-1"><small>{{ lang.global.edit }}</small></a>
                                  <a @click="cancelSchedule()"><small>{{ lang.global.cancel }}  schedule</small></a>
                              </div>
                           </div>
                           <div class="form-group" style="display: none;">
                              <label for="schedule_time">{{ lang.cruds.collection.fields.schedule_time }}</label>
                              <input class="form-control datetime" type="text" placeholder="enter schedule time" name="schedule_time" id="schedule_time" v-model="formData.scheduleTime">
                          </div>
                        </div>
                     </div>
                  </div>
               </div>
                <div class="card">
                  <div class="card-header">
                     <h4 class="card-title">Collection image</h4>
                  </div>
                  <div class="card-body">
                     <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                               <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" 
                               v-on:vdropzone-success="uploadSuccess" v-on:vdropzone-max-files-reached="maxLimit" v-on:vdropzone-files-added="removeIndexZero" :useCustomSlot=true >
                                  <div class="dropzone-custom-content">
                                    <h5 class="dropzone-custom-title">Drag and drop to upload content!</h5>
                                    <div class="subtitle">...or click to select a file from your computer</div>
                                  </div>
                               </vue-dropzone>
                            </div>
                            <div class="form-group">
                                <label for="src_alt_text">{{ lang.cruds.collection.fields.src_alt_text }}</label>
                                <input class="form-control" type="text" placeholder="enter src alt text" v-model="formData.srcAltText">
                            </div>
                          </div>
                     </div>
                  </div>
               </div>
            </div>
          </div>
          <div class="form-group">
              <button class="btn btn-danger" type="submit">
                  {{ lang.global.save }}
              </button>
              <button class="btn btn-danger" type="reset" id="reset" style="display: none">Reset</button>
          </div>
          <input type="hidden" class="collection-file" v-model="formData.url">
      </form>
    </ValidationObserver>
        <!-- Shedule Modals start -->
        <div class="modal fade text-left" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <form class="add-new-record modal-content pt-0">
                  <input type="hidden" id="vendorId">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title" id="myModalLabel1">Schedule Online Store availability</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                          <div class="col-12">
                            <p>Product will be available on</p>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                <!-- Date Picker -->
                                    <div class="input-group mb-2">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i data-feather='calendar'></i></span>
                                      </div>
                                      <input
                                        type="text"
                                        class="form-control"
                                        v-model="formData.scheduleDate"
                                        placeholder="YYYY-MM-DD"
                                        aria-label="YYYY-MM-DD"
                                      />
                                    </div>
                                 </div>
                                  <div class="form-group text-center">
                                      <date-picker
                                       v-model="formData.scheduleDate" 
                                       :inline="true" 
                                       format="Y-MM-DD"
                                       value-type="format"
                                       :disabled-date="notBeforeToday"
                                       type="date"></date-picker>
                                  </div>
                             </div>
                                <!-- Time Picker -->
                              <div class="col-6">
                                <div class="form-group">
                                   <div class="input-group mb-2">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i data-feather='clock'></i></span>
                                      </div>
                                      <input
                                        type="text"
                                        class="form-control"
                                        v-model="formData.scheduleTime"
                                        placeholder="hh:mm"
                                        aria-label="hh:mm"
                                      />
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <date-picker
                                      v-model="formData.scheduleTime"
                                      :minute-step="30"
                                      format="hh:mm a"
                                      value-type="format"
                                      type="time"
                                      placeholder="hh:mm a"
                                      :inline="true"
                                    ></date-picker>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ lang.global.cancel }}</button>
                      <button type="button" class="btn btn-primary data-edit" @click="scheduleDate">{{ lang.global.schedule }} {{ lang.global.avalibity }}</button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
        <!-- Shedule Modals end -->
        <!-- Modal -->
        <div class="modal fade" id="browseProducts" tabindex="-1" role="dialog" aria-labelledby="browseProductsTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="browseProductsTitle">Add products</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="infinite-list">
                  <div class="input-group input-group-merge mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text" id="basic-addon-search2"><i data-feather="search"></i></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon-search2" v-model="searchVal" @keyup="loadMoreCall()"/>
                  </div>
                  <div class="browse-product-list">
                      <div class="d-flex browse-list py-1 border-bottom" v-for="(product, index) in allproducts">
                        <div class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input mt-1" :id="`customCheck_`+ product.id" style="opacity:1" :value="product.id" v-model="addProductList"/>
                        </div>
                        <div class="product-thumb mx-1">
                            <img :src="product.medias[0].image_src[1]" :alt="product.title" v-if="product.medias.length > 0" height="50" width="50" @error="setAltImg">
                            <img src="" :alt="product.title"  height="50" width="50" @error="setAltImg" v-else>
                        </div>
                        <div class="product-title">
                            {{product.title}}
                        </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addProduct" @click="addProduct()">Add</button>
              </div>
            </div>
          </div>
        </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import draggable from 'vuedraggable'
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import moment from 'moment'

export default {
  props: ['list', 'type', 'data'],
  name:'Collection',
  data() {
    return {
      formData:{
        title:'',
        description:'',
        collectionType:0,
        conditionsType:0,
        descriptionPosition:0,
        seoKeywords:'',
        seoDescription:'',
        status:1,
        srcAltText:'',
        onlineStore:0,
        scheduleDate:  moment(new Date()).format('YYYY-MM-DD'),
        scheduleTime: moment(new Date().setHours(new Date().getHours(), 30, 0, 0)).format('HH:mm a'),
        sortType:1,
        url:'',
        is_schudule:0,
        schedule_time:''
      },
      conditions:[
        {
          typeId:1,
          conditionId:1,
          value:null,
          enabledId:[1,2,5,6,7,8],
          isVisibled:true
        }
      ],
      products:[],
      enabled:false,
      dragging:false,
      dropzoneOptions: {
          url: storeMediaUrl,
          thumbnailWidth: 150,
          maxFilesize: 5,
          maxFiles: 2,
          acceptedFiles: '.jpeg,.jpg,.png,.gif',
          headers: {
            'X-CSRF-TOKEN': window.token.content
          }
      },
      src:'',
      hours: Array.from({ length: 10 }).map((_, i) => i + 8),
      scheduleinfo:'',
      allproducts:[],
      loading: false,
      nextItem: 0,
      searchVal:'',
      timer:undefined,
      addProductList:[]
    }
  },
  mounted(){
    if(this.src != '' && this.src != null){
      var file = { size: this.src.size, name: this.src.name, type: this.src.mime_type };
      var url = this.src.url;
      this.$refs.myVueDropzone.manuallyAddFile(file, url);
    }
     // Detect when scrolled to bottom.
    const listElm = document.querySelector('#infinite-list');
      listElm.addEventListener('scroll', e => {
        if(listElm.scrollTop + listElm.clientHeight >= listElm.scrollHeight) {
          var sHeight = document.getElementById("infinite-list").scrollHeight;
          document.getElementById("infinite-list").scrollTop = sHeight - (sHeight / 2);
          let section = $('#infinite-list');
          blockSection(section);
          clearTimeout(this.timer);
          this.timer = setTimeout(() => {
             this.loadMore();
          }, 1000)
        }
    });

    // Initially load some items.
    if(this.formData.collectionType == 0){
      this.loadMore();
    }
  },
  components: {
    draggable,
    vueDropzone: vue2Dropzone,
    DatePicker
  },
  computed: {
    noImage(){
       return '/assets/images/no-image.jpg';
    },
    checkVisibility(){
      return (this.type == 'add') || (this.type == 'edit' && this.formData.collectionType == 1) ? true : false;
    }
  },
  created() {
    if(this.type == 'edit'){
        this.setCollectionData();
    }
  },
  methods: {
    validateCondition(){
      this.$refs.conditionsForm.validate().then(success => {
        if(success){
          this.getConditionProducts();
        }
      });
    },
    setAltImg(event){
       event.target.src = this.noImage;
    },
    loadMoreCall(){
      this.nextItem = 0;
      this.allproducts = [];

      clearTimeout(this.timer)
      this.timer = setTimeout(() => {
         this.loadMore();
      }, 2000)
    },
    loadMore(){
      let section = $('#infinite-list');
      blockSection(section);
      let data = {
        'number': this.nextItem,
        'search': this.searchVal,
      };
      this.$store.dispatch("collectionModule/loadMoreProduct", data)
      .then((res) => {
          if (res.response.status_code == 2044) {
              unblockSection(section);
              let self = this;
              res.response.data.forEach(product => {
                  self.allproducts.push(product);
              });
              this.nextItem = this.nextItem + 20;
          }
          else
            {
              errorModal(res.response.message);
            }
      })
      .catch((err) => {
          unblockSection(section);
          errorModal(err.response.message);
      });
    },
    showAllProducts(){
      let section = $('#showAllProducts');
      blockSection(section);
      this.$store.dispatch("collectionModule/ShowAllProducts", this.formData.id)
      .then((res) => {
          if (res.response.status_code == 2044) {
              unblockSection(section);
              let self = this;
              // res.response.data.forEach(product => {
              //     self.products.push(product);
              // });
              $.each(res.response.data, function(key, product) {
                self.products.push(product);
              });
          }
          else
            {
              errorModal(res.response.message);
            }
      })
      .catch((err) => {
          unblockSection(section);
          errorModal(err.response.message);
      });
    },
    addProduct(){
      let section = $('#addProduct');
      blockSection(section);
      let data = {
        'cid': this.formData.id,
        'products': this.addProductList,
      };
      this.$store.dispatch("collectionModule/AddProduct", data)
      .then((res) => {
          if (res.response.status_code == 2040) {
             successModal(res.response.message);
             this.products = res.response.data;
             $('#browseProducts').modal('hide');
          }
          else{
            errorModal(res.response.message);
          }
          unblockSection(section);
      })
      .catch((err) => {
          unblockSection(section);
          errorModal(err.response.message);
      });
    },
    notBeforeNineOClock(date) {
      // return date < new Date(new Date().setHours(12, 0, 0, 0));
      return date.getHours() < 9;
    },
    notBeforeToday(date) {
      return date < new Date(new Date().setHours(0, 0, 0, 0));
    },
    initFeatherReplace(){
          setTimeout(function () {
              feather.replace();
          }, 500)
      },
    cancelSchedule(){
        this.scheduleinfo = '';
        this.formData.scheduleDate = moment(new Date()).format('YYYY-MM-DD');
        this.formData.scheduleTime = moment(new Date()).format('HH:mm a');
        this.formData.is_schudule = 0;
        this.formData.schedule_time = '';
    },
    scheduleDate(){
      if( (this.formData.scheduleDate != '' && this.formData.scheduleTime != '') && this.formData.onlineStore) {
        this.scheduleinfo = 'Scheduled for '+ moment(this.formData.scheduleDate).format('MMMM Do YYYY') + ', ' + this.formData.scheduleTime;
        $('#scheduleModal').modal('hide');
        this.formData.is_schudule = 1;
      }
    },
    submit(){
              this.$refs.collectionForm.validate().then(success => {
              if (!success) {
                   $("html, body").animate({ scrollTop: 50 }, 200);
                return;
              }
              this.formData.conditions = this.conditions;
              // this.formData.url = this.url;
              this.formData.onlineStore = this.formData.onlineStore ? 1 : 0;

              openLoader();
              if(this.type == 'add')
              {
                this.$store.dispatch("collectionModule/AddCollection", this.formData)
                .then((res) => {
                    if (res.response.status_code == 2037) {
                        successModal(res.response.message);
                        setTimeout(function(){
                          window.location = res.response.data;
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
              }
              else
              {
                this.$store.dispatch("collectionModule/EditCollection", this.formData)
                .then((res) => {
                    if (res.response.status_code == 2039) {
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
              }
      });
    },
    setCollectionData(){
      let collectionDetails = this.data.collectionDetails;
      let list = this.list;
      this.addProductList = collectionDetails.products;

      this.formData = {
        id: collectionDetails.id,
        title : collectionDetails.title,
        description : collectionDetails.description,
        collectionType : collectionDetails.collection_type,
        conditionsType : collectionDetails.conditions_type,
        descriptionPosition : collectionDetails.description_position,
        seoKeywords : collectionDetails.seo_keywords,
        seoDescription : collectionDetails.seo_description,
        status : collectionDetails.status,
        srcAltText : collectionDetails.src_alt_text,
        onlineStore : collectionDetails.online_store == 1 ? true : false,
        scheduleDate: moment(new Date()).format('YYYY-MM-DD'),
        scheduleTime: moment(new Date()).format('HH:mm a'),
        sortType : collectionDetails.sorted_type,
        url:  collectionDetails.url,
        is_schudule:0,
        schedule_time:collectionDetails.schedule_time
      };
      
      if(collectionDetails.schedule_time != '' && collectionDetails.schedule_time != null){
        this.formData.scheduleDate = moment(collectionDetails.schedule_time).format('YYYY-MM-DD');
        this.formData.scheduleTime = moment(collectionDetails.schedule_time).format('HH:mm a');
        this.scheduleinfo = 'Scheduled for '+ moment(this.formData.scheduleDate).format('MMMM DD YYYY') + ', ' + this.formData.scheduleTime;
      }

      this.src = collectionDetails.src;
      if(collectionDetails.sorted_type == 8){
          this.enabled = true;
      }

      if(collectionDetails.collection_conditions.length > 0){
        this.conditions = [];
        let objConditions = [];
        $.each(collectionDetails.collection_conditions, function(key, value) {
            var title = list.condition_titles.filter((title) => { 
              return title.id == value.collection_title_id;
            });
            var conditions = title[0].collection_condition.split(',');
            var newArray = conditions.map((i) => Number(i)); // convert string to number
            var isVisibled = true;
            if(value.condition_id == 9 || value.condition_id == 10){
              isVisibled = false;
            }
            objConditions.push({
                typeId:value.collection_title_id,
                conditionId:value.condition_id,
                value:value.value,
                enabledId: newArray,
                isVisibled:isVisibled   
            })
        });
        this.conditions = objConditions;
      }
      this.getConditionProducts();
    },
    addConditionBlock(){
      this.conditions.push({
          typeId:1,
          conditionId:1,
          value:null,
          enabledId:[1,2,5,6,7,8],
          isVisibled:true
      });

    },
    removeConditionBlock(index){
      this.conditions.splice(index, 1);
    },
    changeConditions(typeId, index){
      let title = this.list.condition_titles.filter((title) => { 
          return title.id == typeId;
      });
      let conditions = title[0].collection_condition.split(',');
      const newArray = conditions.map((i) => Number(i)); // convert string to number

      this.conditions[index].enabledId = newArray;
      this.conditions[index].value = '';
      this.conditions[index].isVisibled = true;
      this.conditions[index].conditionId = 1;
    },
    checkCondition(typeId, conditionId, index){
        if(typeId == 6){
            if(conditionId == 9 || conditionId == 10){
              this.conditions[index].isVisibled = false;
            } else {
              this.conditions[index].isVisibled = true;
            }
        }
    },
    getConditionProducts(){
        let data = {
            id: this.formData.id,
            sortType: this.formData.sortType,
            conditionId: this.formData.conditionsType,
            collectionConditions: this.conditions
        };

        let section = $('#productList');
        blockSection(section);
        this.$store.dispatch("collectionModule/GetConditionProducts", data)
        .then((res) => {
            if (res.response.status_code == 2044) {
                this.products = res.response.data;
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
    },
    getSortProducts(){
        if(this.formData.sortType == 8) { //for manual
          this.enabled = true;
        } else {
           this.enabled = false;
           let data = {id:this.formData.id, sortType: this.formData.sortType}
           let section = $('#productList');
           blockSection(section);
           this.$store.dispatch("collectionModule/GetSortProducts", data)
            .then((res) => {
                if (res.response.status_code == 2043) {
                    unblockSection(section);
                    successModal(res.response.message);
                    this.products = res.response.data;
                }
                else{
                  errorModal(res.response.message);
                }
            })
            .catch((err) => {
                unblockSection(section);
                errorModal(err.response.message);
            });
        }
    },
    changeOrder(){
      let productIds = [];
      this.products.map( product => {
        productIds.push(product.id);
      })
      let data = {id:this.formData.id, order: productIds, sid: this.formData.sortType}
      let section = $('#productList');
      blockSection(section);
      this.$store.dispatch("collectionModule/ChangeSortOrder", data)
      .then((res) => {
          if (res.response.status_code == 2045) {
              unblockSection(section);
              successModal(res.response.message);
          }
          else{
            errorModal(res.response.message);
          }
      })
      .catch((err) => {
          unblockSection(section);
          errorModal(err.response.message);
      });
    },
    uploadSuccess(file, response){
        this.formData.url = response.name;
    },
    maxLimit(file){
      if (file.length > 1) {
        this.$refs.myVueDropzone.removeFile(file[0]);
      }
    },
    removeIndexZero(file){
      if (file.length > 1) {
        this.$refs.myVueDropzone.removeFile(file[0]);
      }
    }
  }
}
</script>

<style scoped>
   .product-detail {
        flex-grow: 1;
    }
    .product-title {
      display: flex;
      flex-flow: column nowrap;
      justify-content: center;
      align-items: flex-start;
      color: #000;
    }
    .product-list{
      line-height: 25px;
    }
    .product-index {
        width: 25px;
        text-align: center;
    }
    .product-image img{
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }
    .product-title a{
        color: #000;
    }
    .dropzone-custom-title {
      margin-top: 0;
      color: #7367f0;
      text-align: center;
    }
    .subtitle {
      color: #314b5f;
    }
</style>