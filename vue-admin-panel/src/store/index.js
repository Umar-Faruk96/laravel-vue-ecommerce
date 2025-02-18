import { createStore } from "vuex";
import * as actions from './actions';
import * as mutations from './mutations';
import state from './state';
// import * as getters from './getters';

const store = createStore({
    state,
    mutations,
    actions,
    getters: {}
});

export default store;