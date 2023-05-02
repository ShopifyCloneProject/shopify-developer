import axios from "axios";

export default {
   AddNotification({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "notifications/store", data,{}
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
    EditNotification({ commit }, data) {
      let id = data.id;
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "notifications/update/" + id ,data,{}
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
    SaveNotification({ commit }, data) {
        return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "settings/notificationdetailssave" , data,{}
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
    RevertNotification({ commit }, data) {
        return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "settings/notificationreverttodefault" , data,{}
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
