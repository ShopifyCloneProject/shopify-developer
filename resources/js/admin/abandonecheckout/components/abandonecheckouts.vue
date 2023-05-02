<template>
    <div id="input-sizing">

         <div class="row">
          <div class="col-md-12 col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">{{ lang.cruds.abandonecheckouts.cart_product }}</h4>
              </div>
              <div class="table-responsive">
                <table class="table">
                  <thead class="thead-dark">
                    <tr>
                      <th class="text-center">Image</th>
                      <th class="table-name-info">Name</th>
                      <th class="text-center">Quantity</th>
                      <th class="text-center">Price</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(product, index) in finalSelectionProduct" :key="index">
                      <td>
                        <div class="text-center">
                          <img :src="product.img_src" :alt="product.productName"  @error="setAltImg" height="80" width="70">
                        </div>
                      </td>
                      <td class="table-name-info">
                        <div class="d-flex">
                          <div class="mr-1" v-if="product.compareprice > 0"><strike >{{ product.compareprice }}</strike ></div>
                          <div><h4 class="mb-0">{{ product.price }}</h4></div>
                        </div class="mt-0">
                        <h2 class="cart-table-prd-name">{{ product.title }}
                        </h2>
                      </td>
                      <td class="text-center">
                        <div class="quantity" v-if="product.stock_status">{{ product.quantity }}</div>
                        <div class="text-danger" v-else>Out of stock</div>
                      </td>
                      <td class="text-center">
                        <div>
                         {{ product.price * product.quantity }}
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

            </div>
          </div>
        </div>
 <!--  <div class="row">
                <div class="col-md-12 col-12">
                    <div class="card"> 
                        <div class="card-header">
                            <h4 class="card-title">{{ lang.cruds.abandonecheckouts.cart_product }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-xl-13">
                                    <div class="cart-table">
                                        <div class="cart-table-prd cart-table-prd--head py-1 d-none d-md-flex">
                                            <div class="cart-table-prd-image text-center cart-font">
                                                Image
                                            </div>
                                            <div class="cart-table-prd-content-wrap">
                                                <div class="cart-table-prd-info cart-font">Name</div>
                                                <div class="cart-table-prd-qty cart-font">Qty</div>
                                                <div class="cart-table-prd-price-total cart-font">Price</div>
                                            </div>
                                        </div>
                                        <div class="cart-table-prd" v-for="(product, index) in finalSelectionProduct" :key="index">
                                            <div class="cart-table-prd-image">
                                                    <img class="lazyload fade-up" :src="product.img_src" :alt="product.productName"  @error="setAltImg" >
                                                </div>
                                                <div class="cart-table-prd-content-wrap">
                                                    <div class="cart-table-prd-info">
                                                        <div class="cart-table-prd-price d-flex">
                                                            <div class="price-old" v-if="product.compareprice > 0">{{ product.compareprice }}</div>
                                                            <div class="price-new">{{ product.price }}</div>
                                                        </div>
                                                        <h2 class="cart-table-prd-name">
                                                            <a :href="'/product/detail/'+ product.slug">{{ product.title }}</a>
                                                        </h2>
                                                    </div>
                                                    <div class="cart-table-prd-qty"  v-if="product.stock_status">
                                                        <div class="qty qty-changer">
                                                            <div class="quantity">{{ product.quantity }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="cart-table-prd-qty text-danger" v-else>Out of stock</div>
                                                    <div class="cart-table-prd-price-total">
                                                        {{ product.price * product.quantity }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-1 d-flex total-footer">
                                            <h5 class="total-quantity">Total Quantity  :  {{ totalQuantity }}</h5>
                                            <h5 class="total-amount">Total Amount  :  {{ totalAmount }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>  -->
          <!--   <div class="row">
                <div class="col-6"> 
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ lang.cruds.abandonecheckouts.shipping_address }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">First Name :  </p>
                                    <span class="ml-1">{{ shippingAddress.first_name }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Last Name : </p>
                                    <span class="ml-1">{{ shippingAddress.last_name }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Email Address : </p>
                                    <span class="ml-1">{{ shippingAddress.email }}</span>
                                </div> 
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Mobile : </p>
                                    <span class="ml-1">{{ shippingAddress.mobile }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Address : </p>
                                    <span class="ml-1">{{ shippingAddress.address }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Address_2 : </p>
                                    <span class="ml-1">{{ shippingAddress.address_2 }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Country : </p>
                                    <span class="ml-1">{{ shippingAddress.Countryname }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">State : </p>
                                    <span class="ml-1">{{ shippingAddress.Statename }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">City : </p>
                                    <span class="ml-1">{{ shippingAddress.city_name }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Postal Code : </p>
                                    <span class="ml-1">{{ shippingAddress.postal_code }}</span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-6"> 
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ lang.cruds.abandonecheckouts.billing_address }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">First Name :  </p>
                                    <span class="ml-1">{{ billingAddress.first_name }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Last Name : </p>
                                    <span class="ml-1">{{ billingAddress.last_name }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Email Address : </p>
                                    <span class="ml-1">{{ billingAddress.email }}</span>
                                </div> 
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Mobile : </p>
                                    <span class="ml-1">{{ billingAddress.mobile }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Address : </p>
                                    <span class="ml-1">{{ billingAddress.address }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Address_2 : </p>
                                    <span class="ml-1">{{ billingAddress.address_2 }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Country : </p>
                                    <span class="ml-1">{{ billingAddress.Countryname }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">State : </p>
                                    <span class="ml-1">{{ billingAddress.Statename }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">City : </p>
                                    <span class="ml-1">{{ billingAddress.city_name }}</span>
                                </div>
                                <div class="col-12 d-flex">    
                                    <p class="font-weight-bolder">Postal Code : </p>
                                    <span class="ml-1">{{ billingAddress.postal_code }}</span>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>  --> 
             <div class="form-group ml-2">
                    <button type="button" class="btn btn-primary mt-2" @click="backtolist()">
                        Back To List
                    </button>
                </div>
            <br/>
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
                    finalSelectionProduct:[],
                    billingAddress:[],
                    shippingAddress:[],
                    totalAmount:0,
                    totalQuantity:0,
                }
            },
            mounted(){
                this.finalSelectionProduct = this.data.objSelectionProducts;
                this.billingAddress = this.data.address;
                this.shippingAddress = this.data.shippingAddress;

                this.calculateTotal();
            },

            computed: {
                noImage(){
                    return '/assets/images/no-image.jpg';
                },
            },
            created() {

            },
            methods: {
                backtolist(){
                    window.location.href = getListingUrl ;
                },
                calculateTotal(){
                    this.finalSelectionProduct.forEach((item, i) => {
                        this.totalAmount += parseFloat(item.price * item.quantity);
                        this.totalQuantity += item.quantity;
                    });
                },

                setAltImg(event){
                    event.target.src = this.noImage;
                },
            },

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
        
    </style>