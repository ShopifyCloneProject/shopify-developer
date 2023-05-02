import axios from "axios";

export default {

    AddEditCustomer({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "edit-customer", data,{
               headers: {
              'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
              }
            }
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
    addCustomerAddress({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "add-customer-address", data,{}
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
    GetFilterOrders({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "get-sort-orders", data,{}
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
            console.log(err);
            reject(err);
          });
      });
    },
    MakeDefaultAddress({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .put(
            API_URL + "default-address/" + id , {},{}
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
    editCustomerAddress({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .put(
            API_URL + "edit-customer-address", data,{}
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
    ChangeTaxStatus({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .put(
            API_URL + "change-tax-status" , data,{}
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
    ChangeEmailSubscriptionStatus({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .put(
            API_URL + "change-subscription-status" , data,{}
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
    AddNote({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .put(
            API_URL + "add-note" , data,{}
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
}
