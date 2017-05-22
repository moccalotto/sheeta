import VueRouter from 'vue-router';

const routes = [
    {
        path: '/',
        component: require('./views/Home'),
    },
    {
        path: '/sheets/:id/:slug',
        component: require('./views/Sheet'),
        props: true,
    }
];

export default new VueRouter({
    routes,
    linkActiveClass: 'is-active'
});
