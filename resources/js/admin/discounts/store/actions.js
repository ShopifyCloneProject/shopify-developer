import axios from "axios";

export default {

     AddEditDiscounts({ commit }, data) {
       return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "add-edit-discount", data,{}
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
    GetSearchProduct({ commit }, data) {
        return new Promise((resolve, reject) => {
        axios
        .post(
          API_URL + "search-product", data , {}
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
     GetSearchUser({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "cart/serach/user", data,{}
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
