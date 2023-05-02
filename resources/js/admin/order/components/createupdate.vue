<template>
  <div id="input-sizing">
    <ValidationObserver ref="orderForm" v-slot="{ handleSubmit }">
      <form method="POST" enctype="multipart/form-data" id="frmAddEditOrder" @submit.prevent="handleSubmit(submit())">
          <div class="row">
            <div class="col-md-12 col-12">
                  <!-- Basic details start -->
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">{{ lang.cruds.order.create_order }}</h4>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="order_nr" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="order_nr">{{ lang.cruds.order.fields.order_nr }}</label>
                              <input class="form-control" placeholder="enter order nr" type="text" v-model="formData.order_nr" disabled="">
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <!-- <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="financial_status_id">{{ lang.cruds.order.fields.financial_status_id }}</label>
                              <select class="custom-select" value="Select Financial status" name="select_Financial_status" id="selectFinancialStatus" v-model="formData.financial_status_id">
                                  <option :value="id" v-for="(item , id) in list.financialStatuses" v-bind:key="id">{{ item }}</option>
                              </select>
                            </div>
                        </div> -->
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="paid_at" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="paid_at">{{ lang.cruds.order.fields.paid_at }}</label>
                              <date-picker 
                                      v-model="formData.paid_at"
                                      type="datetime"
                                      value-type="format"
                                      format="YYYY-MM-DD hh:mm"
                                      placeholder="Select paid datetime">
                              </date-picker>
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="fulfillment_status" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="fulfillment_status">{{ lang.cruds.order.fields.fulfillment_status }}</label>
                              <select class="custom-select" value="Please Select Status" name="select_fulfillment_status" id="selectFulfillmentStatus" v-model="formData.fulfillment_status">
                                <option value="" selected disabled>Please Select</option>
                                <option :value="id" v-for="(item , id) in list.fulfillmentstatus" v-bind:key="id">{{ item }}</option>
                              </select>
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="status" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="status">{{ lang.cruds.order.fields.status }}</label>
                              <select class="custom-select" value="Please Select Status" name="select_status" id="status" v-model="formData.status">
                                <option value="" selected disabled>Please Select</option>
                                 <option :value="id" v-for="(item , id) in list.status" v-bind:key="id">{{ item }}</option>
                              </select>
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="fulfilled_at" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="fulfilled_at">{{ lang.cruds.order.fields.fulfilled_at }}</label>
                              <date-picker 
                                      v-model="formData.fulfilled_at"
                                      type="datetime"
                                      value-type="format"
                                      format="YYYY-MM-DD hh:mm"
                                      placeholder="Select fulfilled datetime">
                              </date-picker>
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                       <div class="col-md-4 col-12">
                          <ValidationProvider  name="financial_status" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="financial_status">{{ lang.cruds.order.fields.financial_status }}</label>
                              <select class="custom-select" value="Please Select Status" name="select_financial_status" id="selectPaymentStatus" v-model="formData.financial_status">
                                <option value="" selected disabled>Please Select</option>
                                 <option :value="id" v-for="(item , id) in list.paymentstatus" v-bind:key="id">{{ item }}</option>
                              </select>
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="currency" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="currency">{{ lang.cruds.order.fields.currency }}</label>
                              <select class="custom-select" value="Please Select currency" name="select_currency" id="selectCurrency"     v-model="formData.currency_id">
                                  <option :value="id" v-for="(item , id) in list.currencies" v-bind:key="id">{{ item }}</option>
                              </select>
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="sub_total" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="sub_total">{{ lang.cruds.order.fields.sub_total }}</label>
                              <input class="form-control" placeholder="enter sub total" type="text" v-model="formData.sub_total">
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="shipping_cost" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="shipping_cost">{{ lang.cruds.order.fields.shipping_cost }}</label>
                              <input class="form-control" placeholder="enter shipping cost" type="text" v-model="formData.shipping_cost">
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="taxes" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="taxes">{{ lang.cruds.order.fields.taxes }}</label>
                              <input class="form-control" placeholder="enter taxes" type="text" v-model="formData.taxes">
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="total" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="total">{{ lang.cruds.order.fields.total }}</label>
                              <input class="form-control" placeholder="enter total" type="text" v-model="formData.total">
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="discount_code">{{ lang.cruds.order.fields.discount_code }}</label>
                              <input class="form-control" placeholder="enter discount code" type="text" v-model="formData.discount_code">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="discount_amount" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="discount_amount">{{ lang.cruds.order.fields.discount_amount }}</label>
                              <input class="form-control" placeholder="enter discount amount" type="text" v-model="formData.discount_amount">
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <!-- <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="shipping_method">{{ lang.cruds.order.fields.shipping_method }}</label>
                              <select class="custom-select" value="Please shipping method" name="select_shipping_method" id="selectShippingMethods" v-model="formData.shipping_method_id">
                                  <option :value="id" v-for="(item , id) in list.shippingMethods" v-bind:key="id">{{ item }}</option>
                              </select>
                            </div>
                        </div> -->
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="user" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="user_status">{{ lang.cruds.order.fields.user }}</label>
                              <select class="custom-select" value="Please select user" name="select_user" id="selectUser" v-model="formData.user_id">
                                  <option :value="id" v-for="(item , id) in list.users" v-bind:key="id">{{ item }}</option>
                                  </select>
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="email" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="email">{{ lang.cruds.order.fields.email }}</label>
                              <input class="form-control" placeholder="enter email" type="text" v-model="formData.email">
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="mobile" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="mobile">{{ lang.cruds.order.fields.mobile }}</label>
                              <input class="form-control" placeholder="enter mobile" type="number" v-model="formData.mobile">
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="gateway" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="gateway">{{ lang.cruds.order.fields.gateway }}</label>
                              <input class="form-control" placeholder="enter gateway" type="text" v-model="formData.gateway">
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        <div class="col-md-4 col-12">
                          <ValidationProvider  name="payment_method" rules="required" v-slot="{ errors }">
                            <div class="form-group">
                              <label class="required" for="payment_method">{{ lang.cruds.order.fields.payment_method }}</label>
                              <select class="custom-select" name="select_payment_method" id="selectPaymentMethod" v-model="formData.payment_method_id">
                                  <option :value="id" v-for="(item , id) in list.paymentMethods" v-bind:key="id">{{ item }}</option>
                              </select>
                              <p class="text-danger">{{ errors[0] }}</p>
                            </div>
                          </ValidationProvider>
                        </div>
                        
                         <div class="col-md-4 col-12">
                            <div class="form-group">
                              <input class="mr-1" type="checkbox" id="acceptsMarketing"  v-model="formData.accepts_marketing">
                              <label class="required" for="acceptsMarketing">{{ lang.cruds.order.fields.accepts_marketing }}</label>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="required label-radio" for="risklevel">{{ lang.cruds.order.fields.risk_level }}</label>
                              <div class="risklevel" id="selectriskLevel" v-for="(riskLevel , index) in riskleveltype">
                                <input type="radio" :id="`risklevel_${index}`"  :value="index" v-model="formData.risk_level" />
                                <label :for="`risklevel_${index}`">{{ riskLevel }}</label>
                              </div>
                        </div>
                        <div class="col-md-4 col-12">
                              <ValidationProvider  name="source" rules="required" v-slot="{ errors }">
                                <label class="required label-radio" for="source">{{ lang.cruds.order.fields.source }}</label>
                                  <div class="source" id="selectsource" v-for="(source , index) in sourcetype">
                                    <input type="radio" :id="`source_${index}`"  :value="index" v-model="formData.source" />
                                    <label :for="`source_${index}`">{{ source }}</label>
                                  </div>
                                <p class="text-danger">{{ errors[0] }}</p>
                              </ValidationProvider>
                        </div>
                        <!-- <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="tax_1_name">{{ lang.cruds.order.fields.tax_1_name }}</label>
                              <input class="form-control" placeholder="enter tax name" type="text" v-model="formData.tax_1_name">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="tax_1_value">{{ lang.cruds.order.fields.tax_1_value }}</label>
                              <input class="form-control" placeholder="enter tax value" type="text" v-model="formData.tax_1_value">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="tax_2_name">{{ lang.cruds.order.fields.tax_2_name }}</label>
                              <input class="form-control" placeholder="enter tax name" type="text" v-model="formData.tax_2_name">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="tax_2_value">{{ lang.cruds.order.fields.tax_2_value }}</label>
                              <input class="form-control" placeholder="enter tax value" type="text" v-model="formData.tax_2_value">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="tax_3_name">{{ lang.cruds.order.fields.tax_3_name }}</label>
                              <input class="form-control" placeholder="enter tax name" type="text" v-model="formData.tax_3_name">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="tax_3_value">{{ lang.cruds.order.fields.tax_3_value }}</label>
                              <input class="form-control" placeholder="enter tax value" type="text" v-model="formData.tax_3_value">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="tax_4_name">{{ lang.cruds.order.fields.tax_4_name }}</label>
                              <input class="form-control" placeholder="enter tax name" type="text" v-model="formData.tax_4_name">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="tax_4_value">{{ lang.cruds.order.fields.tax_4_value }}</label>
                              <input class="form-control" placeholder="enter tax value" type="text" v-model="formData.tax_4_value">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="tax_4_value">{{ lang.cruds.order.fields.tax_5_name }}</label>
                              <input class="form-control" placeholder="enter tax name" type="text" v-model="formData.tax_5_name">
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="tax_5_value">{{ lang.cruds.order.fields.tax_5_value }}</label>
                              <input class="form-control" placeholder="enter tax value" type="text" v-model="formData.tax_5_value">
                            </div>
                        </div> -->
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="receipt_number">{{ lang.cruds.order.fields.receipt_number }}</label>
                              <input class="form-control" placeholder="enter receipt number" type="text" v-model="formData.receipt_number">
                            </div>
                        </div>
                        <div class="col-md-8 col-12">
                            <div class="form-group">
                              <label class="required" for="note">{{ lang.cruds.order.fields.note }}</label>
                              <textarea class="form-control" type="text" placeholder="enter note" rows="4" v-model="formData.note"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                              <label class="required" for="order_nr">{{ lang.cruds.order.fields.parent_order_id }}</label>
                              <input class="form-control" placeholder="enter parent orderId" type="text" v-model="formData.parent_order_id" :disabled="formData.parent_order_id">
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Basic details end -->
            </div>
          </div>

          <div class="row">
             <div class="col-md-6 col-12">
            <div class="card"> 
              <div class="card-header">
                <h4 class="card-title">{{ lang.cruds.order.shipping_address }}</h4>
              </div>
              <div class="card-body">
                <div class="row mt-2">
                  <div class="col-6">
                    <ValidationProvider name="first_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="first_name">{{ lang.cruds.order.fields.first_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter first_name" v-model="shippingAddress.first_name" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="last_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="last_name">{{ lang.cruds.order.fields.last_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter last_name" v-model="shippingAddress.last_name" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="shipping_email" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="shipping_email">{{ lang.cruds.order.fields.email }}</label>
                        <input class="form-control" type="email" placeholder="Enter email" id="shipping_email"v-model="shippingAddress.email" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="shipping_mobile" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="shipping_mobile">{{ lang.cruds.order.fields.mobile }}</label>
                        <input class="form-control" type="number" placeholder="Enter mobile number" id="shipping_mobile" v-model="shippingAddress.mobile" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="address" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="address">{{ lang.cruds.order.fields.address }}</label>
                        <input class="form-control" type="text" placeholder="Enter address" v-model="shippingAddress.address" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="address_2" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="address_2">{{ lang.cruds.order.fields.address_2 }}</label>
                        <input class="form-control" type="text" placeholder="Enter address 2 " v-model="shippingAddress.address_2" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="shippingCountry"  rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="shippingCountry">{{ lang.cruds.order.fields.country }}</label>
                        <select class="custom-select" name="shippingCountry" id="shippingCountry" @change="changeCountryDisplay(1);sameAsShipping()" v-model="shippingAddress.country_id">
                          <option :value="id" v-for="(item , id) in list.countries" v-bind:key="id">{{ item }}</option>
                        </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider  name="shippingState" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="shippingState">{{ lang.cruds.order.fields.state }}</label>
                        <select class="custom-select"  name="shippingState" id="shippingState" v-model="shippingAddress.state_id" @change="sameAsShipping()" >
                          <option :value="index" v-for="(item, index) in shippingStates">{{ item }}</option>
                        </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="city_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="city_name">{{ lang.cruds.order.fields.city_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter city name" v-model="shippingAddress.city_name" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="postal_code" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="postal_code">{{ lang.cruds.order.fields.postal_code }}</label>
                        <input class="form-control" type="text" placeholder="Enter postalcode" v-model="shippingAddress.postal_code" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-12">
            <div class="card"> 
              <div class="card-header">
                <h4 class="card-title">{{ lang.cruds.order.billing_address }}</h4>
                <div class="form-check">
                    <input type="checkbox"  class="form-check-input" id="same_as_shipping" @change="sameAsShipping()" v-model="shippingAddress.same_as_billing" />
                    <label class="form-check-label" for="same_as_shipping">{{ lang.cruds.order.same_as_shipping }}</label>
                  </div>
              </div>
              <div class="card-body">
                <div class="row mt-2">
                  <div class="col-6">
                    <ValidationProvider name="first_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="first_name">{{ lang.cruds.order.fields.first_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter first name" v-model="billingAddress.first_name" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="last_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="last_name">{{ lang.cruds.order.fields.last_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter last name" v-model="billingAddress.last_name" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="billing_email" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="billing_email">{{ lang.cruds.order.fields.email }}</label>
                        <input class="form-control" type="email" placeholder="Enter email" id="billing_email" v-model="billingAddress.email" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="billing_mobile" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="billing_mobile">{{ lang.cruds.order.fields.mobile }}</label>
                        <input class="form-control" type="number" placeholder="Enter mobile number" id="billing_mobile" v-model="billingAddress.mobile" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="address" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="address">{{ lang.cruds.order.fields.address }}</label>
                        <input class="form-control" type="text" placeholder="Enter address" v-model="billingAddress.address" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="address_2" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="address_2">{{ lang.cruds.order.fields.address_2 }}</label>
                        <input class="form-control" type="text" placeholder="Enter address 2" v-model="billingAddress.address_2" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="billingAddressCountry"  rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="billingAddressCountry">{{ lang.cruds.order.fields.country }}</label>
                        <select class="custom-select" name="billingAddressCountry" id="billingAddressCountry" @change="changeCountryDisplay(0)" v-model="billingAddress.country_id" :disabled="disabledBillingAddress">
                          <option :value="id" v-for="(item , id) in list.countries" v-bind:key="id">{{ item }}</option>
                        </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider  name="billingState" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="billingState">{{ lang.cruds.order.fields.state }}</label>
                          <select class="custom-select"  name="billingState" id="billingState" v-model="billingAddress.state_id" :disabled="disabledBillingAddress">
                            <option :value="index" v-for="(item, index) in billingStates">{{ item }}</option>
                          </select>
                          <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="city_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="city_name">{{ lang.cruds.order.fields.city_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter city_name" v-model="billingAddress.city_name" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="postal_code" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="postal_code">{{ lang.cruds.order.fields.postal_code }}</label>
                        <input class="form-control" type="text" placeholder="Enter postalcode" v-model="billingAddress.postal_code" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
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
              <button class="btn btn-danger waves-effect waves-light" type="button" @click="backtolist()" >
                   {{ lang.global.back_to_list }}
              </button>
          </div>
          <br/>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';

export default {
    props: ['list', 'data', 'type'],
    name:'AddOrders',
  
    data() {
      return {
        formData:{
          order_nr:'',
          parent_order_id:'',
          financial_status_id:'',
          financial_status:'',
          status:'',
          fulfillment_status:0,
          currency_id:0,
          shipping_method_id:0,
          user_id:0,
          payment_method_id:0, 
          paid_at:'',
          fulfilled_at:'',
          sub_total:'',
          shipping_cost:'',
          taxes:'',
          total:'',
          email:'',
          note:'',
          mobile:'',
          email:'',
          gateway:'',
          discount_code:'',
          tax_1_name:'',
          tax_1_value:'',
          tax_2_name:'',
          tax_2_value:'',
          tax_3_name:'',
          tax_3_value:'',
          tax_4_name:'',
          tax_4_value:'',
          tax_5_name:'',
          tax_5_value:'',
          receipt_number:'',
          discount_amount:'',
          accepts_marketing:0,
          risk_level:'Low',
          source:'WEB',
        },
        shippingAddress:{
          first_name:'',
          last_name:'',
          email:'',
          mobile:'',
          address:'',
          address_2:'',
          country_id:null,
          state_id:null,
          city_name:'',
          postal_code:'',
          same_as_billing:false,
        },
        billingAddress:{
          first_name:'',
          last_name:'',
          email:'',
          mobile:'',
          address:'',
          address_2:'',
          country_id:null,
          state_id:null,
          city_name:'',
          postal_code:'',
        },
        shippingStates:[],
        billingStates:[],
        disabledBillingAddress: false,
        riskleveltype: {'Low': 'Low','High': 'High' },
        sourcetype: {'WEB': 'Web','APP': 'App' },
        beforeEdit: {},
      }
    },
    mounted(){

      this.billingAddress.country_id = this.shippingAddress.country_id  = this.data.defaultCountry;
      this.shippingStates = this.billingStates = this.list.states;
    
      if(this.type == 'Edit')
      {
        this.formData = this.data.order;
        this.formData.risk_level = 'Low';
        this.financial_status = this.data.financial_status;
        this.billing_address_id = this.data.billing_address_id;
        this.shipping_address_id = this.data.shipping_address_id;
        this.shippingAddress = this.data.shippingAddress;
        this.billingAddress = this.data.billingAddress;
        this.shippingStates = this.list.shippingStates;
        this.billingStates = this.list.billingStates;
      }
     },
    computed: {
    },
    created() {
      
    },
    methods: {
          sameAsShipping(){
                let billing_address_id = this.billingAddress.id;
                if(this.shippingAddress.same_as_billing){
                  this.billingStates = this.shippingStates;
                  this.billingAddress = {...this.shippingAddress};
                  this.billingAddress.id = billing_address_id;
                  this.disabledBillingAddress = true;
                }
                else {
                  this.disabledBillingAddress = false;
                }
                
            },
            changeCountryDisplay(shippingStatus)
            {
            let countryId = this.billingAddress.country_id;
            if(shippingStatus){
              countryId = this.shippingAddress.country_id; 
            }
            this.$store.dispatch("orderModule/GetState", {'country': countryId})
            .then((res) => {
              if (res.response.status_code == 2046) {
                if(shippingStatus){
                  this.shippingStates = res.response.data;
                }
                else {
                  this.billingStates = res.response.data; 
                }
              }
              else{
                    errorModal(err.response.message);
              }
            })
            .catch((err) => {
              errorModal(err.response.message);
            });
          },
      submit(){
                this.$refs.orderForm.validate().then(success => {
                        if (!success) {
                             $("html, body").animate({ scrollTop: 50 }, 200);
                          return;
                        }
                       
                openLoader();
                if(this.type == 'Add')
                {
                  this.$store.dispatch("orderModule/AddOrder", this.formData)
                  .then((res) => {
                      if (res.response.status_code == 3039) {
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
                  this.formData.shippingAddress = this.shippingAddress;
                  this.formData.billingAddress = this.billingAddress;
                  let self = this;
                   Swal.fire(areYouSureWithCancel('Are you want to update order !','Yes , update it!')).then(function (result) {
                      if (result.value) {
                         self.$store.dispatch("orderModule/EditOrder", self.formData)
                          .then((res) => {
                            if (res.response.status_code == 2075) {
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
                      } else if (result.dismiss === Swal.DismissReason.cancel) {
                        closeLoader();
                        safeRecord('order');
                      }
                    });
                }
               
            });
        },
        backtolist(){
            window.history.back();
        },
    },
    
  }
</script>

<style lang="scss" scoped>
.label-radio{
  display: block;
    margin-bottom: 20px;
}
.risklevel,.source{
  display: inline-block;
    margin-right: 10px;
}
hr{
  margin-bottom: 3rem;
}
.mx-datepicker{
  width:100%;
}
</style>