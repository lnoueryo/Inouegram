import VueRouter, { RouteConfig } from 'vue-router'
// import Profile from './components/pages/Profile.vue';

const routes: Array<RouteConfig> = [
    // {
    //     path: '/index',
    //     component: Profile,
    //     name: 'my-profile'
    // },
    // {
    //     path: '/index2',
    //     component: Footer,
    //     name: 'home2'
    // },
];

const router = new VueRouter({
    routes: routes,
    mode: 'history',
});

export default router;
