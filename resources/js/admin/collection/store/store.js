
import actions from './actions';
import getters from './getters';
import mutations from './mutations';


const  state =  {
    altImage: '/assets/images/no-image.jpg',
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
