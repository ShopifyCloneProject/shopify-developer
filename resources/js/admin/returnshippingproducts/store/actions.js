import axios from "axios";

export default {
   ReturnShippingProducts({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "saveReturnShippingProduct", data,{}
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
    availableCouriers({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "availableCouriers", data,{}
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
    removeReturnProducts({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "deleteReturnShippingOrderProduct/"+ id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    deleteReturnShippingOrder({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "deleteReturnShippingOrder/"+ id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    cancelReturnShippingOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "cancelReturnShipping/"+ data, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
     PickupReturnOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "returnPickup", data,{}
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
    getPickupLocation({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "get-pickup-location/"+ id,{}
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
            API_URL + "editReturnShippingAddress", payload, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
     handleActions({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "handleReturnShippingActions", data, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    checkTrackUrl({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "checkTrack/"+ id, {}
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
    deliveredReturnShippingOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "updateReturnShippingDeliveredStatus", data, {}
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
