import axios from "axios";

export default {
  ExchangeOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "orders/addexchangeproduct", data,{}
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
	 DeleteExchangeOrder({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "returnorder/delete/"+ id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    removeExchangeProducts({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "returnorderproduct/delete/"+ id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ShippingExchangeOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "save-returnshipping-order" ,data, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
   GetStates({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "get-states/"+ id,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    UpdateCustomerAddress({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "order/edit/address", payload, {}
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
