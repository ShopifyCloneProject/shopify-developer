import axios from "axios";

export default {
    AddOrderCart({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "carts/add-cart", data,{}
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
    EditOrderCart({ commit }, data) {
      let id = data.cart_id;
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "carts/update-cart/" + id, data,{}
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
    GetSearchProducts({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "cart/serach/product", data,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
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
    GetState({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "get-states/" + data.country,{}
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
