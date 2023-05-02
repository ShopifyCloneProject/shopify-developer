import axios from "axios";

export default {
    AddTheme({ commit }, data) {
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "themes/add-theme", data,{}
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
    EditTheme({ commit }, data) {
      let id = data.id;
      return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "themes/update-theme/" + id ,data,{}
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
    AddEditThemeSelection({ commit }, data) {
       return new Promise((resolve, reject) => {
        axios
          .post(
            API_URL + "storeupdateselected-theme", data,{}
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
