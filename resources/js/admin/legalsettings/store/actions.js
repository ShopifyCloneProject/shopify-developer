import axios from "axios";

export default {
   savePages({ commit }, data) {
        return new Promise((resolve, reject) => {
        axios.post(API_URL + 'update-pages', data)
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
