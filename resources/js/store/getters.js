let API_AUTH_URL = API_URL + 'auth';

export default {
    getUser(state) {
        return state.user;
    },
    getApiUrl(state){
        if(Object.keys(state.user).length > 0){
            return API_AUTH_URL;
        } else {

            return API_URL;
        }
    },
    getDefaultUrl(){
        return API_URL;
    }
   // cartTotal(state){
   //     let total = 0;
   //     state.carts.forEach((item, i) => {
   //          total += item.productPrice * item.quantity;
   //     });
   //     return total;
   // }


}
