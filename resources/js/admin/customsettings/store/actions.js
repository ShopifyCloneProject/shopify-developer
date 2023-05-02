import axios from "axios";

export default {
   SaveCustomSettings({ commit }, payload) {
        return new Promise((resolve, reject) => {
        axios.post(API_URL + 'save-custom-settings', payload)
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
              reject(err);
          });
      });
    },
}
