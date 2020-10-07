import Vue from 'vue';
import Vuex from 'vuex';
import profile from './modules/profile';
import index from './modules/index';
import header from './modules/header';
import post from './modules/post';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        profile,
        index,
        header,
        post,
    }
});
