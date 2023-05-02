import axios from "axios";

export default {
    AddEditLocation({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "add-location", data,{}
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
    MakeDefaultAddress({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .put(
            API_URL + "default-address/" + id , {},{}
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
    GetStates({ commit }, id) {
      return new Promise((resolve, reject) => {
        axios
          .get(
            API_URL + "get-states/"+ id,{}
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
