import VueRouter from 'vue-router';

const routes = [
    {
        path: '/',
        component: require('./views/Home'),
    },
    {
        path: '/sheets/:id',
        component: require('./views/Sheet'),
        props: true,
    },
    {
        path: '/users/:username',
        component: require('./views/Dump'),
        props: true,
    }
];

export default new VueRouter({
    routes,
    linkActiveClass: 'is-active'
});
