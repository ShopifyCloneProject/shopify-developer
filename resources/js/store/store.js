import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const state = {
  errors: false,
  cart: {
        products: [],
        subTotal: 0,
        taxAmount: 0,
        shippingAmount: 0,
        voucherAmount:0,
        total: 0
      },
  user: [],
  no_image: '/assets/images/no-image.jpg',
  wishlistTotal:0
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations,
};
