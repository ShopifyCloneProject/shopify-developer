import axios from "axios";

export default {
    saveProduct({ commit }, data) {
      
        if(data.min_order_limit == '') { data.min_order_limit = 0; }
        if(data.weight == '') { data.weight = 0; }

        let call_url = (data.action == 'add')?'add-product':'update-product';
        
        return new Promise((resolve, reject) => {
        axios.post(API_URL + call_url, data)
          .then(response => {
                resolve(response.data);
          })
          .catch(err => {
            console.log(err);
            reject(err);
          });
      });
    },

    ProductMediaConvert({ commit }) {
     return new Promise((resolve, reject) => {
        axios.get(API_URL + 'products/import/media', {})
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
