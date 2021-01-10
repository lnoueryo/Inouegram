import Vue from 'vue';
import Vuex from 'vuex';
// import profile from './modules/profile';
import {profile} from './modules/profile.ts';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        profile,
    }
});
