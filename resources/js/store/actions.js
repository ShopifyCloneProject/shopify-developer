import axios from 'axios'

export default {
    AddToCart({ commit, getters }, payload) {
      if(localStorage.getItem('uniqueId') == null)
      {
        payload.uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage', 'AddToCart');
      }
      else
      {
        payload.uniqueId = localStorage.getItem('uniqueId');
      }
      
      return new Promise((resolve, reject) => {
        let URL = getters.getApiUrl;
         axios
          .post(URL + "cart/add", payload, {})
          .then(response => {
               resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
   },
   GetCartData({ commit, getters }, payload){
    let uniqueId = null;
    let shippingaddressId = 0;
    let voucher_code = 0;
      if(localStorage.getItem('uniqueId') == null)
      {
        uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage', 'GetCartData');
      }
      else
      {
        uniqueId = localStorage.getItem('uniqueId');
      }
      if(localStorage.getItem('shippingAddressId') == null)
      {
        if(!payload.auth)
        {
          shippingaddressId = localStorage.setItem('shippingAddressId', 0);
        }
      }
      else
      {
        shippingaddressId = localStorage.getItem('shippingAddressId');
      }
      if(localStorage.getItem('voucher_code') == null)
      {
        voucher_code = localStorage.setItem('voucher_code','0');
      }
      else
      {
        voucher_code = localStorage.getItem('voucher_code');
      }
       return new Promise((resolve, reject) => {
         let URL = getters.getApiUrl;
         axios
          .get(URL + "cart?uniqueId="+uniqueId+"&shippingAddressId="+shippingaddressId+"&voucher_code="+voucher_code, {})
          .then(response => {
               commit('setCartData', response.data.response.data);
               resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
   },
   RemoveCartProduct({ commit, getters }, cartProductId) {

       return new Promise((resolve, reject) => {
         let URL = getters.getApiUrl;
         axios
          .delete(URL + "cart/delete/" + cartProductId, {})
          .then(response => {
              commit('removeCartProduct', cartProductId);
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
   },
   ClearCart({ commit, getters }) {
    let uniqueId = null;
      if(localStorage.getItem('uniqueId') == null)
      {
        uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage', 'ClearCart');
      }
      else
      {
        uniqueId = localStorage.getItem('uniqueId');
      }
       return new Promise((resolve, reject) => {
         let URL = getters.getApiUrl;
         axios
          .delete(URL + "cart/clear?uniqueId="+uniqueId, {})
          .then(response => {
              commit('clearCart');
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    DecreaseQuantity({ commit, getters }, id) {
       return new Promise((resolve, reject) => {
         let URL = getters.getApiUrl;
         axios
          .get(URL + "cart/quantity/remove/" + id, {})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    IncreaseQuantity({ commit, getters }, id) {
       return new Promise((resolve, reject) => {
         let URL = getters.getApiUrl;
         axios
          .get(URL + "cart/quantity/add/" + id, {})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ApplyCuponCode({ commit, getters }, payload) {
       let uniqueId = null;
      if(localStorage.getItem('uniqueId') == null)
      {
        uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
      }
      else
      {
        uniqueId = localStorage.getItem('uniqueId');
      }
       return new Promise((resolve, reject) => {
         let URL = getters.getApiUrl;
         axios
          .post(URL + "checkout/checkVoucher?uniqueId="+uniqueId, payload, {})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ClearCuponCode({ commit, getters }, payload) {
       let uniqueId = null;
      if(localStorage.getItem('uniqueId') == null)
      {
        uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
      }
      else
      {
        uniqueId = localStorage.getItem('uniqueId');
      }
       return new Promise((resolve, reject) => {
         let URL = getters.getApiUrl;
         axios
          .post(URL + "checkout/clearVoucher?uniqueId="+uniqueId , payload, {})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    Register({ commit, getters }, payload) {
       return new Promise((resolve, reject) => {
         let URL = getters.getDefaultUrl;
         axios
          .post(URL + "register", payload,{})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    Login({ commit, getters }, payload) {
      if(localStorage.getItem('uniqueId') == null)
      {
        payload.uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage');
      }
      else
      {
        payload.uniqueId = localStorage.getItem('uniqueId');
      }
       return new Promise((resolve, reject) => {
         let URL = getters.getDefaultUrl;
         axios
          .post(URL + "userLogin", payload,{})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    updateUser({ commit, getters }, payload) {
       return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
         axios
          .post(
            URL + "updateUser", payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
     UpdateAddress({ commit, getters }, payload) {
       return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
         axios
          .post(
            URL + "addresses/update-address/" + payload.id, payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
     addReview({ commit, getters }, payload) {
       return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
         axios
          .post(
            URL + "product/detail/add-review" , payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    CancelExchangeOrderRequest({ commit, getters }, id) {
       return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
         axios
          .delete(URL + "cancelExchangeOrder/" + id ,{})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    CancelReturnProduct({ commit, getters }, payload) {
       return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
         axios
          .post(
            URL + "cancelrequest" , payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ChangePassword({ commit, getters }, payload) {
       return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
         axios
          .post(
            URL + "changePassword", payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    GetStates({ commit, getters }, id) {
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .get(
            URL + "get-states/"+ id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
      checkTrackUrl({ commit, getters}, id) {
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "checkTrack/"+ id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            console.log(err);
            reject(err);
          });
      });

    },
    GetCashFreeFormDetails({ commit, getters }, payload) {
      if(localStorage.getItem('uniqueId') == null)
      {
        payload.uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage');
      }
      else
      {
        payload.uniqueId = localStorage.getItem('uniqueId');
      }
      let URL = getters.getDefaultUrl;
      return new Promise((resolve, reject) => {
        axios
          .post(
            URL + "cashfree/", payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    GetRazorpayDetails({ commit, getters }, payload) {
      if(localStorage.getItem('uniqueId') == null)
      {
        payload.uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage');
      }
      else
      {
        payload.uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "razorpay/", payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ProcessInstamojo({ commit, getters }, payload) {
      if(localStorage.getItem('uniqueId') == null)
      {
        payload.uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage');
      }
      else
      {
        payload.uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "instamojo/", payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    
    CodOrderSuccess({ commit, getters }, payload) {
      let  uniqueId = null;
      if(localStorage.getItem('uniqueId') == null)
      {
        uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
      }
      else
      {
        uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "payment/cod?uniqueId="+uniqueId, payload,{}
          )
          .then(response => {
             commit('removeShippingBillingStorage');
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
     ProcessCashOnDelhivery({ commit, getters }, payload) {
      let  uniqueId = null;
      if(localStorage.getItem('uniqueId') == null)
      {
        uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
      }
      else
      {
        uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "cashondelhivery?uniqueId="+uniqueId, payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ProcessPaytm({ commit, getters }, payload) {
      if(localStorage.getItem('uniqueId') == null)
      {
        payload.uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage');
      }
      else
      {
        payload.uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "paytm/", payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    countLive({ commit, getters })
    {
      let uniqueId = null;
      if(localStorage.getItem('uniqueId') == null)
      {
        uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage', 'countLive');
      }
      else
      {
        uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
         let URL = getters.getApiUrl;
          axios
          .get(URL + "countLive?uniqueId="+uniqueId,{})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
              reject(err);
          });
      });
    },
    countCart({ commit, getters })
    {
      let uniqueId = null;
      if(localStorage.getItem('uniqueId') == null)
      {
        uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage', 'countCart');
      }
      else
      {
        uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
         let URL = getters.getApiUrl;
          axios
          .get(URL + "countCart?uniqueId="+uniqueId,{})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
              reject(err);
          });
      });
    },
    countCheckout({ commit, getters })
    {
       let uniqueId = null;
      if(localStorage.getItem('uniqueId') == null)
      {
        uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage', 'countCheckout');
      }
      else
      {
        uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
         let URL = getters.getApiUrl;
         axios
          .get(URL + "countCheckout?uniqueId="+uniqueId,{})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
              reject(err);
          });
      });
    },
    AddAddress({ commit, getters }, payload) {
      if(localStorage.getItem('uniqueId') == null)
      {
        payload.uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage', 'AddAddress');
      }
      else
      {
        payload.uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "address/add", payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    EditAddress({ commit, getters }, payload) {
      if(localStorage.getItem('uniqueId') == null)
      {
        payload.uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage');
      }
      else
      {
        payload.uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .put(
            URL + "address/edit", payload,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ChangeDefaultAddress({ commit, getters }, payload) {
      if(localStorage.getItem('uniqueId') == null)
      {
        payload.uniqueId = localStorage.setItem('uniqueId', Math.floor((Math.random() * 100000)));
        commit('removeShippingBillingStorage');
      }
      else
      {
        payload.uniqueId = localStorage.getItem('uniqueId');
      }
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .put(
            URL + "change-defailt-address", payload, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    AddToWishlist({ commit, getters }, id) {
        let URL = getters.getDefaultUrl;
        return new Promise((resolve, reject) => {
         axios
          .get(URL + "wishlist/add/" + id, {})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
   },
   DeleteWishlistIten({ commit, getters }, id) {
        let URL = getters.getDefaultUrl;
        return new Promise((resolve, reject) => {
         axios
          .delete(URL + "wishlist/delete/" + id, {})
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
   },
  GetOrders({ commit, getters }, payload) {
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "orders", payload, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
  },
  getFilterProducts({ commit, getters }, payload) {
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "filter/products", payload, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    getProducts({ commit, getters }, payload) {
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "products/all", payload, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    GetWishlist({ commit, getters }, page) {
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .get(
            URL + "wishlist/" + page, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ReturnOrderProduct({ commit, getters }, payload ) {
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "returnorder/savereturnorder" , payload, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ExchangeOrderProduct({ commit, getters }, payload ) {
      return new Promise((resolve, reject) => {
        let URL = getters.getDefaultUrl;
        axios
          .post(
            URL + "exchangeorder/save-exchange-order" , payload, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    GetAddresses({ commit }) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "addresses/", {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    RemoveAddress({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "address/delete/" + id , {}, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    SearchProducts({ commit }, title) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "search/1/"+ title, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    GetSearchProducts({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "search/"+ payload.page + '/'+ payload.title, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    GetReviewsData({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "reviewPage/"+ payload.page + '/'+ payload.product_id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ResetPassword({ commit }, email) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "password/reset", {email: email}, {}
          )
          .then(response => {
            resolve(response.data);
          })
          .catch(err => {
            console.log(err);
            reject(err);
          });
      });
    },
    ResetPasswordForm({ commit }, resetData) {
        return new Promise((resolve, reject) => {
          axios
            .post(
              API_URL + "reset/password", resetData, {}
            )
            .then(response => {
              resolve(response.data);
            })
            .catch(err => {
              console.log(err);
              reject(err);
            });
        });
    },
    downlodInvoice({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "invoice/download/" + id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
              reject(err);
          });
      });
    },
}
