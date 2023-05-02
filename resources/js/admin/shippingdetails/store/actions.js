import axios from "axios";

export default {
    SaveShippingDetails({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "saveShippingDetail", data,{}
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
