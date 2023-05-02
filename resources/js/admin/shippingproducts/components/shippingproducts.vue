<template>
  <div>
  <div id="input-sizing" v-if="formData != null">
    <ValidationObserver ref="shippingProductForm" v-slot="{ handleSubmit }">
      <form method="POST" id="frmRefundProduct" @submit.prevent="handleSubmit(submit())">
        <div class="row">
          <div class="col-12">
            <div class="form-group float-right">
              <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_save_shipping_access && data.objShipments.shipment_id == null" type="submit" @click="setShippingStatus = 0">
                {{ lang.global.save }}
              </button>
              <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_save_shipping_order_access && data.objShipments.shipment_id == null" type="submit" @click="setShippingStatus = 1">
                {{ lang.global.save }} & {{ lang.global.shipping }}
              </button>
              <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_delete_shipping_access && data.objShipments.shipment_id == null" type="button" @click="confirmDeleteBox('shipping order','deleteShippingOrder')">
                {{ lang.global.delete_shipping }}
              </button>
              <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_cancel_shipping_access && data.objShipments.shipment_id != null" type="button" @click="confirmCancelBox('cancel shipping order','cancelShippingOrder')">
                {{ lang.global.cancel }} {{ lang.global.shipping }}
              </button>
              <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_pickup_access && data.objShipments.shipment_id != null && data.objShipments.pickup_status == 0 && shippingMethodName == 'ShipRocket'" type="button" @click="confirmPickupBox('pickup order','pickupOrder')">
                {{ lang.global.pickup }}
              </button>
              <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_paid_order_access && data.objShipments.shipment_id != null && data.payment_mode == 'COD'" @click="saveCodPayment()" type="button">
                {{ lang.global.paid }}
              </button>
              <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_track_url_access && data.objShipments.shipment_id != null" type="button" @click="trackUrl()">
                {{ lang.global.check_track }}
              </button>
              <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_shipping_delivered_access && data.objShipments.shipment_id != null" type="button" @click="deliveredShipping()">
                {{ lang.global.delivered }}
              </button>
            </div>
          </div>
        </div>
        <div class="row" >
           <div class="col-md-12 col-12">
            <div class="card">
               <div class="card-body">
                   <div class="row">
                      <div class="col-3">
                          <div class="detail-header">
                            <h5 class="detail-title mb-1">CONTACT INFORMATION</h5>
                         </div>
                         <div class="overview-deails">
                            <div class="contact-details">
                               <div class="mb-1">
                                  <a class="pointer" v-if="order.email != '' && order.email != null">{{ order.email }}</a>
                                  <span v-else>No email available</span>
                               </div>
                               <div v-if="order.mobile != '' && order.mobile != null">
                                  <span>{{order.mobile}}</span>
                               </div>
                               <div v-else>
                                  <span>No phone number</span>
                               </div>
                               <div class="prd-date mt-1"><h6>Order Created</h6>{{order.created_at}}</div>
                               <div class="prd-date mt-1"><h6>Shipping Created</h6>{{data.objShipping.created_at}}</div>
                            </div>
                         </div>
                      </div>
                      <div class="col-3">
                         <div class="detail-header">
                            <h5 class="detail-title mb-1">SHIPPING ADDRESS</h5>
                         </div>
                         <div class="overview-deails" v-if="data.shipping_address">
                            <div class="defaul-address mb-2" v-if="data.shipping_address.hasOwnProperty('address')">
                               <div><span>{{fullNameAddressUser(data.shipping_address)}}</span></div>
                               <div><span>{{data.shipping_address.address}}</span></div>
                               <div><span>{{data.shipping_address.address_2}}</span></div>
                               <div><span>{{fullAddress(data.shipping_address)}}</span></div>
                               <div v-if="data.shipping_address.postal_code"><span>{{data.shipping_address.postal_code}}</span></div>
                               <div v-if="data.shipping_address.mobile">
                                  <span v-if="data.shipping_address.phone_code">+{{ data.shipping_address.phone_code }}</span>
                                  <span>{{data.shipping_address.mobile}}</span>
                               </div>
                            </div>
                            <div class="defaul-address mb-2" v-else>
                               <p>No shipping address proivded</p>
                            </div>
                         </div>
                      </div>
                      <div class="col-3">
                            <div class="detail-header">
                               <h5 class="detail-title mb-1">Billing ADDRESS</h5>
                            </div>
                            <div class="overview-deails" v-if="data.billing_address">
                               <div class="defaul-address mb-2" v-if="data.billing_address.hasOwnProperty('address')">
                                  <div><span>{{fullNameAddressUser(data.billing_address)}}</span></div>
                                  <div><span>{{data.billing_address.address}}</span></div>
                                  <div><span>{{data.billing_address.address_2}}</span></div>
                                  <div><span>{{fullAddress(data.billing_address)}}</span></div>
                                  <div v-if="data.billing_address.postal_code"><span>{{data.billing_address.postal_code}}</span></div>
                                  <div v-if="data.billing_address.mobile">
                                     <span v-if="data.billing_address.phone_code">+{{ data.billing_address.phone_code }}</span>
                                     <span>{{data.billing_address.mobile}}</span>
                                  </div>
                               </div>
                               <div class="defaul-address mb-2" v-else>
                                  <p>Address not provided</p>
                               </div> 
                            </div>
                      </div>
                      <div class="col-3">
                            <div class="detail-header">
                               <h5 class="detail-title mb-1">Note</h5>
                            </div>
                            <div>
                               <p v-if="order.note != '' && order.note != null">{{order.note}}</p>
                               <p v-else>No notes added</p>
                            </div>

                             <div class="detail-header">
                              <h5 class="detail-title mb-1">Order Number</h5>
                           </div>

                            <div class="orderId-links">
                              <div>
                                 <a :href="data.order_link">{{data.order.order_nr}}</a>
                              </div>
                           </div> 
                      </div>
                   </div> 

                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">{{ lang.cruds.shippingproducts.shipping_order_product }}</h4>
                <div class="form-group float-right" v-if="data.objShipments.shipment_id != null">
                  <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_generate_manifest_access && shippingMethodName == 'ShipRocket'" @click="handleActions('generate_manifest_url')" role="button">
                    {{ lang.global.generate_manifest }}
                  </a>
                  <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_print_manifest_access && shippingMethodName == 'ShipRocket'" @click="handleActions('print_manifest_url')" role="button">
                    {{ lang.global.print_manifest }}
                  </a>
                  <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_generate_label_access && (shippingMethodName == 'ShipRocket' || shippingMethodName == 'Ithinklogistics')" role="button" @click="handleActions('label_url')">
                    {{ lang.global.generate_label }}
                  </a>
                  <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_generate_invoice_access && (shippingMethodName == 'ShipRocket' || shippingMethodName == 'Ithinklogistics')" role="button" @click="handleActions('invoice_url')">
                    {{ lang.global.generate_invoice }}
                  </a>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table">
                  <thead class="thead-dark">
                    <tr>
                      <th class="text-center">Image</th>
                      <th class="table-name-info">Name</th>
                      <th class="text-center">Quantity</th>
                      <th class="text-center">Price</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(product, index) in shippingProducts" :key="index">

                      <td>
                        <div class="text-center">
                          <img :src="product.img_src"  :alt="product.productName"  @error="setAltImg" height="80" width="70">
                        </div>
                      </td>
                      <td class="table-name-info">
                        <div class="d-flex">
                          <div class="mr-1" v-if="product.compareprice > 0"><strike >{{ product.compareprice }}</strike ></div>
                          <div><h4 class="mb-0">{{ product.price }}</h4></div>
                        </div class="mt-0">
                        <h2 class="cart-table-prd-name">{{ product.title }}
                          <span class="badge bg-success">{{ product.quantity }}</span>
                        </h2>
                        <div>
                          <span v-if="product.weight > 0">{{ product.weight }} {{ product.weight_type != null ? product.weight_type : 'gm'  }}</span>
                          <span v-if="product.length > 0">{{ product.length }}{{ product.dimension_length_type != null ? product.dimension_length_type : 'cm'}}</span>
                          <span v-if="product.width > 0">{{ product.width }}{{ product.dimension_width_type != null ? product.dimension_width_type : 'cm' }}</span>
                          <span v-if="product.height > 0">{{ product.height }}{{ product.dimension_height_type != null ? product.dimension_height_type : 'cm' }}</span>
                        </div>
                        <div class="cart-table-switch">
                          <div class="prd-sku">
                            <input type="text" class="sku-input" :value="product.sku">
                          </div>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>{{ product.quantity }}</div>
                      </td>
                      <td class="text-center">
                        <div>
                          {{data.order.currency_symbol}} {{ (product.price * product.quantity).toFixed(2) }}
                        </div>
                      </td>
                      <td class="text-center">
                        <div v-if="list.btn_delete_product_action_access">
                          <a data-tooltip="Remove Product" role="button" @click="confirmDeleteBox('shipping product','removeShippingProduct',index)">
                            <i class="fa fa-trash text-danger"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2"><h4 class="total-title">Total : </h4></td>
                      <td class="text-center"><h5>{{ totalQuantity }}</h5></td>
                      <td class="text-center"><h5>{{data.order.currency_symbol}} {{ totalAmount }}</h5></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 col-12">
            <div class="card"> 
              <div class="card-header">
                <h4 class="card-title">{{ lang.cruds.shippingproducts.pickup_location }}</h4>
              </div>
              <div class="card-body">
                <div class="row">
                  
                  <div class="col-12">
                    <ValidationProvider  name="name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="name">{{ lang.cruds.shippingproducts.fields.title }}</label>
                        <textarea class="form-control" id="name" rows="3" v-model="formData.title">
                        </textarea>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                   <div class="col-4">
                    <ValidationProvider  name="payment_mode" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="payment_mode">{{ lang.cruds.shippingproducts.fields.payment_mode }}</label>
                          <select class="custom-select" name="payment_mode" id="payment_mode" v-model="formData.payment_mode">
                            <option :value="index" v-for="(payment_mode, index) in paymentMode">{{ payment_mode }}</option>
                          </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-4">
                    <ValidationProvider  name="quantity" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="quantity">{{ lang.cruds.shippingproducts.fields.quantity }}</label>
                        <input class="form-control" type="text" v-model="formData.quantity">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-4">
                    <ValidationProvider  name="selling_price" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="selling_price">{{ lang.cruds.shippingproducts.fields.selling_price }}</label>
                        <input class="form-control" type="text" v-model="formData.selling_price" @blur="confirmPrice('edit selling price')">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-4">
                    <ValidationProvider  name="taxes" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="taxes">{{ lang.cruds.shippingproducts.fields.taxes }}</label>
                        <input class="form-control" type="text" v-model="formData.tax" @blur="handleSubTotal()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-4">
                    <ValidationProvider  name="shipping_charges" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="shipping_charges">{{ lang.cruds.shippingproducts.fields.shipping_charges }}</label>
                        <input class="form-control" type="text" v-model="formData.shipping_charges" @blur="handleSubTotal()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-4">
                    <ValidationProvider  name="giftwrap_charges" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="giftwrap_charges">{{ lang.cruds.shippingproducts.fields.giftwrap_charges }}</label>
                        <input class="form-control" type="text" v-model="formData.giftwrap_charges" @blur="handleSubTotal()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-4">
                    <ValidationProvider  name="transaction_charges" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="transaction_charges">{{ lang.cruds.shippingproducts.fields.transaction_charges }}</label>
                        <input class="form-control" type="text" v-model="formData.transaction_charges" @blur="handleSubTotal()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-4">
                    <ValidationProvider  name="total_discount" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="total_discount">{{ lang.cruds.shippingproducts.fields.total_discount }}</label>
                        <input class="form-control" type="text" v-model="formData.total_discount" @blur="handleSubTotal()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-4">
                    <ValidationProvider  name="sub_total" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="sub_total">{{ lang.cruds.shippingproducts.fields.sub_total }}</label>
                        <input class="form-control" type="text" v-model="formData.sub_total" @blur="confirmPrice('edit Sub total')">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-6">
                    <ValidationProvider  name="weight" rules="required|min_value:0.1" v-slot="{ errors }">
                      <div class="form-group ">
                        <label class="required" for="weight">{{ lang.cruds.shippingproducts.fields.weight }}</label>
                        <div class="row">
                          <div class="col-8">
                            <input class="form-control" type="number" name="weight" id="weight"  placeholder="0.0" v-model="formData.weight">
                          </div>
                          <div class="col-4">
                            <select class="custom-select" name="weight_type_id" id="weight_type_id" v-model="formData.weight_type_id">
                              <option :value="index" v-for="(item, index) in list.weightmanages">{{ item }}</option>
                            </select>
                          </div>
                        </div>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider  name="length" rules="required|min_value:0.1" v-slot="{ errors }">
                      <div class="form-group ">
                        <label class="required" for="length">{{ lang.cruds.shippingproducts.fields.length }}</label>
                        <div class="row">
                          <div class="col-8">
                            <input class="form-control" type="number" name="length" id="length"  placeholder="0.0" v-model="formData.length">
                          </div>
                          <div class="col-4">
                            <select class="custom-select" name="length_type_id" id="length_type_id" v-model="formData.length_type_id">
                              <option :value="index" v-for="(item, index) in list.dimensions">{{ item }}</option>
                            </select>
                          </div>
                        </div>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider  name="width" rules="required|min_value:0.1" v-slot="{ errors }">
                      <div class="form-group ">
                        <label class="required" for="width">{{ lang.cruds.shippingproducts.fields.width }}</label>
                        <div class="row">
                          <div class="col-8">
                            <input class="form-control" type="number" name="width" id="width"  placeholder="0.0" v-model="formData.width">
                          </div>
                          <div class="col-4">
                            <select class="custom-select" name="width_type_id" id="width_type_id" v-model="formData.width_type_id">
                              <option :value="index" v-for="(item, index) in list.dimensions">{{ item }}</option>
                            </select>
                          </div>
                        </div>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider  name="height" rules="required|min_value:0.1" v-slot="{ errors }">
                      <div class="form-group ">
                        <label class="required" for="height">{{ lang.cruds.shippingproducts.fields.height }}</label>
                        <div class="row">
                          <div class="col-8">
                            <input class="form-control" type="number" name="height" id="height"  placeholder="0.0" v-model="formData.height">
                          </div>
                          <div class="col-4">
                            <select class="custom-select" name="height_type_id" id="height_type_id" v-model="formData.height_type_id">
                              <option :value="index" v-for="(item, index) in list.dimensions">{{ item }}</option>
                            </select>
                          </div>
                        </div>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-5">
                    <ValidationProvider  name="shipping_metohd" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="shipping_metohd">{{ lang.cruds.shippingproducts.fields.shipping_metohd }}</label>
                        <select class="custom-select"  name="shipping_metohd_id" @change="getPickupLocation()" v-model="formData.shipping_method_id" :disabled="data.objShipments.shipment_id != null">
                          <option :value="index" v-for="(item , index) in list.shippingmethods">{{ item }}</option>
                        </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                   <div class="col-5" id="pickupList">
                    <ValidationProvider  name="pickup id" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="pickup_id">{{ lang.cruds.shippingproducts.fields.pickup }}</label>
                        <select class="custom-select" name="pickup_id" id="pickup_id" :disabled="data.objShipments.shipment_id != null" v-model="formData.pickup_id">
                          <option :value="index" v-for="(item , index) in pickups">{{ item }}</option>
                        </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-2 pr-0 btn-available-couriers" v-if="data.objShipments.shipment_id == null">
                    <button class="btn btn-primary waves-effect waves-light" type="button" @click="handleAvailableCouriers()">
                     {{ lang.cruds.shippingproducts.available_couriers }}
                    </button>
                  </div>
                </div>
                <div class="row mt-1" v-if="objCouriersResponse.length > 0">
                  <div class="col-12">
                    <div class="card-body availableCouriers py-0">
                      <div class="row justify-content-between">
                        <div class="col-6 data-section" v-if="objCouriersResponse[0].length > 0">
                          <div class="row">
                            <div class="col-12 tbl-heading">
                              <div class="row">
                                <div class="col-1"></div>
                                  <div class="col-5">
                                    <div class="title">
                                      <h5>{{ lang.cruds.shippingproducts.logistics }}</h5>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <div class="title">
                                      <h5> {{ lang.cruds.shippingproducts.rating }} </h5>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <div class="title">
                                      <h5> {{ lang.cruds.shippingproducts.rate }}</h5>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <div class="title text-center">
                                      <h5> {{ lang.cruds.shippingproducts.days }}</h5>
                                    </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-12 tbl-des">
                               <div class="row" v-for="(response, index) in objCouriersResponse[0]"  :key="index" :for="`couriers_type_${response.id}`">
                                <div class="col-1">
                                  <div class="form-group">
                                    <div class="custom-control custom-radio">
                                      <input type="radio" :id="`couriers_type_${response.id}`" :value="response.logistics_name" class="custom-control-input" v-model="formData.courier_id"/>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-5">
                                  <div class="des-data">
                                    {{ response.logistics_name }}
                                  </div>
                                </div>
                                <div class="col-2">
                                    <div class="des-data">
                                      {{ response.rating }}
                                    </div>
                                </div>
                                <div class="col-2">
                                  <div class="des-data">
                                    {{ response.rate }}
                                  </div>
                                </div>
                                <div class="col-2 text-center">
                                  <div class="des-data ">
                                   {{ response.delivery_days }}
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6 data-section" v-if="objCouriersResponse[1].length > 0">
                           <div class="row">
                            <div class="col-12 tbl-heading">
                              <div class="row">
                                <div class="col-1"></div>
                                  <div class="col-5">
                                    <div class="title">
                                      <h5>{{ lang.cruds.shippingproducts.logistics }}</h5>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <div class="title">
                                      <h5> {{ lang.cruds.shippingproducts.rating }} </h5>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <div class="title">
                                      <h5> {{ lang.cruds.shippingproducts.rate }}</h5>
                                    </div>
                                  </div>
                                  <div class="col-2 text-center">
                                    <div class="title">
                                      <h5> {{ lang.cruds.shippingproducts.days }}</h5>
                                    </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-12 tbl-des">
                               <div class="row" v-for="(response, index) in objCouriersResponse[1]" :key="index" :for="`couriers_type_${response.id}`">
                                <div class="col-1">
                                  <div class="form-group">
                                    <div class="custom-control custom-radio">
                                      <input type="radio" :id="`couriers_type_${response.id}`" :value="response.logistics_name" class="custom-control-input" v-model="formData.courier_id"/>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-5">
                                  <div class="des-data">
                                    {{ response.logistics_name }}
                                  </div>
                                </div>
                                <div class="col-2">
                                    <div class="des-data">
                                      {{ response.rating }}
                                    </div>
                                </div>
                                <div class="col-2">
                                  <div class="des-data">
                                    {{ response.rate }}
                                  </div>
                                </div>
                                <div class="col-2 text-center">
                                  <div class="des-data">
                                   {{ response.delivery_days }}
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
              </div>
            </div>
            <div class="form-group float-left">
              <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_save_shipping_access && data.objShipments.shipment_id == null" type="submit" @click="setShippingStatus = 0">
                {{ lang.global.save }}
              </button>
              <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_save_shipping_order_access && data.objShipments.shipment_id == null" type="submit" @click="setShippingStatus = 1">
                {{ lang.global.save }} & {{ lang.global.shipping }}
              </button>
              <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_delete_shipping_access && data.objShipments.shipment_id == null" type="button" @click="confirmDeleteBox('delete shipping order','deleteShippingOrder')">
                {{ lang.global.delete_shipping }}
              </button>
              <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_cancel_shipping_access && data.objShipments.shipment_id != null" type="button"  @click="confirmCancelBox('cancel shipping order','cancelShippingOrder')">
                {{ lang.global.cancel }} {{ lang.global.shipping }}
              </button>
              <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_pickup_access && data.objShipments.shipment_id != null && data.objShipments.pickup_status == 0 && shippingMethodName == 'ShipRocket'" type="button" @click="confirmPickupBox('pickup order','pickupOrder')">
                {{ lang.global.pickup }}
              </button>
              <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_paid_order_access && data.objShipments.shipment_id != null && data.payment_mode == 'COD'" @click="saveCodPayment()" type="button">
                {{ lang.global.paid }}
              </button>
              <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_track_url_access && data.objShipments.shipment_id != null" type="button" @click="trackUrl()">
                {{ lang.global.check_track }}
              </button>
              <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_shipping_delivered_access && data.objShipments.shipment_id != null" type="button" @click="deliveredShipping()">
                {{ lang.global.delivered }}
              </button>
            </div>
            <div class="form-group float-right" v-if="data.objShipments.shipment_id != null">
              <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_generate_manifest_access && shippingMethodName == 'ShipRocket'" @click="handleActions('generate_manifest_url')" role="button">
                {{ lang.global.generate_manifest }}
              </a>
              <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_print_manifest_access && shippingMethodName == 'ShipRocket'" @click="handleActions('print_manifest_url')" role="button">
                {{ lang.global.print_manifest }}
              </a>
              <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_generate_label_access && (shippingMethodName == 'ShipRocket' || shippingMethodName == 'Ithinklogistics')" role="button" @click="handleActions('label_url')">
                {{ lang.global.generate_label }}
              </a>
              <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_generate_invoice_access && (shippingMethodName == 'ShipRocket' || shippingMethodName == 'Ithinklogistics')" role="button" @click="handleActions('invoice_url')">
                {{ lang.global.generate_invoice }}
              </a>
            </div>
          </div>
        </div>

<!-- Track URL Modal Start-->
        <div class="modal fade pr-0" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Shipping Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <div class="cart-table p-1">
                      <div class="track-heading">  
                        <div class="row border-bottom">
                            <div class="col-4">
                              <div class="title">
                                <h5>Date</h5>
                              </div>
                            </div>
                            <div class="col-4">
                              <div class="title">
                                <h5>Location</h5>
                              </div>
                            </div>
                            <div class="col-4">
                              <div class="title">
                                <h5>Status</h5>
                              </div>
                            </div>
                        </div>
                      </div>
                      <div class="track-data"> 
                        <div class="row py-1 border-bottom" v-for="(response, index) in objCheckTrackUrlResponse" :key="index">
                         <div class="col-4">
                            <div class="des-data">
                              {{ response.date }}
                            </div>
                          </div>
                          <div class="col-4">
                            <div class="des-data">
                              {{ response.location }}
                            </div>
                          </div>
                          <div class="col-4">
                            <div class="des-data">
                              {{ response.status }}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row mt-2" v-if="objTrackUrl != null">
                      <div class="col-12">  
                        <div class="des-data d-flex">
                          <h5>track URL :</h5>
                          <a :href="objTrackUrl" target="_blank"> {{ objTrackUrl }} </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> 
              </div>
          </div>
          </div>
        </div> 
<!-- Track URL Modal End-->
      </form>
    </ValidationObserver>
  </div>
  <div v-else>
         <Datanotfound></Datanotfound>
  </div>
  </div>
</template>
<script>
  import { mapGetters, mapActions } from 'vuex';
  import globalmixin from './../../mixins/action';
  import draggable from 'vuedraggable';
  export default {
    props: ['list', 'data'],
    mixins: [globalmixin],
    name:'shippingProduct',
    data() {
      return {
        objCouriersResponse: [],
        objCheckTrackUrlResponse: null,
        formData : null,
        shippingMethodName:null,
        shippingProducts : [],
        setShippingStatus : 0,
        isOrderShipping : false,
        objTrackUrl:null,
        pickups:[],
        paymentMode:{'Prepaid':'Prepaid','COD':'Cash On Delivery'},
        order:{},
        checkRateData:{},
        customerInfo:{
            email:'',
            phone:'',
        },
      }
    },
    mounted(){

      this.handleCalculate();

    },
    computed: {
      noImage(){
        return '/assets/images/no-image.jpg';
      },
    },
    created() {
       
    },
    methods: {

      fullNameAddressUser(address) {
        return address.first_name + ' ' + address.last_name;
      },
      setAltImg(event){
        event.target.src = this.noImage;
      },
      fullAddress(address){
        let city = address.city_name;
        let state = address.stateName;
        let shortCode = address.shortCode;

        if( (city != null && city != '') && (state != null && state != '') && (shortCode != null && shortCode != '') ){
          return city + ', ' + state + ', '+ shortCode;
        } else if((city != null && city != '') && (state != null && state != '')){
          return city + ', ' + state;
        } else if((city != null && city != '') && (shortCode != null && shortCode != '')){
          return city + ', ' + shortCode;
        } else if((state != null && state != '') && (shortCode != null && shortCode != '')){
          return state + ', ' + shortCode;
        } else if(city != null && city != ''){
          return city;
        } else if(state != null && state != ''){
          return state;
        } else if(shortCode != null && shortCode != ''){
          return shortCode;
        }
      },
      handleCalculate() {
        let self = this;

      if(self.data.hasOwnProperty('objSelectionProducts'))
      {
        self.orignalSellingPrice = self.orignalWeight = self.orignalHeight =  self.orignalWidth = self.orignalLength = self.totalAmount = self.totalQuantity = 0;
        if(Object.keys(self.data.objShipping).length > 0){
          self.formData = {...self.data.objShipping};
          self.formData.selling_price =  self.formData.sub_total = 0
          self.formData.width_type_id = self.formData.length_type_id = self.formData.height_type_id = 2;
           if(self.formData.shipping_method_id == null){
            self.formData.shipping_method_id = Object.keys(self.list.shippingmethods)[0];
          }
          if(self.formData.payment_mode == null){
            self.formData.payment_mode = self.data.payment_mode;
          }
          self.isOrderShipping = true;
        }
          self.shippingProducts = self.data.objSelectionProducts;
          if(self.formData.weight_type_id == null){
            self.formData.weight_type_id = Object.keys(self.list.weightmanages)[2];
          }
        self.formData.title = "";
        if(self.shippingProducts.length <= 0){   
          self.formData = null;
          return;
        }
        self.pickups = self.list.pickup_location;
        self.shippingProducts.forEach( function(element, index) {
          self.formData.title += element.title + ", ";
          self.formData.selling_price += parseFloat(element.price * element.quantity);
          self.totalAmount += parseFloat(element.price * element.quantity);
          self.totalQuantity += parseInt(element.quantity);
          self.orignalWeight += parseFloat(element.weight);
          self.orignalHeight += parseFloat(element.height);
          self.orignalWidth += parseFloat(element.width);
          self.orignalLength += parseFloat(element.length);
        });
          self.orignalSellingPrice = self.formData.selling_price;
          if(self.data.objCourierResponse.length > 0){
            self.handleCourierResponse(self.data.objCourierResponse);
          }
          self.formData.weight = (self.data.objShipping.weight > 0) ? self.data.objShipping.weight : self.orignalWeight;
          self.formData.height = (self.data.objShipping.height > 0) ? self.data.objShipping.height : self.orignalHeight;
          self.formData.width = (self.data.objShipping.width > 0) ? self.data.objShipping.width : self.orignalWidth;
          self.formData.length = (self.data.objShipping.length > 0) ? self.data.objShipping.length : self.orignalLength;
          self.formData.title = self.formData.title.slice(0, -2);
          self.formData.quantity = self.shippingProducts.length;
          self.formData.courier_id = null;
          if(self.data.objShipments.shipment_id != null){
            self.formData.courier_id = self.data.objShipments.courier_id;
          }
          self.order = self.data.order;
          self.customerInfo.email = self.data.order.email;
          self.customerInfo.phone = self.data.order.mobile;
          self.note = self.data.order.note;
          self.shippingMethodName = self.list.shippingmethods[self.formData.shipping_method_id];
          self.handleSubTotal();
          if(self.data.objShipping.pickup_id == null)
          {
            self.getPickupLocation();
          }

      }

      },
      confirmPrice(statusName){

        let self = this;
        let orignalSubTotal = parseFloat(self.formData.selling_price) + parseFloat(self.formData.tax) + parseFloat(self.formData.shipping_charges) + parseFloat(self.formData.giftwrap_charges) + parseFloat(self.formData.transaction_charges) - parseFloat(self.formData.total_discount);

          if(self.orignalSellingPrice > parseFloat(self.formData.selling_price) || orignalSubTotal > parseFloat(self.formData.sub_total)){
            Swal.fire(areYouSureWithCancel('Are You Sure Want To' + statusName ,'Yes')).then(function (result) {
              if (result.value) {
                 if(statusName == 'edit selling price'){
                self.handleSubTotal();
              }
              } 
              else if (result.dismiss === Swal.DismissReason.cancel) {
               if(statusName == 'edit selling price'){
                 self.formData.selling_price = self.orignalSellingPrice;
                  self.handleSubTotal();
              }
                $('.modal').modal('hide');
              }
            });
          }  
          else{
              self.handleSubTotal();

          }
      },
      handleSubTotal(){
        let self = this;
          self.formData.sub_total = parseFloat(self.formData.selling_price) + parseFloat(self.formData.tax) + parseFloat(self.formData.shipping_charges) + parseFloat(self.formData.giftwrap_charges) + parseFloat(self.formData.transaction_charges) - parseFloat(self.formData.total_discount);
      },

      
       handleAvailableCouriers(){
        this.$refs.shippingProductForm.validate().then(success => {
          if (!success) {
            $("html, body").animate({ scrollTop: 800 }, 200);
            return;
          }
          this.checkRateData ={ 
          'to_pincode' : this.data.shipping_address.postal_code,
          'shipping_id' : this.formData.id,
          'weight' : this.formData.weight,
           'weight_type_id' : this.formData.weight_type_id,
           'height' : this.formData.height,
           'height_type_id' : this.formData.height_type_id,
           'width' : this.formData.width,
           'width_type_id' : this.formData.width_type_id,
           'length' : this.formData.length,
           'length_type_id' : this.formData.length_type_id,
           'payment_mode' : this.formData.payment_mode,
           'order_type' : "forward",
           'product_mrp' : this.formData.sub_total,
           'shipping_method_id' : this.formData.shipping_method_id,
           'pickup_id' : this.formData.pickup_id

        };
        openLoader();
            this.$store.dispatch("shippingProductsModule/availableCouriers", this.checkRateData)
            .then((res) => {
              if (res.response.status_code == 3091) {
                let self = this;
                self.handleCourierResponse(res.response.data);
                successModal(res.response.message);

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
      handleCourierResponse(inputTrackArray){
        let self = this;
        if(inputTrackArray.length > 6){

                  let perChunk = Math.round(inputTrackArray.length/2) // items per chunk
                   self.objCouriersResponse = inputTrackArray.reduce((resultArray, item, index) => {
                  let chunkIndex = Math.floor(index/perChunk)

                  if(!resultArray[chunkIndex]) {
                  resultArray[chunkIndex] = [] // start a new chunk
                  }
                  resultArray[chunkIndex].push(item)
                  return resultArray
                  }, [])

                }
                else{
                  self.objCouriersResponse = [inputTrackArray,[]];
                }
              },
      handleActions(actionsName){
            openLoader();
            let data = {
               shipping_id: this.formData.id,
               status: actionsName
            };
            this.$store.dispatch("shippingProductsModule/handleActions", data)
            .then((res) => {
              if (res.response.status_code == 3077) {
                successModal(res.response.message);

                let urlpath = res.response.data;
                    const link = document.createElement('a');
                    link.href = urlpath;
                    link.setAttribute('download', '');
                    link.setAttribute('target', '_blank');
                    document.body.appendChild(link);
                    setTimeout(function(){
                      link.click();
                    }, 2000);
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
      getPickupLocation(){
            this.objCouriersResponse = [];
            this.formData.courier_id = null;
        let section = $('#pickupList');
        blockSection(section);
        let shippingMethodId = this.formData.shipping_method_id;
        this.$store.dispatch("shippingProductsModule/getPickupLocation", shippingMethodId)
        .then((res) => {
          if (res.response.status_code == 3090) {
            let self = this;
            self.pickups = res.response.data;
            self.formData.pickup_id = Object.keys(self.pickups)[0];
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
      removeShippingProduct(index){
        let self = this;
        let id = self.shippingProducts[index].id;
        openLoader();
            self.$store.dispatch("shippingProductsModule/removeProducts", id)
            .then((res) => {
              if (res.response.status_code == 3075) {
                self.shippingProducts.splice(index, 1);
                successModal(res.response.message);
                self.handleCalculate();
                if(self.shippingProducts.length == 0){
                  setTimeout(function(){
                    window.location = "/admin/shippingproducts";
                  },5000);
                }
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
     
      deleteShippingOrder(){
        openLoader();
            this.$store.dispatch("shippingProductsModule/deleteShippingOrder", this.formData.id)
            .then((res) => {
              if(res.response.status_code == 3100)
              {
                successModal(res.response.message);
                setTimeout(function(){
                  window.location = res.response.data.url;
                }, 2000);
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
      },
      cancelShippingOrder(){
        openLoader();
            this.$store.dispatch("shippingProductsModule/cancelShippingOrder", this.formData.id)
            .then((res) => {
              if (res.response.status_code == 3085) {
                successModal(res.response.message);
                setTimeout(function(){
                  window.location = res.response.data;
                }, 2000);
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
      },
      pickupOrder(){
        openLoader();
            this.$store.dispatch("shippingProductsModule/PickupOrder", this.formData.id)
            .then((res) => {
              if (res.response.status_code == 3089) {
                successModal(res.response.message);
                setTimeout(function(){
                   window.location = res.response.data;
                },5000);
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
      trackUrl(){
        openLoader();
         let id = this.data.objShipments.id;
             
            this.$store.dispatch("shippingProductsModule/checkTrackUrl", id)
            .then((res) => {

              if (res.response.status_code == 3093) {
              let successResponse = res.response.data;
              this.objCheckTrackUrlResponse = successResponse.track_data;
              this.objTrackUrl = successResponse.track_url;
              $('#exampleModalCenter').modal('show');
              }
              else {
                errorModal(res.response.message);
              }
              closeLoader();
            })
            .catch((err) => {
              closeLoader();
              errorModal(err.response.message);
            });
      },
      deliveredShipping(){
        openLoader();
            this.$store.dispatch("shippingProductsModule/deliveredShippingOrder", {'shipment_id' : this.data.objShipments.id})
            .then((res) => {

              if (res.response.status_code == 3102) {
                 successModal(res.response.message);
                  setTimeout(function(){
                      window.location = res.response.data.url;
                    },5000);
              }
              else {
                errorModal(res.response.message);
              }
              closeLoader();
            })
            .catch((err) => {
              closeLoader();
              errorModal(err.response.message);
            });
      },
      saveCodPayment(){
        openLoader();
         let id = this.data.objShipments.id;
            this.$store.dispatch("shippingProductsModule/saveCodPayment", id)
            .then((res) => {

             if (res.response.status_code == 3123) {
                 successModal(res.response.message);
                  setTimeout(function(){
                      window.location = res.response.data.url;
                    },5000);
              }
              else {
                errorModal(res.response.message);
              }
              closeLoader();
            })
            .catch((err) => {
              closeLoader();
              errorModal(err.response.message);
            });
      },
      submit(){
        this.$refs.shippingProductForm.validate().then(success => {
          if (!success) {
            $("html, body").animate({ scrollTop: 800 }, 200);
            return;
          }

          this.formData.order_id = this.data.order.id;
          this.formData.shippingProducts = this.shippingProducts; 
          this.formData.shippingStatus = this.setShippingStatus; 
          if(this.formData.courier_id != null || this.formData.shippingStatus == 0){
            openLoader();
            this.$store.dispatch("shippingProductsModule/ShippingProducts", this.formData)
            .then((res) => {
              if (res.response.status_code == 3073 || res.response.status_code == 3095 || res.response.status_code == 3104) {
                successModal(res.response.message);
                if(this.setShippingStatus == 1){
                  setTimeout(function(){
                    window.location = res.response.data.url;
                  },5000);
                }
              }
              else if(res.response.status_code == 500)
              {
                errorModal(res.response.message);
              }
              else{
                errorModal(res.response.message);
              }
              closeLoader();
            })
            .catch((err) => {
              console.log(err);
              closeLoader();
              errorModal(err.response.message);
            });
          }
          else{
            errorModal('Please click on available couriers !!');
          }

          
        });
      },
      
    }
  }
</script>
<style lang="scss" scoped>
  .total-amount{
    margin-left: -40px;
  }
  .total-quantity{
    margin-left:-12px;
  }
  
  label{
    display:block;
  }
  .opacity-0{
    opacity: 0;
  }
  hr{
    margin-bottom: 3rem;
  }
 
 
  .cart-table-switch{
    margin-top:10px;
    display:flex;
    align-items:center;
  }
  .prd-sku {
    margin-right:10px;
    font-size: 14px;
    font-weight: 500;
    line-height: 1em;
    color: #6e6b7b;
  }
  .prd-sku .sku-input{
    width:120px;
  }
 
  .cart-table-prd-name:not(:first-child) {
    margin-top: 7px;
  }
  .cart-table-prd-name {
    font-size: 18px;
    font-weight: 600;
    line-height: 1.2em;
    margin-bottom: 0;
  }
  .cart-table-prd-name a{
    color: #6e6b7b !important;

  }
  .cart-table-prd-info {
    flex-basis:60%;
  }
  
  .track-data .row:last-child{
    border-bottom:none !important;
  }
  .btn-available-couriers{
    margin-top:20px;
  }
  .availableCouriers{
    .data-section{
      flex-basis:49% !important;
      border:1px solid #e1dfdf;
      margin:0px !important;

      .tbl-heading{
        padding:10px 10px !important;
        border-bottom:1px solid #e1dfdf;
      }
      .tbl-des{
        padding: 15px 15px !important;
      }
    }
      .custom-control-input{
        opacity:1;
      }
  }

</style>