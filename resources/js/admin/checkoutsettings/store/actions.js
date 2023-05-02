import axios from "axios";

export default {
    saveSettings({ commit }, data) {
        return new Promise((resolve, reject) => {
        axios.post(API_URL + 'update-checkout-setting', data)
          .then(response => {
                resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },

}
