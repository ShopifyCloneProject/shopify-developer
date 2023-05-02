import axios from "axios";

export default {
    AddOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "order/add", data,{}
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
    EditOrder({ commit }, data) {
      let id = data.id;
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "order/update/" + id, data,{}
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
    downloadInvoice({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "downloadInvoice/" + id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
              reject(err);
          });
      });
    },
    deleteInvoice({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "deleteInvoice/" + id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
              reject(err);
          });
      });
    },
    RefundOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "orders/addrefundproduct", data,{}
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
    UpdateContactInformation({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "order/edit/concact", payload, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    UpdateOrderNote({ commit }, payload) {
      return new Promise((resolve, reject) => {
          axios
          .post(
            API_URL + "order/edit/note", payload, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    MarkAsPaid({ commit }, id) {
      return new Promise((resolve, reject) => {
          axios
          .get(
            API_URL + "order/payment/paid/" + id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    FulFillOrder({ commit }, id) {
      return new Promise((resolve, reject) => {
          axios
          .get(
            API_URL + "order/fulfilled/" + id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    //  GetState({ commit }, data) {
    //   return new Promise((resolve, reject) => {
    //     axios
    //       .get(
    //         API_URL + "get-states/" + data.country,{}
    //       )
    //       .then(response => {
    //           resolve(response.data);
    //       })
    //       .catch(err => {
    //         reject(err);
    //       });
    //   });
    // },
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
    DeleteOrder({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "order/delete/"+ id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ShippingOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "order/save-shipping-order" ,data, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    GetSearchProducts({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "order/serach/product", data,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
     removeProducts({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "orderproduct/delete/"+ id, {}
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
