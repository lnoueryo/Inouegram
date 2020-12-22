import Vue from 'vue';
import bootstrap from './bootstrap';
bootstrap();

// VueRouter
import VueRouter from 'vue-router';
import router from './routes';

// Vuex
import Vuex from 'vuex';
import store from './store/index';

// Vuetify
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';

// Vuetify Icon
import '@mdi/font/css/materialdesignicons.css'
// import '@fortawesome/fontawesome-free/css/all.css'
// import VueSocialauth from 'vue-social-auth'

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
Vue.component('header-bar2', require('./components/globals/Header2.vue').default);
Vue.component('login', require('./components/molecules/Login.vue').default);
Vue.component('reset-password', require('./components/molecules/ResetPassword.vue').default);
Vue.component('dashboard', require('./components/molecules/Dashboard.vue').default);




const app: any = new Vue({
    el: '#app',
    router: router,
    store: store,
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi',
            // iconfont: 'fa'
        }
    }),
});

// Vue.use(VueSocialauth, {
//     providers: {
//       google: {
//         clientId: '',
//         redirectUri: '/auth/github/callback' // Your client app URL
//       }
//     }
//   })
