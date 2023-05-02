import axios from "axios";

export default {
    ShippingProducts({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "order/saveShippingProduct", data,{}
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
     saveCodPayment({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "saveCodPayment/"+ id, {}
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
    deliveredShippingOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "updateShippingDeliveredStatus", data, {}
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
    removeProducts({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "order/deleteShippingOrderProduct/"+ id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    deleteShippingOrder({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "order/deleteShippingOrder/"+ id, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    cancelShippingOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "cancelShipping/"+ data, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    PickupOrder({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "pickup/"+ id,{}
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
    handleActions({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "handleShippingActions", data, {}
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
