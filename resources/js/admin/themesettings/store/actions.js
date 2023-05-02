import axios from "axios";

export default {
   AddEditThemeSettings({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "themesettings/add-themesetting", data,{}
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
