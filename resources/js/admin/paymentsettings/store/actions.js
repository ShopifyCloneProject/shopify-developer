import axios from "axios";

export default {
    ActivatePaymentMethod({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "activate-payment-method", data,{}
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
    deActivatePaymentMethod({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "deactivate-payment-method/"+id,{}
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
    CreateCustomPaymentMethod({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "create-custom-payment-method", data,{}
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
    deActivateCustomPaymentMethod({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "deactivate-custom-payment-method/"+id,{}
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
