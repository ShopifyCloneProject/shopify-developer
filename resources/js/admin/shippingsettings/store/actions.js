import axios from "axios";

export default {
	 SaveRates({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "settings/shipping/add-edit-rate", data,{}
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
    saveShipRoundFigureTaxCharges({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "shippingCharge", data,{}
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
    RemoveRates({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .delete(
            API_URL + "shipping/delete-rates/" + id,{}
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
