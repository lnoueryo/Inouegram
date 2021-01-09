import Vue from 'vue';
import Vuex from 'vuex';
// import profile from './modules/profile';
import {header} from './modules/header.ts';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        header,
    }
});
