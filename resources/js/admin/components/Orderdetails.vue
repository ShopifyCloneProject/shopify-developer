<template>

<div class="row mb-2">
    <div class="col-sm-12">
      <div class="collapse-icon">
          <div class="collapse-margin" id="accordionExample">
            <div class="card">
              <div
                class="card-header"
                id="headingOne"
                data-toggle="collapse"
                role="button"
                data-target="#collapseOne"
                aria-expanded="false"
                aria-controls="collapseOne"
              >
                <h3 class="lead collapse-title"> Order Details 
                    <span v-if="paymentdata.payment_status != null">({{ paymentdata.payment_status}})</span>
                    <span class="pointer" @click="OrderDetailsData()" v-if="paymentdata.payment_id != null" data-toggle="modal" data-backdrop="static" data-keyboard="false">({{ paymentdata.payment_id }})</span>
                </h3>
              </div>

              <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">Subtotal</div>
                        <div class="col-md-2">{{order.currency_symbol}} {{order.sub_total}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">Shipping cost</div>
                        <div class="col-md-2">{{order.currency_symbol}} {{order.shipping_cost}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">Taxes</div>
                        <div class="col-md-2">{{order.currency_symbol}} {{order.taxes}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">Discount amount <span v-if="order.discount_amount > 0">({{order.discount_code}})</span></div>
                        <div class="col-md-2">{{order.currency_symbol}} {{order.discount_amount}}</div>
                    </div>
                    <hr class="mb-1">
                    <div class="row">
                        <div class="col-md-10">Paid by customer</div>
                        <div class="col-md-2">{{order.currency_symbol}} {{paidbycustomer}}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">Total Costing</div>
                        <div class="col-md-2">{{order.currency_symbol}} {{totalcosting}}</div>
                    </div>
                    <hr class="mb-1">
                    <div class="row">
                        <div class="col-md-10">Total Profit</div>
                        <div class="col-md-2">{{order.currency_symbol}} {{profit}}</div>
                    </div>
                    <div class="row" v-if="refunds.length > 0">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                <h3 class="lead collapse-title">Refund Details ({{ financialstatus }})</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">Refund</th>
                                                <th class="text-center">Refund_id</th>
                                                <th class="text-center">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(refund, index) in refunds" :key="index">
                                                <td class="text-center">
                                                    <div>Refund : {{ index + 1 }}</div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="pointer" @click="displayRefundData(index)">({{ refund.refund_id }})</span>
                                                </td>
                                                <td class="text-center">
                                                    <div>
                                                       {{order.currency_symbol}} {{refund.amount}}
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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

       <!-- Refund Data Modal Start-->
    <div class="modal fade pr-0" id="OrderDetailsData" tabindex="-1" role="dialog" aria-labelledby="OrderDetailsTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="OrderDetailsTitle">Order Details Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="payment-body" v-if="paymentBody">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                  <a
                                    class="nav-link active"
                                    id="payment-tab"
                                    data-toggle="tab"
                                    aria-controls="payment"
                                    role="tab"
                                    href="#payment"
                                    aria-selected="true"
                                    ref="paymentTab"
                                    >Payment data</a
                                  >
                                </li>
                                <li class="nav-item">
                                  <a
                                    class="nav-link"
                                    id="current-payment-tab"
                                    data-toggle="tab"
                                    aria-controls="current-payment"
                                    role="tab"
                                    href="#current-payment"
                                    @click="emptyCurrentPaymentData()"
                                    aria-selected="false"
                                    >Current data</a
                                  >
                                </li>
                            </ul>
                        
                            <div class="tab-content">

                                <div class="tab-pane active" id="payment" aria-labelledby="payment-tab" role="tabpanel">
                                    <div class="table-responsive border mb-2">
                                        <table class="table">
                                            <tbody>
                                                <tr v-for="(value , index) in paymentdata.decode_data">
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
                                <div class="tab-pane" id="current-payment" aria-labelledby="current-payment-tab" role="tabpanel">
                                    <div class="mb-2 float-right"> 
                                        <button type="button" class="btn btn-primary" @click="currentPaymentData()">Refresh</button>
                                    </div>
                                    <div class="table-responsive border mb-2">
                                        <table class="table">
                                            <tbody>
                                                <tr v-for="(value , index) in curPaymentData">
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
                            <div>
                                <p>Create Date : {{paymentdata.created_at}}</p>
                                <p>Update Date : {{paymentdata.updated_at}}</p>
                                <p>Payment_id : {{paymentdata.payment_id}}</p>
                                <p>payment_status : {{paymentdata.payment_status}}</p>
                            </div>
                        </div>
                        <div class="refund-body" v-else>
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                  <a
                                    class="nav-link active"
                                    id="refund-tab"
                                    data-toggle="tab"
                                    href="#refund"
                                    aria-controls="refund"
                                    role="tab"
                                    aria-selected="true"
                                    >Refund data</a
                                  >
                                </li>
                                <li class="nav-item">
                                  <a
                                    class="nav-link"
                                    id="current-refund-tab"
                                    data-toggle="tab"
                                    href="#current"
                                    aria-controls="current"
                                    role="tab"
                                    @click="emptyCurrentRefundData()"
                                    aria-selected="false"
                                    >Current data</a
                                  >
                                </li>
                            </ul>
                    
                            <div class="tab-content">

                                <div class="tab-pane active" id="refund" aria-labelledby="refund-tab" role="tabpanel">
                                    <div class="table-responsive border mb-2" v-if="refundData.payment_method_id == 2">
                                        <table class="table">
                                            <tbody>
                                                <tr v-for="(value , index) in refundData.decode_data.head">
                                                    <td>
                                                       {{index}}
                                                    </td>
                                                    <td>
                                                       {{value}}
                                                    </td>
                                                </tr>
                                                <tr v-for="(value , index) in refundData.decode_data.body">
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
                                    <div class="table-responsive border mb-2" v-else>
                                        <table class="table">
                                            <tbody>
                                                <tr v-for="(value , index) in refundData.decode_data">
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
                                <div class="tab-pane" id="current" aria-labelledby="current-refund-tab" role="tabpanel">
                                    <div class="mb-2 float-right"> 
                                        <button type="button" class="btn btn-primary" @click="currentRefundData()">Refresh</button>
                                    </div>
                                    <div class="table-responsive border mb-2" v-if="refundData.payment_method_id == 2">
                                        <table class="table">
                                            <tbody>
                                                <tr v-for="(value , index) in refundData.decode_data.head">
                                                    <td>
                                                       {{index}}
                                                    </td>
                                                    <td>
                                                       {{value}}
                                                    </td>
                                                </tr>
                                                <tr v-for="(value , index) in refundData.decode_data.body">
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
                                    <div class="table-responsive border mb-2" v-else>
                                        <table class="table">
                                            <tbody>
                                                <tr v-for="(value , index) in refundData.decode_current_data">
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
                            <div>
                                <p>Date : {{refundData.created_at}}</p>
                                <p>Date : {{refundData.updated_at}}</p>
                                <p>Payment_id : {{refundData.payment_id}}</p>
                                <p>Refund_id : {{refundData.refund_id}}</p>
                                <p>refund_status : {{refundData.refund_status}}</p>
                                <p>Note : {{refundData.note}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
 <!-- Refund Data Modal end-->

     <!-- Payment Data Modal Start-->
  <!-- <div class="modal fade pr-0" id="payment-data" tabindex="-1" role="dialog" aria-labelledby="refundDataTitle" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="refundDataTitle">Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                        <div class="modal-body">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                  <a
                                    class="nav-link active"
                                    id="payment-tab"
                                    data-toggle="tab"
                                    aria-controls="payment"
                                    role="tab"
                                    aria-selected="true"
                                    ref="paymentTab"
                                    >Payment data</a
                                  >
                                </li>
                                <li class="nav-item">
                                  <a
                                    class="nav-link"
                                    id="current-payment-tab"
                                    data-toggle="tab"
                                    aria-controls="current-payment"
                                    role="tab"
                                    @click="currentPaymentData()"
                                    aria-selected="false"
                                    >Current data</a
                                  >
                                </li>
                            </ul>
                            
                            <div class="tab-content">

                                <div class="tab-pane active" id="payment" aria-labelledby="payment-tab" role="tabpanel">
                                    <div class="table-responsive border mb-2">
                                        <table class="table">
                                            <tbody>
                                                <tr v-for="(value , index) in paymentdata.decode_data">
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
                                <div class="tab-pane" id="current-payment" aria-labelledby="current-payment-tab" role="tabpanel">
                                    <div class="mb-2 float-right"> 
                                        <button type="button" class="btn btn-primary" @click="currentPaymentData()">Refresh</button>
                                    </div>
                                    <div class="table-responsive border mb-2">
                                        <table class="table">
                                            <tbody>
                                                <tr v-for="(value , index) in curPaymentData">
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
                            <div>
                                <p>payment_status : {{paymentdata.payment_status}}</p>
                            </div>
                        </div>

                  </div>
              </div>
</div> -->
 <!-- Payment Data Modal end-->
</div> 




</template>


<script>

import axios from 'axios'

  export default {
    props: ['financialstatus','order' , 'refunds','objselectionproducts' ,'paidbycustomer' , 'profit','totalcosting' , 'paymentid' , 'paymentdata'],
    name:'Orderdetails',
    data() {
      return {
          totalCost: 0,
         totalAmount: 0,
         totalQuantity: 0,
         refundData: [],
         curPaymentData: [],
         paymentBody: true,
      }
    },
     mounted(){

            this.calculateTotal();
            this.curPaymentData = this.paymentdata.decode_current_data;
            // if(this.paymentdata.decode_current_data.length > 0){
            //     this.handleDataResponse(this.paymentdata.decode_current_data);
            // }
            // if(this.refundData.length > 0){
            //     self.handleRefundDataResponse(this.refundData);
            // }
    },
    components:{
    },
    computed:{
    },
    methods: {
        OrderDetailsData(){
          this.paymentBody = true;
          $("#OrderDetailsData").modal({backdrop: 'static', keyboard: false});
           $('#payment-tab').click();
        },
        displayRefundData(index){
          this.paymentBody = false;
          this.refundData = this.refunds[index];
          $("#OrderDetailsData").modal({backdrop: 'static', keyboard: false});
         $('#refund-tab').click();

        },

        calculateTotal(){
            this.objselectionproducts.forEach((item, i) => {
               this.totalAmount += parseFloat(item.price * item.quantity);
               this.totalCost += parseFloat(item.costing_price * item.quantity);
               this.totalQuantity += parseInt(item.quantity);
           });
        },
        emptyCurrentRefundData(){
            if(this.refundData.decode_current_data == 0){
                this.currentRefundData();
            }
        },
        currentRefundData(){
                let section = $('#current-refund-tab');
                openLoader();
                axios
              .post(API_URL + "refund-current-status", {'main_refund_id' : this.refundData.id }, {})
              .then(response => {
                    if (response.data.response.status_code == 3126) {
                        this.refundData = response.data.response.data; 
                        successModal(response.data.response.message);
                    }
                    else
                    {
                        errorModal(response.data.message);
                    }
                    closeLoader();
                })
                .catch(err => {
                    errorModal(err.message);
                });

        },
        emptyCurrentPaymentData(){
            if(this.curPaymentData == 0){
                this.currentPaymentData();
            }
        },
        currentPaymentData(){
            let section = $('#current-payment-tab');
                openLoader();
                axios
              .post(API_URL + "current-status", {'main_payment_id' : this.paymentdata.id }, {})
              .then(response => {
                    if (response.data.response.status_code == 3125) {
                        // this.handleDataResponse(response.data.response.data);
                        this.curPaymentData = response.data.response.data; 
                        successModal(response.data.response.message);
                    }
                    else
                    {
                        errorModal(response.data.message);
                    }
                    closeLoader();
                })
                .catch(err => {
                    errorModal(err.message);
                });
        },
        // handleDataResponse(inputTrackArray){
        // let self = this;
        // if(inputTrackArray.length > 6){

        //           let perChunk = Math.round(inputTrackArray.length/2) // items per chunk
        //           self.curPaymentData = inputTrackArray.reduce((resultArray, item, index) => {
        //           let chunkIndex = Math.floor(index/perChunk)

        //           if(!resultArray[chunkIndex]) {
        //           resultArray[chunkIndex] = [] // start a new chunk
        //           }
        //           resultArray[chunkIndex].push(item)
        //           return resultArray
        //           }, [])

        //         }
        //         else{
        //           self.curPaymentData = [inputTrackArray,[]];
        //         }
              },
      }
  

</script>