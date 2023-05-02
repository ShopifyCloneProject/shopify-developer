import axios from "axios";

export default {
    SaveGiftCardSettings({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "save-giftcard-settings", data,{}
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
