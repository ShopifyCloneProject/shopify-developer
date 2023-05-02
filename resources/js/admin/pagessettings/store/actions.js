import axios from "axios";

export default {
    saveHomePages({ commit }, data) {
        return new Promise((resolve, reject) => {
        axios.post(API_URL + 'settings/home/save', data)
          .then(response => {
                resolve(response.data);
          })
          .catch(err => {
            console.log(err);
            reject(err);
          });
      });
    },
    saveLevelPage({ commit }, data) {
        return new Promise((resolve, reject) => {
        axios.post(API_URL + 'settings/page/level', data)
          .then(response => {
                resolve(response.data);
          })
          .catch(err => {
            console.log(err);
            reject(err);
          });
      });
    },
     getSearchProduct({ commit }, data) {
        return new Promise((resolve, reject) => {
        axios.post(API_URL + 'search-product', data)
          .then(response => {
                resolve(response.data);
          })
          .catch(err => {
            console.log(err);
            reject(err);
          });
      });
    },
   
    //product detail page
    saveDetailPageSettings({ commit }, payload) {
        return new Promise((resolve, reject) => {
        axios.post(API_URL + 'settings/productdetail/save', payload)
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            console.log(err);
            reject(err);
          });
      });
    },
    saveMenu({ commit }, data) {
        return new Promise((resolve, reject) => {
        axios.post(API_URL + 'settings/menu/save', data)
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
