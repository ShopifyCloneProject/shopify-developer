import axios from "axios";

export default {
    AddCollection({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "add-collection", data,{}
          )
          .then(response => {
            	resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    EditCollection({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .put(
            API_URL + "edit-collection", data,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    GetSortProducts({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "get-sort-products/"+ data.id +'/'+ data.sortType,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    GetConditionProducts({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "get-collection-products", data,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ChangeSortOrder({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "change-order", data,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    loadMoreProduct({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "collection/product/load/"+ data.number + '/' + data.search,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    AddProduct({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "collection/product/add/", data, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    ShowAllProducts({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "collection/product/all/" + id, {}
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
