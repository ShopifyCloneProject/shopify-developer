<template>
  <div id="input-sizing">
      <form id="frmShipping">
            <!-- Delivery methods at checkout section start -->
            <div class="row border-bottom">
                <div class="col-md-4 col-12">
                    <div class="store-content">
                        <h6 class="store-title">{{ lang.cruds.shippingSettings.delivery_methods_at_checkout }}</h6>
                        <p class="small">{{ lang.cruds.shippingSettings.delivery_methods_at_checkout_helper }}</p>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <!-- card section start -->
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex">
                                <h6 class="mb-1 font-weight-bold">{{ lang.cruds.shippingSettings.shipping }}</h6>
                                <div class="ml-auto"><a href="shippingdetails">{{ lang.cruds.shippingSettings.manage_shipping }}</a></div>
                                </div>
                                <p class="small">{{ lang.cruds.shippingSettings.shipping_helper }}</p>
                            </div>
                            <!-- <div class="mb-2">
                                <div class="d-flex ">
                                    <h6 class="mb-0.5 font-weight-bold">
                                         {{ lang.cruds.shippingSettings.general_shipping_rates }}
                                    </h6>
                                    <div class="ml-auto"><a href="shipping/rates/manage/123">{{ lang.cruds.shippingSettings.manage_rates }}</a></div>
                                 </div>
                                 <p>{{ lang.cruds.shippingSettings.all_products }}</p>
                            </div>
                            <div class="mb-2">
                               <h6 class="mb-1 font-weight-bold">
                                {{ lang.cruds.shippingSettings.rates_for }}
                               </h6>
                            </div> -->
                        </div>
                        <!-- <div class="card-footer text-muted">
                            <div>
                                <div class="d-flex mb-1">
                                    <h6 class="font-weight-bold">
                                         {{ lang.cruds.shippingSettings.custom_shipping_rates }}
                                    </h6>
                                    <div class="ml-auto"><a href="shipping/rates/create">{{ lang.cruds.shippingSettings.create_new_profile }}</a></div>
                                </div>
                                <p class="small">{{ lang.cruds.shippingSettings.custom_shipping_rates_helper }}</p>
                            </div>
                        </div> -->
                    </div>
                    <!-- card section end -->
                    <!-- card section start -->
                    <!-- <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                               <h6 class="mb-1 font-weight-bold">{{ lang.cruds.shippingSettings.local_delivery }}</h6>
                               <p class="small">{{ lang.cruds.shippingSettings.local_delivery_helper }}</p>
                            </div>
                            <div class="text-capitalize font-weight-bold">{{ lang.cruds.shippingSettings.manaage_deliver_by_location }}</div>
                        </div>
                        <div class="card-body border-bottom" v-for="(address, index) in addresses">
                            <div class="address-deails d-flex">
                                <div class="d-flex align-items-start mb-1">
                                    <div class="mt-25 mr-75">
                                        <i data-feather='map-pin' width="24" height="24"></i>
                                    </div>
                                    <div>
                                        <div v-if="address.locationName" class="mb-25 font-weight-bold">{{address.locationName}}</div>
                                        <div class="mb-25" style="margin-bottom: 5px;">
                                          <span v-if="address.address1">{{address.address1 + ' ,'}}</span>
                                          <span v-if="address.address2">{{address.address2 + ' ,'}}</span>
                                          <span v-if="address.city">{{address.city + ' ,'}}</span>
                                          <span v-if="address.stateName">{{address.stateName + ' ,'}}</span>
                                          <span v-if="address.shortCode">{{address.shortCode + ' ,'}}</span>
                                          <span v-if="address.pincode">{{address.pincode}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                     <a :href="'shipping/local-delivery/'+address.id">{{ lang.cruds.shippingSettings.manage }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" v-if="getAddressLength == 0">
                            <p class="text-center mt-1">{{ lang.global.data_not_found }}</p>
                        </div>
                    </div> -->
                    <!-- card section end -->
                    <!-- card section start -->
                   <!--  <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                               <h6 class="mb-1 font-weight-bold">{{ lang.cruds.shippingSettings.local_pickup }}</h6>
                               <p class="small">{{ lang.cruds.shippingSettings.local_pickup_helper }}</p>
                            </div>
                            <div class="text-capitalize font-weight-bold">{{ lang.cruds.shippingSettings.manage_pickup_by_location }}</div>
                        </div>
                        <div class="card-body border-bottom" v-for="(address, index) in addresses">
                            <div class="address-deails d-flex">
                                <div class="d-flex align-items-start mb-1">
                                    <div class="mt-25 mr-75">
                                        <i data-feather='map-pin' width="24" height="24"></i>
                                    </div>
                                    <div>
                                        <div v-if="address.locationName" class="mb-25 font-weight-bold">{{address.locationName}}</div>
                                        <div class="mb-25" style="margin-bottom: 5px;">
                                          <span v-if="address.address1">{{address.address1 + ' ,'}}</span>
                                          <span v-if="address.address2">{{address.address2 + ' ,'}}</span>
                                          <span v-if="address.city">{{address.city + ' ,'}}</span>
                                          <span v-if="address.stateName">{{address.stateName + ' ,'}}</span>
                                          <span v-if="address.shortCode">{{address.shortCode + ' ,'}}</span>
                                          <span v-if="address.pincode">{{address.pincode}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                     <a :href="'shipping/local-pickup/'+address.id">Manage</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" v-if="getAddressLength == 0">
                            <p class="text-center mt-1">{{ lang.global.data_not_found }}</p>
                        </div>
                    </div> -->
                    <!-- card section end -->
                </div>
            </div>
            <!-- Delivery methods at checkout section end -->

            <!-- Packages section start -->
   <!--          <div class="row border-bottom mt-2">
                <div class="col-md-4 col-12">
                    <div class="store-content">
                        <h6 class="store-title">{{ lang.cruds.shippingSettings.packages }}</h6>
                        <p class="small">{{ lang.cruds.shippingSettings.packages_helper }}</p>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="card">
                        <div class="card-body border-bottom mb-1">
                            <div class="d-flex">
                                <h6 class="font-weight-bold">{{ lang.cruds.shippingSettings.saved_packages }}</h6>
                                <div class="ml-auto"><a data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#addPackage">{{ lang.cruds.shippingSettings.add_packages }}</a></div>
                            </div>
                        </div>
                        <div class="card-body border-bottom">
                            <div class="package-list d-flex">
                                <div class="d-flex align-items-start mb-1">
                                    <div class="mt-25 mr-75">
                                        <i data-feather='book'></i>
                                    </div>
                                    <div>
                                        <div class="mb-25 font-weight-bold">{{ lang.cruds.shippingSettings.sample_box }}</div>
                                        <div>22 × 13.7 × 4.2 cm, 0 kg</div>
                                    </div>
                                    <div class="default-badge">
                                        <div class="badge badge-secondary">{{ lang.global.default }}</div>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                     <a href="#">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- Packages section end -->

            <!-- Packing slips section start -->
           <!--  <div class="row border-bottom mt-2">
                <div class="col-md-4 col-12">
                    <div class="store-content">
                        <h6 class="store-title">{{ lang.cruds.shippingSettings.packing_slips }}</h6>
                        <p class="small">{{ lang.cruds.shippingSettings.packing_slips_helper }}</p>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="card">
                        <div class="card-body border-bottom">
                            <div class="d-flex mb-1">
                                <h6 class="font-weight-bold">{{ lang.cruds.shippingSettings.packing_slips }}</h6>
                                <div class="ml-auto"><a href="#">{{ lang.global.edit }}</a></div>
                            </div>
                            <p class="small">{{ lang.cruds.shippingSettings.packing_slips_helper }}</p>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- Delivery methods at checkout section end -->

            <!-- Carrier accounts section start -->
         <!--    <div class="row border-bottom mt-2">
                <div class="col-md-4 col-12">
                    <div class="store-content">
                        <h6 class="store-title">{{ lang.cruds.shippingSettings.carrier_accounts }}</h6>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="card">
                        <div class="card-body border-bottom">
                            <h6 class="mb-1 font-weight-bold">{{ lang.cruds.shippingSettings.carrier_accounts }}</h6>
                            <p class="small">{{ lang.cruds.shippingSettings.carrier_accounts_helper }}</p>
                            <div class="mt-2">
                                <a class="btn btn-outline-secondary" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#manageCarriers">{{ lang.cruds.shippingSettings.connect_carrier_account }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- Carrier accounts section end -->

            <!-- Add Rates section start -->
            <div class="row border-bottom mt-2">
                <div class="col-md-4 col-12">
                    <div class="store-content">
                        <h6 class="store-title">{{ lang.cruds.shippingSettings.add_rates }}</h6>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                       <h6 class="mb-1 font-weight-bold">{{ lang.cruds.shippingSettings.add_rates }}</h6>
                                        <p class="small">{{ lang.cruds.shippingSettings.add_rates_helper }}</p>
                                        <div class="mt-2">
                                            <a class="btn btn-outline-secondary" @click="addRates()">{{ lang.cruds.shippingSettings.add_rates }}</a>
                                        </div> 
                                    </div>
                                </div>


  <div class="col-md-12"  v-if="nexShippingRates.length > 0">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ lang.cruds.shippingSettings.manage_rates }}</h4>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(rates , index) in nexShippingRates" :key="index">
              <td>
                <div>
                    {{ rates.name }}
                </div>
              </td>
               <td>
                <div>
                    {{ rates.price }}
                </div>
              </td>
              <td class="text-center btn-actions">
                <div>
                    <a data-tooltip="Change shipping rate" role="button" @click="changeRates(index)">
                       <i class="far fa-edit text-primary"></i>
                    </a>
                    <a data-tooltip="Remove shipping rate" role="button" @click="confirmDeleteBox('delete rates','deleteRates',index)">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </a>
               </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
    </div>
  </div>
                               <!--  <div class="col-md-12" v-if="nexShippingRates.length > 0">
                                    <h6 class="mb-1 font-weight-bold  mt-2">{{ lang.cruds.shippingSettings.manage_rates }}</h6>
                                    <div class="border-bottom rates-data" v-for="(rates , index) in nexShippingRates" :key="index">
                                        <div class="row">
                                            <div class="col-md-3">
                                                {{ lang.cruds.shippingSettings.rate_name }} : {{ rates.name }}
                                            </div>
                                            <div class="col-md-3">
                                                {{ lang.cruds.shippingSettings.price }} : {{ rates.price }}
                                            </div>
                                            <div class="col-md-6">
                                                <a class="btn btn-outline-secondary mr-1" @click="changeRates(index)">{{ lang.global.change }}</a>
                                                <a class="btn btn-outline-secondary mr-1" @click="confirmDeleteBox('delete rates','deleteRates',index)">{{ lang.global.delete }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div> -->

                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add Rates section end -->


            <!-- Round Figure section start -->
            <div class="row border-bottom mt-2">
                <div class="col-md-4 col-12">
                    <div class="store-content">
                        <h6 class="store-title">{{ lang.cruds.shippingSettings.round_figure }}</h6>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                       <h6 class="mb-1 font-weight-bold">{{ lang.cruds.shippingSettings.round_figure }}</h6>
                                       <div class="form-check form-check-inline mb-1">
                                          <input class="form-check-input" type="checkbox" id="round_figure_value" @click="statusRoundFigure()" v-model="checkRoundFigureTax"/>
                                          <label class="form-check-label" for="round_figure_value">{{ lang.cruds.shippingSettings.round_value }}</label>
                                        </div>
                                        <p class="small">{{ lang.cruds.shippingSettings.round_figure_helper }}</p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- Round Figure section end -->





            <!-- Custom order fulfillment section start -->
            <!-- <div class="row border-bottom mt-2">
                <div class="col-md-4 col-12">
                    <div class="store-content">
                        <h6 class="store-title">{{ lang.cruds.shippingSettings.custom_order_fulfillment }}</h6>
                        <p class="small">{{ lang.cruds.shippingSettings.custom_order_fulfillment_helper }}</p>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="card">
                        <div class="card-body border-bottom">
                            <h6 class="mb-1 font-weight-bold">{{ lang.cruds.shippingSettings.custom_order_fulfillment }}</h6>
                            <p class="small">{{ lang.cruds.shippingSettings.custom_order_fulfillment_helper }}</p>
                            <div class="mt-2">
                                <a class="btn btn-outline-secondary" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#customOrder">{{ lang.cruds.shippingSettings.add_fulfillment_service }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- Carrier accounts section end -->

      </form>

        <!-- Modals start -->
        <!-- Add package Modals start -->
        <div class="modal fade" id="addPackage" tabindex="-1" role="dialog" aria-labelledby="addPackageTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addPackageTitle">{{ lang.cruds.shippingSettings.add_packages }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="row border-bottom mb-1">
                      <div class="col-12">
                           <h6 class="mb-1 font-weight-bold">{{ lang.cruds.shippingSettings.packege_type }}</h6>
                           <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="box" class="custom-control-input" value="1" v-model.number="addPackageData.packageType" @change="addPackageData.name = 'Custom Box'"/>
                                <label class="custom-control-label" for="box">{{ lang.cruds.shippingSettings.box }}</label>
                              </div>
                           </div>
                           <div class="form-group">
                              <div class="custom-control custom-radio mb-1">
                                <input type="radio" id="evelope" class="custom-control-input" value="2" v-model.number="addPackageData.packageType" @change="addPackageData.name = 'Custom Envelope'"/>
                                <label class="custom-control-label" for="evelope">{{ lang.cruds.shippingSettings.envelope }}</label>
                              </div>
                           </div>
                           <div class="form-group">
                               <div class="custom-control custom-radio mb-1">
                                    <div class="form-group">
                                     <input type="radio" id="softPackeg" class="custom-control-input" value="3" v-model.number="addPackageData.packageType" @change="addPackageData.name = 'Custom Soft package/satchel'" />
                                    <label class="custom-control-label" for="softPackeg">{{ lang.cruds.shippingSettings.soft_package }}</label>
                                  </div>
                               </div>
                           </div>
                        </div>
                  </div>
                  <div class="row mt-2 mb-1 border-bottom">
                      <div class="col-12">
                            <h6 class="mb-1 font-weight-bold">{{ lang.cruds.shippingSettings.size_and_weight }}</h6>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{ lang.cruds.shippingSettings.length }}</label>
                                        <input class="form-control" type="number" step="0.01" placeholder="L" v-model="addPackageData.length">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>{{ lang.cruds.shippingSettings.width }}</label>
                                        <input class="form-control" type="number" step="0.01" placeholder="W" v-model="addPackageData.width">
                                    </div>
                                </div>
                                <div class="col-md-2" v-if="addPackageData.packageType != 2">
                                    <div class="form-group">
                                        <label>{{ lang.cruds.shippingSettings.height }}</label>
                                        <input class="form-control" type="number" step="0.01" placeholder="L" v-model="addPackageData.height">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label></label>
                                        <select class="custom-select" v-model="addPackageData.size">
                                            <option value="cm">cm</option>
                                            <option value="in">in</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" :class="addPackageData.packageType != 2 ? 'offset-md-0' : 'offset-md-2'">
                                    <div class="form-group">
                                        <label>{{ lang.cruds.shippingSettings.weight_when_empty }}</label>
                                        <div class="d-flex">
                                            <input class="form-control" type="number" step="0.01" placeholder="L" v-model="addPackageData.weight">
                                            <select class="custom-select ml-1" v-model="addPackageData.weightType">
                                                <option value="g">g</option>
                                                <option value="kg">kg</option>
                                                <option value="oz">oz</option>
                                                <option value="lb">lb</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                      </div>
                  </div>
                  <div class="row mt-2 mb-1">
                      <div class="col-12">
                            <div class="form-group">
                                <label>{{ lang.cruds.shippingSettings.name }}</label>
                                <input class="form-control" type="text" v-model="addPackageData.name">
                            </div>
                      </div>
                  </div>
                  <div class="row mt-2">
                      <div class="col-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mb-1">
                                    <input type="checkbox" id="set_default" class="custom-control-input" v-model="addPackageData.default" />
                                    <label class="custom-control-label" for="set_default">{{ lang.cruds.shippingSettings.set_default }}</label>
                                    <div class="helper-text small">
                                       {{ lang.cruds.shippingSettings.set_default_helper }}
                                    </div>
                                </div>
                            </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary">{{ lang.cruds.shippingSettings.add_packages }}</button>
              </div>
            </div>
          </div>
        </div>
        <!-- addPackage Modals End -->

        <!-- Custom order fulfillment Modals start -->
        <div class="modal fade" id="manageCarriers" tabindex="-1" role="dialog" aria-labelledby="manageCarriersTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="manageCarriersTitle">{{ lang.cruds.shippingSettings.manage_carriers }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                   <div class="row border-bottom mt-1">
                      <div class="col-md-6">
                            <div class="d-flex">
                                <!-- <img
                                  src="{{ asset('/images/avatars/7.png') }}"
                                  alt="users avatar"
                                  class="user-avatar users-avatar-shadow rounded mr-2 my-25"
                                  height="80"
                                  width="80"
                                /> -->
                                <div class="mr-1">
                                    <i data-feather='meh'></i>
                                </div>
                                <div class="media-body">
                                    <h5 class="font-weight-bold">{{ lang.cruds.shippingSettings.fedex }}</h5>
                                    <p>{{ lang.cruds.shippingSettings.fedex_helper }}</p>
                                </div>
                            </div>
                      </div>
                      <div class="col-md-3 offset-md-3">
                           <button type="button" class="btn btn-primary">{{ lang.cruds.shippingSettings.connect_account }}</button>
                      </div>
                   </div>
                   <div class="row mt-2">
                      <div class="col-md-6">
                            <div class="d-flex">
                                <div class="mr-1">
                                    <i data-feather='meh'></i>
                                </div>
                                <div class="media-body">
                                    <h5 class="font-weight-bold">{{ lang.cruds.shippingSettings.ups }}</h5>
                                    <p>{{ lang.cruds.shippingSettings.ups_helper }}</p>
                                </div>
                            </div>
                      </div>
                      <div class="col-md-3 offset-md-3">
                           <button type="button" class="btn btn-primary">{{ lang.cruds.shippingSettings.connect_account }}</button>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ lang.global.cancel }}</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Custom order fulfillment Modals End -->

        <!-- Custom order fulfillment Modals start -->
        <div class="modal fade" id="customOrder" tabindex="-1" role="dialog" aria-labelledby="customOrderTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="customOrderTitle">{{ lang.cruds.shippingSettings.add_custom_fulfillment_service }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="row mt-2 mb-1">
                      <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ lang.cruds.shippingSettings.custom_order_name }}</label>
                                <input class="form-control" type="text" v-model="customOrderData.name">
                            </div>
                      </div>
                      <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ lang.cruds.shippingSettings.custom_order_email }}</label>
                                <input class="form-control" type="email" v-model="customOrderData.email">
                            </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary">{{ lang.global.save }}</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Custom order fulfillment Modals End -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import globalmixin from './../../mixins/action';

export default {
    props: ['data'],
    name:'Shipping',
    mixins: [globalmixin],
    data() {
      return {
        addresses:[],
        addPackageData:{
            packageType:1,
            lenght:'',
            width:'',
            height:'',
            size:'cm',
            weight:'',
            weightType:'g',
            name:'Custom Box',
            default:false
        },
        checkRoundFigureTax:false,
        nexShippingRates:[],
        customOrderData:{
            name:'',
            email:''
        }
      }
    },
    mounted(){
        this.nexShippingRates = this.data.shippingRates;
        this.checkRoundFigureTax = this.data.roundValue;
    },
    components: {
     
    },
    computed: {
        getAddressLength(){
            return this.addresses.length;
        }
    },
    created() {
        this.setAddress();
    },
    methods: {
        setAddress(){
             this.addresses = this.data.addresses;
        },
        addRates(){
            window.location.href = this.data.addRateUrl;
        },
        changeRates(index){
            window.location.href = this.data.shippingRates[index].rateEditUrl;
        },
        statusCheckShipRoundFigure(){
            this.checkRoundFigureTax = ($('#round_figure_value').is(":checked")) ? 1 : 0;
            openLoader();
                this.$store.dispatch("shippingSettingsModule/saveShipRoundFigureTaxCharges", {'isChecked' : this.checkRoundFigureTax } )
                .then((res) => {
                  if (res.response.status_code == 3117) {
                    successModal(res.response.message);
                    setTimeout(function(){
                            window.location.reload();
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
        statusRoundFigure(){
            let currentStatus = $("#round_figure_value").prop('checked');
              let self = this
                Swal.fire({
                title: 'Are you sure?',
                text: "You want to take round value on shipping tax!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, edit it!',
                customClass: {
                  confirmButton: 'btn btn-primary',
                  cancelButton: 'btn btn-outline-danger ml-1'
                },
                 buttonsStyling: false
            })
             .then(function (result) {
                if (result.value) {
                  self.statusCheckShipRoundFigure();
                }else if (result.dismiss === Swal.DismissReason.cancel) {
                    $("#round_figure_value").prop('checked',!currentStatus);
                  } 
            });
          },
        deleteRates(index){
                let self = this;
                let id = self.nexShippingRates[index].id;
                openLoader();
                    self.$store.dispatch("shippingSettingsModule/RemoveRates", id)
                    .then((res) => {
                      if (res.response.status_code == 3116) {
                        self.nexShippingRates.splice(index, 1);
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
svg.feather {
    height: 2em;
    width: 2em;
}
.rates-data:last-child{
    border-bottom: none !important;
}
.form-check-input{
    height: 15px;
    width: 15px;
}
.btn-actions{
    margin-right: 10px;
}
</style>