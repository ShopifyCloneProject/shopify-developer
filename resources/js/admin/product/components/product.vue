<template>
  <div id="input-sizing">
     <ValidationObserver ref="productForm" v-slot="{ handleSubmit }">
        <form @submit.prevent="handleSubmit(saveProduct())">
          <div class="row">
            <div class="col-md-8 col-12">
                  <!-- Basic details start -->
                  <div class="card">
                      <div class="card-body">
                            <ValidationProvider  name="Title" rules="required" v-slot="{ errors }">
                              <div class="form-group">
                                  <label for="largeInput">{{ lang.cruds.product.fields.title }} <span class="text-danger">*</span></label>
                                  <input class="form-control" type="text" placeholder="enter title" name="title" v-model="product.title">
                                    <p class="text-danger">{{ errors[0] }}</p>
                              </div>
                            </ValidationProvider>
                             <div class="form-group">
                                  <label for="largeInput">{{ lang.cruds.product.fields.description }}</label>
                                  <ckeditor v-model="product.description" placeholder="Enter product description"/>
                             </div>
                        </div>
                  </div>
                  <!-- Basic details end -->
                  <!-- Media start -->
                  <div class="card" id="mediaDataId">
                      <div class="card-header">
                         <h4 class="card-title">{{ lang.global.media }}</h4>
                         <button v-if="showDeleteBtn" @click.prevent="deleteMedia" type="button" class="btn btn-outline-warning waves-effect pull-right">{{ lang.global.deleteselectmedia }}</button>
                      </div>
                      <div class="card-body">
                        <section>
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
                                                    <img class="main-image" :src="imagedata.image_src[3]"  
                                                    :alt="imagedata.src_alt_text">
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
                        
                        <!-- multi file upload ends -->
                      </div>
                  </div>
                  <!-- Media end -->
                  <!-- Pricing start -->
                  <div class="card" id="pricingdiv">
                      <div class="card-header">
                         <h4 class="card-title">{{ lang.global.pricing }}</h4>
                      </div>
                      <div class="card-body">
                         <div class="row">
                            <div class="col-6">
                             <div class="form-group">
                              <label for="price">{{ lang.cruds.product.fields.price }}</label>
                              <div class="input-group input-group-merge mb-2">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="basic-addon5">{{ lang.global.current_currency }}</span>
                                </div>
                                 <input class="form-control" type="number" v-model="product.price" placeholder="0.00" @blur="calculateMainMarginProfit()">
                              </div>
                            </div>
                            </div>
                            <div class="col-6">
                              <div class="form-group">
                                   <label for="compare_at_price">{{ lang.cruds.product.fields.compare_at_price }}</label>
                                  <div class="input-group input-group-merge mb-2">
                                     <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon5">{{ lang.global.current_currency }}</span>
                                     </div>
                                     <input class="form-control" type="number" v-model="product.compare_at_price" placeholder="0.00" @blur="calculateMainMarginProfit()">
                                 </div>
                              </div>
                            </div>
                         </div>
                         <hr />
                         <div class="row">
                            <div class="col-6">
                              <div class="form-group">
                               <label for="cost_per_item">{{ lang.cruds.product.fields.cost_per_item }}</label>
                                <div class="input-group input-group-merge mb-2">
                                   <div class="input-group-prepend">
                                      <span class="input-group-text" id="basic-addon5">{{ lang.global.current_currency }}</span>
                                   </div>
                                   <input class="form-control" type="number" v-model="product.cost_per_item" placeholder="0.00" @blur="calculateMainMarginProfit()">
                                </div>
                              </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                   <label>{{ lang.global.margin }}</label>
                                   <br>
                                   <label>{{ totalMainMargin }}</label>
                                  </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                   <label>{{ lang.global.profit }}</label>
                                   <br>
                                   <label>{{ totalMainProfit }}</label>
                                  </div>
                            </div>
                         </div>
                         <div class="row">
                              <div class="col-12">
                                    <div class="custom-control custom-checkbox">
                                      <input type="checkbox" class="custom-control-input" id="is_product_charge" v-model="product.is_product_charge" />
                                      <label class="custom-control-label" for="is_product_charge">{{ lang.global.charge_tax_variant }}</label>
                                    </div>
                              </div>
                         </div>
                      </div>
                  </div>
                  <!-- Pricing end -->
                  <!-- Inventory start -->
                  <div class="card" id="inventorydiv">
                      <div class="card-header">
                         <h4 class="card-title">{{ lang.global.inventory }}</h4>
                      </div>
                      <div class="card-body">
                          <div class="row">
                            <div class="col-6">
                               <div class="form-group">
                                   <label for="sku">{{ lang.cruds.product.fields.sku }}</label>
                                   <input class="form-control" type="text" placeholder="enter sku" v-model="product.sku" @change="changeAllSku()">
                                </div>
                            </div>
                            <div class="col-6">
                               <div class="form-group">
                                   <label for="barcode">{{ lang.cruds.product.fields.barcode }}</label>
                                   <input class="form-control" type="text" placeholder="enter barcode" v-model="product.barcode">
                                </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-12">
                               <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                      <input type="checkbox" class="custom-control-input" id="is_track" v-model="product.is_track" />
                                      <label class="custom-control-label" for="is_track">{{ lang.cruds.product.fields.is_track }}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                  <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="is_continue_selling" v-model="product.is_continue_selling" >
                                    <label class="custom-control-label" for="is_continue_selling">{{ lang.cruds.product.fields.is_continue_selling }}</label>
                                  </div>
                                </div>
                                <div class="form-group" v-if="list.action == 'add'">
                                  <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="handleSameQty" v-model="handleSameQty" >
                                    <label class="custom-control-label" for="handleSameQty">{{ lang.global.sameqty}}</label>
                                  </div>
                                </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-8">
                                <h5>{{ lang.global.location }} {{ lang.global.name }}</h5>
                            </div>
                            <div class="col-4">
                                <h5>{{ lang.global.available }} {{ lang.global.qty }}</h5>
                            </div>
                          </div>
                          <hr>
                          <div class="row" v-for="(locationData,locationIndex) in locations">
                              <div class="col-8">
                                 <div class="form-group">
                                  <h5>{{ locationData.name }}</h5>
                                </div>
                              </div>
                              <div class="col-4">
                                 <div class="form-group">
                                  <input class="form-control" type="number" v-model="locationData.available">
                                </div>
                              </div>
                          </div>
                          <hr />
                          <div class="row">
                            <div class="col-6">
                               <div class="form-group">
                                   <label for="min_order_limit">{{ lang.cruds.product.fields.min_order_limit }}</label>
                                   <input class="form-control" type="number" v-model="product.min_order_limit">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                   <label for="max_order_limit">{{ lang.cruds.product.fields.max_order_limit }}</label>
                                   <input class="form-control" type="number" v-model="product.max_order_limit">
                                </div>
                            </div>
                          </div>
                          <hr />
                          <div class="row">
                              <div class="col-12">
                                   <div class="demo-inline-spacing mb-2">
                                       <div class="custom-control custom-checkbox">
                                          <input class="custom-control-input" type="checkbox" v-model="product.is_cod_enabled" id="is_cod_enabled" >
                                          <label class="custom-control-label" for="is_cod_enabled">{{ lang.cruds.product.fields.is_cod_enabled }}</label>
                                       </div>
                                   
                                       <div class="custom-control custom-checkbox">
                                          <input class="custom-control-input" type="checkbox" name="is_size_chart_enabled" id="is_size_chart_enabled" v-model="product.is_size_chart_enabled">
                                          <label class="custom-control-label" for="is_size_chart_enabled">{{ lang.cruds.product.fields.is_size_chart_enabled }}</label>
                                       </div>
                                   
                                       <div class="custom-control custom-checkbox">
                                          <input class="custom-control-input" type="checkbox" name="is_special_product" id="is_special_product" v-model="product.is_special_product">
                                          <label class="custom-control-label" for="is_special_product">{{ lang.cruds.product.fields.is_special_product }}</label>
                                       </div>
                                    </div>
                              </div>
                          </div>
                          <hr />
                          <div class="row">
                              <div class="col-12">
                                  <div class="form-group">
                                       <div class="custom-control custom-checkbox">
                                          <input class="custom-control-input" type="checkbox" name="special_product_status" id="special_product_status" v-model="product.special_product_status" @change="initFlatPicker();">
                                          <label class="custom-control-label" for="special_product_status">{{ lang.cruds.product.fields.special_product_status }}</label>
                                       </div>
                                    </div>
                              </div>
                              <div class="col-6 special_price_dv" v-if="product.special_product_status">
                                  <div class="form-group">
                                       <label for="special_price">{{ lang.cruds.product.fields.special_price }}</label>
                                      <div class="input-group input-group-merge mb-2">
                                         <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon5">{{ lang.global.current_currency }}</span>
                                         </div>
                                         <input class="form-control" type="number" v-model="product.special_price" placeholder="0.00">
                                       </div>
                                  </div>
                              </div>
                              <div class="col-6 special_price_dv" v-if="product.special_product_status">
                                  <div class="form-group">
                                       <label for="expiry_date">{{ lang.cruds.product.fields.expiry_date }}</label>
                                       <input class="form-control flatpickr-basic date" type="text" name="expiry_date" id="expiry_date" v-model="product.expiry_date"  placeholder="YYYY-MM-DD">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- Inventory end -->
                  <!-- Shipping start -->
                  <div class="card" id="shippingdiv">
                      <div class="card-header">
                         <h4 class="card-title">{{ lang.global.shipping }}</h4>
                      </div>
                      <div class="card-body">
                         <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                   <div class="custom-control custom-checkbox">
                                      <input class="custom-control-input" type="checkbox" name="is_physical_product" id="is_physical_product" v-model="product.is_physical_product">
                                      <label class="custom-control-label" for="is_physical_product">{{ lang.cruds.product.fields.is_physical_product }}</label>
                                   </div>
                                </div>
                            </div>
                         </div>
                          <div class="row is-physical-dv" v-if="product.is_physical_product">
                          <hr />
                            <div class="col-6">
                                <div class="form-group ">
                                    <label for="weight">{{ lang.cruds.product.fields.weight }}</label>
                                    <div class="row">
                                        <div class="col-8">
                                            <input class="form-control" type="number" name="weight" id="weight"  placeholder="0.0" v-model="product.weight">
                                        </div>
                                        <div class="col-4">
                                            <select class="custom-select" name="weight_type_id" id="weight_type_id" v-model="product.weight_type_id">
                                                <option :value="index" v-for="(ldata, index) in list.weight_type">{{ ldata }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group ">
                                    <label for="length">{{ lang.cruds.product.fields.length }}</label>
                                    <div class="row">
                                        <div class="col-8">
                                          <input class="form-control" type="number" name="length" id="length"  placeholder="0.0" v-model="product.length">
                                        </div>
                                        <div class="col-4">
                                            <select class="custom-select" name="length_type_id" id="length_type_id" v-model="product.length_type_id">
                                                <option :value="index" v-for="(ldata, index) in list.dimension_types">{{ ldata }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group ">
                                    <label for="width">{{ lang.cruds.product.fields.width }}</label>
                                    <div class="row">
                                        <div class="col-8">
                                            <input class="form-control" type="number" name="width" id="width"  placeholder="0.0" v-model="product.width">
                                        </div>
                                        <div class="col-4">
                                            <select class="custom-select" name="width_type_id" id="width_type_id" v-model="product.width_type_id">
                                                <option :value="index" v-for="(ldata, index) in list.dimension_types">{{ ldata }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group ">
                                    <label for="height">{{ lang.cruds.product.fields.height }}</label>
                                    <div class="row">
                                        <div class="col-8">
                                            <input class="form-control" type="number" name="height" id="height"  placeholder="0.0" v-model="product.height">
                                        </div>
                                        <div class="col-4">
                                            <select class="custom-select" name="height_type_id" id="height_type_id" v-model="product.height_type_id">
                                                <option :value="index" v-for="(ldata, index) in list.dimension_types">{{ ldata }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="row is-physical-dv" v-if="product.is_physical_product">
                          <hr />
                              <div class="col-12">
                                   <div class="form-group">
                                       <label for="country_id">{{ lang.cruds.product.fields.country }}</label>
                                       <select class="custom-select" name="country_id" id="country_id" v-model="product.country_id">
                                          <option :value="ldata.id" v-for="(ldata, index) in list.countries">{{ldata.name }}</option>
                                       </select>
                                    </div>
                                    <div class="form-group">
                                       <label for="hs_code">{{ lang.cruds.product.fields.hs_code }}</label>
                                       <input class="form-control" type="text" placeholder="enter hs code" name="hs_code" id="hs_code" v-model="product.hs_code">
                                    </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- Shipping end -->
                  <!-- Variants start -->
                  <div class="card" id="variantdiv">
                      <div class="card-header">
                         <h4 class="card-title">Variants</h4>
                      </div>
                      <div class="card-body">
                         <div class="row">
                            <div class="col-12">
                               <div class="form-group">
                                  <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" name="is_variant" id="is_variant" v-model="product.is_product_variant">
                                    <label class="custom-control-label" for="is_variant">This product has multiple options, like different sizes or colors</label>
                                  </div>
                               </div>
                            </div>
                         </div>
                         <hr v-show="product.is_product_variant" />
                          <!-- Variants repeater start -->
                          <div v-show="product.is_product_variant">
                              <div v-for="(variantData, index) in objVariantData" :step="counter = index +1">
                                <div class="row d-flex align-items-end" v-if>
                                  <div class="col-md-3 col-12">
                                    <div class="form-group">
                                       <h5>Option {{ counter }}</h5>
                                       <select v-model="variantData.selectOptions" class="custom-select variant_types" name="variant_types">
                                          <option :value="dataOptions.id" v-for="(dataOptions, index) in variantData.setOptions">{{ dataOptions.title }}</option>
                                       </select>
                                    </div>
                                  </div>

                                  <template>
                                  <div class="col-12" :class="(objVariantData.length == 1)?'col-md-9':'col-md-7'">
                                     <div class="form-group">
                                      <input-tag  v-model="variantData.selectvalue" @input="setCombination()" :placeholder="lang.cruds.tag.fields.title_helper" :allow-duplicates="false" />
                                    </div>
                                  </div>
                                  </template>

                                  <div class="col-md-2 col-12 mb-50">
                                    <div class="form-group" v-if="objVariantData.length > 1">
                                      <button class="btn btn-outline-danger text-nowrap px-1" @click="removeProductVariant(index); setCombination()"  type="button">
                                        <i class="fa fa-trash"></i>
                                      </button>
                                    </div>
                                  </div>
                                </div>
                                <hr />
                              </div>
                            <div class="row" v-show="objVariantData.length <= 2">
                              <div class="col-12">
                                <button class="btn btn-icon btn-primary"  @click="addProductVariant()" type="button" >
                                  <i data-feather="plus" class="mr-25"></i>
                                  <span>{{ lang.global.add }} {{ lang.global.new }}</span>
                                </button>
                              </div>
                            </div>
                                <!-- start with combination -->
                                <div class="row mt-2" v-if="productVariantCombination.length > 0">
                                    <div class="table-responsive">
                                    <table class="table table-hover" id="productVariantCombination">
                                      <thead>
                                        <tr >
                                          <th>
                                            <div class="custom-control custom-control-primary custom-checkbox">
                                              <label class="custom-control-label " for="variantCombinationData_all">Showing {{ productVariantCombination.length }} variants</label>
                                            </div>
                                          </th>
                                          <th>Status</th>
                                          <th>Actions</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr @click.prevent="openEditProductVariant($event,index)" v-for="(variantCombinationData, index) in productVariantCombination">
                                          <td>
                                            <div class="demo-inline-spacing">
                                                <div class="custom-control custom-control-primary custom-checkbox">
                                                  <label class="custom-control-label cursor " :for="'variantCombinationData_'+index">{{  variantCombinationData.variant_name}}</label>
                                                </div>
                                            </div>
                                          </td>
                                          <td ><span class="badge badge-pill badge-light-primary mr-1">Active</span></td>
                                          <td>
                                            <div class="action">
                                                <a  class="ml-2" href="javascript:void(0);">
                                                  <i data-feather="edit" class="mr-50"></i>
                                                </a>
                                            </div>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                 <!-- end with combination -->
                          </div>
                          <!-- Variants repeater end -->
                      </div>
                  </div>
                  <!-- Variants end -->
                  <!-- SEO start -->
                  <div class="card">
                      <div class="card-header">
                         <h4 class="card-title">Search engine listing preview</h4>
                      </div>
                      <div class="card-body">
                         <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                   <label for="seo_title">{{ lang.cruds.product.fields.seo_title }}</label>
                                   <input class="form-control" type="text" placeholder="Enter seo title" name="seo_title" id="seo_title" v-model="product.seo_title">
                                </div>
                                <div class="form-group">
                                   <label for="seo_description">{{ lang.cruds.product.fields.seo_description }}</label>
                                  <textarea class="form-control" placeholder="Enter seo description" name="seo_description" id="seo_description" v-model="product.seo_description"></textarea>
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
                     <h4 class="card-title">{{ lang.cruds.product.title_singular }} status</h4>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-12">
                          <div class="form-group">
                               <label class="required">{{ lang.cruds.product.fields.status }}</label>
                               <select class="custom-select" name="status" id="status" v-model="product.status">
                                  <option :value="index" v-for="(ldata, index) in list.status">{{ ldata }}</option>
                               </select>
                               <p>{{ lang.cruds.product.fields.status_helper }}</p>
                          </div>
                        </div>
                        <hr>
                        <div class="col-12">
                            <div class="form-group">
                              <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="onlineStore" v-model="product.is_online"/>
                                <label class="custom-control-label" for="onlineStore">{{ lang.cruds.collection.fields.online_store }}</label>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="mt-1" v-if="product.is_online && scheduleinfo == ''">
                                <a href="javascript:void(0)"  @click="editSchecule"><small>{{ lang.global.schedule }} {{ lang.global.availibility }}</small></a>
                              </div>
                             </div>
                             <div class="form-group">
                              <div class="mt-1" v-if="product.is_online && scheduleinfo != ''">
                                  <p>{{scheduleinfo}}</p>
                                   <a href="javascript:void(0)"  @click="editSchecule" class="mr-1"><small>{{ lang.global.edit }}</small></a>
                                  <a @click="cancelSchedule()"><small>{{ lang.global.cancel }}  {{ lang.global.schedule }}</small></a>
                              </div>
                             </div>
                              <input class="form-control" type="hidden" name="schedule_time" id="schedule_time" v-model="product.schedule_time">
                           </div>
                        </div>
                     </div>
                  </div>
                <div class="card">
                  <div class="card-header">
                     <h4 class="card-title">Organization</h4>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                               <label for="product_type_id">{{ lang.cruds.product.fields.product_type }}</label>
                               <multiselect
                                          v-model="varProduct_type"
                                          :options="list.product_types"
                                          :taggable="true"
                                          @tag="addProductType"
                                          tag-placeholder="Add this product type"
                                          placeholder="search or add product type"
                                          :custom-label="FilterTitleName"
                                          track-by="id"
                                          :close-on-select="true"
                                          :clear-on-select="true"
                                          >
                                </multiselect>
                            </div>
                            <div class="form-group">
                               <label for="vendor_id">{{ lang.cruds.product.fields.vendor }}</label>
                               <multiselect
                                          v-model="varvendor"
                                          :options="list.vendors"
                                          :taggable="true"
                                          @tag="addVendors"
                                          tag-placeholder="Add this vender"
                                          placeholder="Type to search or add vendor"
                                          :custom-label="FilterName"
                                          track-by="id"
                                          :close-on-select="true"
                                          :clear-on-select="true"
                                          >
                                </multiselect>  
                            </div>
                            <div class="form-group sel-collection-section">
                               <label for="collection_id">{{ lang.cruds.collection.title }}</label>
                               <multiselect
                                          v-model="collectionSelected"
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
                                              <label class="form-check-label"  :for="'inlineCheckbox'+scope.option.id">{{ scope.option.title }}</label>
                                          </div>
                                          </template>
                                          <template slot="selection" slot-scope="{ values, search }">
                                            <span class="multiselect__single" v-if="values.length">{{ values.length }} collections selected</span>
                                          </template>
                                    </multiselect> 
                                     
                               <span>Add this product to a collection so it’s easy to find in your store.</span>
                            </div>
                            <div class="form-group">
                                <span v-for="(collectionSelectdata, index) in collectionSelected" v-if="collectionSelectdata.checked">
                                <span class="badge badge-glow badge-primary mb-1 mr-1 pointer" @click.prevent="onRemoveCollections(collectionSelectdata);removeCollection(index)">{{ collectionSelectdata.title }}  &#10006; </span>
                                </span>
                            </div>
                            <div class="form-group">
                               <label for="tag_id">{{ lang.cruds.tag.title }}</label>
                                   <multiselect
                                          v-model="taggingSelected"
                                          :options="list.tags"
                                          :multiple="true"
                                          :taggable="true"
                                          @tag="addTag"
                                          tag-placeholder="Add this as new tag"
                                          placeholder="Type to search or add tag"
                                          :custom-label="FilterTitleName"
                                          track-by="id"
                                          :close-on-select="false"
                                          :clear-on-select="false"
                                          @select=onSelect($event)
                                          @remove=onRemove($event)
                                          >
                                            <template  slot="option" slot-scope="scope">
                                              <div class="form-check form-check-inline"   @click.self="select(scope.option)">
                                                  <input class="form-check-input" type="checkbox" :id="'inlineCheckbox'+scope.option.id" v-model="scope.option.checked" @focus.prevent />
                                                  <label class="form-check-label"  :for="'inlineCheckbox'+scope.option.id">{{ scope.option.title }}</label>
                                              </div>
                                            </template>
                                           <template slot="selection" slot-scope="{ values, search }">
                                            <span class="multiselect__single" v-if="values.length">{{ values.length }} tags selected</span>
                                          </template>
                                    </multiselect>  
                            </div>
                             <div class="form-group">
                                <span v-for="(tagsSelectedData, index) in taggingSelected" v-if="tagsSelectedData.checked">
                                <span class="badge badge-glow badge-primary mb-1 mr-1 pointer" @click.prevent="onRemove(tagsSelectedData)">{{ tagsSelectedData.title }} &#10006;</span>
                                </span>
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
          </div>
        </form>
    </ValidationObserver>

      <div
                class="modal fade"
                tabindex="-1"
                role="dialog"
                aria-labelledby="exampleModalScrollableTitle"
                aria-hidden="true"
                id="edit-product-variant"
              >
                <div class="modal-dialog modal-dialog-scrollable modal-lg"  role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">{{ lang.global.edit }} {{ editVariantCombination.variant_name }}</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                            <div class="col-xl-3 col-md-3 col-12 mb-1">
                              <div class="form-group">
                               <label for="cost_per_item">{{ lang.cruds.product.fields.price }}</label>
                                <div class="input-group input-group-merge mb-2">
                                   <div class="input-group-prepend">
                                      <span class="input-group-text">{{ lang.global.current_currency }}</span>
                                   </div>
                                   <input class="form-control" type="number" v-model="editVariantCombination.price" placeholder="0.00" @blur="calculateMarginProfit()">
                                </div>
                              </div>
                            </div>
                            <div class="col-xl-3 col-md-3 col-12 mb-1">
                              <div class="form-group">
                               <label for="cost_per_item">{{ lang.cruds.product.fields.compare_at_price }}</label>
                                <div class="input-group input-group-merge mb-2">
                                   <div class="input-group-prepend">
                                      <span class="input-group-text">{{ lang.global.current_currency }}</span>
                                   </div>
                                   <input class="form-control" type="number" v-model="editVariantCombination.compare_at_price" placeholder="0.00" @blur="calculateMarginProfit()">
                                </div>
                              </div>
                            </div>
                            <div class="col-xl-4 col-md-4 col-12 mb-1">
                                <div class="form-group">
                                    <label for="cost_per_item">{{ lang.cruds.product.fields.cost_per_item }} </label>
                                    <div class="input-group input-group-merge">
                                       <div class="input-group-prepend">
                                          <span class="input-group-text" id="basic-addon5">{{ lang.global.current_currency }}</span>
                                       </div>
                                       <input class="form-control" type="number"  v-model="editVariantCombination.cost_per_item" placeholder="0.00" @blur="calculateMarginProfit()">
                                    </div>
                                    <span class="small">({{ lang.global.customerNotSee }})</span>
                                </div>                              
                            </div>
                            <div class="col-xl-1 col-md-1 col-12 mb-1">
                                <div class="form-group">
                                    <label>{{ lang.global.margin }}</label><br>
                                    <label class="productMargin">{{ totalProductMargin }}</label>
                                </div>                              
                            </div>
                            <div class="col-xl-1 col-md-1 col-12 mb-1">
                                <div class="form-group">
                                    <label>{{ lang.global.profit }}</label><br>
                                    <label class="productProfit">{{ totalProductProfit }}</label>
                                </div>                              
                            </div>
                      </div>
                      <div class="row">
                        <div class="col-12 mb-1">
                          <div class="form-group">     
                              <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="charge_tax_variant" v-model="editVariantCombination.is_product_charge"/>
                                <label class="custom-control-label" for="charge_tax_variant">{{ lang.global.charge_tax_variant }}</label>
                              </div>
                            </div>
                        </div>
                      </div>
                      
                      <h4 class="mb-1">{{ lang.global.inventory }}</h4>
                      <div class="row">
                          <div class="col-xl-6 col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label>{{ lang.cruds.product.fields.sku }} {{ lang.cruds.product.fields.sku_helper }}</label>
                                    <input type="text" class="form-control" v-model="editVariantCombination.sku"  />
                                </div>                              
                            </div>
                             <div class="col-xl-6 col-md-6 col-12 mb-1">
                                <div class="form-group">
                                    <label>{{ lang.cruds.product.fields.barcode }} {{ lang.cruds.product.fields.barcode_helper }}</label>
                                    <input type="text" class="form-control" v-model="editVariantCombination.barcode" />
                                </div>                              
                            </div>
                      </div>
                      <div class="row">
                           <div class="col-xl-6 col-md-6 col-12 mb-1">
                                <div class="form-group">
                                  <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="variant_is_track" v-model="editVariantCombination.is_track"/>
                                    <label class="custom-control-label" for="variant_is_track">{{ lang.cruds.product.fields.is_track }}</label>
                                  </div>
                                </div>                              
                            </div>
                             <div class="col-xl-6 col-md-6 col-12 mb-1">
                                <div class="form-group">
                                   <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="variant_is_continue_selling" v-model="editVariantCombination.is_continue_selling"/>
                                    <label class="custom-control-label" for="variant_is_continue_selling">{{ lang.cruds.product.fields.is_continue_selling }}</label>
                                  </div>
                                </div>                              
                            </div>
                      </div>
                      <div class="row">
                           <div class="col-xl-9 col-md-9 col-12">
                                <div class="form-group">
                                    <h5>{{ lang.global.location }} {{ lang.global.name }} </h5>
                                </div>                              
                            </div>
                             <div class="col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <h5>{{ lang.cruds.stock.fields.available_quantity }}</h5>
                                </div>                              
                            </div>
                      </div>
                      <hr>
                      <div class="row" v-if="handleEditLocations.length > 0 " v-for="handleLocationData in handleEditLocations">
                           <div class="col-xl-9 col-md-9 col-12">
                                <div class="form-group">
                                    <h5> {{ handleLocationData.name }}</h5>
                                </div>                              
                            </div>
                             <div class="col-xl-3 col-md-3 col-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" v-model="handleLocationData.available" />
                                </div>                              
                            </div>
                      </div>
                       <hr>
                       <div class="row">
                            <div class="col-6">
                               <div class="form-group">
                                   <label for="min_order_limit">{{ lang.cruds.product.fields.min_order_limit }}</label>
                                   <input class="form-control" type="number" v-model="editVariantCombination.min_order_limit">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                   <label for="max_order_limit">{{ lang.cruds.product.fields.max_order_limit }}</label>
                                   <input class="form-control" type="number" v-model="editVariantCombination.max_order_limit">
                                </div>
                            </div>
                          </div>
                        <hr>
                       <h4>{{  lang.cruds.product.shipping }}</h4>
                       <div class="row">
                            <div class="col-12 mb-1">
                                <div class="form-group">
                                   <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="variant_is_physical_product" v-model="editVariantCombination.is_physical_product"/>
                                    <label class="custom-control-label" for="variant_is_physical_product">{{ lang.cruds.product.fields.is_physical_product }}</label>
                                  </div>
                                </div>                              
                            </div>
                       </div>
                      <div class="row is-physical-dv" v-if="editVariantCombination.is_physical_product">
                        <div class="col-6">
                                <div class="form-group ">
                                   <label for="weight">{{ lang.cruds.product.fields.weight }}</label>
                                   <div class="row">
                                        <div class="col-8">
                                          <input class="form-control" type="number" name="variant_weight" id="variant_weight"  placeholder="0.0" v-model="editVariantCombination.weight">
                                        </div>
                                        <div class="col-4">
                                          <select class="custom-select" name="variant_weight_type_id" id="variant_weight_type_id" v-model="editVariantCombination.weight_type_id">
                                             <option :value="index" v-for="(ldata, index) in list.weight_type">{{ ldata }}</option>
                                         </select>
                                        </div>
                                    </div>
                                </div>
                          </div>
                          <div class="col-6">
                              <div class="form-group ">
                                   <label for="length">{{ lang.cruds.product.fields.length }}</label>
                                   <div class="row">
                                    <div class="col-8">
                                      <input class="form-control" type="number" name="variant_length" id="variant_length"  placeholder="0.0" v-model="editVariantCombination.length">
                                    </div>
                                    <div class="col-4">
                                      <select class="custom-select" name="variant_length_type_id" id="variant_length_type_id" v-model="editVariantCombination.length_type_id">
                                         <option :value="index" v-for="(ldata, index) in list.dimension_types">{{ ldata }}</option>
                                     </select>
                                   </div>
                                 </div>
                                </div>
                          </div>
                          <div class="col-6">
                              <div class="form-group ">
                                   <label for="width">{{ lang.cruds.product.fields.width }}</label>
                                   <div class="row">
                                    <div class="col-8">
                                      <input class="form-control" type="number" name="variant_width" id="variant_width"  placeholder="0.0" v-model="editVariantCombination.width">
                                    </div>
                                    <div class="col-4">
                                      <select class="custom-select" name="variant_width_type_id" id="variant_width_type_id" v-model="editVariantCombination.width_type_id">
                                         <option :value="index" v-for="(ldata, index) in list.dimension_types">{{ ldata }}</option>
                                     </select>
                                   </div>
                                 </div>
                                </div>
                          </div>
                          <div class="col-6">
                              <div class="form-group ">
                                   <label for="height">{{ lang.cruds.product.fields.height }}</label>
                                   <div class="row">
                                    <div class="col-8">
                                      <input class="form-control" type="number" name="variant_height" id="variant_height"  placeholder="0.0" v-model="editVariantCombination.height">
                                    </div>
                                    <div class="col-4">
                                      <select class="custom-select" name="variant_height_type_id" id="variant_height_type_id" v-model="editVariantCombination.height_type_id">
                                         <option :value="index" v-for="(ldata, index) in list.dimension_types">{{ ldata }}</option>
                                     </select>
                                   </div>
                                 </div>
                                </div>
                          </div>
                      </div>
                      <div class="row" v-if="editVariantCombination.is_physical_product">
                        <div class="col-12">
                            <h3>{{  lang.global.customer }} {{  lang.global.information }}</h3>   
                        </div>
                      </div>
                      <hr v-if="editVariantCombination.is_physical_product">
                      <div class="row is-physical-dv" v-if="editVariantCombination.is_physical_product">
                          <div class="col-12">
                               <div class="form-group">
                                   <label for="country_id">{{ lang.cruds.product.fields.country }}</label>
                                   <select class="custom-select" name="country_id" id="country_id" v-model="editVariantCombination.country_id">
                                      <option :value="ldata.id" v-for="(ldata, index) in list.countries">{{ldata.name }}</option>
                                   </select>
                                   <p><small class="form-text">{{ lang.global.manufactured_country_helper }}</small></p>
                                </div>
                                <div class="form-group">
                                   <label for="hs_code">{{ lang.cruds.product.fields.hs_code }}</label>
                                   <input class="form-control" type="text" placeholder="enter title"  v-model="editVariantCombination.hs_code">
                                    <p><small class="form-text">{{ lang.cruds.product.fields.hs_code_helper }}</small></p>
                                </div>
                          </div>
                      </div>

                      <!-- start product variants media  -->
                      <div class="row">
                         <div class="col-12 mb-1">
                          <h3 class="card-title d-i-b">{{ lang.global.media }}</h3>
                         <button v-if="showVariantDeleteBtn" @click="deleteMediaVariant" type="button" class="btn btn-outline-warning waves-effect float-right">{{ lang.global.deleteselectmedia }}</button>
                        </div>
                      </div>
                        <section>
                        <div class="row" id="mediaVariant">
                            <div class="draggable-outer-line" v-if="variantMediaData.length == 0"> 
                                <div class="row no-item"  @click.prevent="callMediaDataVariant">
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
                                        <draggable tag="div" v-model="variantMediaData" class="dropzone-second-div" draggable=".item">
                                        <div v-for="(imagedata,index) in variantMediaData" class="item">
                                            <button class="btnImage" type="button" @mouseover="mouseOverDivVariant(index)" @mouseleave="mouseLeaveDivVariant(index)">
                                                <div class="checkbox-outer" v-show="imagedata.displaycheckbox">
                                                    <div class="custom-control custom-checkbox">
                                                      <input type="checkbox" class="custom-control-input" :id="'deletecheckboxvariant'+imagedata.id" v-model="imagedata.checked" @change="checkAnySelectVariant">
                                                      <label class="custom-control-label" :for="'deletecheckboxvariant'+imagedata.id"></label>
                                                    </div>
                                                </div>
                                                <div class="image-outer" :class="(imagedata.displaycheckbox)?'opacitityDown':''">
                                                    <img class="main-image" :src="imagedata.image_src[3]"  
                                                    :alt="imagedata.src_alt_text">
                                                </div>
                                            </button>
                                        </div>
                                        <div class="add-media-outer">
                                            <button class="add-image" type="button" @click.prevent="callMediaDataVariant">
                                                    <span class="add-image-link">{{ lang.global.add_media }}</span>
                                            </button>
                                        </div>
                                    </draggable>
                                </div>
                            </div>
                        </div>
                            <template>
                            <form ref="mediaformvariant">
                            <input type="file" name="variantmediafile[]" multiple="" accept="image/jpeg, image/png" id="variantmediafile" class="opacity-0" @change="passImageDataVariant($event)">
                            </form>
                            </template>
                        </section>
                      <!-- End product variants media  -->

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary " data-dismiss="modal">{{ lang.global.cancel }}</button>
                      <button type="button" class="btn btn-relief-success" @click.prevent="doneEditProductVariant(editVariantCombination.index)">{{ lang.global.done }}</button>
                    </div>
                  </div>
                </div>
              </div>

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
                                        v-model="scheduleDate"
                                        placeholder="YYYY-MM-DD"
                                        aria-label="YYYY-MM-DD"
                                      />
                                    </div>
                                 </div>
                                  <div class="form-group text-center">
                                      <date-picker
                                       v-model="scheduleDate" 
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
                                        v-model="scheduleTime"
                                        placeholder="h:mm"
                                        aria-label="h:mm"
                                      />
                                    </div>
                                </div>
                                <div class="form-group text-center">
                                    <date-picker
                                      v-model="scheduleTime"
                                      :minute-step="10"
                                      format="h:mm a"
                                      value-type="format"
                                      type="time"
                                      placeholder="h:mm a"
                                      :inline="true"
                                    ></date-picker>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ lang.global.cancel }}</button>
                      <button type="button" class="btn btn-primary data-edit" @click="selectScheduleDate">{{ lang.global.schedule }} {{ lang.global.avalibity }}</button>
                    </div>
                  </div>
                </form>
            </div>
        </div>
        <!-- Shedule Modals end -->
  </div>
</template>

<script>
import moment from 'moment';
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import draggable from 'vuedraggable'
export default {
    props: ['list'],
    name:'product',
    data() {
      return {
            handleSameQty: false,
            scheduleDate:  moment(new Date()).format('YYYY-MM-DD'),
            scheduleTime: moment().format('h:mm a'),
            scheduleinfo: '',
            showDeleteBtn: false,
            showVariantDeleteBtn: false,
            totalOptions: [],
            objVariantData: [{
                    selectOptions: 1,
                    setOptions: [],
                    selectvalue: []
            }],
            productVariantCombination: [],
            editSaveVariantCombination: [],
            editVariantCombination: [],
            totalMainMargin: 0+'%',
            totalMainProfit: 0,
            totalProductMargin: '-',
            totalProductProfit: '-',
            collectionSelected: [],
            taggingSelected: [],
            mediaData: [],
            variantMediaData: [],
            locations: [],
            handleEditLocations: [],
            varProduct_type: [],
            varvendor: [],
            product: {title: '',  description: '',  price: '',  compare_at_price: '',  cost_per_item: '',  is_product_charge: false, sku: '', barcode: '',  is_track: false,  is_continue_selling: false, quantity: '',  min_order_limit: 0, max_order_limit: 5,  is_cod_enabled: false,  is_size_chart_enabled: false,  is_special_product: false, special_product_status: false,  special_price: '', expiry_date: '', is_physical_product: false,  weight: '',length: '', width: '', height: '', weight_type: 0,dimension_types:0, country_id: 101,  hs_code: '', seo_title: '',  seo_description: '',  status: 0,  is_online:  false, product_type: [], vendor : [], collections: [], tags: [], is_gift_card: false, is_product_variant: false, variants: [],variantData: [], action: '', media: [], schedule_time: ''
                },
      } 
    },
    components: {
        draggable: draggable,
    },
    created() {
    },
    mounted() {
        // this.handleMedia();
        this.totalOptions = this.list.variants;
        this.objVariantData[0].setOptions = this.list.variants;
        if(this.list.action == 'add')
        {
            this.locations = this.list.locations;
            this.product.weight_type_id = Object.keys(this.list.weight_type)[0];
            this.product.length_type_id = this.product.width_type_id = this.product.height_type_id = Object.keys(this.list.dimension_types)[0];
        }
        else
        {
            this.productVariantCombination = this.list.product_variant_option;
            this.editSaveVariantCombination = this.productVariantCombination;
            if(this.list.variant_data.length > 0)
            {
                this.objVariantData = this.list.variant_data;
            }
            this.product = this.list.product;
            this.locations = this.product.locations;
            this.collectionSelected = this.list.selectCollections;
            this.taggingSelected = this.list.taggingSelected;
            this.mediaData = this.list.product_media;
            if(this.product.is_online)
            {   
                if(this.product.schedule_time != '' && this.product.schedule_time != null){
                     let tempScheduleDate = this.product.schedule_time.split(" ");
                     this.scheduleDate = tempScheduleDate[0];
                     this.scheduleTime = moment(tempScheduleDate[1],'HHmmss').format("h:mm a");
                     this.scheduleinfo = 'Scheduled for '+ moment(this.scheduleDate).format('MMMM Do YYYY') + ', ' + this.scheduleTime;
                }
            }
            this.setProductType();
            this.setVendor();
            this.calculateMainMarginProfit();
            this.initFlatPicker();

            if(this.product.is_product_variant)
            {
                $("#pricingdiv").hide();
                $("#inventorydiv").hide();
                $("#shippingdiv").hide();
            }
        }
        
    },
    methods: {
        callFileData(){
            $("#media").trigger('click');
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
        appendMedia(base64image){
            this.mediaData.push({id: Math.floor((Math.random() * -10000000)),  imageurl: base64image, checked: false, displaycheckbox: false, media: [] });
        },
        callMediaDataVariant(){
            $("#variantmediafile").trigger('click');
        },
        passImageDataVariant(event){
            blockSection($("#mediaVariant"));
            let self = this;
            for (const [key, value] of Object.entries(event.target.files)) {
                const  reader = new FileReader();
                 reader.readAsDataURL(value); 
                 reader.onload  = function() {
                        self.appendMediaVariant(reader.result);
                    }
            }
            self.$refs.mediaformvariant.reset();
            unblockSection($("#mediaVariant"));
        },
        appendMediaVariant(base64image){
            this.variantMediaData.push({id: Math.floor((Math.random() * -10000000)),  imageurl: base64image, checked: false, displaycheckbox: false});
        },
        saveProduct(){
            this.$refs.productForm.validate().then(success => {
                if (!success) {
                     $("html, body").animate({ scrollTop: 50 }, 200);
                  return;
                }
                openLoader();
                let browserUniqueId = localStorage.setItem('browserUniqueId', Math.floor((Math.random() * -1000)))
                this.product.variants = this.productVariantCombination;
                this.product.variantData = this.objVariantData;
                this.product.action = this.list.action;
                this.product.tags = this.taggingSelected;
                this.product.collections = this.collectionSelected;
                this.product.media = this.mediaData;
                this.product.locations = this.locations;
                this.product.product_type =  this.varProduct_type;
                this.product.vendor =  this.varvendor;
                this.product.browserUniqueId =  browserUniqueId;
                this.$store.dispatch("productModule/saveProduct", this.product)
                .then((res) => {
                    closeLoader();
                    if(res.response.status_code == 2040 || res.response.status_code == 2042)
                    {
                        successModal(res.response.message);
                        if(this.list.action == 'add')
                        {
                            setTimeout(function(){
                                // location.replace("/admin/products");
                                window.location = res.response.data.url;
                            },2000);
                           
                        }
                        else
                        {
                            this.list.action = 'edit';
                            setTimeout(function(){
                               window.location = res.response.data.url;
                            },2000);
                        }
                        this.$store.dispatch("productModule/ProductMediaConvert").then((res) => {}).catch((err) => {});
                    }
                })
                .catch((err) => {
                  closeLoader();
                  errorModal(err.response.message);
                });
            });
        },
        addProductVariant(){
            if(this.objVariantData.length == 1)
            {
                let objVariant = [...this.totalOptions];
                let removeIndex =  objVariant.findIndex(project => project.id == this.objVariantData[0].selectOptions);
                objVariant.splice(removeIndex, 1);
                this.objVariantData.push({
                    selectOptions: objVariant[0].id,
                    setOptions: objVariant,
                    selectvalue: []
                });
            }
            else if (this.objVariantData.length == 2) {
                let objVariant2 = [...this.totalOptions];
                let removeIndex1 = objVariant2.findIndex(project => project.id == this.objVariantData[0].selectOptions);
                objVariant2.splice(removeIndex1, 1);
                let removeIndex2 = objVariant2.findIndex(project => project.id == this.objVariantData[1].selectOptions);
                objVariant2.splice(removeIndex2, 1);
                this.objVariantData.push({
                    selectOptions: objVariant2[0].id,
                    setOptions: objVariant2,
                    selectvalue: []
                });
               
            }

        },
        setCombination(){
            let objTemp = this.objVariantData[0].selectvalue;
            if(this.objVariantData.length > 1)
            {   let arraySlash = [" / "];
                objTemp = objTemp.flatMap(d => arraySlash.map(v => d + v)); 
                objTemp = objTemp.flatMap(d => this.objVariantData[1].selectvalue.map(v => d + v)); 
                if(this.objVariantData.length == 3)
                {
                    objTemp = objTemp.flatMap(d => arraySlash.map(v => d + v));
                    objTemp = objTemp.flatMap(d => this.objVariantData[2].selectvalue.map(v => d + v)); 
                }
            }
            let objSetData = [];
            for (const [key, value] of Object.entries(objTemp)) {
                let addVariantcombination = true;
                let objTempVariantName = {};
                if(this.list.action == 'edit'){
                    let findIndex = this.editSaveVariantCombination.findIndex(project => project.variant_name == value);
                    if(findIndex >= 0)
                    {
                        objTempVariantName = this.editSaveVariantCombination[findIndex];
                        addVariantcombination = false;
                    }
                }
                if(addVariantcombination)
                {
                    objTempVariantName.variant_name = value;
                    objTempVariantName.id = Math.floor((Math.random() * -10000000));
                    objTempVariantName.checked = false;
                    objTempVariantName.stock = 0;
                    objTempVariantName.totallocations = 0;
                    objTempVariantName.min_order_limit = (this.product.min_order_limit == '')? 0 : this.product.min_order_limit;
                    objTempVariantName.max_order_limit = (this.product.max_order_limit == '')? 0 : this.product.max_order_limit;
                    objTempVariantName.price = (this.product.price == '')? 0 : this.product.price;
                    objTempVariantName.cost_per_item = (this.product.cost_per_item == '')? 0 : this.product.cost_per_item;
                    objTempVariantName.compare_at_price =  (this.product.compare_at_price == '') ? 0 : this.product.compare_at_price;
                    objTempVariantName.sku =  this.product.sku + value.split(" / ").join("-");
                    objTempVariantName.barcode = this.product.barcode;
                    objTempVariantName.hs_code = this.product.hs_code;
                    objTempVariantName.media = [];
                    objTempVariantName.is_product_charge = this.product.is_product_charge;
                    objTempVariantName.is_track = this.product.is_track;
                    objTempVariantName.is_continue_selling = this.product.is_continue_selling;
                    objTempVariantName.is_physical_product = this.product.is_physical_product;
                    objTempVariantName.weight = (this.product.weight == '')? 0 : this.product.weight;
                    objTempVariantName.weight_type_id = this.product.weight_type_id;
                     objTempVariantName.length = (this.product.length == '')? 0 : this.product.length;
                    objTempVariantName.length_type_id = this.product.length_type_id;
                     objTempVariantName.width = (this.product.width == '')? 0 : this.product.width;
                    objTempVariantName.width_type_id = this.product.width_type_id;
                     objTempVariantName.height = (this.product.height == '')? 0 : this.product.height;
                    objTempVariantName.height_type_id = this.product.height_type_id;
                    objTempVariantName.country_id = 101;
                    objTempVariantName.locations = [];

                    if(this.handleSameQty)
                    {
                        objTempVariantName.locations = this.locations;
                    }
                    else 
                    {
                        for (const [key, value] of Object.entries(this.locations)) {
                            objTempVariantName.locations.push({id: value.id, name: value.name, available: 0, incoming:0 });
                        }
                        
                    }
                }
                objSetData.push(objTempVariantName);
            }
            this.productVariantCombination = objSetData;
            this.initFeatherReplace();
        },
        removeProductVariant(index){
            this.objVariantData.splice(index, 1);
        },
        openEditProductVariant(event,index){
            this.variantMediaData = [];
            this.handleEditLocations = [];
            this.editVariantCombination = Object.assign({},this.productVariantCombination[index]);
            if(this.editVariantCombination.locations.length > 0)
            {
                this.handleEditLocations =  JSON.parse(JSON.stringify(this.editVariantCombination.locations));
            }

            if(this.editVariantCombination.media.length > 0)
            {
                this.variantMediaData = JSON.parse(JSON.stringify(this.editVariantCombination.media));
            }

            this.editVariantCombination.index = index;
            this.calculateMarginProfit();
            $("#edit-product-variant").modal({backdrop: 'static', keyboard: false}); 
        },
        doneEditProductVariant(index){
            this.productVariantCombination[index] = this.editVariantCombination;
            this.productVariantCombination[index].media = this.variantMediaData;
            this.productVariantCombination[index].locations = this.handleEditLocations;
            $("#edit-product-variant").modal('hide');
        },
        addTag(newTag){
            const tag = {
                id: Math.floor((Math.random() * 10000000)),
                title: newTag,
                status: 1,
                checked: true
              };
              this.taggingSelected.push(tag);
              this.list.tags.push(tag);

        },
        onSelect(options) {
         let index = this.list.tags.findIndex(item => item.id==options.id);
         this.list.tags[index].checked = true;
        },
        onRemove(options) {
          let taggingselectedindex = this.taggingSelected.findIndex(item => item.id==options.id);
          this.taggingSelected.splice(taggingselectedindex,1); 
          let listTagsIndex = this.list.tags.findIndex(item => item.id==options.id);
          this.list.tags[listTagsIndex].checked = false;
        },
        onSelectCollections(options) {
         let index = this.list.collections.findIndex(item => item.id==options.id);
         this.list.collections[index].checked = true;
        },
        onRemoveCollections(options) {
          let optionIndex = this.list.collections.findIndex(item => item.id==options.id);
          this.list.collections[optionIndex].checked = false;
         
        },
         removeCollection(index){
            this.collectionSelected.splice(index, 1);
            },
        addVendors(vendorname){
            const vendor = {
                id: Math.floor((Math.random() * 10000000)),
                name: vendorname,
                status: 1,
              };
              this.list.vendors.push(vendor);
              this.varvendor = vendor;

        },
        addProductType(type){
            const productType = {
                id: Math.floor((Math.random() * 10000000)),
                title: type,
                status: 1,
              };
              this.list.product_types.push(productType);
              this.varProduct_type = productType;

        },
        calculateMarginProfit(){
            if(this.product.cost_per_item == "" || this.product.cost_per_item == null)
            {
                this.totalProductProfit = '-';
                this.totalProductMargin = '-';
            }
            else
            {
                this.totalProductProfit = this.editVariantCombination.price - this.editVariantCombination.cost_per_item;
                this.totalProductMargin = ((this.totalProductProfit / this.editVariantCombination.price) * 100).toFixed(2) +'%';
            }
        },
        calculateMainMarginProfit(){
            if(this.product.cost_per_item == "" || this.product.cost_per_item == null)
            {
                this.totalMainProfit = '-';
                this.totalMainMargin = '-';
            }
            else
            {
                this.totalMainProfit = this.product.price - this.product.cost_per_item;
                this.totalMainMargin = ((this.totalMainProfit / this.product.price) * 100).toFixed(2) +'%';
            }
        },
        changeAllSku(){
            let ProductVariantionLength = this.productVariantCombination.length;
            if(ProductVariantionLength > 0)
            {
                for (const [key, value] of Object.entries(this.productVariantCombination)) {
                    this.productVariantCombination[key].sku = this.product.sku + '-' + value.variant_name.split(" / ").join("-");
                }
            }
          
        },
        setProductType(){
            this.varProduct_type = [];
            let findProducttypeIndex = this.list.product_types.findIndex(project => project.id == this.product.product_type_id);
            if(findProducttypeIndex != -1)
            {
                this.varProduct_type = this.list.product_types[findProducttypeIndex];
            }
        },
        setVendor(){
            this.varvendor = [];
            let findVendorIndex = this.list.vendors.findIndex(project => project.id == this.product.vendor_id);
            if(findVendorIndex != -1)
            {
                this.varvendor = this.list.vendors[findVendorIndex];
            }
        },
        mouseOverDiv(index){
            this.mediaData[index].displaycheckbox = true;
        },
        mouseLeaveDiv(index){
            this.mediaData[index].displaycheckbox = false;
            this.checkAnySelect();
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
        mouseOverDivVariant(index){
            this.variantMediaData[index].displaycheckbox = true;
        },
        mouseLeaveDivVariant(index){
            this.variantMediaData[index].displaycheckbox = false;
            this.checkAnySelectVariant();
        },
        checkAnySelectVariant(){
            let funCheckDisplayCheckbox = false;
            for (const [key, value] of Object.entries(this.variantMediaData)) {
                if(value.checked)
                {
                    funCheckDisplayCheckbox = true;
                    this.showVariantDeleteBtn = true;
                    break;
                }
            }

            if(funCheckDisplayCheckbox)
            {
                for (const [key, value] of Object.entries(this.variantMediaData)) {
                    value.displaycheckbox = true;
                }
            }
            else
            {
                for (const [key, value] of Object.entries(this.variantMediaData)) {
                    value.displaycheckbox = false;
                }
                    this.showVariantDeleteBtn = false;
            }
        },
        deleteMediaVariant(){
             for (const [key, value] of Object.entries(this.variantMediaData)) {
                if(value.checked) 
                {
                    this.deleteMediaIndexVariant();
                }
            }
            this.showVariantDeleteBtn = false;
            this.checkAnySelectVariant();
        },
        deleteMediaIndexVariant(){
            for (const [key, value] of Object.entries(this.variantMediaData)) {
                if(value.checked)
                {
                    this.variantMediaData.splice(key, 1);
                    this.deleteMediaVariant();
                }
            }
        }, 
        editSchecule(){
            if(this.product.schedule_time != '' && this.product.schedule_time != null)
            {

             let scheduledata = this.product.schedule_time.split(" ");
             this.scheduleDate = moment(new Date(scheduledata[0])).format('YYYY-MM-DD');
             this.scheduleTime = moment(scheduledata[1]+ " "+scheduledata[2], 'hh:mm A').format('h:mm a');
            }
            else
            {
              let currentMinute = moment().minutes();
              let roundMinute =  Math.ceil(currentMinute/10)*10;
              this.scheduleDate = moment(new Date()).format('YYYY-MM-DD');
              this.scheduleTime = moment(new Date()).set('minute', roundMinute).format('h:mm a');
            }

            $("#scheduleModal").modal('show');
        },
        cancelSchedule(){
          this.scheduleinfo = '';
          this.scheduleDate = moment(new Date()).format('YYYY-MM-DD');
          this.scheduleTime = moment(new Date()).format('h:mm a');
          this.product.schedule_time = '';
        },
        selectScheduleDate(){
            if(this.scheduleDate != '' && this.scheduleTime != '' && this.product.is_online) {
              this.scheduleinfo = 'Scheduled for '+ moment(this.scheduleDate).format('MMMM Do YYYY') + ', ' + this.scheduleTime;
              this.product.schedule_time = moment(this.scheduleDate).format('YYYY-MM-DD')+ " " + this.scheduleTime;
              $('#scheduleModal').modal('hide');
            }
        },
        notBeforeToday(date) {
            return date < new Date(new Date().setHours(0, 0, 0, 0));
        },
        initFlatPicker(){
            setTimeout(function () {
            $('.flatpickr-basic').flatpickr();
            }, 500)
        },
        initFeatherReplace(){
            setTimeout(function () {
                feather.replace();
            }, 500)
        },
      FilterTitleName(option) {
        return `${option.title}`;
      },
      FilterName(option) {
        return `${option.name}`;
      },
       
    },
    computed: {
    },
}
</script>

<style lang="scss" scoped>
.opacity-0{
    opacity: 0;
}
.productVariantCombination{
    tbody{
        tr{
            &:hover{
                background-color: #f8f8f8;
            }
        }
    }
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

.Polaris-Choice--labelHidden_14tn9 {
    padding: 0;
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

.dropzone-second-div>:first-child {
    grid-column: 1/span 2;
    grid-row: 1/span 2;
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
.d-i-b{
    display: inline-block;
}
.float-right{
    float: right;
}
</style>


