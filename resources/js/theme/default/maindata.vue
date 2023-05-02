<template>
  <div>
      <template v-if="data.page == 'home'">
        <template v-for="(sortsection,index) in pageSettings" v-if="sortsection.page == 1">
        <Header :cartLength="cartLength" :cartTotal="cartTotal" :user="data.user" :auth="auth"  v-if="sortsection.sectionname=='header' && sortsection.status == 1"></Header>
        <HeaderMobile :cartTotal="cartTotal" :user="data.user" :auth="auth" :cartcalulatedata="cartCalulateData" v-if="sortsection.sectionname=='header' && sortsection.status == 1"></HeaderMobile>
        <div class="page-content">
           <MainSlider v-if="data.page == 'home' && sortsection.sectionname == 'header'" :auth="auth" :sliders="data.sliders"></MainSlider>
           <Home :data="data" v-if="data.page == 'home'" :auth="auth" :sortsection="sortsection"></Home>
        </div>
        <Footer v-if="sortsection.sectionname=='footer' && sortsection.status == 1"></Footer>
        </template> 
      </template>

      <template v-else>
          <Header :cartLength="cartLength" :cartTotal="cartTotal" :user="data.user" :auth="auth" :cartcalulatedata="cartCalulateData"  ></Header>
          <HeaderMobile :cartTotal="cartTotal" :user="data.user" :auth="auth"  :cartcalulatedata="cartCalulateData" @callcart="getCartData"></HeaderMobile>
          <div class="page-content">
             <Login :data="data" v-if="data.page == 'login'" :auth="auth"></Login>
             <Account v-if="data.page == 'account'" :data="data" :auth="auth"></Account>
             <ProductDetail :data="data" v-if="data.page == 'productdetail'" :auth="auth"></ProductDetail>
             <Cart :data="data" :carts="cartlist" :cartTotal="cartTotal" :cartcalulatedata="cartCalulateData" v-if="data.page == 'cart'" :auth="auth" @callcart="getCartData"></Cart>
             <Checkout :data="data" :carts="cartlist" :cartTotal="cartTotal" :cartcalulatedata="cartCalulateData" v-if="data.page == 'checkout'" :auth="auth" @callcart="getCartData"></Checkout>
             <Wishlist :data="data" v-if="data.page == 'wishlist'" :auth="auth"></Wishlist>
             <Thankyou :data="data" v-if="data.page == 'thankyou'" :auth="auth"></Thankyou>

             <OrderData :data="data" v-if="data.page == 'orderdata'" :auth="auth"></OrderData>
             <ReturnOrder :data="data" v-if="data.page == 'returnorder'" :auth="auth"></ReturnOrder>
             <ExchangeOrder :data="data" v-if="data.page == 'exchangeorder'" :auth="auth"></ExchangeOrder>
             <CancelExchangeOrder :data="data" v-if="data.page == 'cancelexchangeorder'" :auth="auth"></CancelExchangeOrder>
             <Static :data="data" v-if="data.page == 'static'" :auth="auth"></Static>
             <PageNotFound  v-if="data.page == 'pagenotfound'" :auth="auth"></PageNotFound>
             <Collections :data="data" v-if="data.page == 'collections'" :auth="auth"></Collections>
             <CollectionDetail :data="data" v-if="data.page == 'collectiondetail'" :auth="auth"></CollectionDetail>
             <Shop :data="data" v-if="data.page == 'shop'" :auth="auth"></Shop>
             <Search :data="data" v-if="data.page == 'search'" :auth="auth"></Search>
             <ResetPassword :data="data" v-if="data.page == 'resetpassword'" :auth="auth"></ResetPassword>
             <ResetPasswordForm :data="data" v-if="data.page == 'resetpasswordform'" :auth="auth"></ResetPasswordForm>
          </div>
          <Footer></Footer>
      </template>
  </div>
</template>

<script>
import PageNotFound from './PageNotFound';
import Header from './Header';
import HeaderMobile from './HeaderMobile';
import MainSlider from './MainSlider';
import Login from './auth/login';
import ResetPassword from './auth/resetpassword';
import ResetPasswordForm from './auth/resetpasswordform';
import Account from './profile/account';
import OrderData from './profile/orderdata';
import ReturnOrder from './profile/returnorder';
import ExchangeOrder from './profile/exchangeorder';
import CancelExchangeOrder from './profile/cancelexchangeorder';
import Home from './home/Home';
import ProductDetail from './productdetail/Index';
import Footer from './Footer';
import Cart from './cart/cart';
import Checkout from './checkout/checkout';
import Wishlist from './wishlist/wishlist';
import Thankyou from './thankyou/thankyou';
import Collections from './collection/index';
import Search from './search/index';
import CollectionDetail from './collection/detail';
import Shop from './shop/index';
import Static from './Static';
import { mapState } from 'vuex'

export default {
    name: "maindata",
    props: ['data'],
    data() {
      return {
        cruds: [],
        pageSettings: [],
      }
    },
    computed: {
      ...mapState(['globalStore']),
      cartlist(){
         return this.globalStore.cart.products;
      },
      cartLength(){
         return this.globalStore.cart.products.length;
      },
      cartTotal(){
          return this.globalStore.cart.subTotal;
      },
      cartCalulateData(){
        let calculation = this.globalStore.cart;
          return {
                  'subTotal': calculation.subTotal,
                  'taxAmount': calculation.taxAmount,
                  'shippingAmount': calculation.shippingAmount,
                  'voucherAmount': calculation.voucherAmount,
                  'total': calculation.total
                 };
      },
      auth(){
         return Object.keys(this.data.user).length > 0 ? true : false;
      }
    },
    created(){
      
    },
    mounted(){
      this.pageSettings = JSON.parse(globalsettings).themePages;
      this.$store.commit("globalStore/setUserData", this.data.user);
      this.getCartData();
    },
    methods: {
      getCartData(){
        let section = $('#userCart');
        if(window.location.pathname == "/checkout")
        {
          openLoader();
        }

         blockSection(section);
          this.$store.dispatch("globalStore/GetCartData", {auth: this.auth})
         .then((res) => {
              let section = $('#userCart');
              unblockSection(section);
              if(this.data.page == 'checkout')
              {
                if(res.response.data.length == 0)
                {
                  window.location.href = "/cart";
                }
              }
              if(window.location.pathname == "/checkout")
              {
                closeLoader();
              }
          })
          .catch((err) => {
             alert(err);
          });
      },
      callFromMixinfunction(){
        // console.log('home page load');
      },
    },
    components: {
      Static, PageNotFound, Header, HeaderMobile, MainSlider,Login, Account,  Home, Footer, ProductDetail, Cart, Checkout, Wishlist, Thankyou, OrderData, ReturnOrder, ExchangeOrder, CancelExchangeOrder, Collections, CollectionDetail, Shop, Search, ResetPassword, ResetPasswordForm
    }
}
</script>
<style scoped>
   .page-content{
      min-height: auto !important;
   }
</style>