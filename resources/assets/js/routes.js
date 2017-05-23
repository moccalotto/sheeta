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
    },
    {
        path: '/sheets/:id/row/:tableIdx/:rowIdx',
        component: require('./views/Dump'),
        props: true,
        name: 'rowEdit',
    }
];

export default new VueRouter({
    routes,
    linkActiveClass: 'is-active'
});
