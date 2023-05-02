import axios from "axios";

export default {
   AddEditLanguageSelection({ commit }, data) {
       return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "storeUpdateSelectLanguage", data,{}
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
