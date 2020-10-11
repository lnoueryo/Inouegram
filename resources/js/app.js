require('./bootstrap');

window.Vue = require('vue');
const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

// VueRouter
import VueRouter from 'vue-router';
import router from './routes';

// Vuex
import Vuex from 'vuex';
import store from './store/index.js';

// Vuetify
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';

// Vuetify Icon
import '@mdi/font/css/materialdesignicons.css'
import '@fortawesome/fontawesome-free/css/all.css'
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(Vuetify);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('profile', require('./components/pages/Profile.vue').default);
Vue.component('index', require('./components/pages/Index.vue').default);
Vue.component('post', require('./components/pages/Post.vue').default);
Vue.component('practice', require('./components/pages/Practice.vue').default);
Vue.component('crop', require('./components/organisms/Crop.vue').default);
Vue.component('header-bar', require('./components/globals/Header.vue').default);



const app = new Vue({
    el: '#app',
    router: router,
    store: store,
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi',
            iconfont: 'fa'
        }
    })
});
