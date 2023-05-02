<template>
<div class="template-product checkout-content">
   <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/"> {{ lang.global.home }} </a></li>
               <li><span> {{ lang.global.checkout.checkout }} </span></li>
            </ul>
         </div>
   </div>
   <div class="holder">
      <div class="container">
         <h1 class="text-center checkout-head"> {{ lang.global.checkout.checkout }} {{ lang.global.checkout.wizard }} </h1>
         <div class="row">
            <div class="col-md-10">
               <div class="steps-progress">
                  <ul class="nav nav-tabs">
                     <li class="nav-item" style="max-width: 33.33%;" @click="changeProgress(1)">
                        <a class="nav-link step1 active" data-toggle="tab" href="#step1" ref="step1" data-step="1"><span>01.</span><span> {{ lang.global.checkout.shipping_address }} </span></a>
                     </li>
                     <li class="nav-item" style="max-width: 33.33%;" @click="changeProgress(2)">
                        <a class="nav-link step2" href="#step2" ref="step2" data-step="2"><span>02.</span><span> {{ lang.global.checkout.billing_address }} </span></a>
                     </li>
                     <!-- <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#step3" ref="step3"  data-step="3"><span>03.</span><span>Delivery Method</span></a>
                     </li> -->
                     <li class="nav-item" style="max-width: 33.33%;" @click="changeProgress(3)">
                        <a class="nav-link step4" href="#step4" ref="step4" data-step="4"><span>03.</span><span> {{ lang.global.checkout.payment_method }} </span></a>
                     </li>
                  </ul>
                  <div class="progress">
                     <div class="progress-bar progress-bar-success" role="progressbar" id="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="100" :style="{ width: progressbarWidth + '%' }"></div>
                  </div>
               </div>
               <div class="tab-content">
                  <div class="tab-pane fade show active" id="step1">
                     <div class="tab-pane-inside">
                        <!-- <p><a href="account-create.html">Login</a> or <a href="account-create.html">Register</a> for faster payment.</p> -->
                        <div v-if="!addShippingAddress">
                           <div v-if="shippingAddresses.length > 0">
                              <div class="card" v-for="(address, index) in shippingAddresses">
                                 <div class="card-body">
                                    <input :id="`formshippingRadio_`+index" :value="address.id" type="radio" class="radio bg-white input" v-model="shippingAddressId"  @change="changeDefaultAddress(address.id, 'shipping')" >
                                    <label :for="`formshippingRadio_`+index" class="w-100">
                                       <div class="d-flex">
                                          <div class="address-deails ml-2">
                                             <div v-if="address.first_name">
                                                <span v-if="address.first_name" class="view-details">{{address.first_name}}</span> 
                                                <span v-if="address.last_name" class="view-details">{{address.last_name}}</span> 
                                                <div v-if="address.email" class="view-details mb-1">{{address.email}}</div> 
                                                <div v-if="address.mobile" class="view-details">+{{ address.phone_code + ' ' + address.mobile }}</div>
                                             </div>
                                             <div class="mb-2" style="margin-bottom: 5px;">
                                                <span v-if="address.address" class="view-details">{{address.address + ' ,'}}</span>
                                                <span v-if="address.address_2" class="view-details">{{address.address_2 + ' ,'}}</span>
                                                <span v-if="address.city_name" class="view-details">{{address.city_name + ' ,'}}</span>
                                                <span v-if="address.Statename" class="view-details">{{address.Statename + ' ,'}}</span>
                                                <span v-if="address.Shortcode" class="view-details">{{address.Shortcode + ' ,'}}</span>
                                                <span v-if="address.postal_code" class="view-details">{{address.postal_code}}</span>
                                              </div>
                                          </div>
                                          <div class="ml-auto" v-if="shippingAddressId == address.id">
                                             <a class="pointer" @click="editAddress(index, 'shipping')"> {{ lang.global.edit }}</a>
                                          </div>
                                       </div>
                                       <div  class="ml-2" v-if="shippingAddressId == address.id">
                                          <button class="btn btn-sm continue" @click="setLocalStorage(address.id, 'shipping');"> {{ lang.global.continue }} </button>
                                       </div>
                                    </label>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="card mt-1" v-if="!addShippingAddress && checkLogin">
                           <div class="card-body">
                              <a class="pointer" @click="addNewShippingAddress()"> {{ lang.global.checkout.add_new_address }} </a>
                           </div>
                        </div>
                        <div class="card mt-1" v-if="addShippingAddress">
                           <div class="card-body">
                              <ValidationObserver ref="shippingform" v-slot="{ handleSubmit, invalid }">
                                 <form class="shippingform" @submit.prevent="handleSubmit(step1)">
                                    <div class="row mt-2">
                                          <div class="col-sm-9">
                                             <ValidationProvider name="First Name"  rules="required" v-slot="{ errors }">
                                                <label> {{ lang.global.checkout.first_name }} <span class="text-danger">*</span></label>
                                                <div class="form-group">
                                                   <input type="text" class="form-control input" v-model="shippingAddress.first_name">
                                                   <span class="error text-danger">{{ errors[0] }}</span>
                                                </div>
                                             </ValidationProvider>
                                          </div>
                                       <div class="col-sm-9">
                                          <ValidationProvider name="Last Name"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.last_name }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="shippingAddress.last_name">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                    </div>
                                    <!-- 3 = Customers can only check out using Mobile -->
                                    <div class="row mt-2" v-if="settings.toCheckOut != 3">
                                       <div class="col-md-18" v-if="settings.toCheckOut == 4 && settings.toCheckOut == 2">
                                          <ValidationProvider name="email" rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.login.email }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="email" class="form-control input" v-model="shippingAddress.email">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                       <div class="col-md-18" v-else>
                                          <label> {{ lang.global.login.email }}:</label>
                                          <div class="form-group">
                                             <input type="email" class="form-control input" v-model="shippingAddress.email">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row mt-2" v-if="settings.toCheckOut != 2">
                                       <div class="col-md-18" v-if="settings.toCheckOut == 4 && settings.toCheckOut == 3">
                                          <ValidationProvider name="Phone"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.phone }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="shippingAddress.mobile">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                       <div class="col-md-18" v-else>
                                          <label> {{ lang.global.checkout.phone }}:</label>
                                          <div class="form-group">
                                             <input type="text" class="form-control input" v-model="shippingAddress.mobile">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row mt-2">
                                       <div class="col-md-18">
                                          <ValidationProvider name="Address"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.address }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="shippingAddress.address">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                    </div>
                                    <div class="row mt-2">
                                       <div class="col-md-18">
                                          <label> {{ lang.global.checkout.apartment_suite }}{{ lang.global.etc }} </label>
                                          <div class="form-group">
                                             <input type="text" class="form-control input" v-model="shippingAddress.address_2">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row mt-2">
                                       <div class="col-md-18">
                                          <ValidationProvider name="Country"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.country }} <span class="text-danger">*</span></label>
                                             <div class="form-group select-wrapper select">
                                                <select class="form-control input" v-model="shippingAddress.country_id" @change="getStates(shippingAddress.country_id, 'shipping')">
                                                   <option :value="index" v-for="(country,index) in data.countries">{{country}}</option>
                                                </select>
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                    </div>
                                    <div class="row mt-2">
                                       <div class="col-sm-6">
                                          <ValidationProvider name="State"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.state }} <span class="text-danger">*</span></label>
                                             <div class="form-group select-wrapper select">
                                                <select class="form-control input" id="stateList" v-model="shippingAddress.state_id">
                                                   <option :value="index" v-for="(state,index) in shippingAddress.allStates">{{state}}</option>
                                                </select>
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                       <div class="col-sm-6">
                                          <ValidationProvider name="City_name"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.city }}{{ lang.global.login.name }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="shippingAddress.city_name">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                       <div class="col-sm-6">
                                          <ValidationProvider name="zip/postal code"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.zip }}{{ lang.global.checkout.postal_code }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="shippingAddress.postal_code">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                    </div>
                                    <div class="clearfix mt-2" v-if="!checkLogin">
                                       <input id="formcheckoutCheckbox1" name="checkbox1" type="checkbox" v-model="shippingAddress.isSaveAddress">
                                       <label for="formcheckoutCheckbox1">  {{ lang.global.checkout.save_information }} </label>
                                    </div>
                                    <div class="text-right mt-2" v-if="checkLogin">
                                       <button type="button" class="btn btn-sm" @click="cancelAddNewShippingAddress(true)"> {{ lang.global.cancel }} </button>
                                       <button type="submit" class="btn btn-sm" v-if="shippingType == 'add'"> {{ lang.global.checkout.save_and_continue }} </button>
                                       <button type="submit" class="btn btn-sm" v-else> {{ lang.global.save }} </button>
                                    </div>
                                    <div class="text-right mt-2" v-else>
                                       <button type="button" class="btn btn-sm" @click="cancelAddNewShippingAddress(false)">{{ lang.global.cancel }}</button>
                                       <button type="submit" class="btn btn-sm" v-if="shippingType == 'add'"> {{ lang.global.checkout.continue_shopping }} </button>
                                       <button type="submit" class="btn btn-sm" v-else> {{ lang.global.save }} </button>
                                    </div>
                                 </form>
                              </ValidationObserver>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="step2">
                     <div class="tab-pane-inside">
                        <div v-if="!addBillingAddress">
                           <div v-if="billingAddresses.length > 0">
                              <div class="card" v-for="(address, index) in billingAddresses">
                                 <div class="card-body">
                                    <input :id="`formbillingRadio_`+index" :value="address.id" type="radio" class="radio bg-white" v-model="billingAddressId"  @change="changeDefaultAddress(address.id, 'billing')">
                                    <label :for="`formbillingRadio_`+index" class="w-100">
                                       <div class="d-flex">
                                         <div class="address-deails ml-2">
                                             <div v-if="address.first_name">
                                                <span v-if="address.first_name" class="view-details">{{address.first_name}}</span> 
                                                <span v-if="address.last_name" class="view-details">{{address.last_name}}</span> 
                                                <div v-if="address.email" class="view-details mb-1">{{address.email}}</div> 
                                                <div v-if="address.mobile" class="view-details">+{{ address.phone_code + ' ' + address.mobile }}</div>
                                             </div>
                                             <div class="mb-2" style="margin-bottom: 5px;">
                                                <span v-if="address.address" class="view-details">{{address.address + ' ,'}}</span>
                                                <span v-if="address.address_2" class="view-details">{{address.address_2 + ' ,'}}</span>
                                                <span v-if="address.city_name" class="view-details">{{address.city_name + ' ,'}}</span>
                                                <span v-if="address.Statename" class="view-details">{{address.Statename + ' ,'}}</span>
                                                <span v-if="address.Shortcode" class="view-details">{{address.Shortcode + ' ,'}}</span>
                                                <span v-if="address.postal_code" class="view-details">{{address.postal_code}}</span>
                                             </div>
                                          </div>
                                          <div class="ml-auto" v-if="billingAddressId == address.id">
                                             <a class="pointer" @click="editAddress(index, 'billing')"> {{ lang.global.edit }} </a>
                                          </div>
                                       </div>
                                       <div class="ml-2" v-if="billingAddressId == address.id">
                                          <button class="btn btn-sm continue" @click="setLocalStorage(address.id, 'billing')"> {{ lang.global.continue }} </button>
                                       </div>
                                    </label>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="card mt-1" v-if="!addBillingAddress && checkLogin">
                           <div class="card-body">
                              <a class="pointer" @click="addNewBillingAddress()"> {{ lang.global.checkout.add_new_address }} </a>
                           </div>
                        </div>
                        <div class="card" v-if="addBillingAddress">
                           <div class="card-body">
                              <ValidationObserver ref="billingform" v-slot="{ handleSubmit, invalid }">
                                 <form class="billingform" @submit.prevent="handleSubmit(step2)">
                                    <!-- <div class="clearfix" v-if="!checkLogin"> -->
                                    <div class="clearfix">
                                       <input id="formcheckoutCheckbox2" name="checkbox2" type="checkbox" v-model="isSameAsBilling" @change="setBillingAddress()">
                                       <label for="formcheckoutCheckbox2"> {{ lang.global.checkout.the_same_as_shipping_address }} </label>
                                    </div>
                                    <div class="row mt-2">
                                       <div class="col-sm-9">
                                          <ValidationProvider name="First Name"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.first_name }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="billingAddress.first_name">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                       <div class="col-sm-9">
                                         <ValidationProvider name="Last Name"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.last_name }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="billingAddress.last_name">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                    </div>
                                    <div class="row mt-2" v-if="settings.toCheckOut != 3">
                                       <div class="col-md-18" v-if="settings.toCheckOut == 4 && settings.toCheckOut == 2">
                                          <ValidationProvider name="email" rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.login.email }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="email" class="form-control input" v-model="billingAddress.email">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                       <div class="col-md-18" v-else>
                                          <label> {{ lang.global.login.email }}:</label>
                                          <div class="form-group">
                                             <input type="email" class="form-control input" v-model="billingAddress.email">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row mt-2" v-if="settings.toCheckOut != 2">
                                       <div class="col-md-18" v-if="settings.toCheckOut == 4 && settings.toCheckOut == 3">
                                          <ValidationProvider name="Phone"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.phone }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="billingAddress.mobile">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                       <div class="col-md-18" v-else>
                                          <label> {{ lang.global.checkout.phone }}:</label>
                                          <div class="form-group">
                                             <input type="text" class="form-control input" v-model="billingAddress.mobile">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row mt-2">
                                       <div class="col-md-18">
                                         <ValidationProvider name="Address"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.address }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="billingAddress.address">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                    </div>
                                    <div class="row mt-2">
                                       <div class="col-md-18">
                                          <label> {{ lang.global.checkout.apartment_suite }}{{ lang.global.etc }}:</label>
                                          <div class="form-group">
                                             <input type="text" class="form-control input" v-model="billingAddress.address_2">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row mt-2">
                                       <div class="col-md-18">
                                          <ValidationProvider name="Country"  rules="required" v-slot="{ errors }">
                                             <label class="select" for="slct"> {{ lang.global.checkout.country }} <span class="text-danger">*</span></label>
                                             <div class="form-group select-wrapper">
                                                <select class="form-control input" id="slct" v-model="billingAddress.country_id" @change="getStates(billingAddress.country_id, 'billing')">
                                                  <option :value="index" v-for="(country,index) in data.countries">{{country}}</option>
                                                </select>
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>   
                                       </div>
                                    </div>
                                    <div class="row mt-2">
                                       <div class="col-sm-6">
                                          <ValidationProvider name="State"  rules="required" v-slot="{ errors }">
                                             <label class="select" for="stateList"> {{ lang.global.checkout.state }} <span class="text-danger">*</span></label>
                                             <div class="form-group select-wrapper">
                                                <select class="form-control input" id="stateList" v-model="billingAddress.state_id">
                                                   <option :value="index" v-for="(state,index) in billingAddress.allStates">{{state}}</option>
                                                </select>
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                       <div class="col-sm-6">
                                          <ValidationProvider name="City_name"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.city_name }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="billingAddress.city_name">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                       <div class="col-sm-6">
                                          <ValidationProvider name="zip/postal code"  rules="required" v-slot="{ errors }">
                                             <label> {{ lang.global.checkout.zip }}{{ lang.global.checkout.postal_code }} <span class="text-danger">*</span></label>
                                             <div class="form-group">
                                                <input type="text" class="form-control input" v-model="billingAddress.postal_code">
                                                <span class="error text-danger">{{ errors[0] }}</span>
                                             </div>
                                          </ValidationProvider>
                                       </div>
                                    </div>
                                    <div class="text-right mt-2" v-if="checkLogin">
                                       <button type="button" class="btn btn-sm" @click="cancelAddNewBillingAddress(true)"> {{ lang.global.cancel }} </button>
                                       <button type="sublit" class="btn btn-sm" v-if="billingType == 'add'"> {{ lang.global.checkout.save_and_continue }} </button>
                                       <button type="sublit" class="btn btn-sm" v-else> {{ lang.global.save }} </button>
                                    </div>
                                    <div class="text-right mt-2" v-else>
                                       <button type="button" class="btn btn-sm" @click="cancelAddNewBillingAddress(false)"> {{ lang.global.cancel }} </button>
                                       <button type="sublit" class="btn btn-sm" v-if="billingType == 'add'"> {{ lang.global.checkout.continue_shopping }} </button>
                                       <button type="sublit" class="btn btn-sm" v-else> {{ lang.global.save }} </button>
                                    </div>
                                 </form>
                              </ValidationObserver>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- <div class="tab-pane fade" id="step3">
                     <div class="tab-pane-inside">
                        <div class="clearfix">
                           <input id="formcheckoutRadio1" value="" name="radio1" type="radio" class="radio" checked="checked">
                           <label for="formcheckoutRadio1">Standard Delivery $2.99 (3-5 days)</label>
                        </div>
                        <div class="clearfix">
                           <input id="formcheckoutRadio2" value="" name="radio1" type="radio" class="radio">
                           <label for="formcheckoutRadio2">Express Delivery $10.99 (1-2 days)</label>
                        </div>
                        <div class="clearfix">
                           <input id="formcheckoutRadio3" value="" name="radio1" type="radio" class="radio">
                           <label for="formcheckoutRadio3">Same-Day $20.00 (Evening Delivery)</label>
                        </div>
                        <div class="text-right">
                           <button type="button" class="btn btn-sm step-next">Continue</button>
                        </div>
                     </div>
                  </div> -->
                  <div class="tab-pane fade" id="step4">
                     <div class="tab-pane-inside">
                     <!-- razor pay-->

                        <div v-if="paymentMethods.length > 0">
                           <div class="clearfix mb-1" v-for="(method, index) in paymentMethods">
                              <input :id="`formcheckoutRadio_`+index" :value="method.id" name="paymentMethod" type="radio" class="radio input" v-model.number="activePaymentMethod" @click="setFinalPaymentMethod">
                              <label :for="`formcheckoutRadio_`+index">{{ method.title }}</label>
                           </div>
                        </div>
                        <div v-else>
                           <p class="p-1 font-weight-bold text-justify"> {{ lang.global.checkout.payment_method_not_available }} </p>
                        </div>
                        
                        <form name='razorpayform' method="POST">
                            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                            <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
                        </form>

                        <!-- cashfree form -->
                        <form id="cashfreeForm" method="post" :action="data.cashfreeurl" ref="cashfreeForm" style="visibility: hidden;">
                            <input type="hidden" name="appId" v-model="cashfreeForm.appId"/>
                            <input type="hidden" name="orderId" v-model="cashfreeForm.orderId" />
                            <input type="hidden" name="orderAmount" v-model="cashfreeForm.orderAmount"/>
                            <input type="hidden" name="orderCurrency" v-model="cashfreeForm.orderCurrency"/>
                            <input type="hidden" name="orderNote" v-model="cashfreeForm.orderNote"/>
                            <input type="hidden" name="customerName" v-model="cashfreeForm.customerName"/>
                            <input type="hidden" name="customerEmail" v-model="cashfreeForm.customerEmail"/>
                            <input type="hidden" name="customerPhone" v-model="cashfreeForm.customerPhone"/>
                            <input type="hidden" name="returnUrl" v-model="cashfreeForm.returnUrl"/>
                            <input type="hidden" name="notifyUrl" v-model="cashfreeForm.notifyUrl"/>
                            <input type="hidden" name="signature" v-model="cashfreeForm.signature"/>
                        </form>

                        <!-- paytm form -->
                        <form method="post" :action="data.paytmurl" id="paytmForm" ref="paytmForm" style="visibility: hidden;">
                           <table border="1">
                           <tbody>
                           <input type="hidden" name="REQUEST_TYPE"  v-model="paytmData.REQUEST_TYPE" />
                           <input type="hidden" name="MID"  v-model="paytmData.MID" />
                           <input type="hidden" name="ORDER_ID"  v-model="paytmData.ORDER_ID" />
                           <input type="hidden" name="CUST_ID"  v-model="paytmData.CUST_ID" />
                           <input type="hidden" name="INDUSTRY_TYPE_ID"  v-model="paytmData.INDUSTRY_TYPE_ID" />
                           <input type="hidden" name="CHANNEL_ID"  v-model="paytmData.CHANNEL_ID" />
                           <input type="hidden" name="TXN_AMOUNT"  v-model="paytmData.TXN_AMOUNT" />
                           <input type="hidden" name="WEBSITE"  v-model="paytmData.WEBSITE" />
                           <input type="hidden" name="CALLBACK_URL"  v-model="paytmData.CALLBACK_URL" />
                           <input type="hidden" name="MOBILE_NO"  v-model="paytmData.MOBILE_NO" />
                           <input type="hidden" name="EMAIL"  v-model="paytmData.EMAIL" />
                           <input type="hidden" name="CHECKSUMHASH" v-model="paytmData.CHECKSUMHASH" />
                           </tbody>
                           </table>
                        </form>
                     </div>
                     <div class="clearfix mt-1 mt-md-2" v-if="paymentMethods.length > 0">
                        <button type="button" id="placeOrder" class="btn btn--lg w-100" @click="placeOrder()"> {{ lang.global.checkout.place_order }} </button>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-8 pl-lg-8 mt-2 mt-md-0">
               <h2 class="custom-color"> {{ lang.global.checkout.order_summary }} </h2>
               <div class="cart-table cart-table--sm pt-3 pt-md-0">
                  <div class="cart-table-prd cart-table-prd--head py-1 d-none d-md-flex">
                     <div class="cart-table-prd-image text-center">
                        {{ lang.global.cart.image }}
                     </div>
                     <div class="cart-table-prd-content-wrap">
                        <div class="cart-table-prd-info"> {{ lang.global.login.name }} </div>
                        <div class="cart-table-prd-qty"> {{ lang.global.cart.qty }} </div>
                        <div class="cart-table-prd-price"> {{ lang.global.cart.price }} </div>
                     </div>
                  </div>
                  <div class="cart-table-prd" v-for="(product, index) in carts">
                     <div class="cart-table-prd-image">
                        <a :href="'/product/detail/'+ product.slug">
                           <img class="lazyload fade-up" :src="product.productImageSrc[2]" :alt="product.productName" @error="setAltImg"></a>
                     </div>
                     <div class="cart-table-prd-content-wrap">
                        <div class="cart-table-prd-info">
                           <h2 class="cart-table-prd-name"><a :href="'/product/detail/'+ product.slug">{{ product.productName }}</a></h2>
                           <div class="text-danger" v-if="!product.stock_status"> {{ lang.global.cart.out_of_stock }} </div>
                        </div>
                        <div class="cart-table-prd-qty">
                           <div class="qty qty-changer">
                              <button class="decrease" @click="decreaseQuantity(product.id)" :disabled="product.quantity == 0"></button>
                              <input type="text" class="qty-input disabled input" disabled="disabled" :value="product.quantity">
                              <button class="increase" @click="increaseQuantity(product.id)" :disabled="increaseDisable(product.isContinueSelling,product.maxOrderLimit,product.quantity)"></button>
                           </div>
                        </div>
                        <div class="cart-table-prd-price-total">
                           {{ $settings.CURRECNY_SYMBOL }}{{ product.productPrice * product.quantity }}
                        </div>
                     </div>
               </div>
               </div>
               <div class="mt-2"></div>
               <div class="card">
                  <div class="card-body">
                     <h3>{{ lang.global.checkout.apply_promocode }}</h3>
                     <p> {{ lang.global.checkout.promocode_got }} </p>
                     <div class="form-inline mt-2">
                        <input type="text" class="form-control input mr-1" v-model="couponCode" :disabled="disabledcuponcodeinput" placeholder="Promotion/Discount Code">
                        <button type="submit" class="btn btn-apply-code" @click="applyCuponCode()"> {{ lang.global.checkout.apply }} </button>
                        <button type="button" class="btn btn-clear-code d-none" @click="clearCuponCode()" ref="clearBtn"> {{ lang.global.clear }} </button>
                     </div>
                     <div class="coupon-message" v-if="couponMassage.message != ''">
                        <p :class="couponMassage.status ? 'text-success' : 'text-danger'"> {{ couponMassage.message }} </p>
                     </div>
                  </div>
               </div>
               <div class="mt-2"></div>
               <div class="mt-3 total-footer">
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <tbody>
                          <tr class="table-border-first">
                            <td>Sub Total</td>
                            <td class="text-right">{{ $settings.CURRECNY_SYMBOL }} {{cartcalulatedata.subTotal}}</td>
                          </tr>
                           <tr>
                            <td>Shipping Amount</td>
                            <td class="text-right">{{ $settings.CURRECNY_SYMBOL }} {{cartcalulatedata.shippingAmount}}</td>
                          </tr>
                           <tr>
                            <td>Tax Amount</td>
                            <td class="text-right">{{ $settings.CURRECNY_SYMBOL }} {{cartcalulatedata.taxAmount}}</td>
                          </tr>
                          <tr v-if="cartcalulatedata.voucherAmount > 0">
                            <td>Voucher Amount</td>
                            <td class="text-right">{{ $settings.CURRECNY_SYMBOL }} {{cartcalulatedata.voucherAmount}}</td>
                          </tr>
                          <tr>
                            <td class="font-weight-bold">Total</td>
                            <td class="font-weight-bold text-right">{{ $settings.CURRECNY_SYMBOL }} {{cartcalulatedata.total}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
               </div>
               <div class="mt-2"></div>
               <div class="card">
                  <div class="card-body">
                     <h3> {{ lang.global.checkout.order_comment }} </h3>
                     <textarea class="form-control input textarea--height-100" placeholder="Place your comment here" v-model="cashfreeForm.orderNote"></textarea>
                     <div class="card-text-info mt-2">
                        <p> {{ lang.global.checkout.savings }} </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>
</template>

<script>
import { mapState } from 'vuex';
import paymentMixin from './../../mixins/payment';


  export default {
    name: "Checkout",
    mixins: [paymentMixin],
    props:['carts', 'cartTotal', 'data', 'cartcalulatedata'],
    data() {
      return {
        cruds: [],
        paymentMethods:[],
        client_id: CLIENT_ID,
        activePaymentMethod:1,
        razorpayOptions:'',
        shippingAddresses:[],
        billingAddresses:[],
        isSameAsBilling:false,
        addShippingAddress:false,
        addBillingAddress:false,
        shippingAddressId:'',
        billingAddressId:'',
        couponCode:'',
        couponMassage:{'status': true, 'message': ''},
        disabledcuponcodeinput:false,
        shippingType:'add',
        billingType:'add',
        shippingAddress:{
            first_name:'',
            last_name:'',
            email:'',
            mobile:'',
            address:'',
            address_2:'',
            country_id:101,
            state_id:'',
            postal_code:'',
            city_name:'',
            status:0,
            isSaveAddress:true,
            allStates:[]
        },
        billingAddress:{
            first_name:'',
            last_name:'',
            email:'',
            mobile:'',
            address:'',
            address_2:'',
            country_id:101,
            state_id:'',
            postal_code:'',
            city_name:'',
            status:1,
            allStates:[]
        },
        cashfreeForm:{
            orderId: '',
            orderAmount: '',
            orderCurrency: '',
            orderNote: '',
            customerName: '',
            customerEmail: '',
            customerPhone: '',
            returnUrl: '',
            notifyUrl: '',
            signature: '',
        },
        paytmData:{
            REQUEST_TYPE:'',
            MID:'',
            ORDER_ID:'',
            CUST_ID:'',
            INDUSTRY_TYPE_ID:'',
            CHANNEL_ID:'',
            TXN_AMOUNT:'',
            WEBSITE:'',
            CALLBACK_URL:'',
            MOBILE_NO:'',
            EMAILL:'',
            CHECKSUMHASH:'',
        },
        tempAddress:{},
        settings:{},
        progressbarWidth:15,
        temp:{
            first_name:'',
            last_name:'',
            email:'',
            phone:'',
            address:'',
            address_2:'',
            country:101,
            state:'',
            postal_code:'',
            city_name:'',
            status:0,
            isSaveAddress:true,
            allStates:[]
        },
      }
    },
    computed: {
      ...mapState(['globalStore']),
      noImage(){
         return this.globalStore.no_image;
      },
      checkLogin(){
         return Object.keys(this.data.user).length > 0 ? true : false;
      }
    },
    created(){
       this.signature = this.data.signature;
       this.paymentMethods = this.data.paymentMethods;
       this.shippingAddresses = [...this.data.shippingAddresses];
       this.billingAddresses = [...this.data.billingAddresses];
       this.settings = this.data.settings;

      if( this.shippingAddresses.length > 0 ){
         var vm = this;
         this.shippingAddresses.forEach((item, i) => {
            if(item.is_default == 1){
               vm.shippingAddressId = item.id;
            }
         });
      } else {
         this.addShippingAddress = true;
         this.shippingAddress.allStates = this.data.states;
      }

      if( this.billingAddresses.length > 0 ){
         var vm = this;
         this.billingAddresses.forEach((item, i) => {
            if(item.is_default == 1){
               vm.billingAddressId = item.id;
            }
         });
      } else {
         this.addBillingAddress = true;
          this.billingAddress.allStates = this.data.states;
      }
    },
    mounted(){
      localStorage.setItem('voucher_code','0');
      this.callCheckout();
      if(this.checkLogin)
      {
         if(localStorage.getItem('billingAddressId') != null && localStorage.getItem('billingAddressId')!= 0)
         {
           this.setLocalStorage(localStorage.getItem('billingAddressId'), "billing");
           if(localStorage.getItem('shippingAddressId') != null && localStorage.getItem('shippingAddressId') != 0)
           {
               localStorage.setItem("shippingAddressId", localStorage.getItem('shippingAddressId'));
               this.shippingAddressId = localStorage.getItem('shippingAddressId');
           }
         }
         else if(localStorage.getItem('shippingAddressId') != null && localStorage.getItem('shippingAddressId') != 0)
         {
            this.setLocalStorage(localStorage.getItem('shippingAddressId'), "shipping");
         }
        if(localStorage.getItem('selectPaymentMethod') != null)
        {
            this.activePaymentMethod = localStorage.getItem('selectPaymentMethod');
        }
      }
      document.title = "Checkout";
    },
    methods: {
      setFinalPaymentMethod()
      {
         let self = this;
         setTimeout(function(){
            localStorage.setItem("selectPaymentMethod", self.activePaymentMethod);
         }, 200)
      },
      setLocalStorage(addressid, type, nextStep=true)
      {
         if(type=="billing")
         {
            localStorage.setItem("billingAddressId", addressid);
            this.billingAddressId = addressid;
            this.addShippingAddress = false;
            this.addBillingAddress = false;
            
            if(nextStep)
            {
               this.nextStep("payment");
            }
         }
         else if(type=="shipping")
         {
            localStorage.setItem("shippingAddressId", addressid);
            this.shippingAddressId = addressid;
            this.addShippingAddress = false;
            if(nextStep)
            {
               this.nextStep("billing");
            }
            this.$emit('callcart');
         }
      },
      step1(){
         this.$refs.shippingform.validate().then(success => {
            if (!success) {
               return;
            }
            this.callFBInitiantCheckout();
            let section = $('.shippingform');
            blockSection(section);
            if(this.shippingType == 'add'){
               this.$store.dispatch("globalStore/AddAddress", this.shippingAddress)
               .then((res) => {
                  if (res.response.status_code == 2070) {
                     this.shippingAddresses.push(res.response.data);
                     this.addShippingAddress = false;
                     this.shippingAddress = {...this.temp}
                     this.shippingType = "edit";
                     this.$toast.open({
                       message:  res.response.message,
                       type: "success",
                     })
                     this.setLocalStorage(res.response.data.id, "shipping", false);
                  } 
                  unblockSection(section);
               })
               .catch((err) => {
                  unblockSection(section);
                  this.$toast.open({
                    message: err,
                    type: "error",
                  });
               });
            } else {
               this.$store.dispatch("globalStore/EditAddress", this.shippingAddress)
               .then((res) => {
                  if (res.response.status_code == 2071) {
                     var index = this.shippingAddresses.findIndex(address => address.id === this.shippingAddress.id)
                     this.shippingAddresses[index] = this.shippingAddress;
                     this.addShippingAddress = false;
                     this.shippingAddress = {...this.temp}
                     this.$toast.open({
                       message:  res.response.message,
                       type: "success",
                     })
                     console.log(this.shippingAddress.id);
                     this.setLocalStorage(this.shippingAddress.id, "shipping", false);
                  }
                  unblockSection(section);
               })
               .catch((err) => {
                  unblockSection(section);
                  this.$toast.open({
                    message: err,
                    type: "error",
                  });
               });
            }
         });
      },
      step2(){
         this.$refs.billingform.validate().then(success => {
            if (!success) {
               return;
            }
            let section = $('.billingform');
            blockSection(section);
            if(this.billingType == 'add'){
               this.$store.dispatch("globalStore/AddAddress", this.billingAddress)
               .then((res) => {
                  if (res.response.status_code == 2070) {
                     
                     this.billingAddresses.push(res.response.data);
                     this.addBillingAddress = false;
                     this.billingAddress = {...this.temp}
                     this.billingType = "edit";
                     this.$toast.open({
                       message:  res.response.message,
                       type: "success",
                     })
                      this.setLocalStorage(res.response.data.id, "billing", false);
                  }
                  unblockSection(section);
               })
               .catch((err) => {
                  unblockSection(section);
                  this.$toast.open({
                    message: err,
                    type: "error",
                  });
               });
            } else {
               this.$store.dispatch("globalStore/EditAddress", this.billingAddress)
               .then((res) => {
                  if (res.response.status_code == 2071) {
                     var index = this.billingAddresses.findIndex(address => address.id === this.billingAddress.id)
                     this.billingAddresses[index] = this.billingAddress;
                     this.addBillingAddress = false;
                     this.billingAddress = {...this.temp}
                     this.$toast.open({
                       message:  res.response.message,
                       type: "success",
                     })
                     this.setLocalStorage(this.billingAddress.id, "billing", false);
                  }
                  unblockSection(section);
               })
               .catch((err) => {
                  unblockSection(section);
                  this.$toast.open({
                    message: err,
                    type: "error",
                  });
               });
            }
         });
      
      },
      editAddress(index, type){
         if(type == 'shipping'){
            this.tempAddress = {...this.shippingAddress};
            this.addShippingAddress = true;
            this.shippingAddress = {...this.shippingAddresses[index]};
            this.shippingType = 'edit';
         } else {
            this.tempAddress = {...this.billingAddress};
            this.addBillingAddress = true;
            this.billingAddress = {...this.billingAddresses[index]};
            this.billingType = 'edit';
         }
      },
      changeDefaultAddress(id, type){
         let payload = {
            id:id,
            status: (type == 'shipping') ? 0 : 1
         };
         let section = $('.continue');
         blockSection(section);
         this.$store.dispatch("globalStore/ChangeDefaultAddress", payload)
         .then((res) => {
            if (res.response.status_code == 2071) {
               if(type == 'shipping'){
                  var index = this.shippingAddresses.findIndex(address => address.id === id)
                  
                  this.shippingAddresses.forEach((value, index) => {
                      if(value.id == id){
                        this.shippingAddresses[index].is_default = 1;
                      } else {
                        this.shippingAddresses[index].is_default = 0;
                      }
                  });

                  this.shippingAddressId = id;
               } else {
                  var index = this.billingAddresses.findIndex(address => address.id === id)
                  this.billingAddressId = id;
               }
            }
            unblockSection(section);
         })
         .catch((err) => {
            this.$toast.open({
              message: err,
              type: "error",
            });
            unblockSection(section);
         });
      },
      nextStep(type){
         if(localStorage.getItem('shippingAddressId') == 'undefined')
         {
            type = "shipping";
         }
         else if(localStorage.getItem('billingAddressId') == 'undefined')
         {
            type = "billing";
         }

         if(type == 'shipping'){
            this.progressbarWidth = 15.50;
            $('.step1').attr('data-toggle', 'tab');
            this.$refs.step1.click();

         } else if(type == 'billing') {
            this.progressbarWidth = 50.50;
            $('.step2').attr('data-toggle', 'tab');
            this.$refs.step2.click();
         }
         else if(type == 'payment')
         {
            this.progressbarWidth = 70.50;
            $('.step4').attr('data-toggle', 'tab');
            this.$refs.step4.click();
         }
      }, 
      
      getStates(countryId, type){

          let section = $('#stateList');
          blockSection(section);
          this.$store.dispatch("globalStore/GetStates", countryId)
          .then((res) => {
              if (res.response.status_code == 2046) {
                  if(type == 'shipping'){
                     this.shippingAddress.allStates = res.response.data;
                  } else {
                     this.billingAddress.allStates = res.response.data;
                  }
                  unblockSection(section);
              }
          })
          .catch((err) => {
            unblockSection(section);
             this.$toast.open({
              message: err,
              type: "error",
            });
          });
      },
      setBillingAddress(){
         if(this.isSameAsBilling){
            this.tempAddress = {...this.billingAddress}
            var index = this.shippingAddresses.findIndex( address => address.id == this.shippingAddressId);

            this.billingAddress = {...this.shippingAddresses[index]}
           
            // edit address set billing address id else remove shipping id from billingaddress
            if(typeof this.tempAddress.id !== 'undefined'){
               this.billingAddress.id = this.tempAddress.id;
            } else {
               Vue.delete(this.billingAddress, 'id');
            }
            this.billingAddress.status = 1;
         } else {
            this.billingAddress = {...this.tempAddress}
         }
         if(typeof this.billingAddress.country !== 'undefined'){
            this.getStates(this.billingAddress.country_id, 'billing');
         }
      },
      callCheckout(){
          this.$store.dispatch("globalStore/countCheckout").then((res) => {
            if(res.response.status_code == 2067)
            {
               let data = res.response.data;
               let notEmptyBilling = false;
               let notEmptyShipping = false;
               if(data.shippingAddresses.hasOwnProperty('id'))
               {
                  this.shippingAddresses.push(data.shippingAddresses);
                  if(this.shippingAddresses.length == 1){
                     this.shippingAddressId = this.shippingAddresses[0].id;
                     localStorage.setItem('shippingAddressId', this.shippingAddressId);
                  }
                  this.shippingAddress = this.shippingAddresses[0];
                  this.shippingType = 'edit';
                  notEmptyShipping = true;
                  
               }
               if(data.billingAddresses.hasOwnProperty('id'))
               {
                  this.billingAddresses.push(data.billingAddresses);
                  if(this.billingAddresses.length == 1){
                     this.billingAddressId = this.billingAddresses[0].id;
                     localStorage.setItem('billingAddressId', this.billingAddressId);
                  }
                  this.billingAddress = this.billingAddresses[0];
                  this.billingType = 'edit';
                  notEmptyBilling = true;
               }
               if(notEmptyShipping)
               { 
                  // this.$store.commit('globalStore/removeShippingBillingStorage', "guestuser");
                  if(this.equal(this.shippingAddress,this.billingAddress, ['address','address_2','country_id','state_id','city_name','postal_code']))
                  {
                     this.isSameAsBilling = true
                  }
                  if(notEmptyBilling)
                  {
                     this.setLocalStorage(this.billingAddress.id,"billing"); 
                  }
                  else if(!notEmptyBilling)
                  {
                     this.setLocalStorage(this.shippingAddress.id,"shipping"); 
                  }
                  
               }
            }


          }).catch((err) => {});
      },
      equal(obj1, obj2, fieldsToCheckFor) {
        return fieldsToCheckFor.every((key) => obj1[key] == obj2[key]);
      },
      addNewShippingAddress(){
         this.shippingType = 'add';
         this.tempAddress = {...this.shippingAddress}
         this.addShippingAddress = true;
         this.shippingAddress.allStates = this.data.states;
         this.billingAddress.allStates = this.data.states;
      },
      cancelAddNewShippingAddress(loginstatus){
         this.shippingAddress = {...this.tempAddress}
         this.tempAddress = {};
         this.addShippingAddress = true;
         if(loginstatus)
         {
            this.addShippingAddress = false;
         }
         this.shippingType = 'add';
      },
      addNewBillingAddress(){
         this.billingType = 'add';
         this.tempAddress = {...this.billingAddress}
         this.addBillingAddress = true;
      },
      cancelAddNewBillingAddress(loginstatus){
         this.billingAddress = {...this.tempAddress}
         this.tempAddress = {};
         this.addBillingAddress = false;
         this.billingType = 'add';
      },
      callFBInitiantCheckout(){
         fbq('track', 'InitiateCheckout');
      },
      changeProgress(step){
         if(step == 1){
            $('.step2').removeAttr('data-toggle');
            this.progressbarWidth = 15.50;
         } else if(step == 2){
            if(localStorage.getItem('shippingAddressId') != null)
            {
               this.nextStep("billing");
            }
         } else if(step == 3){
            if(localStorage.getItem('billingAddressId') != null)
            {
               this.nextStep("payment");
            }
            
         }
      },
      decreaseQuantity(id){
         this.$refs.clearBtn.click();
         localStorage.setItem('voucher_code','0');
         let section = $('.cart-table');
         blockSection(section);
         this.$store.dispatch("globalStore/DecreaseQuantity", id)
         .then((res) => {
              if (res.response.status_code == 2063) {
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
                  //this.$store.commit('globalStore/decreaseQuantity', id);
                  this.$emit("callcart");
              } else if(res.response.status_code == 7004){
                  this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
              }
              unblockSection(section);
         })
         .catch((err) => {
            unblockSection(section);
            this.$toast.open({
              message: err,
              type: "error",
            });
         });
      },
      increaseQuantity(id){
         this.$refs.clearBtn.click();
         localStorage.setItem('voucher_code','0');
         let section = $('.cart-table');
         blockSection(section);
         this.$store.dispatch("globalStore/IncreaseQuantity", id)
         .then((res) => {
               if (res.response.status_code == 2063) {
                  this.$toast.open({
                    message:  res.response.message,
                    type: "success",
                  })
                  //this.$store.commit('globalStore/increaseQuantity', id);
                  this.$emit("callcart");
               } else if(res.response.status_code == 7004){
                  this.$toast.open({
                    message:  res.response.message,
                    type: "success",
                  })
               }
               unblockSection(section);
         })
         .catch((err) => {
            unblockSection(section);
            this.$toast.open({
              message: err,
              type: "error",
            })
         });
      },
      applyCuponCode(){
         let payload = {
            couponcode: this.couponCode,
            amount: this.cartTotal       
         };
         let section = $('.btn-apply-code');
         openLoader();
         blockSection(section);
         this.$store.dispatch("globalStore/ApplyCuponCode", payload)
         .then((res) => {
            let couponMassage = res.response.message;
               if (res.response.status_code == 3118) {
                  this.$toast.open({
                    message:  couponMassage,
                    type: "success",
                  })
                  localStorage.setItem('voucher_code',payload.couponcode);
                  this.couponMassage = {'status' : true , 'message':couponMassage};
                  this.$emit('callcart');
                  this.disabledcuponcodeinput = true;
                  $(".btn-apply-code").hide();
                  $(".coupon-message").show();
                  $(".btn-clear-code").removeClass("d-none");
               } 
               else if(res.response.status_code == 7009){
                  this.$toast.open({
                     message: couponMassage,
                     type: "error",
                  })
                  this.couponMassage = {'status' : false , 'message':couponMassage};
               }
               else if(res.response.status_code == 7010){
                  this.$toast.open({
                     message: couponMassage,
                     type: "error",
                  })
                  this.couponMassage = {'status' : false , 'message':couponMassage};
               }
               else if(res.response.status_code == 7011){
                  this.$toast.open({
                     message: couponMassage,
                     type: "error",
                  })
                  this.couponMassage = {'status' : false , 'message':couponMassage};
               }
               else if(res.response.status_code == 7012){
                  this.$toast.open({
                     message: couponMassage,
                     type: "error",
                  })
                  this.couponMassage = {'status' : false , 'message':couponMassage};
               }
               else if(res.response.status_code == 7008){
                  this.$toast.open({
                     message: couponMassage,
                     type: "error",
                  })
                  this.couponMassage = {'status' : false , 'message':couponMassage};
               }
               else{
                     unblockSection(section);
                     this.$toast.open({
                       message: couponMassage,
                       type: "error",
                     })
                  this.couponMassage = {'status' : false , 'message':couponMassage};
               }
               closeLoader();
               unblockSection(section);
         })
         .catch((err) => {
            unblockSection(section);
            this.$toast.open({
              message: err,
              type: "error",
            })
         });
      },

      clearCuponCode(){
        localStorage.setItem('voucher_code','0');
        let payload = {
            couponcode: this.couponCode
         };
         let section = $('.btn-clear-code');
         openLoader();
         blockSection(section);
         this.$store.dispatch("globalStore/ClearCuponCode", payload)
         .then((res) => {
               if (res.response.status_code == 3121) {
                 
                  this.$emit('callcart');
                  this.couponCode = '';
                  this.disabledcuponcodeinput = false;
                  $(".btn-apply-code").show();
                  $(".coupon-message").hide();
                  $(".btn-clear-code").addClass("d-none");
               } 
               else{
                     unblockSection(section);
                     this.$toast.open({
                    message: err,
                    type: "error",
                  })
               }
               closeLoader();
               unblockSection(section);
         })
         .catch((err) => {
            unblockSection(section);
            this.$toast.open({
              message: err,
              type: "error",
            })
         }); 
      },
      increaseDisable(isContinueSelling,max_order_limit,buy_quantity){
         if(isContinueSelling == 1){
            return false;
         }
         else if(max_order_limit <= buy_quantity){
            return true;
         }
         return false;
      },
      setAltImg(event){
         event.target.src = this.noImage;
      },
 
    },
    components: {
    }
  }
</script>
<style scoped>
   .input:focus {
  box-shadow:0 0 0 2px rgba(0,119,255,0.2) !important;
  outline: none !important;
  border-color: #e8eaed !important;
}
   input[type='checkbox'] + label:before, input[type='radio'] + label:before {
       border-color: #ddd;
       background-color: #ddd;
   }
   .view-details{
      font-size: 16px;
      color: #282828;
   }
   .qty-changer input[type='text']{
      width: 35px;
      max-width: 35px;
   }
 .table-border-first{
   border: 1px solid #e1dfdf;
 }
.table-bordered tbody  tr td{
   border-color: #e2e2e2;
   padding: 10px 10px;
}
.form-inline .btn:last-child {
    height: 37px;
}
</style>