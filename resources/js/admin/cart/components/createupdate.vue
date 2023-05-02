<template>
  <div id="input-sizing">
    <ValidationObserver ref="orderForm" v-slot="{ handleSubmit }">
      <form method="POST" enctype="multipart/form-data" id="frmAddEditOrder" @submit.prevent="handleSubmit(submit())">
        <div class="row">
          <div class="col-md-12 col-12">
            <div class="card"> 
              <div class="card-header">
                <h4 class="card-title">{{ lang.cruds.cart.create_order_product }}</h4>
              </div>
               <div class="card-body">
                <div class="table-responsive mb-3">
                   <table class="table">
                  <thead class="thead-dark">
                    <tr>
                          <th class="text-center">Image</th>
                          <th class="table-name-info">Name</th>
                          <th class="text-center">Quantity</th>
                          <th class="text-center">Price</th>
                          <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr  v-for="(product, index) in finalSelectionProduct" :key="index">
                      <td class="text-center">
                         <div class="table-img">
                            <img  :src="product.img_src"  :data-src="product.img_src" :alt="product.productName"  @error="setAltImg" height="80" width="70">
                         </div>
                      </td>
                      <td class="table-name-info">
                       <div class="d-flex">
                          <div class="mr-1" v-if="product.compareprice > 0"><strike >{{ product.compareprice }}</strike ></div>
                          <div class="mt-0"><h4>{{ product.price }}</h4></div>
                       </div class="mt-0">
                           <h2 class="cart-table-prd-name">
                             <a href="'javascript:void(0)'">{{ product.title }}</a>
                          </h2>
                      </td>
                       <td class="text-center">
                        <div  v-if="product.stock_status">
                            <div class="d-flex justify-content-center">
                                <button class="decrease" type="button" @click="decreaseQuantity(index)" :disabled="product.quantity == 0">-</button>
                                <input type="number" class="qty-input" disabled="disabled" :value="product.quantity">
                                <button class="increase" type="button" @click="increaseQuantity(index)" :disabled="increaseDisable(product.isContinueSelling,product.maxOrderLimit,product.quantity)">+</button>
                              </div>
                        </div>
                            <div class="text-danger" v-else>Out of stock</div>
                             
                        </td>
                       <td class="text-center">
                           <div>
                               <label class="control-label"> {{ (product.price * product.quantity).toFixed(2) }}</label>
                          </div>
                        </td>
                      <td class="text-center">
                        <div>
                          <div>
                             <a class="pointer btn" data-tooltip="Remove Product" role="button" @click="removeCartProduct(index)"><i class="fa fa-trash text-danger"></i></a>
                          </div>
                       </div>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2"><h4 class="total-title">Total : </h4></td>
                      <td class="text-center"><h5>{{ totalQuantity }}</h5></td>
                      <td class="text-center"><h5>{{ totalAmount }}</h5></td>
                    </tr>
                  </tbody>
                </table>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6 col-12">
                      <div class="form-group">
                        <label for="product_type_id">{{ lang.cruds.cart.fields.user }}</label>
                        <multiselect
                        v-model="selectUserId"
                        :options="objUserSelect"
                        :taggable="true"
                        tag-placeholder="Add this user"
                        placeholder="search or add user"
                        :custom-label="FilterUserName"
                        track-by="email"
                        :close-on-select="true"
                        :clear-on-select="true"
                        :loading="isUserLoading" 
                        @search-change="SearchUsers"
                        >
                      </multiselect>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4 col-12">
                    <div class="form-group">
                      <label for="product_type_id">{{ lang.cruds.cart.fields.product }}</label>
                      <multiselect
                      v-model="selectProductId"
                      :options="objProductsSelect"
                      :taggable="true"
                      tag-placeholder="Add this product"
                      placeholder="search or add product"
                      :custom-label="FilterTitleName"
                      track-by="title"
                      :close-on-select="true"
                      :clear-on-select="true"
                      :loading="isLoading" 
                      @search-change="SearchProducts"
                      >
                    </multiselect>
                  </div>
                 </div>
                <div class="col-md-2 col-12">
                  <ValidationProvider  name="quantity" rules="required" v-slot="{ errors }">
                    <div class="form-group">
                      <label class="required" for="quantity">{{ lang.cruds.cart.fields.quantity }}</label>
                      <input class="form-control" type="number" v-model="selectProductId.quantity">
                      <p class="text-danger">{{ errors[0] }}</p>
                    </div>
                  </ValidationProvider>
                </div>
                <div class="col-md-2 col-12">
                  <div class="form-group">
                    <label class="label-control">{{ lang.cruds.cart.fields.compare_price }}</label>
                    <label class="label-control"> {{ selectProductId.compareprice }} </label>
                  </div>
                </div>
                <div class="col-md-2 col-12">
                  <div class="form-group">
                    <label class="label-control">{{ lang.cruds.cart.fields.price }}</label>
                    <label class="label-control"> {{ selectProductId.price }} </label>
                  </div>
                </div>
                <div class="col-md-2 col-12">
                  <div class="form-group">
                    <label class="label-control">{{ lang.cruds.cart.fields.total_price }}</label>
                    <label class="label-control"> {{ selectProductId.price * selectProductId.quantity }} </label>
                  </div>
                </div>
              </div>
               <hr/>
              <div class="row">
                <div class="col-12">
                  <button class="btn btn-icon btn-primary" type="button" @click="finalSelectedProduct()">
                    <i data-feather="plus" class="mr-25"></i>
                    <span>{{ lang.global.add }} {{ lang.global.new }}</span>
                  </button>
                </div>
              </div>
               
              </div>
          </div>

        <div class="row">
          <div class="col-md-6 col-12">
            <div class="card"> 
              <div class="card-header">
                <h4 class="card-title">{{ lang.cruds.cart.shipping_address }}</h4>
              </div>
              <div class="card-body">
                <div class="row mt-2">
                  <div class="col-6">
                    <ValidationProvider name="first_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="first_name">{{ lang.cruds.cart.fields.first_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter first_name" v-model="shippingAddress.first_name" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="last_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="last_name">{{ lang.cruds.cart.fields.last_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter last_name" v-model="shippingAddress.last_name" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="email" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="email">{{ lang.cruds.cart.fields.email }}</label>
                        <input class="form-control" type="email" placeholder="Enter email" v-model="shippingAddress.email" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="mobile" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="mobile">{{ lang.cruds.cart.fields.mobile }}</label>
                        <input class="form-control" type="number" placeholder="Enter mobile number" v-model="shippingAddress.mobile" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="address" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="address">{{ lang.cruds.cart.fields.address }}</label>
                        <input class="form-control" type="text" placeholder="Enter address" v-model="shippingAddress.address" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="address_2" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="address_2">{{ lang.cruds.cart.fields.address_2 }}</label>
                        <input class="form-control" type="text" placeholder="Enter address 2 " v-model="shippingAddress.address_2" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="shippingCountry"  rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="shippingCountry">{{ lang.cruds.cart.fields.country }}</label>
                        <select class="custom-select" name="shippingCountry" id="shippingCountry" @change="changeCountryDisplay(1);sameAsShipping()" v-model="shippingAddress.country_id">
                          <option :value="id" v-for="(item , id) in list.countries" v-bind:key="id">{{ item }}</option>
                        </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider  name="shippingState" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="shippingState">{{ lang.cruds.cart.fields.state }}</label>
                        <select class="custom-select"  name="shippingState" id="shippingState" v-model="shippingAddress.state_id" @change="sameAsShipping()" >
                          <option :value="index" v-for="(item, index) in shippingStates">{{ item }}</option>
                        </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="city_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="city_name">{{ lang.cruds.cart.fields.city_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter city name" v-model="shippingAddress.city_name" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="postal_code" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="postal_code">{{ lang.cruds.cart.fields.postal_code }}</label>
                        <input class="form-control" type="text" placeholder="Enter postalcode" v-model="shippingAddress.postal_code" @change="sameAsShipping()">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-12">
            <div class="card"> 
              <div class="card-header">
                <h4 class="card-title">{{ lang.cruds.cart.billing_address }}</h4>
                <div class="form-check">
                  <input type="checkbox"  class="form-check-input" id="same_as_shipping" @change="sameAsShipping()" v-model="shippingAddress.same_as_billing" />
                  <label class="form-check-label" for="same_as_shipping">{{ lang.cruds.cart.same_as_shipping }}</label>
                </div>
              </div>
              <div class="card-body">
                <div class="row mt-2">
                  <div class="col-6">
                    <ValidationProvider name="first_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="first_name">{{ lang.cruds.cart.fields.first_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter first name" v-model="billingAddress.first_name" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="last_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="last_name">{{ lang.cruds.cart.fields.last_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter last name" v-model="billingAddress.last_name" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="email" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="email">{{ lang.cruds.cart.fields.email }}</label>
                        <input class="form-control" type="email" placeholder="Enter email" v-model="billingAddress.email" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="mobile" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="mobile">{{ lang.cruds.cart.fields.mobile }}</label>
                        <input class="form-control" type="number" placeholder="Enter mobile number" v-model="billingAddress.mobile" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="address" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="address">{{ lang.cruds.cart.fields.address }}</label>
                        <input class="form-control" type="text" placeholder="Enter address" v-model="billingAddress.address" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="address_2" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="address_2">{{ lang.cruds.cart.fields.address_2 }}</label>
                        <input class="form-control" type="text" placeholder="Enter address 2" v-model="billingAddress.address_2" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="billingAddressCountry"  rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="billingAddressCountry">{{ lang.cruds.cart.fields.country }}</label>
                        <select class="custom-select" name="billingAddressCountry" id="billingAddressCountry" @change="changeCountryDisplay(0)" v-model="billingAddress.country_id" :disabled="disabledBillingAddress">
                          <option :value="id" v-for="(item , id) in list.countries" v-bind:key="id">{{ item }}</option>
                        </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider  name="billingState" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="billingState">{{ lang.cruds.cart.fields.state }}</label>
                        <select class="custom-select"  name="billingState" id="billingState" v-model="billingAddress.state_id" :disabled="disabledBillingAddress">
                          <option :value="index" v-for="(item, index) in billingStates">{{ item }}</option>
                        </select>
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="city_name" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="city_name">{{ lang.cruds.cart.fields.city_name }}</label>
                        <input class="form-control" type="text" placeholder="Enter city_name" v-model="billingAddress.city_name" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                  <div class="col-6">
                    <ValidationProvider name="postal_code" rules="required" v-slot="{ errors }">
                      <div class="form-group">
                        <label class="required" for="postal_code">{{ lang.cruds.cart.fields.postal_code }}</label>
                        <input class="form-control" type="text" placeholder="Enter postalcode" v-model="billingAddress.postal_code" :disabled="disabledBillingAddress">
                        <p class="text-danger">{{ errors[0] }}</p>
                      </div>
                    </ValidationProvider>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group float-left">
        <button class="btn btn-primary waves-effect waves-light" type="submit">
          {{ lang.global.save }}
        </button>
        <button class="btn btn-danger waves-effect waves-light" type="button" @click="cancel()" >
          {{ lang.global.cancel }}
        </button>
      </div>
    </div>
      <br/>
    </form>
  </ValidationObserver>
</div>
</template>
<script>
  import { mapGetters, mapActions } from 'vuex';
  import draggable from 'vuedraggable';

  export default {
    props: ['list', 'data', 'type'],
    name:'AddOrders',

    data() {
      return {
        shippingAddress:{
          first_name:'',
          last_name:'',
          email:'',
          mobile:'',
          address:'',
          address_2:'',
          country_id: this.data.defaultCountry,
          state_id: (typeof this.list.states != 'undefined')? Object.keys(this.list.states)[0]: Object.keys(this.list.shippingStates)[0],
          city_name:'',
          postal_code:'',
          same_as_billing:false,
        },
        billingAddress:{
          first_name:'',
          last_name:'',
          email:'',
          mobile:'',
          address:'',
          address_2:'',
          country_id: this.data.defaultCountry,
          state_id: (typeof this.list.states != 'undefined')? Object.keys(this.list.states)[0]: Object.keys(this.list.billingStates)[0],
          city_name:'',
          postal_code:'',
        },
        shippingStates:[],
        billingStates:[],
        selectUserId: {'id':null, 'email':'','mobile':''},
        selectProductId: [],
        objProductsSelect: [],
        objUserSelect:[],
        finalSelectionProduct:[],
        isLoading : false,
        isUserLoading : false,
        totalAmount:0,
        totalQuantity:0,
        disabledBillingAddress:false,

      }
    },
    mounted(){
      this.shippingStates = this.billingStates = this.list.states;
      this.setProductData(this.list.objProducts);
      this.setUserData(this.list.objUsers);
      if(this.type == 'Edit')
      {
        if(this.data.objCartUser != null)
        {
          this.selectUserId = this.data.objCartUser;
          this.objUserSelect = [this.selectUserId];
        }
        this.finalSelectionProduct = this.data.objSelectionProducts;
        this.shippingAddress = this.data.shippingAddress;
        this.billingAddress = this.data.billingAddress;
        this.shippingStates = this.list.shippingStates;
        this.billingStates = this.list.billingStates;
      }
      this.calculateTotal();
    },
    computed: {
      noImage(){
        return '/assets/images/no-image.jpg';
      },
    },
    created() {
      this.setSelectionProductId();
    },
    methods: {
      sameAsShipping(){
        let billing_address_id = this.billingAddress.id;
        if(this.shippingAddress.same_as_billing){
          this.billingStates = this.shippingStates;
          this.billingAddress = {...this.shippingAddress};
          this.billingAddress.id = billing_address_id;
          this.disabledBillingAddress = true;
        }
        else {
          this.disabledBillingAddress = false;
        }

      },
      calculateTotal(){
        this.finalSelectionProduct.forEach((item, i) => {
          this.totalAmount += parseFloat(item.price * item.quantity);
          this.totalQuantity += item.quantity;
        });
      },
      setSelectionProductId(){
        let setSelectProductId =  {'id':null, 'title': '', 'quantity': 0, 'price': 0, 'total': 0, 'compareprice': 0, 'maxOrderLimit': 0, 'minOrderLimit' : 0, 'isContinueSelling' : 0, 'cart_details_id': Math.floor((Math.random() * -10000000)) }
        this.selectProductId = setSelectProductId;
      },
      setAltImg(event){
        event.target.src = this.noImage;
      },
      FilterTitleName(option) {
        return `${option.title}`;
      },
      FilterUserName({ email,mobile }) {
        if(mobile == ''){
          return `${email}`;
        }
        return `${email} - (${mobile})`;
      },
      setProductData(objProducts)
      {
        this.objProductsSelect = objProducts;
      },
      setUserData(objUsers)
      {
        this.objUserSelect = objUsers;
      },
      finalSelectedProduct()
      {
        if(this.selectProductId.id != null){
          if(this.selectProductId.quantity > 0){
            this.selectProductId.total = this.selectProductId.quantity * this.selectProductId.price;
            this.selectProductId.cart_details_id =  Math.floor((Math.random() * -10000000));
            this.finalSelectionProduct.push(this.selectProductId);
            this.setSelectionProductId();
          }
          else {
            errorModal('Please enter correct quantity!!');
          }
        }
        else {
          errorModal('Please enter product!!');
        }
      },
      clearAll(){
        this.finalSelectionProduct=[];
      },
      removeCartProduct(index){
        this.finalSelectionProduct.splice(index, 1);
      },
      SearchProducts(searchKeyWord) {
        if(searchKeyWord.length > 3){
          this.isLoading = true;
          this.$store.dispatch("cartModule/GetSearchProducts", {'search': searchKeyWord})
          .then((res) => {
            if (res.response.status_code == 2044) {
              this.objProductsSelect = res.response.data;
              this.isLoading = false;
            }
            else
            {
              errorModal(res.response.message);
            }

          })
          .catch((err) => {
            errorModal(err.response.message);
            this.isLoading = false;
          });
        }
      },
      increaseQuantity(index){

        this.finalSelectionProduct[index].quantity ++;
        this.totalAmount=0;
        this.totalQuantity=0;
        this.calculateTotal();
      },
      decreaseQuantity(index){

        this.finalSelectionProduct[index].quantity --;
        this.totalAmount=0;
        this.totalQuantity=0;
        this.calculateTotal();
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
      SearchUsers(searchUserKeyWord) {
        if(searchUserKeyWord.length > 3){
          this.isUserLoading = true;
          this.$store.dispatch("cartModule/GetSearchUser", {'search': searchUserKeyWord})
          .then((res) => {
            if (res.response.status_code == 3038) {
              this.objUserSelect = res.response.data;
              this.isUserLoading = false;
            }
            else
            {
              errorModal(res.response.message);
            }
          })
          .catch((err) => {
            errorModal(err.response.message);
            this.isLoading = false;
          });
        }
      },
      changeCountryDisplay(shippingStatus)
      {
        let countryId = this.billingAddress.country_id;
        if(shippingStatus){
          countryId = this.shippingAddress.country_id; 
        }
        this.$store.dispatch("cartModule/GetState", {'country': countryId})
        .then((res) => {
          if (res.response.status_code == 2046) {
            if(shippingStatus){
              this.shippingStates = res.response.data;
            }
            else {
              this.billingStates = res.response.data; 
            }
          }
          else
          {
            errorModal(res.response.message);
          }

        })
        .catch((err) => {
          errorModal(err.response.message);
        });
      },
      submit(){
        if(this.selectUserId.id !== null){
          this.$refs.orderForm.validate().then(success => {
            if (!success) {
              $("html, body").animate({ scrollTop: 50 }, 200);
              return;
            }
            openLoader();
            if(this.type == 'Add')
            {
              this.$store.dispatch("cartModule/AddOrderCart", {'user_id': this.selectUserId.id,'products':this.finalSelectionProduct,'shipping_address':this.shippingAddress,'billing_address':this.billingAddress})
              .then((res) => {
                if (res.response.status_code == 2061) {
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
            else
            {
              this.$store.dispatch("cartModule/EditOrderCart", {'cart_id': this.data.cart_id ,'user_id': this.selectUserId.id,'products':this.finalSelectionProduct,'shipping_address':this.shippingAddress,'billing_address':this.billingAddress})
              .then((res) => {
                if (res.response.status_code == 2063) {
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
          });

        }
        else {
          errorModal('Please select user !!');
        }
      },
      cancel(){
        location.reload();
      },
    }

  }
</script>

<style lang="scss" scoped>
  img{
    max-width:100px;
    max-height:100px
  }
  label{
    display:block;
  }
   .table td{
    border-top:1px;
   }
  hr{
    margin-bottom: 3rem;
  }
  .qty-input{
    width:50px;
    text-align:center;
  }
  .increase , .decrease{
    font-size: 14px;
    width: 30px;
    height: 26px;
    font-weight: 500;
    border: none;
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