import axios from "axios";

export default {
   loadMoreProduct({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "product/load/"+ data.number + '/' + data.search,{}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    GetCollectionProducts({ commit }, id) {
      let url = '';
      if(typeof id != 'undefined'){
        url = API_URL + "xmlfeed/products/all/" + id;
      } else {
        url = API_URL + "xmlfeed/products/all/";
      }

      return new Promise((resolve, reject) => {
        axios
          .get(
            url, {}
          )
          .then(response => {
              resolve(response.data);
          })
          .catch(err => {
            reject(err);
          });
      });
    },
    saveDefaultXML({ commit }, data) {
        return new Promise((resolve, reject) => {
        axios.post(API_URL + 'defaultxml/save', data)
          .then(response => {
                resolve(response.data);
          })
          .catch(err => {
            console.log(err);
            reject(err);
          });
      });
    },
    generateXML({ commit }, payload) {
        return new Promise((resolve, reject) => {
        axios.post(API_URL + 'xmlfeed/create', payload)
          .then(response => {
                resolve(response.data);
          })
          .catch(err => {
            console.log(err);
            reject(err);
          });
      });
    },
    // saveXMLFeedDetails({ commit }, payload) {
    //     return new Promise((resolve, reject) => {
    //     axios.post(API_URL + 'xmlfeed/add', payload)
    //       .then(response => {
    //             resolve(response.data);
    //       })
    //       .catch(err => {
    //         console.log(err);
    //         reject(err);
    //       });
    //   });
    // },
   
}
