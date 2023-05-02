<template>
  <div id="addressesSection">
     <h1 class="mb-3 text-center">{{ lang.global.profile.address.my_addresses }}</h1>
      <div class="row" v-if="this.shippingAddress.length > 0">
         <div class="col-sm-9  mt-2 mt-sm-0" v-for="(address,index) in shippingAddress" :key="index" :step="count = index + 1">
            <div class="card">
               <div class="card-body">
                  <h3>{{ lang.global.profile.address.address }} {{count}} <span v-if="address.is_default == 1">(Default)</span></h3>
                  <div>
                     <div v-if="address.address1">{{address.address1}}</div>
                     <div v-if="address.address2">{{address.address2}}</div>
                     <div v-if="address.city">{{address.city}}</div>
                     <span v-if="address.stateName">{{address.stateName + ' ,'}}</span>
                     <span v-if="address.shortCode">{{address.shortCode + ' ,'}}</span>
                     <span v-if="address.pincode">{{address.pincode}}</span>
                  </div>
                  <p>
                     <div class="font-weight-bold" v-if="address.email">
                         <span>{{address.email}}</span>
                     </div>
                     <div class="font-weight-bold" v-if="address.phone">
                         <span v-if="address.phoneCode">+{{ address.phoneCode }}</span>
                         <span>{{address.phone}}</span>
                     </div>
                  </p>
                  <div class="action d-flex mt-2 justify-content-between align-items-center">
                    <div class="clearfix">
                     <a class="link-icn float-left pointer" data-toggle="modal" data-target="#addressEditModal" @click="editAddress = shippingAddress[index];selectedIndex = index">
                        <i class="icon-pencil"></i>{{ lang.global.edit }}</a>
                     </a>
                  </div>
                  <div class="clearfix" v-if="address.is_default == 0">
                     <a class="link-icn float-right pointer" data-toggle="modal" data-target="#addressDeleteModal" @click="id = address.id">
                        <i class="icon-recycle"></i>{{ lang.global.delete }}
                     </a>
                  </div>
                  </div>
                  
               </div>
            </div>
         </div>
      </div>
      <div v-else>
         <p class="text-center p-2 h5">{{ lang.global.profile.address.address_not_found }}</p>
      </div>

      <div class="modal fade" id="addressEditModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
             <ValidationObserver ref="updateuserform" v-slot="{ handleSubmit, invalid, reset }">
               <form class="loginform" @submit.prevent="handleSubmit(EditAddress())" @reset.prevent="resetaddressform">
                    <div class="modal-header">
                      <h5 class="modal-title" id="addressModalLabel">{{ lang.global.profile.address.edit_address }}</h5>
                    </div>
                <div class="modal-body">
                    <div class="row my-2">
                       <div class="col-9 mt-1">
                          <label class="text-uppercase label">{{ lang.global.profile.address.address }}</label>
                          <div class="form-group">
                              <ValidationProvider name="Address1"  rules="required" v-slot="{ errors }">
                                 <input type="text" class="form-control input" v-model="editAddress.address1">
                                 <span class="error text-danger">{{ errors[0] }}</span>
                              </ValidationProvider>
                          </div>
                       </div>
                       <div class="col-9 mt-1">
                          <label class="text-uppercase label">{{ lang.global.profile.address.address_2 }}</label>
                          <div class="form-group">
                             <ValidationProvider name="Address2"  rules="required" v-slot="{ errors }">
                                 <input type="text" class="form-control input" v-model="editAddress.address2">
                                 <span class="error text-danger">{{ errors[0] }}</span>
                              </ValidationProvider>
                          </div>
                       </div>
                       <div class="col-9 mt-1">
                          <label class="text-uppercase label">{{ lang.global.profile.address.city }}</label>
                          <div class="form-group">
                            <ValidationProvider name="City"  rules="required" v-slot="{ errors }">
                             <input type="text" class="form-control  input" v-model="editAddress.city">
                             <span class="error text-danger">{{ errors[0] }}</span>
                              </ValidationProvider>
                          </div>
                       </div>
                       <div class="col-9 mt-1">
                          <label class="text-uppercase label">{{ lang.global.profile.address.pincode }}</label>
                          <div class="form-group">
                              <ValidationProvider name="Pincode"  rules="required" v-slot="{ errors }">
                                <input type="text" class="form-control input" v-model="editAddress.pincode">
                                <span class="error text-danger">{{ errors[0] }}</span>
                              </ValidationProvider>
                          </div>
                       </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" id="resetaddressform" class="btn btn-secondary" @click="closeModal('addressEditModal')">{{ lang.global.cancel }}</button>
                  <button type="submit" class="btn btn-primary">{{ lang.global.update }}</button>
                </div>
              </form>
            </ValidationObserver>
          </div>
        </div>
      </div>
      <div class="modal fade" id="addressDeleteModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addressModalLabel">{{ lang.global.profile.address.delete_address }}</h5>
            </div>
            <div class="modal-body">
               {{ lang.global.profile.address.are_you_sure_remove_adress }}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeModal('addressDeleteModal')">{{ lang.global.cancel }}</button>
              <button type="button" class="btn btn-primary" @click="removeAddress()">{{ lang.global.yes }}</button>
            </div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>

import { mapState } from 'vuex'

  export default {
    name: "addresssection",
    props: ['data'],
    data() {
      return {
        shippingAddress: [],
        editAddress:{
          address1:'',
          address2:'' ,
          city: '',
          pincode: '',
        },
        id:'',
        selectedIndex:''
      }
    },
    mounted(){
      if(this.shippingAddress.length == 0){
         this.getAddresses();
      }
    },
    methods: {
      closeModal(id){
        $("#" + id).modal('hide');
      },
      getAddresses(){
         let section = $('#addressesSection');
         blockSection(section);
         this.$store.dispatch("globalStore/GetAddresses")
         .then((res) => {
              if (res.response.status_code == 2093) {
                  this.shippingAddress = [...res.response.data];
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

      EditAddress(){
         let section = $('.modal-dialog');
         blockSection(section);
         this.$store.dispatch("globalStore/UpdateAddress", this.editAddress)
          .then((res) => {
               if (res.response.status_code == 2071) {
                  this.$toast.open({
                     message: res.response.message,
                     type: 'success',
                  });

                  this.shippingAddress[this.selectedIndex] = this.editAddress;

               }
               this.closeModal('addressEditModal');
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
      removeAddress(){
         let section = $('.modal-dialog');
         blockSection(section);
         this.$store.dispatch("globalStore/RemoveAddress", this.id)
          .then((res) => {
               if (res.response.status_code == 2095) {
                  this.$toast.open({
                     message: res.response.message,
                     type: 'success',
                  });

                  let index = this.shippingAddress.indexOf(this.id);
                  this.shippingAddress.splice(index, 1);
                  this.id = '';
               }
               this.closeModal('addressDeleteModal');
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
    },
  }
</script>
<style scoped>
.hidden {
    display: none;
}
.open {
    display: block!important;
}
.modal-open {
  overflow: hidden;
}
.modal {
  display: none;
  overflow: hidden;
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1050;
  -webkit-overflow-scrolling: touch;
  outline: 0;
  background-color: #00000063;
  padding-top: 100px;
}
.modal-dialog {
  position: relative;
  width: auto;
  margin: 10px;
}
.btn{
  padding: 10px 25px;
}
.modal-content {
  position: relative;
  background-color: #ffffff;
  border: 1px solid #999999;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 6px;
  -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  -webkit-background-clip: padding-box;
  background-clip: padding-box;
  outline: 0;
}
.modal-backdrop {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1040;
  background-color: #000000;
}
.modal-header {
  padding: 15px;
  border-bottom: 1px solid #e5e5e5;
  min-height: 16.42857143px;
}
.modal-title {
  margin: 0;
  line-height: 1.42857143;
}
.modal-body {
  position: relative;
  padding: 15px;
}
.modal-footer {
  padding: 15px;
  text-align: right;
  border-top: 1px solid #e5e5e5;
}
.close{
    float: right;
    font-size: 30px;
    font-weight: bold;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    position: absolute;
    right: 10px;
    top: 10px;
}
/* button.close {
  -webkit-appearance: none;
  padding: 0;
  cursor: pointer;
  background: transparent;
  border: 0;
} */
.close:hover, .close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
  filter: alpha(opacity=50);
  opacity: .5;
}
@media (min-width: 768px) {
  .modal-dialog {
    width: 700px;
    margin: 30px auto;
  }
  .modal-content {
    -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
  }
  .modal-sm {
    width: 300px;
  }
}
@media (min-width: 992px) {
  .modal-lg {
    width: 900px;
  }
}
[role="button"] {
  cursor: pointer;
}
.hide {
  display: none !important;
}
.show {
  display: block !important;
}
.invisible {
  visibility: hidden;
}
.text-hide {
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}
.hidden {
  display: none !important;
}
.affix {
  position: fixed;
}
</style>
