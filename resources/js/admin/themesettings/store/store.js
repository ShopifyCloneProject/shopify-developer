
import actions from './actions';
import getters from './getters';
import mutations from './mutations';


const  state =  {
	checkState: "Hello state from helloworld store",
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
