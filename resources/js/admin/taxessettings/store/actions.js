import axios from "axios";

export default {
    SaveCountryTaxes({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "tax/saveSelectedCountry", data,{}
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
    SaveStateTaxes({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "tax/saveStateTax", data,{}
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
