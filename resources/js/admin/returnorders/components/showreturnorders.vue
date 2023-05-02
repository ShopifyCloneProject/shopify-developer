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
                                  <span>{{order.mobile}}</span>
                               </div>
                               <div v-else>
                                  <span>No phone number</span>
                               </div>
                               <div class="prd-date mt-1"><h6>Order Created</h6>{{data.objReturnOrder.created_at}}</div>
                            </div>
                         </div>
                      </div>
                      <div class="col-3">
                         <div class="detail-header">
                            <h5 class="detail-title mb-1">SHIPPING ADDRESS</h5>
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
                             <div class="d-flex">
                               <h6>{{ lang.global.total_shipping }}  : </h6>
                               <span class="ml-1">{{ getTotalShipping }} Shipping</span>
                            </div>


                            <div class="shipping-links">
                               <div  v-for="(shipping, index) in data.shipping_link" :key="index">
                                  <a :href="shipping">{{ lang.global.shipping }}  : {{++index}}</a>
                               </div>
                            </div>  
                      </div>
                   </div> 
                </div>
            </div>
          </div>

          <div class="col-md-12 col-12">
            <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ lang.cruds.returnorders.return_product }}</h4>
      </div>
      <div class="table-responsive">
        <table class="table">
          <thead class="thead-dark">
            <tr>
               <th class="text-center">
                  <div class="check-input">
                     <input class="form-check-input" type="checkbox" id="shipAllProduct" @click="shippingAllProduct()" v-model="boolShipAllProduct"/>
                  </div>
               </th>
               <th class="text-center">Image</th>
               <th class="table-name-info">Name</th>
               <th class="text-center">Qty</th>
               <th class="text-center">Costing</th>
               <th class="text-center">Price</th>
               <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr  v-for="(product, index) in objSelectionReturnProducts" :key="index">
              <td class="text-center">
                  <div class="check-input">
                      <input class="form-check-input" type="checkbox" :id="`shipProduct` + index" @click="statusShippingProduct(index)" :disabled="product.isShipping" v-model="product.isChecked" />
                  </div>
               </td>
               <td>
                  <div class="table-img text-center">
                     <img  :src="product.img_src" :alt="product.productName"  @error="setAltImg" >
                  </div>
               </td>
              <td class="table-name-info">
               <div class="d-flex">
                  <div class="mr-1" v-if="product.compareprice > 0"><strike >{{ product.compareprice }}</strike ></div>
                  <div><h4 class="mb-0">{{ product.price }}</h4></div>
               </div class="mt-0">
                   <h2 class="cart-table-prd-name">{{ product.title }}</h2>
                  <div>
                    <span v-if="product.weight > 0">{{ product.weight }} {{ product.weight_type != null ? product.weight_type : 'gm'  }}</span>
                    <span v-if="product.length > 0">{{ product.length }}{{ product.dimension_length_type != null ? product.dimension_length_type : 'cm'}}</span>
                    <span v-if="product.width > 0">{{ product.width }}{{ product.dimension_width_type != null ? product.dimension_width_type : 'cm' }}</span>
                    <span v-if="product.height > 0">{{ product.height }}{{ product.dimension_height_type != null ? product.dimension_height_type : 'cm' }}</span>
                  </div>
                  <div>Cost price : {{globalsettings.CURRECNY_SYMBOL}}  {{ product.costing_price }}</div>
              </td>
              <td class="text-center">
                  <div v-if="product.stock_status">{{ product.quantity }}</div>
                  <div class="text-danger" v-else>Out of stock</div>
              </td>
               <td class="text-center">
                   <div>{{data.order.currency_symbol}} {{ product.costing_price * product.quantity }}</div>
                </td>
               <td class="text-center">
                   <div>
                        {{data.order.currency_symbol}}  {{ product.price * product.quantity }}
                  </div>
                </td>
              <td class="text-center">
                <div>
                  <div v-if="list.btn_return_delete_action_access && product.shipping_link == null">
                     <a data-tooltip="Remove Product" role="button"  @click="confirmDeleteBox('order product','removeReturnProduct',index)" >
                        <i class="fa fa-trash text-danger"></i>
                     </a>
                  </div>
                  <div v-if="list.btn_return_shipping_action_access && product.shipping_link != null">
                     <a  :href="product.shipping_link" role="button">
                        <i class="fa fa-truck text-danger"></i>
                     </a>
                  </div>
               </div>
              </td>
            </tr>
            <tr>
                <td colspan="3"><h4 class="total-title">Total : </h4></td>
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
          
          <div class="col-12">
            <button type="button" class="btn btn-danger mr-1" id="deleteRecordBtn" v-if="list.btn_return_delete_order_access" @click="deleteRecord()">Delete Return Order</button>
            <button type="button" class="btn btn-primary" id="shippingProductBtn" v-if="list.btn_return_shipping_order_access" @click="shippingReturnOrder()">Shipping Return  Order</button>
          </div>
       </div>

    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import globalmixin from './../../mixins/action';


export default {
    props: ['list', 'data','globalsettings'],
    mixins: [globalmixin],
    name:'showreturnorders',
    data() {
      return {
         objSelectionReturnProducts:[],
         objFinalSelectionProducts:[],
         totalCost:0,
         totalAmount:0,
         totalQuantity:0,
         boolShipAllProduct:false,
         changeCountry:false,
         states:[],
         order:{},
         client_id : CLIENT_ID,
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
         },
         note:''
      }
    },
    mounted(){
            this.order = this.data.order;
            this.customerInfo.email = this.data.order.email;
            this.customerInfo.phone = this.data.order.mobile;
            this.note = this.data.order.note;
            this.objSelectionReturnProducts = this.data.objSelectionProducts;
            this.calculateTotal();
    },
    components:{
     
    },
    computed:{
      getTotalShipping(){
         return this.data.shipping_link.length;
      },
      
      noImage(){
         return '/assets/images/no-image.jpg';
      },
    },
    created(){
        
    },
    methods: {
        calculateTotal(){
            this.objSelectionReturnProducts.forEach((item, i) => {
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
            let checkedCount = self.objSelectionReturnProducts.filter(function(value){ return value.isChecked == true });
              if(self.objSelectionReturnProducts.length == checkedCount.length){
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
              self.objSelectionReturnProducts.forEach( function(element, index) {
                  element.isChecked = true;
            });
        }
        else{
              self.objSelectionReturnProducts.forEach( function(element, index) {
               if(!element.isShipping){
                element.isChecked = false;
               }
            });
         }
        },200);
      },
      removeReturnProduct(index){
        let self = this;
        let id = self.objSelectionReturnProducts[index].id;
        openLoader();
            self.$store.dispatch("showreturnordersModule/removeReturnProducts", id)
            .then((res) => {
              if (res.response.status_code == 3094) {
                self.objSelectionReturnProducts.splice(index, 1);
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
         Swal.fire(deleteConfirmBox('return order')).then(function (result) {
            if (result.value) {
               let section = $('#deleteRecordBtn');
               blockSection(section);
               let id = self.data.returnOrderId;
               self.$store.dispatch("showreturnordersModule/DeleteReturnOrder", id)
               .then((res) => {
                  if (res.response.status_code == 3083) {
                     successModal(res.response.message);
                     setTimeout(function(){
                        window.location = res.response.data.url;
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
            }
         });
      },
       shippingReturnOrder(){
         let self = this;
          self.objSelectionReturnProducts.forEach( function(element, index) {
                  if(element.isChecked && !element.isShipping){
                     self.objFinalSelectionProducts.push(self.objSelectionReturnProducts[index])
                  }
            });
          if(self.objFinalSelectionProducts.length > 0)
          {
            let section = $('#shippingProductBtn');
            blockSection(section);
            let  formData = {
                  order_id: self.data.order.id,
                  user_id: self.order.user_id,
                  objFinalSelectionProducts: self.objFinalSelectionProducts
            };
            self.$store.dispatch("showreturnordersModule/ShippingReturnOrder", formData)
            .then((res) => {
               if (res.response.status_code == 3079) {
                  successModal(res.response.message);
                  setTimeout(function(){
                  window.location = res.response.data.url;
                  },2000);
               }
               else{
                errorModal(res.response.message);

              }
               unblockSection(section);
            })
            .catch((err) => {
               unblockSection(section);
               errorModal(err.response.message);
            });
          }
          else
          {
             errorModal("Please select atlease 1 products for shipping");
          }
         
      },
     
    }
  }
</script>

<style scoped>
  
.table td{
   padding: 10px 15px; 
}
.form-check-input{
     height: 18px;
   width: 18px;
   margin-top: 0px;
   margin-left: 0px;
}
.img-fluid.card-img-top{
    height: 50px !important;
    width: 50px !important;
}
.ecommerce-application .list-view .ecommerce-card {
    grid-template-columns: 0.3fr 2fr 1.0fr 0.7fr;
    display: inline-grid;
    margin-bottom: 0;
    border-bottom: 1px solid #ddd;
}
.payment-card{
    grid-template-columns: 1fr 2fr 0.7fr;
    display: inline-grid;
    margin-bottom: 0;
    width: 100%;
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
    .opacity-0{
        opacity: 0;
    }
    .total-quantity{
        text-align: center;
        margin-left: 45px
    }
    .total-cost{
      margin-left: 55px;
    }
    hr{
        margin-bottom: 3rem;
    }
    .quantity{
        font-size: 14px;
    }
    .increase , .decrease{
        font-size: 14px;
        width: 30px;
        height: 26px;
        font-weight: 500;
        border: none;
    }
    div.cart-table-prd{
        display:flex;
        border: 1px solid #e1dfdf;
        padding-top: 30px;
        padding-bottom: 30px;
        align-items:center;
        overflow:hidden;
    }
    .cart-table-prd-price .price-old {
        font-size: 16px;
        font-weight: 300;
        line-height: 1em;
        text-decoration: line-through;
    }
    .cart-table-prd-price .price-new {
        font-size: 20px;
        font-weight: 500;
        line-height: 1em;
        color: #6e6b7b;
    }
    .cart-table .cart-table-prd > :first-child {
        flex-basis: 12%;
        padding-left: 15px !important;
        padding-right: 15px;
        text-align:center;
    }
    .cart-table-prd-price>* {
        margin: 0 5px;
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
   .cart-table-prd-image{
      flex-basis: 10%;
      text-align: center;
   }
    .cart-table-prd-check{
      flex-basis: 5% !important;
      padding: 0px;
      align-items: center;
    }
   .cart-table-prd-check .form-check-input{
      height: 18px;
      width: 18px;
      margin: 0px;
   }
    .cart-table-prd-info {
      flex-basis: 60%;
  }
  .cart-table-prd-qty {
      flex-basis: 20%;
      text-align:center;
  }
  .cart-table-prd-cost {
      flex-basis: 20%;
      text-align:center;
  }
  .cart-table-prd-price-total {
      flex-basis: 20%;
      text-align:center;
  }

  .cart-table-prd-action {
      flex-basis: 10%;
      text-align:center;
      padding-right:15px;
  }
  .cart-table-prd-content-wrap {
    display: flex;
    align-items: center;
    flex: 1;
}
.cart-font{
    font-size: 15px;
    font-weight: 500;
}
</style>
