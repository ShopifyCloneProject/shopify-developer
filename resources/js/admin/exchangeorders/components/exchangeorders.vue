<template>
  <div>
  <div id="input-sizing" v-if="data.hasOwnProperty('orderId')">
      <form method="POST"  id="frmExchangeProduct">
        <div class="row">
          <div class="col-md-12 col-12">
            <div class="card"> 
              <div class="card-header">
                <h4 class="card-title">{{ lang.cruds.exchangeorders.exchange_product }}</h4>
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
                          <th class="text-center">ExchangeQty</th>
                          <th class="text-center">Amount</th>
                    </tr>
                  </thead>
                  <tbody v-for="(product, index) in exchangeProducts" :key="index">
                    <tr>
                      <td class="text-center">
                         <div class="table-img">
                            <img  :src="product.img_src"  :alt="product.productName"  @error="setAltImg" height="80" width="70">
                         </div>
                      </td>
                      <td class="table-name-info">
                       <div class="d-flex">
                          <div class="mr-1" v-if="product.compareprice > 0"><strike >{{ product.compareprice }}</strike ></div>
                          <div class="mt-0"><h4>{{ product.price }}</h4></div>
                       </div class="mt-0">
                           <h2 class="cart-table-prd-name">
                             <a href="'javascript:void(0)'">{{ product.title }}</a>
                             <a href="javascript:void(0)" title="Request Quantity"><span class="badge bg-success">{{ product.quantity }}</span></a>
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
                                <a href="javascript:void(0)" title="Approved Quantity">
                                  <span class="badge bg-success" v-if="product.exchangeApproveQuantity > 0">{{ product.exchangeApproveQuantity }}</span>
                                </a>
                              </div>
                      </td>
                       <td class="text-center">
                        <div  v-if="product.stock_status">
                            <div class="d-flex justify-content-center">
                                <button class="decrease" type="button" @click="decreaseQuantity(index)" :disabled="product.decreaseDisable">-</button>
                                <span class="qty-refund mx-1">{{ product.exchangeApproveQuantity }}</span>
                                <button class="increase" type="button" @click="increaseQuantity(index)" :disabled="product.increaseDisable">+</button>
                              </div>
                        </div>
                            <div class="text-danger" v-else>Out of stock</div>
                             
                        </td>
                       <td class="text-center">
                           <div>
                               <label class="control-label">  {{ product.exchangeQuantity }}</label>
                          </div>
                        </td>
                      <td class="text-center">
                        <div>
                          <div>
                            <input type="number" class="refund-input" v-model="product.exchangeAmount">
                          </div>
                       </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="5">
                         <div class="py-0">
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
                                <div class="card-body exchangeproductdescription border">
                                  <div class="row">
                                    <div class="col-12 border-bottom p-2">
                                       <div class="description-table d-flex">
                                          <div class="cart-table-prd-info text-left">{{ lang.cruds.exchangeorders.fields.description }}</div>
                                          <div class="cart-table-prd-qty">{{ lang.cruds.exchangeorders.fields.request_qty }}</div>
                                          <div class="cart-table-prd-qty">{{ lang.cruds.exchangeorders.fields.approve_qty }}</div>
                                          <div class="cart-table-prd-qty">{{ lang.cruds.exchangeorders.fields.date }}</div>
                                          <div class="cart-table-prd-qty">{{ lang.cruds.exchangeorders.fields.image }}</div>
                                       </div>
                                    </div>
                                    <div class="col-12 description-data">
                                       <div class="exchange-orderdescription border-bottom" v-for="(description, index) in product.description" :key="index">
                                          <div  class="my-2">
                                                <div class="d-flex">
                                                   <div class="cart-table-prd-info text-left d-flex">
                                                      <label>Request :</label>
                                                      <span class="cart-table-prd-info ml-1" v-if=" description.deleted_at != null"><strike >{{description.client_request}}</strike></span>
                                                      <span class="cart-table-prd-info ml-1" v-else>{{description.client_request}}</span>
                                                   </div>
                                                    <div class="cart-table-prd-qty">
                                                      <span>{{description.exchangeClientQuantity}}</span>
                                                   </div>
                                                   <div class="cart-table-prd-qty">
                                                      <span></span>
                                                   </div>
                                                   <div class="cart-table-prd-qty">
                                                      <span>{{description.created_at}}</span>
                                                   </div>
                                                   <div class="cart-table-prd-qty">
                                                      <button type="button" class="btn btn-primary" @click="openModal(description.img_src)">{{lang.global.show}}
                                                      </button>
                                                   </div>
                                                </div>
                                                <div class="d-flex" v-if="description.exchangeApproveQuantity > 0">
                                                   <div class="cart-table-prd-info text-left d-flex">
                                                      <label>Response :</label>
                                                      <span class="cart-table-prd-info ml-1">{{description.admin_response}}</span>
                                                   </div>
                                                   <div class="cart-table-prd-qty">
                                                      <span></span>
                                                   </div>
                                                   <div class="cart-table-prd-qty">
                                                      <span>{{description.exchangeApproveQuantity}}</span>
                                                   </div>
                                                   <div class="cart-table-prd-qty">
                                                      <span>{{description.updated_at}}</span>
                                                    </div>
                                                    <div class="cart-table-prd-qty">
                                                      <span></span>
                                                   </div>
                                                </div>
                                          </div>
                                         <!-- image modal start  -->
                                            <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Image Media</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <div class="row">
                                                      <div class="col-md-4" v-if="description_img_src.length > 0" v-for="(image , index) in description_img_src" :key="index">
                                                        <a :href="image.img_src" target="_new">
                                                          <img :src="image.img_src"  @error="setAltImg" height="200" >
                                                        </a>
                                                      </div>
                                                      <div v-else>
                                                        <h2>No Image Found</h2>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                      <!-- image modal end  -->
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
                         <div class="pt-0 mt-2">
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
          <div class="row">
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
            <div class="col-lg-12 col-xl-13 mt-2 total-footer">
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
                            <td>Total exchange Qty</td>
                            <td>{{totalExchangeQuantity}}</td>
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
                            <th colspan="2" class="text-center">Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>Total exchange amount</td>
                            <td>{{totalPrice}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                </div>
              </div>
            </div> 
          </div>
          <div class="form-group float-left">
            <button class="btn btn-primary waves-effect waves-light" type="button" @click="saveExchangeApprove()">
              {{ lang.global.exchange }}
            </button>
            <button class="btn btn-danger waves-effect waves-light" type="button" @click="cancel()" >
              {{ lang.global.cancel }}
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div v-else>
        <Datanotfound></Datanotfound>      
  </div>
</div>
</template>
<script>
  import { mapGetters, mapActions } from 'vuex';
  import draggable from 'vuedraggable';
  export default {
    props: ['list', 'data'],
    name:'exchangeorder',
    
    data() {
      return {
        formData: {},
        exchangeAmount:0,
        exchangeQuantity:0,
        totalExchangeQuantity:0,
        totalPrice:0,
        all_approve:false,
        exchangeProducts:[],
        description_img_src: [],
      }
    },
    mounted(){
      if(this.data.hasOwnProperty('orderId')){
        this.exchangeProducts = [...this.data.objSelectionProducts];
        this.exchangeProducts.forEach( function(element, index) {
          element.approveOrderDescription ='';
          let descriptionLenth = element.description.length;
          if(descriptionLenth > 0)
          {
            element.approveOrderDescription = element.description[descriptionLenth-1].admin_response;
          }
          element.exchangeQuantity = element.exchangeApproveQuantity;
          element.isApprove = false;
          element.increaseDisable = false;
          element.decreaseDisable = true;
          if(element.exchangeApproveQuantity == element.quantity){
            element.increaseDisable = true;
            element.decreaseDisable = true;
          }
          if(element.is_approve == 1){
            element.isApprove = true;
          }
        });
          
        this.calculateTotal();
        }
    },
    computed: {
      noImage(){
        return '/assets/images/no-image.jpg';
      },
    },
    created() {
    },
    methods: {
      openModal(description_img_src){
          this.description_img_src = description_img_src;
          $("#imageModal").modal('show');
      },
      checkAllStatus()
      {
        let finalswitchStatus = 0;
        this.exchangeProducts.forEach( function(element, index) {
          if(element.isApprove)
          {
            finalswitchStatus++;
          }

        });
        if(finalswitchStatus == this.exchangeProducts.length)
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
            this.exchangeProducts[index].isApprove = true;
          }
          else{
            this.exchangeProducts[index].isApprove = false;
          }
          this.checkAllStatus();
        },
      allAprovedChangeTop(){
        if($("#allApproveSwitchTop").prop("checked")){
        this.all_approve = true;
          this.exchangeProducts.forEach( function(element, index) {
            element.isApprove = true;
          });
        }
        else{
          this.all_approve = false;
          this.exchangeProducts.forEach( function(element, index) {
            element.isApprove = false;
          });
        }
      },
      allAprovedChangeDown()
      {
        if($("#allApproveSwitchBottom").prop("checked")){
          this.all_approve = true;
          this.exchangeProducts.forEach( function(element, index) {
            element.isApprove = true;
          });
        }
        else{
          this.all_approve = false;
          this.exchangeProducts.forEach( function(element, index) {
            element.isApprove = false;
          });
        }
      },
      increaseQuantity(index){
        this.exchangeProducts[index].exchangeQuantity++;
        this.exchangeProducts[index].exchangeApproveQuantity++;
        this.exchangeProducts[index].decreaseDisable = false;
        if((this.exchangeProducts[index].exchangeApproveQuantity + 1) > this.data.objSelectionProducts[index].quantity){
          this.exchangeProducts[index].increaseDisable = true;
          this.exchangeProducts[index].decreaseDisable = false;
        }


        this.calculateTotal();
      },
      decreaseQuantity(index){
        this.exchangeProducts[index].exchangeQuantity--;
        this.exchangeProducts[index].exchangeApproveQuantity--;
        this.exchangeProducts[index].increaseDisable = false;
        if(this.exchangeProducts[index].exchangeApproveQuantity >= this.data.objSelectionProducts[index].quantity){
          this.exchangeProducts[index].exchangeApproveQuantity++;
          this.exchangeProducts[index].exchangeQuantity++;
          this.exchangeProducts[index].decreaseDisable = true;
          this.exchangeProducts[index].increaseDisable = false;
        }
        if(this.exchangeProducts[index].exchangeApproveQuantity == 0)
        {
          this.exchangeProducts[index].decreaseDisable = true;
        }

        this.calculateTotal();
      },
       
      calculateTotal(){
        this.exchangeQuantity = 0;
        this.exchangeAmount = 0;
        this.totalExchangeQuantity = 0;
        this.totalPrice = 0;
        this.exchangeProducts.forEach((item, index) => {
          this.exchangeProducts[index].exchangeAmount = parseFloat(this.exchangeProducts[index].price * this.exchangeProducts[index].exchangeQuantity);
          this.exchangeQuantity += item.exchangeQuantity;
          this.exchangeAmount += this.exchangeProducts[index].exchangeAmount;
        });
        this.totalExchangeQuantity =  parseFloat(this.exchangeQuantity);
        this.totalPrice =  parseFloat(this.exchangeAmount);
      },
      setAltImg(event){
        event.target.src = this.noImage;
      },
      saveExchangeApprove(){
          let isApprove = false;
          for(let i = 0; i < this.exchangeProducts.length; i++){
            if(this.exchangeProducts[i].isApprove){
              isApprove = true;
              break;
            }
          }
          if(isApprove){
          this.formData.order_id = this.data.orderId;
          this.formData.exchangeAmount = this.exchangeAmount;
          this.formData.exchangeProducts = this.exchangeProducts; 

            openLoader();
            this.$store.dispatch("showexchangeordersModule/ExchangeOrder", this.formData)
            .then((res) => {
              if (res.response.status_code == 3099) {
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
          else{
             errorModal('Please click on approve product !!');
          }
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
   .theme-bg-color{
      background-color: #283046;
   }
   .theme-back-bg-color{
      background-color: #f8f8f8;
   }
    .table td{
    border-top:0px;
   }
  .refund-input{
    width: 100px;
    text-align: center;
    padding:5px 10px;
    border:1px solid #e1dfdf;
  }
  .cart-table-switch{
    margin-top:10px;
    display:flex;
    align-items:center;
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
  
  .increase , .decrease{
    font-size: 14px;
    width: 30px;
    height: 26px;
    font-weight: 500;
    border: none;
  }
  .cart-table-switch{
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
    flex-basis:50%;
  }
  .cart-table-prd-qty {
    flex-basis: 20%;
    text-align:center;
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
  .exchangeproductdescription{
    .cart-table-prd-info{
       flex-basis:70% !important;
    }
  }
    .exchange-orderdescription:last-child{
      border-bottom:none !important;
      margin-bottom:0px !important;
   }


</style>