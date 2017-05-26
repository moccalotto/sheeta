import VueRouter from 'vue-router';

const routes = [
    {
        path: '/',
        component: require('./views/Home'),
    },
    {
        path: '/sheets/:id',
        component: require('./views/SheetView'),
        props: true,
    },
    {
        path: '/sheets/:id/edit',
        component: require('./views/SheetEdit'),
        props: true,
        name: 'editSheet',
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
