<template>
    <div id="input-sizing">
       <div class="row">
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
                                 <!-- <span v-if="formData.phoneCode">+{{formData.phoneCode}}</span> -->
                                 <span>{{order.mobile}}</span>
                              </div>
                              <div v-else>
                                 <span>No phone number</span>
                              </div>
                              <div class="prd-date mt-1"><h6>Order Created</h6>{{order.created_at}}</div>
                              <div class="row">
                                 <div class="prd-date mt-1 col">
                                    <h6>Order No.</h6>
                                    <span>{{order.order_nr}} ({{ order.financial_status }})</span>
                                    <span class="pointer" data-target="#PaymentData" v-if="data.objPayment.payment_id != null" data-toggle="modal" data-backdrop="static" data-keyboard="false">({{ data.objPayment.payment_id }})</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-3">
                        <div class="detail-header">
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
                     <div class="col-3">
                           <div class="detail-header">
                              <h5 class="detail-title mb-1">Billing ADDRESS</h5>
                              <div class="ml-auto">
                               <a href="" class="manage-action" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editAddress" @click="editAddress('billing')">Edit</a>
                           </div>
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
                           <div class="d-flex" v-if="getTotalShipping > 0">
                              <h6>{{ lang.global.total_shipping }}  : </h6>
                              <span class="ml-1">{{getTotalShipping}} Shipping</span>
                           </div>

                           <div class="shipping-links" v-if="getTotalShipping > 0">
                              <div  v-for="(shipping, index) in data.shipping_link" :key="index">
                                 <a :href="shipping">{{ lang.global.shipping }}  : {{++index}}</a>
                              </div>
                           </div> 
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

<div class="row" id="table-head">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ lang.cruds.abandonecheckouts.cart_product }}</h4>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead class="thead-dark">
            <tr>
                <th class="align-middle">
                  <div class="check-input">
                     <input class="form-check-input position-relative" type="checkbox" id="shipAllProduct" @click="shippingAllProduct()" v-model="boolShipAllProduct"/>
                  </div>
                </th>
              <th class="align-middle">Image</th>
              <th class="table-name-info">Name</th>
              <th class="text-center">Qty</th>
              <th class="text-center">Costing</th>
              <th class="text-center">Price</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(product, index) in objSelectionProducts" :key="index">
                <td class="text-center align-middle">
                   <div class="check-input">
                      <input class="form-check-input position-relative" type="checkbox" :id="`shipProduct` + index" @click="statusShippingProduct(index)" :disabled="product.isShipping" v-model="product.isChecked" />
                   </div>
                </td>
              <td class="align-middle">
                  <img  :src="product.img_src"  :alt="product.productName"  @error="setAltImg" >
              </td>
              <td class="table-name-info">
               <div class="d-flex">
                  <div v-if="product.compareprice > 0"><strike >{{ globalsettings.CURRECNY_SYMBOL }} {{ product.compareprice }}</strike ></div>
                  <div class="ml-1"><h4>{{ globalsettings.CURRECNY_SYMBOL }} {{ product.price }}</h4></div>
               </div class="mt-0">
                   <h2 class="cart-table-prd-name">
                     <a :href="'/product/detail/'+ product.slug">{{ product.title }}</a>
                  </h2>
                  <div>Cost price : {{globalsettings.CURRECNY_SYMBOL}}  {{ product.costing_price }}</div>
                  <div>
                    <span v-if="product.weight > 0">{{ product.weight }} {{ product.weight_type != null ? product.weight_type : 'gm'  }}</span>
                    <span v-if="product.length > 0">{{ product.length }}{{ product.dimension_length_type != null ? product.dimension_length_type : 'cm'}}</span>
                    <span v-if="product.width > 0">{{ product.width }}{{ product.dimension_width_type != null ? product.dimension_width_type : 'cm' }}</span>
                    <span v-if="product.height > 0">{{ product.height }}{{ product.dimension_height_type != null ? product.dimension_height_type : 'cm' }}</span>
                  </div>
              </td>
              <td class="text-center">
                  <div v-if="product.stock_status">{{ product.quantity }}</div>
                  <div v-else>Out of stock</div>
              </td>
               <td class="text-center">
                   {{data.order.currency_symbol}} {{ product.costing_price * product.quantity }}
                </td>
               <td class="text-center">
                        {{data.order.currency_symbol}} {{ product.price * product.quantity }}
                </td>
              <td class="text-center">
                <div>
                  <div v-if="list.btn_delete_action_access && product.shipping_link == null">
                     <a data-tooltip="Remove Product" role="button"  @click="confirmDeleteBox('order product','removeProduct')" >
                        <i class="fa fa-trash text-danger"></i>
                     </a>
                  </div>
                  <div v-if="list.btn_shipping_action_access && product.shipping_link != null">
                     <a :href="product.shipping_link" role="button">
                        <i class="fa fa-truck text-danger"></i>
                     </a>
                  </div>
               </div>
              </td>
            </tr>
            <tr>
                <td colspan="3"><h4 class="total-title">Total : {{getTotalItems}} Items</h4></td>
                <td class="text-center"><h5>{{ totalQuantity }}</h5></td>
                <td class="text-center"><h5>{{data.order.currency_symbol}} {{ totalCost }}</h5></td>
                <td class="text-center"><h5>{{data.order.currency_symbol}} {{ totalAmount }}</h5></td>
                <td></td>
            </tr>
          </tbody>
        </table>
      </div>
      
    </div>
  </div>
</div>  

<!-- order Details Start -->
    <div v-if="order != 'undefined'">
                   <Orderdetails :financialstatus="getFinancialStatus" :order="order" :refunds="data.objRefunds" :objselectionproducts="data.objSelectionProducts" :paidbycustomer="data.paidByCustomer" :totalcosting="data.totalCosting" :profit="data.profit" :paymentdata="data.objPayment" ></Orderdetails> 
    </div>
<!-- order Details End -->

 

<div class="row">
    <div class="col-12">
        <button type="button" class="btn btn-danger mr-1" id="deleteRecordBtn" v-if="list.btn_delete_order_access" @click="confirmDeleteBox('delete order','deleteOrder')">Delete Order</button>
        <button type="button" class="btn btn-primary mr-1" id="shippingProductBtn" v-if="list.btn_shipping_order_access" @click="shippingOrder()">Shipping Order</button>
        <button type="button" class="btn btn-primary mr-1" v-if="list.btn_download_invoice_access && order.parent_order_id == null" id="downloadInvoice" @click="downloadInvoice()">Download Invoice</button>
        <button type="button" class="btn btn-primary mr-1" v-if="list.btn_delete_invoice_access && order.parent_order_id == null" id="deleteInvoice" @click="confirmDeleteBox('delete order invoice','deleteInvoice')">Delete Invoice</button>
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

        <!-- Payment Data Modal Start-->
        <div class="modal fade pr-0" id="PaymentData" tabindex="-1" role="dialog" v-if="data.objPayment != null"aria-labelledby="paymentDataTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="paymentDataTitle">Payment Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr v-for="(value , index) in data.objPayment.decode_data">
                              <td>
                               {{index}}
                              </td>
                               <td>
                               {{value}}
                              </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
              </div>
          </div>
          </div>
        </div> 
<!-- Payment Data Modal End-->


      

    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import globalmixin from './../../mixins/action';


export default {
    props: ['list', 'type', 'data','globalsettings'],
    mixins: [globalmixin],
    name:'OrderSummary',
    data() {
      return {
         objSelectionProducts:[],
         objFinalSelectionProducts:[],
         totalCost:0,
         totalAmount:0,
         totalQuantity:0,
         boolShipAllProduct:false,
         changeCountry:false,
         states:[],
         order:{
            payment_method:[],
         },
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
         },
         note:''
      }
    },
    mounted(){
            this.order = this.data.order;
            this.customerInfo.email = this.data.order.email;
            this.customerInfo.phone = this.data.order.mobile;
            this.note = this.data.order.note;
            this.objSelectionProducts = this.data.objSelectionProducts;
            this.calculateTotal();

    },
    components:{
    },
    computed:{
      
      getTotalItems(){
         return this.data.objSelectionProducts.length;
      },
      getTotalShipping(){
         return this.data.shipping_link.length;
      },
      getFinancialStatus(){
         let fStatus = this.data.order.financial_status;
         if(fStatus == 'authorized'){
            return this.list.payment_status.paid;
         } else if(fStatus == 'expired'){
            return this.list.payment_status.expired;
         } else if(fStatus == 'paid'){
            return this.list.payment_status.paid;
         } else if(fStatus == 'partially_paid'){
            return this.list.payment_status.partially_paid;
         } else if(fStatus == 'partially_refunded'){
            return this.list.payment_status.partially_refunded;
         } else if(fStatus == 'pending'){
            return this.list.payment_status.pending;
         } else if(fStatus == 'refunded'){
            return this.list.payment_status.refunded;
         } else if(fStatus == 'unpaid'){
            return this.list.payment_status.unpaid;
         } else if(fStatus == 'voided'){
            return this.list.payment_status.voided;
         } else if(fStatus == 'failed'){
            return this.list.payment_status.failed;
         }else if(fStatus == 'exchanged'){
            return this.list.payment_status.exchanged;
         }
      },
      noImage(){
         return '/assets/images/no-image.jpg';
      },
    },
    created(){
      
    },
    methods: {
        calculateTotal(){
            this.objSelectionProducts.forEach((item, i) => {
               this.totalAmount += parseFloat(item.price * item.quantity);
               this.totalCost += parseFloat(item.costing_price * item.quantity);
               this.totalQuantity += parseInt(item.quantity);
           });
        },
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
     
       statusShippingProduct(index){
         let self = this;
         setTimeout(function(){
            let checkedCount = self.objSelectionProducts.filter(function(value){ return value.isChecked == true });
              if(self.objSelectionProducts.length == checkedCount.length){
               self.boolShipAllProduct = true;
              }
              else{
               self.boolShipAllProduct = false;
              }

         }, 200);
      },
      shippingAllProduct(){
         let self = this;
         setTimeout(function(){
            if(self.boolShipAllProduct){
              self.objSelectionProducts.forEach( function(element, index) {
                  element.isChecked = true;
            });
        }
        else{
              self.objSelectionProducts.forEach( function(element, index) {
               if(!element.isShipping){
                element.isChecked = false;
               }
            });
         }
        },200);
      },
      editAddress(type){
         if(type == 'shipping'){ 
            this.address = {...this.data.shipping_address};
         } 
         else if(type == 'billing'){
            this.address = {...this.data.billing_address};
         }
         this.getState();
      },
      getState(){
          let section = $('#stateList');
          blockSection(section);
          let countryId = this.address.country_id;
         this.$store.dispatch("orderModule/GetStates", countryId)
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
      
      updateCustomerAddress(){
         let section = $('#editAddressBtn');
         blockSection(section);
         this.$store.dispatch("orderModule/UpdateCustomerAddress", this.address)
         .then((res) => {
              if (res.response.status_code == 2071) {
                  $('#editAddress').modal('hide');
                  this.shippingAddress = {...this.address};
                  this.billingAddress = {...this.address};
                  successModal(res.response.message);
                  setTimeout(function(){
                      window.location = res.response.data;
                  },2000);
                  unblockSection(section);
                 
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
      removeProduct(index){
        let self = this;
        let id = self.objSelectionProducts[index].id;
        openLoader();
            self.$store.dispatch("orderModule/removeProducts", id)
            .then((res) => {
              if (res.response.status_code == 3034) {
                self.objSelectionProducts.splice(index, 1);
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
     
      deleteRecord(){
         let self = this;
         openLoader();
         let section = $('#deleteRecordBtn');
         blockSection(section);
         self.$store.dispatch("orderModule/DeleteOrder", self.order.id)
         .then((res) => {
            if (res.response.status_code == 2079) {
               unblockSection(section);
               closeLoader();
               successModal(res.response.message);
               setTimeout(function(){
                  window.location = res.response.data.url;
                },2000);
            }
            else{
               errorModal(res.response.message);
            }
         })
         .catch((err) => {
            unblockSection(section);
            closeLoader();
            errorModal(err.response.message);
         });
      },
       shippingOrder(){
         let self = this;
          self.objSelectionProducts.forEach( function(element, index) {
               if(element.isChecked && !element.isShipping){
                  self.objFinalSelectionProducts.push(self.objSelectionProducts[index])
               }
            });
          if(self.objFinalSelectionProducts.length > 0)
          {
            let section = $('#shippingProductBtn');
            openLoader();
            let  formData = {
                  order_id: self.data.order_id,
                  user_id: self.order.user_id,
                  objFinalSelectionProducts: self.objFinalSelectionProducts
            };
            self.$store.dispatch("orderModule/ShippingOrder", formData)
            .then((res) => {
               if (res.response.status_code == 3072) {
                 closeLoader();
                  successModal(res.response.message);
                  window.location = res.response.data.url;
               }
                else{
                     errorModal(res.response.message);
                  }
            })
            .catch((err) => {
               closeLoader();
               errorModal(err.response.message);
            });
          }
          else
          {
             errorModal("Please select atlease 1 products for shipping");
          }
      },

      downloadInvoice(){
         openLoader();
         let finalOrderId = this.order.id;
         if(this.order.parent_order_id != null)
         {
            finalOrderId = this.order.parent_order_id;
         }
         this.$store.dispatch("orderModule/downloadInvoice", finalOrderId)
         .then((res) => {
              if (res.response.status_code == 3004) {
                  successModal(res.response.message);
                 let fileName = res.response.data.file_name,
                 urlpath = res.response.data.url;
                  const link = document.createElement('a');
                  link.href = urlpath+fileName;
                  link.setAttribute('download', fileName);
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

      deleteInvoice(){
         openLoader();
         let finalOrderId = this.order.id;
         if(this.order.parent_order_id != null)
         {
            finalOrderId = this.order.parent_order_id;
         }
         this.$store.dispatch("orderModule/deleteInvoice", finalOrderId)
         .then((res) => {
              if (res.response.status_code == 3111 || res.response.status_code == 3112) {
                  closeLoader();
                  successModal(res.response.message);
                  setTimeout(function(){
                        window.location = res.response.data;
                      },2000);
              }
              else{
                     errorModal(res.response.message);
               }
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
.img-title{
   margin-left: 60px;
}
.table-img{
   margin-left: 50px;
}
.table thead th{
    border-bottom: 0px;
    border-top: 0px;
} 
.check-input .form-check-input{
   height: 18px;
   width: 18px;
   margin-left: 0px;
   margin-top: -9px;
}
.table-name-info{
   text-align: left;
   padding-left: 0px;
}
.ecommerce-application .list-view .ecommerce-card {
    grid-template-columns: 0.3fr 2fr 1.0fr 0.7fr;
    display: inline-grid;
    margin-bottom: 0;
    border-bottom: 1px solid #ddd;
}
.ecommerce-application .list-view .ecommerce-card .card-body{
    border-right: 0;
}
.defaul-address div{
    line-height: 1.75rem;
}
.detail-header {
    display: flex;
}
.total-amount{
   margin-left: -17px;
}
.total-quantity{
   margin-left: 25px;
}
.shipping-links{
   margin-top: 10px;
}
a.manage-action {
    font-size: 12px;
}
img{
        max-width:100px;
        max-height:100px
    }
label{
    display:block;
}
hr{
    margin-bottom: 3rem;
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
</style>
