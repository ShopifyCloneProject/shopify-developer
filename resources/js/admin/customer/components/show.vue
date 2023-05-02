<template>
   <div id="input-sizing">
      <div class="row">
         <div class="col-12 customer-info">
            <div class="row">
               <div class="col-12">
                  <div class="detail-header mb-2">
                     <h4 class="detail-title">
                       {{ lang.cruds.customers.customer_information }}
                     </h4>
                  </div>
               </div>
               <div class="col-12">
                  <div class="card">
                      <div class="card-body">
                         <div class="row">
                            <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.name }}</h5>
                              <span>{{viewCustomer.name}} {{viewCustomer.last_name}}</span>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.mobile }}</h5>
                              <span>{{viewCustomer.mobile}}</span>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.email }}</h5>
                              <span>{{viewCustomer.email}}</span>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.gender }}</h5>
                              <span>{{gendertype[viewCustomer.gender]}}</span>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.email_notification_status }}</h5>
                              <span>{{list.emailStatus[viewCustomer.email_notification_status]}}</span>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.sms_notification_status }}</h5>
                              <span>{{list.smsStatus[viewCustomer.sms_notification_status]}}</span>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.blocked_status }}</h5>
                              <span>{{list.blocked[viewCustomer.blocked]}}</span>
                            </div>
                            <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.image }}</h5>
                              <img :src="viewCustomer.image" height="70" width="70"  id="displayImage">
                            </div>
                         </div>
                      </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-12 filter-orders">
            <div class="row">
               <div class="col-12">
                  <div class="detail-header mb-2">
                     <h4 class="detail-title">
                         {{ lang.cruds.customers.filter_orders }}
                     </h4>
                  </div>
               </div>

               <div class="col-12">
                  <div class="card">
                     <div class="card-body">
                        <ValidationObserver ref="FilterOrderYearForm" v-slot="{ handleSubmit }">
                           <form method="POST" enctype="multipart/form-data" id="frmFilterOrderYear" @submit.prevent="handleSubmit(submit())">
                              <div class="row">
                                 <div class="col-md-4 col-12">
                                    <div class="form-group">
                                       <label class="required" for="filterYear">{{ lang.cruds.customers.fields.filter_year }}</label>
                                       <select class="form-control" id="basicSelect" v-model="formData.selectFilterOrder">
                                         <option :value="Object.keys(items)[0]" v-for="(items, index) in filterOrdersYear">{{Object.keys(items)[0]}}</option>
                                       </select>
                                     </div>
                                 </div>
                                 <div class="col-md-4 col-12">
                                     <label class="required" for="order_no">{{ lang.cruds.customers.fields.order_no }}</label>
                                       <input
                                         type="text"
                                         class="form-control"
                                         placeholder="Search order no..."
                                         aria-label="Search order no..."
                                         aria-describedby="basic-addon-search2"
                                         v-model="formData.filterOrderNo"
                                       />
                                 </div>
                                 <div class="col-md-4 col-12">
                                     <label class="required" for="order_id">{{ lang.cruds.customers.fields.order_id }}</label>
                                       <input
                                         type="text"
                                         class="form-control"
                                         placeholder="Search order id..."
                                         aria-label="Search order id..."
                                         aria-describedby="basic-addon-search2"
                                         v-model="formData.filterOrderId"
                                       />
                                 </div>
                                 <div class="col-md-12 col-12">
                                    <div class="form-group float-left">
                                      <button class="btn btn-primary waves-effect waves-light" type="submit">
                                        {{ lang.global.search }}
                                      </button>
                                      <button class="btn btn-danger waves-effect waves-light" type="button" @click="cancel()" >
                                        {{ lang.global.clear }}
                                      </button>
                                    </div>
                                 </div>
                              </div>
                           </form>
                        </ValidationObserver>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-12 view-orders" v-if="this.viewCustomerOrder.length > 0">
            <div class="row">
               <div class="col-12">
                  <div class="detail-header mb-2">
                     <h4 class="detail-title">
                         {{ lang.cruds.customers.view_orders }}
                     </h4>
                  </div>
               </div>

               <div class="col-12">
                  <div class="card" v-for="(orders, index) in viewCustomerOrder" :key="index">
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.order_no }}</h5>
                              <a :href="'/admin/orders/'+ orders.id">{{orders.order_nr}}</a>
                           </div>
                           <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.order_id }}</h5>
                              <a :href="'/admin/orders/'+ orders.id">{{orders.id}}</a>
                              <!-- <span>{{orders.id}}</span> -->
                           </div>
                           <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.paid_at }}</h5>
                              <span>{{orders.paid_at}}</span>
                           </div>
                           <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.payment_method }}</h5>
                              <span>{{list.paymentMethod[orders.payment_method_id]}}</span>
                           </div>
                           <div class="col-md-4 col-12 mb-2">
                              <h5 class="detail-title">{{ lang.cruds.customers.fields.payment_type }}</h5>
                              <span>{{orders.financial_status}}</span>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12">
                              <div class="detail-header mt-2">
                                 <h4 class="detail-title">
                                    {{ lang.cruds.customers.payment_detail }}
                                 </h4>
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                       <div class="col-12">
                                          <div id="payment-card">
                                             <div class="card payment-card">
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price">{{ lang.cruds.customers.fields.sub_total }}</h6>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price"> {{orders.quantity}} {{ lang.cruds.customers.fields.items }}</h6>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price">{{globalsettings.CURRECNY_SYMBOL}} {{orders.sub_total}}</h6>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                              <div class="card payment-card">
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price">{{ lang.cruds.customers.fields.shipping_cost }}</h6>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price"></h6>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price">{{globalsettings.CURRECNY_SYMBOL}} {{orders.shipping_cost}}</h6>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="card payment-card">
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price">{{ lang.cruds.customers.fields.taxes }}</h6>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price"></h6>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price">{{globalsettings.CURRECNY_SYMBOL}} {{orders.taxes}}</h6>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="card payment-card">
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price">{{ lang.cruds.customers.fields.discount_amount }}</h6>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price"></h6>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price">{{globalsettings.CURRECNY_SYMBOL}} {{orders.discount_amount}}</h6>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="card payment-card pt-1 border-top">
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price">{{ lang.cruds.customers.fields.paid_by_customer }}</h6>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price"></h6>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="item-options">
                                                   <div class="item-wrapper">
                                                      <div class="item-cost">
                                                         <h6 class="item-price">{{globalsettings.CURRECNY_SYMBOL}} {{orders.total }}</h6>
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
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>
         

<script>
import moment from 'moment';
import { mapGetters, mapActions } from 'vuex'
export default {
    props: ['list', 'data','globalsettings'],
    name:'showcustomer',
    data() {
      return {
         
         formData : {
            selectFilterOrder:'All',
            filterOrderNo:'',
            filterOrderId:'',
         
         },
         viewCustomer:[],
         viewCustomerOrder:[],
         filterOrdersYear:[
                        {'All':'All'},
                        {'Last_Month':'Last Month'},
                        {'Last_3_Month':'Last 3 Month'},
                        {'Last_6_Month':'Last 6 Month'},
                        {'Last_Year':'Last Year'}
                     ],
         gendertype: {'M':'Male','F':'Female','T':'Transgender'}

         
      }
    },
    created(){
       this.setFilterOrdersYear();
    },
    mounted(){
      this.viewCustomer = this.data.user;
      this.viewCustomerOrder = this.data.customerOrder;
    },
    methods:{
        setFilterOrdersYear(){
         let year = moment().year();
         let lastYear = parseInt(year)-2020;
         for(let i=0;i<=lastYear;i++){
           this.filterOrdersYear.push({[year]: year});
           year -= 1;
         }
          
        },
        submit(){
              this.$refs.FilterOrderYearForm.validate().then(success => {
                if (!success) {
                  $("html, body").animate({ scrollTop: 50 }, 200);
                  return;
                }
                openLoader();
                  this.formData.user_id = this.data.user.id;
                  this.$store.dispatch("customerModule/GetFilterOrders", this.formData)
                  .then((res) => {
                    if (res.response.status_code == 3059) {
                      this.viewCustomerOrder = res.response.data;
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
              });
        },
        cancel(){
           location.reload();
        },
    }
}
</script>

<style scoped>
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
        margin-left:49%;
    }
    .total-amount{
        margin-left:16%;
    }
    .total-cost{
        margin-left:18%;
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
    .cart-table-prd-info {
      flex-basis: 50%;
  }
  .cart-table-prd-qty {
      flex-basis: 26%;
      text-align:center;
  }
  .cart-table-prd-cost {
      flex-basis: 25%;
      text-align:center;
  }
  .cart-table-prd-price-total {
      flex-basis: 25%;
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
