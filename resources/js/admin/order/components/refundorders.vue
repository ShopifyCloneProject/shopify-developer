<template>
  <div id="input-sizing">
    <!-- order Details Start -->
    <div v-if="order != 'undefined'">
        <Orderdetails :financialstatus="getFinancialStatus" :order="order" :refunds="data.objRefunds" :objselectionproducts="data.objSelectionProducts" :paidbycustomer="data.paidByCustomer" :totalcosting="data.totalCosting" :profit="data.profit" ></Orderdetails>            
    </div>
  <!-- order Details End -->
    <ValidationObserver ref="refundProductForm" v-slot="{ handleSubmit }">
      <form method="POST" id="frmShippingProduct" @submit.prevent="handleSubmit(submit())">
        <div class="row">
          <div class="col-md-12 col-12">
            <div class="card"> 
              <div class="card-header">
                <h4 class="card-title">{{ lang.cruds.order.refund_order_product }}</h4>
                <div class="prd-approved-switch">
                  <div class="custom-control custom-switch custom-switch-success">
                    <input type="checkbox" class="custom-control-input all-approve-switch" @click="allAprovedChangeTop()" id="allApproveSwitchTop" v-model="all_approve"/>
                    <label class="custom-control-label" for="allApproveSwitchTop">
                      <span class="switch-icon-left"><i data-feather="check"></i></span>
                      <span class="switch-icon-right"><i data-feather="x"></i></span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table">
                  <thead class="thead-dark">
                    <tr>
                      <th class="text-center">Image</th>
                      <th class="table-name-info">Name</th>
                      <th class="text-center">Quantity</th>
                      <th class="text-center">RefundQty</th>
                      <th class="text-center">Amount</th>
                    </tr>
                  </thead>
                  <tbody v-for="(product, index) in refundProducts" :key="index">
                    <tr>
                      <td class="text-center">
                        <div class="table-img">
                          <img  :src="product.img_src"  :alt="product.productName"  @error="setAltImg" >
                        </div>
                      </td>
                      <td class="table-name-info">
                        <div class="d-flex">
                          <div v-if="product.compareprice > 0"><strike >{{ product.compareprice }}</strike ></div>
                          <div class="ml-1"><h4>{{ product.price }}</h4></div>
                        </div class="mt-0">
                        <h2 class="cart-table-prd-name">
                          <a :href="'/product/detail/'+ product.slug">{{ product.title }}</a>
                          <span class="badge bg-success">{{ product.quantity }}</span>
                        </h2>
                        <div>
                          <span v-if="product.weight > 0">{{ product.weight }} {{ product.weight_type != null ? product.weight_type : 'gm'  }}</span>
                          <span v-if="product.length > 0">{{ product.length }}{{ product.dimension_length_type != null ? product.dimension_length_type : 'cm'}}</span>
                          <span v-if="product.width > 0">{{ product.width }}{{ product.dimension_width_type != null ? product.dimension_width_type : 'cm' }}</span>
                          <span v-if="product.height > 0">{{ product.height }}{{ product.dimension_height_type != null ? product.dimension_height_type : 'cm' }}</span>
                        </div>
                        <div class="cart-table-switch">
                          <div class="prd-sku">{{ product.sku }}</div>
                          <div class="prd-approved-switch">
                            <div class="custom-control custom-switch custom-switch-success">
                              <input type="checkbox" class="custom-control-input" :id="`approveSwitch` + index" @click="statusApprovedChange(index)" v-model="product.isApprove"/>
                              <label class="custom-control-label" :for="`approveSwitch` + index">
                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                              </label>
                            </div>
                          </div>
                          <span class="badge bg-success" v-if="product.requestReturnQuantity > 0">{{ product.requestReturnQuantity }}</span>
                        </div>
                      </td>
                      <td class="text-center">
                        <div v-if="product.stock_status">
                          <div class="d-flex">
                            <button class="decrease" type="button" @click="decreaseQuantity(index)" :disabled="product.decreaseDisable">-</button>
                            <span class="mx-1">{{ product.refundquantity }}</span>
                            <button class="increase" type="button" @click="increaseQuantity(index)" :disabled="product.increaseDisable">+</button>
                          </div>
                        </div>
                        <div v-else>Out of stock</div>
                      </td>
                      <td class="text-center">
                        <div>
                          <label class="control-label"> {{ product.currentRefundQuantity }} </label>
                        </div>
                      </td>
                      <td class="text-center">
                        <div>
                          <div v-if="product.displayText">
                            <input type="text" class="refund-input" @blur="setCurrentRefundamountToatal" v-model="product.refundAmount">
                          </div>
                          <div v-else>
                            <label class="control-label">{{ product.price }}</label>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="5">
                        <div class="cart-table-prd py-0">
                          <div class="collapse-default prd-description">
                            <div class="card">
                              <a
                              href="#"
                              :id="`headingCollapse`+ index"
                              class="card-header text-primary theme-bg-color justify-content-center text-light  font-weight-bold"
                              data-toggle="collapse"
                              role="button"
                              :data-target="`#collapse`+ index"
                              aria-expanded="false"
                              aria-controls="collapse"
                              >
                              Show Details
                              </a>
                              <div :id="`collapse`+ index" role="tabpanel" :aria-labelledby="`headingCollapse`+ index" class="collapse theme-back-bg-color">
                                <div class="card-body returnproductdescription border">
                                  <div class="row">
                                    <div class="col-5">
                                      <div class="title">
                                        <h4>User Description</h4>
                                      </div>
                                    </div>
                                    <div class="col-7">
                                      <div class="title">
                                        <h4>Admin Description</h4>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row my-1">
                                    <div class="col-5">
                                      <div class="des-heading">
                                        <div class="row">
                                          <div class="col-4">
                                            <h6 class='des-qty'>Quantity</h6>
                                          </div>
                                          <div class="col-4">
                                            <h6 class='des-description'>Description</h6>
                                          </div>
                                          <div class="col-4">
                                            <h6 class='des-date'>Date</h6>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-7">
                                      <div class="des-heading">
                                        <div class="row">
                                          <div class="col-3">
                                            <h6 class='des-qty'>Quantity</h6>
                                          </div>
                                          <div class="col-3">
                                            <h6 class='des-total'>Total</h6>
                                          </div>
                                          <div class="col-3">
                                            <h6 class='des-description'>Description</h6>
                                          </div>
                                          <div class="col-3">
                                            <h6 class='des-date'>Date</h6>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row" v-for="(description, index) in product.description" :key="index">
                                    <div class="col-5">
                                      <div class="des-data" v-if="description.hasOwnProperty('admin_approve')">
                                        <div class="row">
                                          <div class="col-4">
                                            <p class='des-qty'>{{description.quantity}}</p>
                                          </div>
                                          <div class="col-4">
                                            <p class='des-description'>{{description.description}}</p>
                                          </div>
                                          <div class="col-4">
                                            <p class='des-date'>{{description.created_at}}</p>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-7">
                                      <div class="des-data" v-if="!description.hasOwnProperty('admin_approve')">
                                        <div class="row">
                                          <div class="col-3">
                                            <p class='des-qty'>{{description.quantity}}</p>
                                          </div>
                                          <div class="col-3">
                                            <p class='des-total'>{{description.total}}</p>
                                          </div>
                                          <div class="col-3">
                                            <p class='des-description'>{{description.description}}</p>
                                          </div>
                                          <div class="col-3">
                                            <p class='des-date'>{{description.updated_at}}</p>
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
                      </td>
                    </tr>
                    <tr>
                      <td colspan="5">
                        <div class="pt-0 mt-1">
                          <div class="form-group green-border-focus"> 
                            <label class="label" :for="`productReturnDescription`+index">Description:</label>
                            <textarea class="form-control" :id="`productReturnDescription`+index" rows="3" v-model="product.approveOrderDescription">
                            </textarea>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-12 mt-2">
            <div class="prd-approved-switch float-right">
              <div class="custom-control custom-switch custom-switch-success">
                <input type="checkbox" class="custom-control-input all-approve-switch" @click="allAprovedChangeDown()" id="allApproveSwitchBottom" v-model="all_approve"/>
                <label class="custom-control-label" for="allApproveSwitchBottom">
                  <span class="switch-icon-left"><i data-feather="check"></i></span>
                  <span class="switch-icon-right"><i data-feather="x"></i></span>
                </label>
              </div>
            </div>
          </div>
          <div class="col-lg-12 col-xl-13 mt-3">
            <div class="returnOrderNote"> 
              <div class="form-group green-border-focus"> 
                <label class="label" for="returnDescription">Note :</label>
                <textarea class="form-control" id="returnDescription" rows="3" v-model="note"></textarea>
              </div>
            </div>
          </div>  
          <div class="col-lg-12 col-xl-13 mt-3 total-footer">
            <div class="row">
              <div class="col-6 align-items-center">
                <div class="table-responsive">
                  <table class="table table-bordered table-dark">
                    <thead>
                      <tr>
                        <th colspan="2" class="text-center">Quantity</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Old refund</td>
                        <td>{{oldrefundQuantity}}</td>
                      </tr>
                      <tr>
                        <td>Current refund</td>
                        <td>{{currentRefundQuantity}}</td>
                      </tr>
                      <tr>
                        <td>Total refund</td>
                        <td>{{totalRefundQuantity}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-6 align-items-center">
                <div class="table-responsive">
                  <table class="table table-bordered table-dark">
                    <thead>
                      <tr>
                        <th colspan="2" class="text-center">Refund Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Old amount</td>
                        <td>{{data.order.currency_symbol}} {{oldrefundAmount}}</td>
                      </tr>
                      <tr>
                        <td>Current amount</td>
                        <td>{{data.order.currency_symbol}} {{currentRefundAmount}}</td>
                      </tr>
                      <tr>
                        <td>Total amount</td>
                        <td>{{data.order.currency_symbol}} {{totalPrice}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group float-left" v-if="data.order.financial_status != 'refunded'">
            <button class="btn btn-primary waves-effect waves-light" type="submit" >
              {{ lang.global.refund }}
            </button>
            <button class="btn btn-danger waves-effect waves-light mr-1" type="button" @click="cancel()" >
              {{ lang.global.cancel }}
            </button>
          </div>
          <div>
            <button class="btn btn-info waves-effect waves-light" type="button" @click="goBack()" >
              {{ lang.global.back }}
            </button>
          </div>
        </div>
      </form>
    </ValidationObserver>
  </div>
</template>
<script>
  import { mapGetters, mapActions } from 'vuex';
  import draggable from 'vuedraggable';
  export default {
    props: ['list', 'data'],
    name:'refundOrders',
    data() {
      return {
        formData: {},
        note:'',
        oldrefundAmount:0,
        currentRefundAmount:0,
        currentRefundQuantity:0,
        totalRefundQuantity:0,
        totalPrice:0,
        oldrefundQuantity:0,
        oldrefundQuantity:0,
        all_approve:false,
        refundProducts:[],
        refundData:[],
        order: [],
      }
    },
    mounted(){
      this.data.objSelectionProducts;
      this.order = this.data.order;
      this.refundProducts = this.data.objSelectionProducts;
      this.refundProducts.forEach( function(element, index) {
        element.approveOrderDescription ='';
        let descriptionLenth = element.description.length;
         if(descriptionLenth > 0)
        {
          for(let i=descriptionLenth-1 ; i>=0 ;i--){

            if(typeof element.description[i].admin_approve == 'undefined'){
              element.approveOrderDescription = element.description[i].description;
              break;
            }
          }
        }
        element.currentRefundQuantity = 0;
        element.isApprove = false;
        element.increaseDisable = false;
        element.decreaseDisable = true;
        element.displayText = true;
        element.oldrefundQuantity = element.refundquantity;
        if(element.refundquantity == element.quantity){
          element.increaseDisable = true;
          element.decreaseDisable = true;
          element.displayText = false;
        }

      });
      this.calculateTotal();
      if(this.data.order.financial_status == 'refunded')
      {
        this.all_approve = true;
        this.refundProducts.forEach( function(element, index) {
          element.isApprove = true;
        });
      }
    },
    computed: {
      noImage(){
        return '/assets/images/no-image.jpg';
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
    },
    methods: {
       displayRefundData(index){
        this.refundData = this.data.objRefunds[index];
        $("#RefundData").modal({backdrop: 'static', keyboard: false});
      },
      goBack()
      {
        window.history.back();
      },
      checkAllStatus()
      {
          let finalswitchStatus = 0;
            this.refundProducts.forEach( function(element, index) {
                if(element.isApprove)
                {
                  finalswitchStatus++;
                }

            });
          if(finalswitchStatus == this.refundProducts.length)
          {
            this.all_approve = true;
          }
          else
          {
           this.all_approve = false; 
          }

      },
      statusApprovedChange(index){
        if($("#approveSwitch" + index).prop("checked")){
            this.refundProducts[index].isApprove = true;
        }
        else{
          this.refundProducts[index].isApprove = false;
        }
        this.checkAllStatus();
      },
      allAprovedChangeTop(){
        if($("#allApproveSwitchTop").prop("checked")){
              this.all_approve = true;
              this.refundProducts.forEach( function(element, index) {
                element.isApprove = true;
            });
        }
        else{
              this.all_approve = false;
              this.refundProducts.forEach( function(element, index) {
               element.isApprove = false;
            });
        }
      },
      allAprovedChangeDown()
      {
        if($("#allApproveSwitchBottom").prop("checked")){
              this.all_approve = true;
              this.refundProducts.forEach( function(element, index) {
                element.isApprove = true;
            });
        }
        else{
              this.all_approve = false;
              this.refundProducts.forEach( function(element, index) {
               element.isApprove = false;
            });
        }
      },
    statusIncreaseQty(index){
      let self = this
        Swal.fire({
        title: 'Are you sure?',
        text: "You want to increase return quantity!",
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
          self.finalIncreaseQuantity(index);
        }else if (result.dismiss === Swal.DismissReason.cancel) {
          } 
    });
    },
    increaseQuantity(index){
    if(this.refundProducts[index].refundquantity >= this.data.objSelectionProducts[index].requestReturnQuantity){
      this.statusIncreaseQty(index);
    }
    else {
      this.finalIncreaseQuantity(index);
    }


    },
    finalIncreaseQuantity(index){
    this.refundProducts[index].refundquantity++;
    this.refundProducts[index].currentRefundQuantity++;
    this.refundProducts[index].decreaseDisable = false;
    if((this.refundProducts[index].refundquantity + 1) > this.data.objSelectionProducts[index].quantity){
      this.refundProducts[index].increaseDisable = true;
      this.refundProducts[index].decreaseDisable = false;
    }

    this.setIndexWiseRefundAmount(index,this.refundProducts);
    },
    decreaseQuantity(index){
      this.refundProducts[index].refundquantity--;
      this.refundProducts[index].currentRefundQuantity--;
      this.refundProducts[index].increaseDisable = false;
      if(this.refundProducts[index].refundquantity >= this.data.objSelectionProducts[index].quantity){
        this.refundProducts[index].refundquantity++;
        this.refundProducts[index].currentRefundQuantity++;
        this.refundProducts[index].decreaseDisable = true;
        this.refundProducts[index].increaseDisable = false;
      }
      if(this.refundProducts[index].currentRefundQuantity == 0)
      {
        this.refundProducts[index].decreaseDisable = true;
      }

      this.setIndexWiseRefundAmount(index);
    },
    calculateTotal(){
      this.oldrefundQuantity = 0;
      this.oldrefundAmount = 0;
      this.totalRefundQuantity = 0;
      this.totalPrice = 0;
      this.refundProducts.forEach((item, index) => {
        this.refundProducts[index].refundAmount = (typeof item.refundAmount == 'undefined')?0:item.refundAmount;
        this.oldrefundQuantity += item.oldrefundQuantity;
        this.oldrefundAmount += item.refundtotal;
      });
      this.totalRefundQuantity =  parseFloat(this.oldrefundQuantity + this.currentRefundQuantity);
      this.totalPrice =  parseFloat(this.oldrefundAmount + this.currentRefundAmount);
    },
    setIndexWiseRefundAmount(index)
    {
       this.refundProducts[index].refundAmount = parseFloat(this.refundProducts[index].price * this.refundProducts[index].currentRefundQuantity);
       this.setCurrentRefundamountToatal();
       this.calculateTotal();
       
    },
    setCurrentRefundamountToatal()
    {
      this.currentRefundQuantity = 0;
      this.currentRefundAmount = 0;
      this.refundProducts.forEach((item, index) => {
        this.currentRefundAmount += parseFloat(item.refundAmount);
        this.currentRefundQuantity += item.currentRefundQuantity;
      });
      this.calculateTotal();
    },
    setAltImg(event){
      event.target.src = this.noImage;
    },
    submit(){
      this.$refs.refundProductForm.validate().then(success => {
        if (!success) {
          $("html, body").animate({ scrollTop: 50 }, 200);
          return;
        }
        let isApprove = false;
          for(let i = 0; i < this.refundProducts.length; i++){
            if(this.refundProducts[i].isApprove){
              isApprove = true;
              break;
            }

          }
        if(isApprove){
          openLoader();
          this.formData.order_id = this.data.order_id;
          this.formData.currentRefundAmount = this.currentRefundAmount;
          this.formData.refundProducts = this.refundProducts; 
          this.formData.note = this.note; 
            this.$store.dispatch("orderModule/RefundOrder", this.formData)
            .then((res) => {
              if (res.response.status_code == 3051) {
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
          }
          else
          {
             errorModal('Please click on approve product !!');
          }
      });
    },
    cancel(){
      location.reload();
    },
  }
}
</script>
<style lang="scss" scoped>
  .table-dark
  {
    background-color: #283046;
  }
  .refund-input{
    width: 100px;
    text-align: center;
    padding:5px 10px;
    border:1px solid #e1dfdf;
  }
  label{
    display:block;
    font-weight: 500;
  }
  .control-label{
    font-size: 1.25rem;
  }
  .opacity-0{
    opacity: 0;
  }
  hr{
    margin-bottom: 3rem;
  }
  img{
        max-width:100px;
        max-height:100px
    }
  .increase , .decrease{
    font-size: 14px;
    width: 30px;
    height: 26px;
    font-weight: 500;
    border: none;
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
 
  .cart-table-prd .green-border-focus,.returnOrderNote .green-border-focus{
    flex-basis:100% !important;
    label{
      font-size:14px;
      text-align:left;
    }
    .form-control{
      background-color:#fff;
      border:1px solid #e1dfdf;
    }

  }
  .returnproductdescription{
    .title{
      margin:15px 0px !important;
      text-align:center;
    }
  }
  
   .theme-bg-color{
    background-color: #283046;
   }
   .theme-back-bg-color{
    background-color: #f8f8f8;
   }
   .table td{
    border-top:0px;
   }


</style>