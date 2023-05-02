 <template>
  <div>
    <div id="input-sizing" v-if="formData != null">
      <ValidationObserver ref="returnShippingProductForm" v-slot="{ handleSubmit }">
        <form method="POST" id="frmRefundProduct" @submit.prevent="handleSubmit(submit())">
          <div class="row">
            <div class="col-12">
              <div class="form-group float-right">
                 <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_save_shipping_access && data.objShipments.shipment_id == null" type="submit" @click="setShippingStatus = 0">
                {{ lang.global.save }}
              </button>
                <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_save_shipping_order_access && data.objShipments.shipment_id == null" type="submit" @click="setShippingStatus = 1">{{ lang.global.save }} & {{ lang.global.return}} {{ lang.global.shipping }}
                </button>
                <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_return_delete_shipping_access && data.objShipments.shipment_id == null" type="button" @click="confirmDeleteBox('delete return shipping order','deleteReturnShippingOrder')">
                  {{ lang.global.delete_return_shipping }}
                </button>
                <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_return_cancel_shipping_access && data.objShipments.shipment_id != null" type="button"  @click="confirmCancelBox('cancel return shipping order','cancelReturnShippingOrder')">
                  {{ lang.global.cancel }} {{ lang.global.return}} {{ lang.global.shipping }}
                </button>
                <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_return_pickup_access  && data.objShipments.shipment_id != null && data.objShipments.pickup_status == 0 && shippingMethodName == 'ShipRocket'" type="button" @click="confirmPickupBox('pickup order','pickupReturnOrder')">
                  {{ lang.global.pickup }}
                </button>
                <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_track_url_access && data.objShipments.pickup_status == 1" type="button" @click="trackUrl()">
                  {{ lang.global.check_track }}
                </button>
                <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_shipping_delivered_access && data.objShipments.shipment_id != null" type="button" @click="deliveredReturnShipping()">
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
                    <div class="col-4">
                      <div class="detail-header">
                        <h5 class="detail-title mb-1">CONTACT INFORMATION</h5>
                      </div>
                      <div class="overview-deails">
                        <div class="contact-details" v-if="data.order != null">
                          <div class="mb-1">
                            <a class="pointer" v-if="data.order.email != null">{{ data.order.email }}</a>
                            <span v-else>No email available</span>
                          </div>
                          <div v-if="data.order.mobile != null">
                            <!-- <span v-if="formData.phoneCode">+{{formData.phoneCode}}</span> -->
                            <span>{{data.order.mobile}}</span>
                          </div>
                          <div v-else>
                            <span>No phone number</span>
                          </div>
                          <div class="prd-date mt-1"><h6>Order Created</h6>{{order.created_at}}</div>
                          <div class="prd-date mt-1"><h6>Shipping Created</h6>{{data.objReturnShipping.display_created_at}}</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="detail-header d-flex">
                        <h5 class="detail-title mb-1">SHIPPING ADDRESS</h5>
                        <div class="ml-auto">
                          <a href="" class="manage-action" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editAddress" @click="editAddress('shipping')">Edit</a>
                        </div>
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
                     <div class="col-4">
                            <div class="detail-header">
                               <h5 class="detail-title mb-1">Note</h5>
                            </div>
                            <div>
                               <p v-if="order.note != '' && order.note != null">{{order.note}}</p>
                               <p v-else>No notes added</p>
                            </div>

                             <div class="detail-header">
                              <h5 class="detail-title mb-1">Order Id</h5>
                           </div>

                            <div class="orderId-links">
                              <div>
                                 <a :href="data.order_link">{{data.order_id}}</a>
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
                <h4 class="card-title">{{ lang.cruds.returnshippingproducts.shipping_order_product }}</h4>
                <div class="form-group float-right" v-if="data.objShipments.shipment_id  != null">
                 <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_generate_manifest_access && shippingMethodName == 'ShipRocket'" @click="handleActions('generate_manifest_url')" role="button">
                      {{ lang.global.generate_manifest }}
                    </a>
                    <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_print_manifest_access && shippingMethodName == 'ShipRocket'" @click="handleActions('print_manifest_url')" role="button">
                      {{ lang.global.print_manifest }}
                    </a>
                    <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_generate_label_access && (shippingMethodName == 'ShipRocket' || shippingMethodName == 'Ithinklogistics')" role="button" @click="handleActions('label_url')">
                      {{ lang.global.generate_label }}
                    </a>
                    <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_generate_invoice_access && (shippingMethodName == 'ShipRocket' || shippingMethodName == 'Ithinklogistics')" role="button" @click="handleActions('invoice_url')">
                      {{ lang.global.generate_invoice }}
                    </a>
                </div>
              </div>
              <div class="card-body table-responsive">
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
                    <tr v-for="(product, index) in returnShippingProducts" :key="index">

                      <td>
                        <div class="text-center">
                          <img :src=" product.img_src" :alt="product.productName"  @error="setAltImg" height="80" width="70">
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
                        <div v-if="list.btn_return_delete_product_action_access">
                          <a data-tooltip="Remove Product" role="button"  @click="confirmDeleteBox('return shipping product','removeReturnShippingProduct',index)">
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
                  <h4 class="card-title">{{ lang.cruds.returnshippingproducts.pickup_location }}</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                   
                    <div class="col-12">
                      <ValidationProvider  name="name" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                          <label class="required" for="name">{{ lang.cruds.returnshippingproducts.fields.title }}</label>
                          <textarea class="form-control" id="name" rows="3" v-model="formData.title">
                          </textarea>
                          <p class="text-danger">{{ errors[0] }}</p>
                        </div>
                      </ValidationProvider>
                    </div>
                    <div class="col-4">
                      <ValidationProvider  name="quantity" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                          <label class="required" for="quantity">{{ lang.cruds.returnshippingproducts.fields.quantity }}</label>
                          <input class="form-control" type="text" v-model="formData.quantity">
                          <p class="text-danger">{{ errors[0] }}</p>
                        </div>
                      </ValidationProvider>
                    </div>
                    <div class="col-4">
                      <ValidationProvider  name="selling_price" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                          <label class="required" for="selling_price">{{ lang.cruds.returnshippingproducts.fields.selling_price }}</label>
                          <input class="form-control" type="text" v-model="formData.selling_price" @blur="confirmPrice('edit selling price')">
                          <p class="text-danger">{{ errors[0] }}</p>
                        </div>
                      </ValidationProvider>
                    </div>

                    <div class="col-4">
                      <ValidationProvider  name="taxes" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                          <label class="required" for="taxes">{{ lang.cruds.returnshippingproducts.fields.taxes }}</label>
                          <input class="form-control" type="text" v-model="formData.tax" @blur="handleSubTotal()">
                          <p class="text-danger">{{ errors[0] }}</p>
                        </div>
                      </ValidationProvider>
                    </div>
                    <div class="col-4">
                      <ValidationProvider  name="shipping_charges" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                          <label class="required" for="shipping_charges">{{ lang.cruds.returnshippingproducts.fields.shipping_charges }}</label>
                          <input class="form-control" type="text" v-model="formData.shipping_charges" @blur="handleSubTotal()">
                          <p class="text-danger">{{ errors[0] }}</p>
                        </div>
                      </ValidationProvider>
                    </div>
                    <div class="col-4">
                      <ValidationProvider  name="transaction_charges" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                          <label class="required" for="transaction_charges">{{ lang.cruds.returnshippingproducts.fields.transaction_charges }}</label>
                          <input class="form-control" type="text" v-model="formData.transaction_charges" @blur="handleSubTotal()">
                          <p class="text-danger">{{ errors[0] }}</p>
                        </div>
                      </ValidationProvider>
                    </div>
                    <div class="col-4">
                      <ValidationProvider  name="total_discount" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                          <label class="required" for="total_discount">{{ lang.cruds.returnshippingproducts.fields.total_discount }}</label>
                          <input class="form-control" type="text" v-model="formData.total_discount" @blur="handleSubTotal()">
                          <p class="text-danger">{{ errors[0] }}</p>
                        </div>
                      </ValidationProvider>
                    </div>
                    <div class="col-4">
                      <ValidationProvider  name="sub_total" rules="required" v-slot="{ errors }">
                        <div class="form-group">
                          <label class="required" for="sub_total">{{ lang.cruds.returnshippingproducts.fields.sub_total }}</label>
                          <input class="form-control" type="text" v-model="formData.sub_total" @blur="confirmPrice('edit sub total')">
                          <p class="text-danger">{{ errors[0] }}</p>
                        </div>
                      </ValidationProvider>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-6">
                      <ValidationProvider  name="weight" rules="required|min_value:0.1" v-slot="{ errors }">
                        <div class="form-group ">
                          <label class="required" for="weight">{{ lang.cruds.returnshippingproducts.fields.weight }}</label>
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
                          <label class="required" for="length">{{ lang.cruds.returnshippingproducts.fields.length }}</label>
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
                          <label class="required" for="width">{{ lang.cruds.returnshippingproducts.fields.width }}</label>
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
                          <label class="required" for="height">{{ lang.cruds.returnshippingproducts.fields.height }}</label>
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
                          <label class="required" for="shipping_metohd">{{ lang.cruds.returnshippingproducts.fields.shipping_metohd }}</label>
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
                        <label class="required" for="pickup_id">{{ lang.cruds.returnshippingproducts.fields.pickup }}</label>
                        <select class="custom-select" name="pickup_id" id="pickup_id" v-model="formData.pickup_id" :disabled="data.objShipments.shipment_id != null">
                          <option :value="index" v-for="(item , index) in pickups">{{ item }}</option>
                        </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                    <div class="col-2 pr-0 btn-available-couriers">
                      <button class="btn btn-primary waves-effect waves-light" type="button" v-if="data.objShipments.shipment_id == null" @click="handleAvailableCouriers()">
                       {{ lang.cruds.returnshippingproducts.available_couriers }}
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
                                      <h5>{{ lang.cruds.returnshippingproducts.logistics }}</h5>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <div class="title">
                                      <h5> {{ lang.cruds.returnshippingproducts.rating }}</h5>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <div class="title">
                                      <h5> {{ lang.cruds.returnshippingproducts.rate }}</h5>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <div class="title text-center">
                                      <h5> {{ lang.cruds.returnshippingproducts.days }}</h5>
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
                                  <div class="des-data">
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
                                      <h5>{{ lang.cruds.returnshippingproducts.logistics }}</h5>
                                    </div>
                                  </div>
                                   <div class="col-2">
                                    <div class="title">
                                      <h5> {{ lang.cruds.returnshippingproducts.rating }}</h5>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <div class="title">
                                      <h5> {{ lang.cruds.returnshippingproducts.rate }}</h5>
                                    </div>
                                  </div>
                                  <div class="col-2">
                                    <div class="title text-center">
                                      <h5> {{ lang.cruds.returnshippingproducts.days }}</h5>
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
                                <div class="col-2">
                                  <div class="des-data text-center">
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
                <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_save_shipping_access && data.objShipments.shipment_id == null" type="submit" @click="setShippingStatus = 0">
                {{ lang.global.save }}
              </button>
                <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_save_shipping_order_access && data.objShipments.shipment_id == null" type="submit" @click="setShippingStatus = 1">{{ lang.global.save }} & {{ lang.global.return}} {{ lang.global.shipping }}
                </button>
                <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_return_delete_shipping_access && data.objShipments.shipment_id == null" type="button" @click="confirmDeleteBox('delete return shipping order','deleteReturnShippingOrder')">
                  {{ lang.global.delete_return_shipping }}
                </button>

                <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_return_cancel_shipping_access && data.objShipments.shipment_id != null" type="button"  @click="confirmCancelBox('cancel return shipping order','cancelReturnShippingOrder')">
                  {{ lang.global.cancel }} {{ lang.global.return}} {{ lang.global.shipping }}
                </button>
                <button class="btn btn-danger waves-effect waves-light" v-if="list.btn_return_pickup_access  && data.objShipments.shipment_id != null && data.objShipments.pickup_status == 0 && shippingMethodName == 'ShipRocket'" type="button" @click="confirmPickupBox('pickup order','pickupReturnOrder')">
                  {{ lang.global.pickup }}
                </button>
                <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_track_url_access && data.objShipments.pickup_status == 1" type="button" @click="trackUrl()">
                  {{ lang.global.check_track }}
                </button>
                <button class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_shipping_delivered_access && data.objShipments.shipment_id != null" type="button" @click="deliveredReturnShipping()">
                {{ lang.global.delivered }}
              </button>
              </div>
              <div class="form-group float-right" v-if="data.objShipments.shipment_id != null">
                <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_generate_manifest_access && shippingMethodName == 'ShipRocket'" @click="handleActions('generate_manifest_url')" role="button">
                {{ lang.global.generate_manifest }}
                </a>
                <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_print_manifest_access && shippingMethodName == 'ShipRocket'" @click="handleActions('print_manifest_url')" role="button">
                  {{ lang.global.print_manifest }}
                </a>
                <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_generate_label_access && (shippingMethodName == 'ShipRocket' || shippingMethodName == 'Ithinklogistics')" role="button" @click="handleActions('label_url')">
                  {{ lang.global.generate_label }}
                </a>
                <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" v-if="list.btn_return_generate_invoice_access && (shippingMethodName == 'ShipRocket' || shippingMethodName == 'Ithinklogistics')" role="button" @click="handleActions('invoice_url')">
                  {{ lang.global.generate_invoice }}
                </a>
              </div>
            </div>
          </div>

          <!-- editAddress Modals start -->
          <div class="modal fade" id="editAddress" tabindex="-1" role="dialog" aria-labelledby="editAddressTitle" aria-hidden="true" ref="editAddress">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editAddressTitle">Edit address</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label class="required" for="fname">First Name</label>
                        <input class="form-control" type="text" v-model="address.first_name" id="fname">
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input class="form-control" type="text" v-model="address.last_name" id="lname">
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label class="required" for="address">{{ lang.cruds.address.fields.address }}</label>
                        <input class="form-control" type="text" v-model="address.address">
                      </div>
                      <div class="form-group">
                        <label for="address_2">{{ lang.cruds.address.fields.address_2 }}</label>
                        <input class="form-control" type="text" v-model="address.address_2">
                      </div>
                      <div class="form-group">
                        <label class="required" for="city_name">{{ lang.cruds.address.fields.city_name }}</label>
                        <input class="form-control" type="text" v-model="address.city_name">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="country_id">{{ lang.cruds.address.fields.country }}</label>
                        <select class="custom-select" v-model="address.country_id" @change="getState()">
                          <option value="">Country/Region</option>
                          <option :value="ldata.id" v-for="(ldata, index) in list.countries">{{ ldata.name }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4" id="stateList">   
                      <div class="form-group">
                        <label class="required" for="state_id">{{ lang.cruds.address.fields.state }}</label>
                        <select class="custom-select" v-model="address.state_id">
                          <option :value="index" v-for="(ldata, index) in states">{{ ldata }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="required" for="postal_code">{{ lang.cruds.address.fields.postal_code }}</label>
                        <input class="form-control" type="text" v-model="address.postal_code">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="mobile">{{ lang.cruds.address.fields.phone_code }}</label>
                        <select class="custom-select" v-model="address.phone_code">
                          <option :value="ldata.phone_code" v-for="(ldata, phone_code) in list.countries">{{ ldata.name + '(+' + ldata.phone_code + ')' }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-10">
                      <div class="form-group">
                        <label for="mobile" class="ml-3">{{ lang.cruds.address.fields.mobile }}</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text">+{{ address.phone_code }}</span>
                          </div>
                          <input class="form-control" type="number" v-model="address.mobile">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ lang.global.cancel }}</button>
                  <button type="button" class="btn btn-primary" id="editAddressBtn" @click="updateCustomerAddress()">Edit</button>
                </div>
              </div>
            </div>
          </div>
          <!-- editAddress Modals End -->

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
    name:'returnshippingProduct',
    data() {
      return {
        objCouriersResponse: [],
        objCheckTrackUrlResponse: null,
        isOrderShipping : false,
        formData : null,
        setShippingStatus : 0,
        shippingMethodName:null,
        objTrackUrl:null,
        returnShippingProducts : [],
        checkRateData:{},
        order: {},
        states: [],
        pickups:[],
        address:{
          first_name:'',
          last_name:'',
          mobile:'',
          address:'',
          address_2:'',
          city_name:'',
          country_id:'',
          state_id:'',
          postal_code:'',
          phone_code:''
        },
        customerInfo:{
          email:'',
          phone:'',
          isUpdateProfile:false
        }
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
       setAltImg(event){
        event.target.src = this.noImage;
      },
      fullNameAddressUser(address) {
        return address.first_name + ' ' + address.last_name;
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
      editAddress(type){
        if(type == 'shipping'){ 
          this.address = {...this.data.shipping_address};
        } 
        
        this.getState();
      },
      
      confirmPrice(statusName){
        let self = this;
        let orignalSubTotal = parseFloat(self.formData.selling_price) + parseFloat(self.formData.tax) + parseFloat(self.formData.shipping_charges) + parseFloat(self.formData.transaction_charges) - parseFloat(self.formData.total_discount);
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
          self.formData.sub_total = parseFloat(self.formData.selling_price) + parseFloat(self.formData.tax) + parseFloat(self.formData.shipping_charges) + parseFloat(self.formData.transaction_charges) - parseFloat(self.formData.total_discount);
      },
     
      handleCalculate() {
      let self = this;
      if(self.data.hasOwnProperty('objSelectionProducts')){
        self.orignalSellingPrice  = self.orignalWeight = self.orignalHeight =  self.orignalWidth = self.orignalLength = self.totalAmount = self.totalQuantity = 0;
      if(Object.keys(self.data.objReturnShipping).length > 0){
        self.formData = {...self.data.objReturnShipping};
        self.formData.selling_price =  self.formData.sub_total = 0
        self.formData.width_type_id = self.formData.length_type_id = self.formData.height_type_id = 2;
         if(self.formData.shipping_method_id == null){
           self.formData.shipping_method_id = Object.keys(self.list.shippingmethods)[0];;
        }
        self.isOrderShipping = true;
      }
      self.returnShippingProducts = self.data.objSelectionProducts;
          if(self.formData.weight_type_id == null){
            self.formData.weight_type_id = Object.keys(self.list.weightmanages)[2];
          }
          self.formData.title = "";
        if(self.returnShippingProducts.length <= 0){   
          self.formData = null;
          return;
        }
        self.pickups = self.list.pickup_location;
      self.returnShippingProducts.forEach( function(element, index) {
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
        self.formData.weight = (self.data.objReturnShipping.weight > 0) ? self.data.objReturnShipping.weight : self.orignalWeight;
          self.formData.height = (self.data.objReturnShipping.height > 0) ? self.data.objReturnShipping.height : self.orignalHeight;
          self.formData.width = (self.data.objReturnShipping.width > 0) ? self.data.objReturnShipping.width : self.orignalWidth;
          self.formData.length = (self.data.objReturnShipping.length > 0) ? self.data.objReturnShipping.length : self.orignalLength;
        self.formData.title = self.formData.title.slice(0, -2);
        self.formData.quantity = self.returnShippingProducts.length;
        self.order = self.data.order;
        self.customerInfo.email = self.data.order.email;
        self.customerInfo.phone = self.data.order.mobile;
        self.note = self.data.order.note;
        self.shippingMethodName = self.list.shippingmethods[self.formData.shipping_method_id];
        self.handleSubTotal();
        if(self.data.objReturnShipping.pickup_id == null)
        {
            self.getPickupLocation();
        }
      }

      },
       handleAvailableCouriers(){
         this.$refs.returnShippingProductForm.validate().then(success => {
          if (!success) {
            $("html, body").animate({ scrollTop: 800 }, 200);
            return;
          }
        this.checkRateData ={ 
          'to_pincode' : this.data.shipping_address.postal_code,
          'return_shipping_id' : this.formData.id,
          'weight' : this.formData.weight,
           'weight_type_id' : this.formData.weight_type_id,
           'height' : this.formData.height,
           'height_type_id' : this.formData.height_type_id,
           'width' : this.formData.width,
           'width_type_id' : this.formData.width_type_id,
           'length' : this.formData.length,
           'payment_mode' : "Prepaid",
           'length_type_id' : this.formData.length_type_id,
           'order_type' : "Reverse",
           'product_mrp' : this.formData.sub_total,
           'shipping_method_id' : this.formData.shipping_method_id,
           'pickup_id' : this.formData.pickup_id

        };
        openLoader();
            this.$store.dispatch("returnShippingProductsModule/availableCouriers", this.checkRateData)
            .then((res) => {
              if (res.response.status_code == 3091) {
                let self = this;
                self.handleCourierResponse(res.response.data);
                successModal(res.response.message);
                this.formData.courier_id = null;

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
            this.$store.dispatch("returnShippingProductsModule/handleActions", data)
            .then((res) => {
              if (res.response.status_code == 3077) {
                successModal(res.response.message);
                let urlpath = res.response.data;
                  const link = document.createElement('a');
                  link.href = urlpath;
                  link.setAttribute('download', urlpath);
                  link.setAttribute('target', '_blank');
                  document.body.appendChild(link);
                  setTimeout(function(){
                      link.click();
                    }, 2000);
              }
              else{
                errorModal(err.response.message);
              }
              closeLoader();
            })
            .catch((err) => {
              closeLoader();
              errorModal(err.response.message);
            });
      },
      getState(){
        let section = $('#stateList');
        blockSection(section);
        let countryId = this.address.country_id;
        this.$store.dispatch("returnShippingProductsModule/GetStates", countryId)
        .then((res) => {
          if (res.response.status_code == 2046) {
            this.states = res.response.data;
            this.shippingStates = this.billingStates = this.states;
          }
          else{
                errorModal(err.response.message);
          }
          unblockSection(section);
        })
        .catch((err) => {
          unblockSection(section);
          errorModal(err.response.message);
        });
      },
      getPickupLocation(){
        this.objCouriersResponse = [];
        this.formData.courier_id = null;
        let section = $('#pickupList');
        blockSection(section);
        let shippingMethodId = this.formData.shipping_method_id;
        this.$store.dispatch("returnShippingProductsModule/getPickupLocation", shippingMethodId)
        .then((res) => {
          if (res.response.status_code == 3090) {
            this.pickups = res.response.data;
            this.formData.pickup_id = Object.keys(this.pickups)[0];
          }
          else{
                errorModal(err.response.message);
          }
          unblockSection(section);
        })
        .catch((err) => {
          unblockSection(section);
          errorModal(err.response.message);
        });
      },

      updateCustomerAddress(){
        let section = $('#editAddressBtn');
        blockSection(section);
        this.address.order_id = this.data.order_id;
        this.address.user_id = this.data.order.user_id;
        this.address.email = this.data.order.email;
        this.$store.dispatch("returnShippingProductsModule/UpdateCustomerAddress", this.address)
        .then((res) => {
          if (res.response.status_code == 2071) {
            $('#editAddress').modal('hide');
            this.shippingAddress = {...this.address};
            successModal(res.response.message);
            setTimeout(function(){
              window.location = res.response.data;
            },2000);
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


      removeReturnShippingProduct(index){
        let self = this;
        let id = self.returnShippingProducts[index].id;
        openLoader();
        self.$store.dispatch("returnShippingProductsModule/removeReturnProducts", id)
        .then((res) => {
          if (res.response.status_code == 3082) {
            self.returnShippingProducts.splice(index, 1);
            successModal(res.response.message);
            if(self.returnShippingProducts.length == 0){
              setTimeout(function(){
                window.location = res.response.url;
              },2000);
            }
            else{
                errorModal(res.response.message);
              }
          }
          closeLoader();
        })
        .catch((err) => {
          closeLoader();
          errorModal(err.response.message);
        });
      },
       deleteReturnShippingOrder(){
        openLoader();
            this.$store.dispatch("returnShippingProductsModule/deleteShippingOrder", this.formData.id)
            .then((res) => {
              if (res.response.status_code == 3081) {
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
      pickupReturnOrder(){
        openLoader();
            this.$store.dispatch("returnShippingProductsModule/PickupReturnOrder", this.formData)
            .then((res) => {
              if (res.response.status_code == 3089) {
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
      },
       trackUrl(){
        openLoader();
         let id = this.data.objShipments.id;
             
            this.$store.dispatch("returnShippingProductsModule/checkTrackUrl", id)
            .then((res) => {
              if (res.response.status_code == 3093) {
                let successResponse = res.response.data;
              this.objCheckTrackUrlResponse = successResponse.track_data;
              this.objTrackUrl = successResponse.track_url;
              $('#exampleModalCenter').modal('show');
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
       deliveredReturnShipping(){
        openLoader();
            this.$store.dispatch("returnShippingProductsModule/deliveredReturnShippingOrder", {'shipment_id' : this.data.objShipments.id})
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
      cancelReturnShippingOrder(){
        openLoader();
        this.$store.dispatch("returnShippingProductsModule/cancelReturnShippingOrder", this.formData.id)
        .then((res) => {
          if (res.response.status_code == 3081) {
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
      },
      submit(){
        this.$refs.returnShippingProductForm.validate().then(success => {
          if (!success) {
            $("html, body").animate({ scrollTop: 50 }, 200);
            return;
          }
          this.formData.order_id = this.data.order_id;
          this.formData.returnShippingProducts = this.returnShippingProducts;
          this.formData.shippingStatus = this.setShippingStatus; 
           if(this.formData.courier_id != null || this.formData.shippingStatus == 0){ 
            openLoader();
            this.$store.dispatch("returnShippingProductsModule/ReturnShippingProducts", this.formData)
            .then((res) => {
              if (res.response.status_code == 3073 || res.response.status_code == 3095 || res.response.status_code == 3106) {
                successModal(res.response.message);
                if(this.setShippingStatus == 1){
                  setTimeout(function(){
                     window.location = res.response.data.url;
                  },2000);
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
  .btn-available-couriers{
    margin-top:20px;
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
  .total-quantity{
        margin-left:-10px;
  }
  .total-amount{
      margin-left:-25px;
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