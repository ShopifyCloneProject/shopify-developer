<template>
  <div>
      <div class="holder breadcrumbs-wrap mt-0">
         <div class="container">
            <ul class="breadcrumbs">
               <li><a href="/">{{ lang.global.home }}</a></li>
               <li><a href="/orders">{{ lang.global.profile.orderdata.your_orders }}</a></li>
               <li><span>{{ orderdata.order_nr }}</span></li>
            </ul>
         </div>
      </div>

      <div class="holder">
          <div class="container">
            <div class="row">
               <h1 class="mb-3 text-center">{{ lang.global.profile.orderdata.order_details }}</h1>
            </div>
            <div class="d-flex">
               <h5>{{ lang.global.profile.orderdata.order_on }} {{ data.order.paid_at }} | {{ lang.global.profile.orderdata.order_id }} #{{ data.order.order_nr }}</h5> 
               <div class="ml-auto" v-if="data.shipments.main_order_delivered">
                 <a class="manage-action pointer" style="color: #0000EE;" @click="downlodInvoice()" id="downlodInvoice">{{ lang.global.profile.orderdata.download_invoice }}</a>
               </div> 
            </div>
            <div class="row order-outline justify-content-between">
                <div class="col-md-4" id="shipping_address" v-if="data.shippingaddress">
                     <h5>{{ lang.global.profile.orderdata.shipping_address }}</h5>
                     <p>{{ data.shippingaddress.first_name }} {{ data.shippingaddress.last_name }} </p>
                     <p>{{ data.shippingaddress.address }} </p>
                     <p v-if="data.shippingaddress.address_2 != null">{{ data.shippingaddress.address_2 }} </p>
                     <p>{{ data.shippingaddress.city_name }}, {{ data.shippingaddress.Statename }}, {{ data.shippingaddress.Countryname }} </p>
                     <p>{{ data.shippingaddress.postal_code }}</p>
                     <p>{{ data.shippingaddress.email }}</p>
                </div>
                <div class="col-md-8 align-self-center">
                    <div class="row mb-1">
                        <div class="col-md-4 col-sm-12">
                            <div class="image">
                                <a :href="'/product/detail/'+productdata.slug" target="_new"><img :src="productdata.image_src[3]" @error="setAltImg" height="100" width="90"  v-if="typeof productdata.image_src != 'undefined'"></a>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 product-detail">
                          <a :href="'/product/detail/'+productdata.slug" target="_new"><h5>{{ productdata.title }}</h5></a>
                          <p>{{ lang.global.profile.orderdata.sku }} {{ productdata.sku }}</p>
                          <p>{{ lang.global.profile.orderdata.qunatity }} {{ productdata.quantity}}</p>
                          <p>{{ lang.global.profile.orderdata.price }} {{ productdata.symbol }} {{ productdata.price }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center" v-if="data.refund_status != 'refunded'">
                    <div class="row mb-1" v-if="data.shipments.trackStatus">
                         <a href="javascript:void(0)" class="btn btn--sm btn--grey" ref="trackBtn" @click="trackUrl()"><i class="icon-delivery-truck"></i><span>{{ lang.global.profile.orderdata.track }}</span></a>
                    </div>
                    <!-- <div class="row mb-1">
                         <a href="javascript:void(0)" class="btn btn--sm btn--grey"><i class="icon-checkout"></i><span>{{ lang.global.profile.orderdata.write_review }}</span></a>
                    </div>-->                    
                    <div class="row mb-1"  v-if="data.shipments.main_order_delivered == 1 && data.exchangeRequest == 0">
                         <a href="javascript:void(0)" class="btn btn--sm btn--grey" @click="ExchangeProduct()"><i class="icon-return"></i><span>{{ lang.global.profile.orderdata.exchange }}</span></a>
                    </div>
                    <div class="row mb-1" v-if="data.exchangeRequest">
                         <a href="javascript:void(0)"  @click="CancelExchangeProduct()" class="btn btn--sm btn--grey"><i class="icon-close-bold"></i><span>{{ lang.global.profile.orderdata.cancel_exchange }}</span></a>
                    </div>
                    <div class="row mb-1" v-if="displayReturnBtn">
                         <a href="javascript:void(0)" class="btn btn--sm btn--grey"  @click="ReturnOrder()"><i class="icon-arrow-left"></i><span>{{ lang.global.profile.orderdata.return }}</span></a>
                    </div>
                    <div class="row mb-1" v-if="displayCancelBtn">
                         <a href="javascript:void(0)" class="btn btn--sm btn--grey" data-toggle="modal" data-target="#cancelReturnproductModal"><i class="icon-close-bold"></i><span>{{ lang.global.cancel }} {{ lang.global.return }}</span></a>
                    </div>
                </div>
            </div>

            <div class="row orderdata-outline mt-3 align-items-center" v-if="displayStep">
                <div class="col-md-18 col-sm-12">
                    <div class="card-timeline px-2"> 
                     <vue-step-progress-indicator
                          :steps="progressSteps"
                          :active-step="currentActiveStep"
                          :is-reactive="false"
                          :styles="styleData"
                          :colors="colorData"
                          :show-bridge="true"
                          :show-label="true"
                        />
                    </div>
                </div>
                <div class="col-18 mt-2" v-if="objCheckTrackUrlResponse != null">
                    <div class="header">
                        <h5 class="title">{{ lang.global.profile.orderdata.shipping_details }}</h5>
                    </div>
                    <div class="body">
                        <div class="row">
                          <div class="col-18"  style="overflow-x:auto;">
                            <table class="table">
                              <thead class="thead-dark">
                                <tr>
                                  <th>{{ lang.global.profile.orderdata.date }}</th>
                                  <th>{{ lang.global.profile.orderdata.location }}</th>
                                  <th>{{ lang.global.profile.orderdata.status }}</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="(response, index) in objCheckTrackUrlResponse" :key="index">
                                  <th>{{ response.date }}</th>
                                  <td>{{ response.location }}</td>
                                  <td>{{ response.status }}</td>
                                </tr>
                              </tbody>
                            </table>
                           </div>
                        </div>   
                        <div class="row mt-2" v-if="objTrackUrl != null">
                          <div class="col-18">  
                            <div class="des-data d-flex">
                              <h5>{{ lang.global.profile.orderdata.track_url }}</h5>
                              <a :href="objTrackUrl" target="_blank"> {{ objTrackUrl }} </a>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal start -->
            <div class="modal fade cancelOrderModal" id="cancelReturnproductModal" tabindex="-1" role="dialog" aria-labelledby="cancelReturnOrderLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <ValidationObserver ref="cancelproductform" v-slot="{ handleSubmit, invalid, reset }">
                       <form class="loginform" @submit.prevent="handleSubmit(CancelReturnProduct())">
                            <div class="modal-header">
                              <h5 class="modal-title" id="cancelReturnOrderLabel">{{ lang.global.profile.orderdata.cancel_eturn_order_request }}</h5>
                            </div>
                        <div class="modal-body">
                            <div class="row my-2">
                                <div class="col-18 d-flex justify-content-between">
                                    <h5>{{ productdata.title }}</h5>
                                    <span class="minicart-qty">{{ productdata.quantity }}</span>
                               </div>
                               <div class="col-18">
                                  <label class="label" for="cancelRequestDescription">{{ lang.global.profile.orderdata.cancel_description }}</label>
                                  <div class="form-group green-border-focus">
                                      <ValidationProvider name="cancelRequestDescription"  rules="required" v-slot="{ errors }">
                                         <textarea class="form-control" id="cancelRequestDescription" rows="3" v-model="cancelDescription">
                                         </textarea>
                                         <span class="error text-danger">{{ errors[0] }}</span>
                                      </ValidationProvider>
                                  </div>
                               </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" id="resetaddressform" class="btn btn-secondary" @click="closeModal('cancelReturnproductModal')">{{ lang.global.cancel }}</button>
                          <button type="submit" class="btn btn-primary">{{ lang.global.save }}</button>
                        </div>
                      </form>
                    </ValidationObserver>
                  </div>
                </div>
            </div> 
            <!-- Modal end -->
         </div>
      </div>

      
  </div>
</template>
<script>
import { mapState } from 'vuex';
import alertMixin from './../../mixins/alertaction';
import VueStepProgressIndicator from "vue-step-progress-indicator";

  export default {
    name: "orderdata",
    mixins: [alertMixin],
    props: ['data'],
    data() {
      return {
            progressSteps:[
                              'Order Confirmed',
                              'Shipped',
                              'Out For Delivery',
                              'Delivered',
                          ],
         displayStep: false,
         cancelDescription:'',
         returnProduct:[],
         track_url:null,
         objCheckTrackUrlResponse:null,
         objTrackUrl:null,
         objReturnShippingTrackResponse:null,
         displayReturnBtn:false,
         currentActiveStep: 0,
         styleData: {
            progress__wrapper: {
               justifyContent: 'center',
             },
         },
         colorData: {
            progress__bubble: {
                active: {
                    color: "#fff",
                    backgroundColor: "#ffc107",
                    borderColor: "#ffc107",
                },
            },
            progress__label: {
                active: {
                    color: "#ffc107",
                },
            },
            progress__bridge: {
                active: {
                    backgroundColor: "ffc107",
                },
                
            },
         },
         orderdata: [],
         productdata:'',
         client_id: CLIENT_ID,
      }
    },
    mounted(){
      let self = this;
        self.data.shipments;
        self.productdata = self.data.orderproduct;
        self.objShippingTrackResponse = self.data.objResponse;
        self.objReturnShippingTrackResponse = self.data.objReturnResponse;
          if(Object.keys(self.data.order).length > 0)
          {
             self.orderdata = self.data.order;
          }
          if(self.data.objreturnorderproduct == null){

            self.returnProduct = self.data.objreturnorderproduct;
          }
          else if (Object.keys(self.data.objreturnorderproduct).length > 0) {
              
            self.returnProduct = self.data.objreturnorderproduct;
          }
          if(self.data.shipments.hasOwnProperty('trackStatus')){

            let objTrackStatus = self.data.shipments.trackStatus.description;
              if(objTrackStatus == "Shipped"){
                self.currentActiveStep = 1;
                self.displayStep = true;
              }
               else if(objTrackStatus == "Out For Delivery"){
                self.currentActiveStep = 2;
                self.displayStep = true;
              } 
              else if(objTrackStatus == "Delivered"){
                self.currentActiveStep = 3;
                self.displayStep = true;
              }
              else if(objTrackStatus == "Cancelled"){
                self.progressSteps[3] = "Cancelled";
                self.currentActiveStep = 3;
                self.displayStep = true;
              }
              else{
                self.currentActiveStep = 0;
                self.displayStep = false;
              }
          }
         
      self.displayBtnStatus();
     
    },
    computed: {
       ...mapState(['globalStore']),
      noImage(){
         return this.globalStore.no_image;
      },
      displayCancelBtn(){
            if(this.data.objreturnorderproduct != null){
                if(this.data.objreturnorderproduct.length > 0){
                    return true;
                }
            }
            return false;
      },
    },
    methods: {
        displayBtnStatus(){
            if(this.data.shipments.main_order_delivered == 1){
                if(this.data.objreturnorderproduct != null){
                    if(this.data.objreturnorderproduct.hasOwnProperty('quantity')){
                        if(this.returnProduct.quantity == this.productdata.quantity){
                            this.displayReturnBtn = false;
                            return;
                        }
                    }
                }

                    this.displayReturnBtn = true;
            }
            if(this.data.shipments.trackStatus)
            {
                this.$refs.trackBtn.click();
            }
        },
        closeModal(id){
            $("#" + id).modal('hide');
        },
    
      ReturnOrder() {
              window.location.href = this.data.returnorderurl;
      },
      ExchangeProduct() {
              window.location.href = this.data.exchangeorderurl;
      },
      CancelExchangeProduct() {
              window.location.href = this.data.cancelexchangeorderurl;
      },
      setAltImg(event){
         event.target.src = this.noImage;
      },
 
      CancelReturnProduct(){
         let section = $('.modal-dialog');
         blockSection(section);
         const  payload = {
               orderproductId:this.productdata.id,
               cancelDescription: this.cancelDescription
             };
         this.$store.dispatch("globalStore/CancelReturnProduct", payload)
          .then((res) => {
               if (res.response.status_code == 3058) {
                  this.$toast.open({
                     message: res.response.message,
                     type: 'success',
                  });
               }
               this.closeModal('cancelReturnproductModal');
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
      cancelExchangeOrder(){
        openLoader();
            this.$store.dispatch("globalStore/cancelExchangeOrder", this.formData.id)
            .then((res) => {
              if (res.response.status_code == 3100) {
                successModal(res.response.message);
                setTimeout(function(){
                  window.location = res.response.data;
                }, 2000);
              }
              else if (res.response.status_code == 7000) {
                errorModal(res.response.data.message);
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
      trackUrl(){
        openLoader();
        this.objCheckTrackUrlResponse = null;
         let id = this.data.shipments.shipmentId;
             
            this.$store.dispatch("globalStore/checkTrackUrl", id)
            .then((res) => {
              if (res.response.status_code == 3092) {
                let successResponse = res.response.data;
              this.objCheckTrackUrlResponse = successResponse.track_data;
              this.objTrackUrl = successResponse.track_url;
               this.$toast.open({
                      message: res.response.message,
                      type: 'success',
                  });
              }
              else if (res.response.status_code == 7000) {
                errorModal(res.response.data.message);
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
      downlodInvoice(){
         let section = $('#downlodInvoice');
         blockSection(section);
         let finalOrderId = this.orderdata.id;
         if(this.orderdata.parent_order_id != null)
         {
            finalOrderId = this.orderdata.parent_order_id;
         }
         this.$store.dispatch("globalStore/downlodInvoice", finalOrderId)
         .then((res) => {
              if (res.response.status_code == 3004) {
                 let fileName = res.response.data.file_name,
                 urlpath = res.response.data.url;
                  const link = document.createElement('a');
                  link.href = urlpath+fileName;
                  link.setAttribute('download', fileName);
                  document.body.appendChild(link);
                  link.click();
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
      }
    },
    components: { 
         VueStepProgressIndicator
    }
  }
</script>
<style lang="scss">
.track-data .row{
    padding:10px 0px !important;
    margin:0px !important;
}
.track-data .title h5{
    margin:0px !important;
}
    .track-data .row:last-child{
    border-bottom:none !important;
  }
  .track-heading .title h5{
    margin-bottom: 0px;
  }
  .trackUrl .row{
    margin : 0px;
  }
   .order-outline{
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 5px;
    #shipping_address{
      p{
         margin: 0;
      }
    }
    .product-detail{
      p{
         margin: 0;
      }
    }
   }
   .orderdata-outline{
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 5px;
    .product-detail{
      p{
         margin: 0;
      }
    }
   }
   .justify-center{
      justify-content: center;
   }
   .form-control{
      background-color:#fff;
      border:1px solid #e1dfdf;
      border-radius:5px;
   }
   .green-border-focus .form-control:focus {
            border: 1px solid #80bdff;
            border-radius:5px;
            background-color:transparent;
            box-shadow:0px 0px 0px 2px rgb(0,123,255,0.25);
            
   }

.hidden {
    display: none;
}
.open {
    display: block!important;
}
.modal-open {
  overflow: hidden;
}
.cancelOrderModal .minicart-qty{
          position: relative;
          color: #fff;
          border-color: #17c6aa;
          background-color: #17c6aa;
          padding: 13px;
          font-size: 14px;
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
  background-color: #00000063 !important;
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
  background-color: #00000063;
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