<template>
  <div id="input-sizing">
      <form method="POST" enctype="multipart/form-data" @submit.prevent="submit()">
         <div class="row">
            <div class="col-md-8 col-12">
                  <!-- Basic details start -->
                  <div class="card">
                      <div class="card-body">
                           <h5 class="detail-title mb-1">{{fullName}}</h5>
                           <p v-if="getAddressLength">{{fullAddress}}</p>
                           <div class="form-group">
                              <label for="note">Customer Note</label>
                              <textarea  class="form-control" v-model="formData.note"></textarea> 
                           </div>
                           <button type="button" class="btn btn-primary" @click="addNote()" id="addNote" v-if="formData.note != customerBeforeEdit.note">Save</button>
                      </div>
                  </div>
                  <!-- Basic details end -->
                  <!-- Basic details start -->
                  <div class="card">
                     <div class="card-header">
                        <h5 class="detail-title mb-1">Last order placed</h5>
                     </div>
                      <div class="card-body">
                          <div class="order-placed mt-2 text-center">
                              <div>
                                  <p class="h4">This customer hasn’t placed any orders.</p>
                              </div>
                              <div>
                                  <button type="button" class="btn btn-primary">Create Order</button>
                              </div>
                          </div>
                      </div>
                  </div>
                  <!-- Basic details end -->
            </div>
            <div class="col-md-4 col-12">
               <div class="card">
                  <div class="card-body">
                     <div class="detail-header">
                        <h5 class="detail-title mb-1">Customer overview</h5>
                        <div class="ml-auto">
                          <a href="" class="manage-action" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editCustomer">Edit</a>
                        </div>
                     </div>
                     <div class="overview-deails">
                         <div class="contact-details">
                              <div class="mb-1"><a href="">{{formData.email}}</a></div>
                              <div v-if="formData.phone">
                                <span v-if="formData.phoneCode">+{{formData.phoneCode}}</span>
                                <span>{{formData.phone}}</span>
                              </div>
                         </div>
                     </div>
                     <hr />
                     <div class="detail-header">
                        <h5 class="detail-title mb-1">Default Address</h5>
                        <div class="ml-auto" v-if="getAddressLength">
                          <a href="" class="manage-action" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#manageAddress">Manage</a>
                        </div>
                      </div>
                      <div class="overview-deails">
                         <div class="defaul-address mb-2" v-if="getAddressLength">
                            <div><span>{{fullNameAddressUser}}</span></div>
                            <div><span>{{formData.defaultAddress.address1}}</span></div>
                            <div><span>{{formData.defaultAddress.address2}}</span></div>
                            <div><span>{{fullAddress}}</span></div>
                            <div v-if="formData.defaultAddress.pincode"><span>{{formData.defaultAddress.pincode}}</span></div>
                            <div v-if="formData.defaultAddress.phone">
                              <span v-if="formData.defaultAddress.phoneCode">+{{ formData.defaultAddress.phoneCode }}</span>
                              <span>{{formData.defaultAddress.phone}}</span>
                            </div>
                         </div>
                         <div class="defaul-address mb-2" v-else>
                            <p>Address not provided</p>
                         </div>
                         <a href="" class="new-address" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#addAddress">Add New Address</a>
                     </div>
                     <hr />
                      <div class="detail-header">
                        <h5 class="detail-title mb-1">Tax Setting</h5>
                        <div class="ml-auto">
                          <a href="" class="manage-action" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#manageTax">Change Status</a>
                        </div>
                      </div>
                      <div class="tax-deails">
                         <p v-if="formData.is_collect_tax" class="badge badge-pill badge-primary">No exemptions</p>
                         <p v-else class="badge badge-pill badge-warning">Taxes are not collected</p>
                     </div>
                  </div>
               </div><!-- card end -->
               <div class="card">
                  <div class="card-body">
                      <div class="detail-header">
                        <h5 class="detail-title mb-1">Email Marketing</h5>
                        <div class="ml-auto">
                          <a href="" class="manage-action" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#manageEmailStatus">Manage</a>
                        </div>
                      </div>
                      <div class="marketing-detail">
                          <div class="badge badge-pill badge-glow badge-success mb-1" v-if="formData.is_agree">Subscribed</div>
                          <div class="badge badge-pill badge-secondary mb-1" v-else>Not Subscribed</div>
                          <p>Subscribed 11:13 am.</p>
                      </div>
                  </div>
               </div><!-- card end -->
                <div class="card">
                  <div class="card-body">
                      <div class="detail-header">
                        <h5 class="detail-title mb-1">Tags</h5>
                        <div class="ml-auto">
                          <a href="" class="manage-action" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#viewAllTags">View All</a>
                        </div>
                      </div>
                      <div>
                          <input-tag v-model="formData.tags"></input-tag>
                      </div>
                  </div>
               </div><!-- card end -->
            </div>
          </div>
      </form>
      <!-- Modals start -->
      <!-- editCustomer Modals start -->
      <div class="modal fade" id="editCustomer" tabindex="-1" role="dialog" aria-labelledby="editCustomerTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editCustomerTitle">Edit customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-6">
                          <div class="form-group">
                              <label class="required" for="fname">First Name</label>
                              <input class="form-control" type="text" v-model="formData.firstName" id="fname">
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label for="lname">Last Name</label>
                               <input class="form-control" type="text" v-model="formData.lastName" id="lname">
                          </div>
                      </div>
                      <div class="col-12">
                          <div class="form-group">
                              <label class="required" for="email">Email</label>
                              <input class="form-control" type="email" v-model="formData.email" id="email">
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-10">
                          <div class="form-group">
                              <label for="phone">Phone number</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">+{{ formData.phoneCode }}</span>
                                    </div>
                                    <input class="form-control" type="text" v-model="formData.phone" id="phone">
                                </div>
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <label for="mobile"></label>
                              <select class="custom-select" v-model="formData.phoneCode">
                                <option value="" disabled="disabled">Country</option>
                                <option :value="ldata.phone_code" v-for="(ldata, phone_code) in list.countries">{{ ldata.name + '(+' + ldata.phone_code + ')' }}</option>
                              </select>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                          <div class="form-group">
                             <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_agree" v-model="formData.is_agree"/>
                                <label class="custom-control-label" for="is_agree">Customer agreed to receive marketing emails.</label>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" @click="hideModal()">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary" @click="changeCustomerDetails">Submit</button>
              </div>
            </div>
          </div>
      </div>
      <!-- editCustomer Modals End -->

      <!-- manageAddress Modals start -->
      <div class="modal fade" id="manageAddress" tabindex="-1" role="dialog" aria-labelledby="manageAddressTitle" aria-hidden="true" ref="manageAddress">
          <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="manageAddressTitle">Manage addresses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="detail-header">
                      <h5 class="detail-title mb-1">Default Address</h5>
                  </div>
                  <div class="overview-deails" v-for="(address, index) in addresses">
                       <div class="defaul-address mb-1">
                          <div><span>{{address.firstName + ' ' + address.lastName}}</span></div>
                          <div v-if="address.address1"><span>{{address.address1}}</span></div>
                          <div v-if="address.address2"><span>{{address.address2}}</span></div>
                          <div><span>{{ getFullAddress(address.city,  address.stateName, address.shortCode) }}</span></div>
                          <div v-if="address.pincode"><span>{{address.pincode}}</span></div>
                          <div v-if="address.phone">
                            <span v-if="address.phoneCode">+{{ address.phoneCode }}</span>
                            <span>{{address.phone}}</span>
                          </div>
                       </div>
                        <div class="d-flex">
                          <a href="" class="new-address" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#editAddress" @click="editAddress(index)">Edit Address</a>
                          <button class="btn btn-outline-secondary ml-auto" v-if="address.is_default != 1" @click="makeDefaultAddress(address.id)">Make Default</button>
                        </div>
                       <hr />
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" @click="hideModal()">{{ lang.global.cancel }}</button>
              </div>
            </div>
          </div>
      </div>
      <!-- manageAddress Modals End -->

      <!-- addAddress Modals start -->
      <div class="modal fade" id="addAddress" tabindex="-1" role="dialog" aria-labelledby="addAddressTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addAddressTitle">Add new address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 <div class="row">
                     <div class="col-6">
                          <div class="form-group">
                              <label class="required" for="fname">First Name</label>
                              <input class="form-control" type="text" v-model="formData.address.firstName" id="fname">
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label for="lname">Last Name</label>
                               <input class="form-control" type="text" v-model="formData.address.lastName" id="lname">
                          </div>
                      </div>
                      <div class="col-12">
                         <div class="form-group">
                            <label for="company">{{ lang.cruds.userStore.fields.company }}</label>
                            <input class="form-control" type="text" v-model="formData.address.company">
                         </div>
                          <div class="form-group">
                              <label class="required" for="address">{{ lang.cruds.address.fields.address }}</label>
                              <input class="form-control" type="text" v-model="formData.address.address1">
                          </div>
                          <div class="form-group">
                              <label for="address_2">{{ lang.cruds.address.fields.address_2 }}</label>
                              <input class="form-control" type="text" v-model="formData.address.address2">
                          </div>
                          <div class="form-group">
                              <label class="required" for="city_name">{{ lang.cruds.address.fields.city_name }}</label>
                              <input class="form-control" type="text" v-model="formData.address.city">
                          </div>
                      </div>
                  </div>
                  <div class="row">
                     <div class="col-md-4">
                          <div class="form-group">
                              <label for="country_id">{{ lang.cruds.address.fields.country }}</label>
                              <select class="custom-select" v-model="formData.address.country" @change="getState()">
                                <option value="">Country/Region</option>
                                <option :value="ldata.id" v-for="(ldata, index) in list.countries">{{ ldata.name }}</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-4" id="stateList">   
                          <div class="form-group">
                              <label class="required" for="state_id">{{ lang.cruds.address.fields.state }}</label>
                              <select class="custom-select" v-model="formData.address.state">
                                  <option :value="index" v-for="(ldata, index) in states">{{ ldata }}</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-group">
                              <label class="required" for="postal_code">{{ lang.cruds.address.fields.postal_code }}</label>
                              <input class="form-control" type="text" v-model="formData.address.pincode">
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-2">
                          <div class="form-group">
                              <label for="mobile">{{ lang.cruds.address.fields.mobile }}</label>
                              <select class="custom-select" v-model="formData.address.phoneCode">
                                <option :value="ldata.phone_code" v-for="(ldata, phone_code) in list.countries">{{ ldata.name + '(+' + ldata.phone_code + ')' }}</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-10">
                          <div class="form-group">
                              <label for="mobile"></label>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">+{{ formData.address.phoneCode }}</span>
                                </div>
                                <input class="form-control" type="number" v-model="formData.address.phone">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" @click="hideModal()">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary" @click="addCustomerAddress()">Submit</button>
              </div>
            </div>
          </div>
      </div>
      <!-- addAddress Modals End -->

      <!-- editAddress Modals start -->
      <div class="modal fade" id="editAddress" tabindex="-1" role="dialog" aria-labelledby="editAddressTitle" aria-hidden="true" ref="editAddress">
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editAddressTitle">Edit address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 <div class="row">
                     <div class="col-6">
                          <div class="form-group">
                              <label class="required" for="fname">First Name</label>
                              <input class="form-control" type="text" v-model="formData.address.firstName" id="fname">
                          </div>
                      </div>
                      <div class="col-6">
                          <div class="form-group">
                              <label for="lname">Last Name</label>
                               <input class="form-control" type="text" v-model="formData.address.lastName" id="lname">
                          </div>
                      </div>
                      <div class="col-12">
                         <div class="form-group">
                            <label for="company">{{ lang.cruds.userStore.fields.company }}</label>
                            <input class="form-control" type="text" v-model="formData.address.company">
                         </div>
                          <div class="form-group">
                              <label class="required" for="address">{{ lang.cruds.address.fields.address }}</label>
                              <input class="form-control" type="text" v-model="formData.address.address1">
                          </div>
                          <div class="form-group">
                              <label for="address_2">{{ lang.cruds.address.fields.address_2 }}</label>
                              <input class="form-control" type="text" v-model="formData.address.address2">
                          </div>
                          <div class="form-group">
                              <label class="required" for="city_name">{{ lang.cruds.address.fields.city_name }}</label>
                              <input class="form-control" type="text" v-model="formData.address.city">
                          </div>
                      </div>
                  </div>
                  <div class="row">
                     <div class="col-md-4">
                          <div class="form-group">
                              <label for="country_id">{{ lang.cruds.address.fields.country }}</label>
                              <select class="custom-select" v-model="formData.address.country" @change="getState()">
                                <option value="">Country/Region</option>
                                <option :value="ldata.id" v-for="(ldata, index) in list.countries">{{ ldata.name }}</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-4" id="stateList">   
                          <div class="form-group">
                              <label class="required" for="state_id">{{ lang.cruds.address.fields.state }}</label>
                              <select class="custom-select" v-model="formData.address.state">
                                  <option :value="index" v-for="(ldata, index) in states">{{ ldata }}</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-group">
                              <label class="required" for="postal_code">{{ lang.cruds.address.fields.postal_code }}</label>
                              <input class="form-control" type="text" v-model="formData.address.pincode">
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-2">
                          <div class="form-group">
                              <label for="mobile">{{ lang.cruds.address.fields.mobile }}</label>
                              <select class="custom-select" v-model="formData.address.phoneCode">
                                <option :value="ldata.phone_code" v-for="(ldata, phone_code) in list.countries">{{ ldata.name + '(+' + ldata.phone_code + ')' }}</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-10">
                          <div class="form-group">
                              <label for="mobile"></label>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">+{{ formData.address.phoneCode }}</span>
                                </div>
                                <input class="form-control" type="number" v-model="formData.address.phone">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" @click="hideModal()">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary" @click="editCustomerAddress()">Edit</button>
              </div>
            </div>
          </div>
      </div>
      <!-- editAddress Modals End -->

      <!-- manageTax Modals start -->
      <div class="modal fade" id="manageTax" tabindex="-1" role="dialog" aria-labelledby="manageTaxTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="manageTaxTitle">Edit tax exemption</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 <div class="row">
                     <div class="col-12">
                          <div class="form-group mt-2">
                             <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_collect_tax" v-model="formData.is_collect_tax"/>
                                <label class="custom-control-label" for="is_collect_tax">Collect tax</label>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" @click="hideModal()">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary" @click="changeTaxStatus()">Submit</button>
              </div>
            </div>
          </div>
      </div>
      <!-- manageTax Modals End -->

      <!-- manageEmailStatus Modals start -->
     <div class="modal fade" id="manageEmailStatus" tabindex="-1" role="dialog" aria-labelledby="manageEmailStatusTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="manageEmailStatusTitle">Edit email marketing status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                    <div class="col-12">
                        <div class="form-group mt-2">
                           <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input" id="is_agree" v-model="formData.is_agree"/>
                              <label class="custom-control-label" for="is_agree">Customer agreed to receive marketing emails.</label>
                            </div>
                        </div>
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" @click="hideModal()">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary"  @click="changeEmailSubscriptionStatus()">Submit</button>
              </div>
            </div>
          </div>
      </div>
      <!-- manageEmailStatus Modals End -->

      <!-- viewAllTags Modals start -->
      <div class="modal fade" id="viewAllTags" tabindex="-1" role="dialog" aria-labelledby="viewAllTagsTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="viewAllTagsTitle">Tags</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="hideModal()">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <div>
                      <h5>Applied Tags</h5>
                      <div class="demo-inline-spacing" v-if="formData.tags.length > 0">
                        <div class="badge badge-pill badge-primary" v-for="(tag, index) in formData.tags">{{tag}}</div>
                      </div>
                      <small v-else>Select previously used tags from the list below to add them to this customer.</small>
                  </div>
                  <hr />
                  <div>
                    <h5>ALL TAGS</h5>
                    <div class="demo-inline-spacing">
                      <div class="badge badge-secondary mt-1 mr-1" v-for="(tag, index) in list.tags">
                        <a :data-id="index" class="pointer">{{ tag }}</a>
                      </div>
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" @click="hideModal()">{{ lang.global.cancel }}</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Accept</button>
              </div>
            </div>
          </div>
      </div>
      <!-- viewAllTags Modals End -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
export default {
    props: ['list', 'type', 'data'],
    name:'EditCustomer',
    data() {
      return {
        states:[],
        formData:{
          firstName:'',
          lastName:'',
          email:'',
          phone:'',
          is_agree:false,
          is_collect_tax:false,
          note:'',
          tags:[],
          defaultAddress:'',
          phoneCode:'',
          address:{
              firstName:'',
              lastName:'',
              company:'',
              phone:'',
              address1:'',
              address2:'',
              phone:'',
              city:'',
              country:'',
              state:'',
              pincode:'',
              phoneCode:''
          }
        },
        addresses: [],
        customerBeforeEdit:{},
        beforeAddresses:[],
      }
    },
    mounted(){
      
    },
    components: {
     
    },
    computed: {
      fullName: function () {
        if(this.formData.lastName != '' && this.formData.lastName != null){
          return this.formData.firstName + ' ' + this.formData.lastName
        } else {
          return this.formData.firstName
        }
      },
      fullNameAddressUser: function () {
        return this.formData.defaultAddress.firstName + ' ' + this.formData.defaultAddress.lastName
      },
      fullAddress: function(){
        let defaultAddress = this.formData.defaultAddress;
        let city = defaultAddress.city;
        let state = defaultAddress.stateName;
        let shortCode = defaultAddress.shortCode;

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
      getAddressLength: function () {
        return this.addresses.length > 0 ? true : false;
      }
    },
    created() {
        this.setFormData();
    },
    methods: {
      setFormData(){
          let user = this.data.user;
          let addresses = this.data.addresses;
          this.formData.id =  user.id;
          this.formData.firstName =  user.name;
          this.formData.lastName =  user.last_name;
          this.formData.email =  user.email;
          this.formData.phone =  user.mobile;
          this.formData.is_agree =  user.accept_marketing == 1 ? true :  false;
          this.formData.is_collect_tax =  user.tax_exempt == 1 ? true : false;
          this.formData.note =  user.note;
          this.formData.tags =  (user.tags != '' && user.tags != null) ? user.tags.split(', ') : [];
          this.formData.phoneCode = user.phone_code;

          if(addresses.length > 0){
              this.formData.defaultAddress = addresses[0];
              this.addresses = addresses;
          }

          this.customerBeforeEdit = { ...this.formData };
          this.customerBeforeEdit.address = { ...this.formData.address };
      },
      changeCustomerDetails(){
          openLoader();
          let data = {
              id:this.formData.id,
              firstName:this.formData.firstName,
              lastName:this.formData.lastName,
              email:this.formData.email,
              phone:this.formData.phone,
              is_agree: this.formData.is_agree ? 1 : 0,
              phoneCode: this.formData.phoneCode
          };

          this.$store.dispatch("customerModule/EditCustomer", data)
          .then((res) => {
              if (res.response.status_code == 2048) {
                  closeLoader();
                  $('#editCustomer').modal('hide');
                  successModal(res.response.message);
                  this.customerBeforeEdit = { ...this.formData };
              }
              else
              {
                errorModal(res.response.message);
              }
          })
          .catch((err) => {
             closeLoader();
             errorModal(err.response.message);
          });
      },
      addCustomerAddress(){
          openLoader();
          let data = this.formData.address;
          data.uid = this.formData.id;
          this.$store.dispatch("customerModule/addCustomerAddress", data)
          .then((res) => {
              if (res.response.status_code == 2049) {
                  closeLoader();
                  $('#addAddress').modal('hide');
                  this.formData.address = { ...this.customerBeforeEdit.address }
                  this.addresses.push(res.response.data);
                  if( this.addresses.length == 1){
                    this.formData.defaultAddress = res.response.data;
                    this.customerBeforeEdit.defaultAddress = res.response.data;
                  }

                  successModal(res.response.message);
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
      editAddress(index){
        this.formData.address =  { ...this.addresses[index] };
        $('#manageAddress').modal('hide');
        if( this.formData.address.country != '' &&  this.formData.address.country != null ){
          //get state data of country
          this.getState(this.formData.address.country);
        }
      },
      editCustomerAddress(){
          openLoader();
          let data = this.formData.address;
          data.uid = this.formData.id;
          this.$store.dispatch("customerModule/editCustomerAddress", data)
          .then((res) => {
              if (res.response.status_code == 2050) {
                  closeLoader();
                  let index = 0;
                  $.each(this.addresses, function(key, value) {
                    if(value.id == data.id){
                      index = key;
                    } 
                  }); 
                  $('#editAddress').modal('hide');
                  this.addresses[index] = { ...data }; //set edited data to current address object
                  if(data.is_default == 1){
                    this.formData.defaultAddress = { ...data }; // set default address to new edited data
                  }

                  this.formData.address = { ...this.customerBeforeEdit.address } //reset address
                  successModal(res.response.message);
              }
              else
              {
                errorModal(res.response.message);
              }
          })
          .catch((err) => {
             closeLoader();
             errorModal(err.response.message);
          });
      },
      getState(){
          let section = $('#stateList');
          blockSection(section);
          let countryId = this.formData.address.country;
          this.$store.dispatch("customerModule/GetStates", countryId)
          .then((res) => {
              if (res.response.status_code == 2046) {
                  this.states = res.response.data;
                  unblockSection(section);
              }
          })
          .catch((err) => {
            unblockSection(section);
            errorModal(err.response.message);
          });
      },
      makeDefaultAddress(id){
         openLoader();
          this.$store.dispatch("customerModule/MakeDefaultAddress", id)
          .then((res) => {
              if (res.response.status_code == 2053) {
                  closeLoader();
                  successModal(res.response.message);
                  let defaulAddress = {};
                  $.each(this.addresses, function(key, value) {
                    if(value.id == id){
                      value.is_default = 1
                      defaulAddress = value;
                    } else {
                      value.is_default = 0;
                    }
                  }); 
                  this.formData.defaultAddress = defaulAddress; //set default

                  this.addresses =  this.addresses.slice().sort(function(a, b) {
                      return b.is_default - a.is_default;
                  });
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
      changeTaxStatus(){
          openLoader();
          let data = {
              id: this.formData.id,
              taxStatus: this.formData.is_collect_tax ? 1 : 0,
          };

          this.$store.dispatch("customerModule/ChangeTaxStatus", data)
          .then((res) => {
              if (res.response.status_code == 2051) {
                  closeLoader();
                  $('#manageTax').modal('hide');
                  successModal(res.response.message);
              }
              else
              {
                errorModal(res.response.message);
              }
          })
          .catch((err) => {
             closeLoader();
             errorModal(err.response.message);
          });
      },
      changeEmailSubscriptionStatus(){
          openLoader();
          let data = {
              id: this.formData.id,
              subscriptionStatus: this.formData.is_agree ? 1 : 0,
          };

          this.$store.dispatch("customerModule/ChangeEmailSubscriptionStatus", data)
          .then((res) => {
              if (res.response.status_code == 2052) {
                  closeLoader();
                  $('#manageEmailStatus').modal('hide');
                  successModal(res.response.message);
              }
              else
              {
                errorModal(res.response.message);
              }
          })
          .catch((err) => {
             closeLoader();
             errorModal(err.response.message);
          });
      },
      addNote(){
          let section = $('#addNote');
          blockSection(section);
          let data = {
              id: this.formData.id,
              note: this.formData.note,
          };

          this.$store.dispatch("customerModule/AddNote", data)
          .then((res) => {
              if (res.response.status_code == 2048) {
                  unblockSection(section);
                  this.customerBeforeEdit.note = this.formData.note;
                  successModal(res.response.message);
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
      hideModal(){
          this.formData = { ...this.customerBeforeEdit }
          this.formData.address = { ...this.customerBeforeEdit.address }
      },
      getFullAddress(city, state, shortCode){
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
      }
    }
  }
</script>

<style scoped>
  .defaul-address div{
    line-height: 1.75rem;
  }
  .detail-header {
    display: flex;
  }
  a.manage-action {
    font-size: 12px;
  }
</style>