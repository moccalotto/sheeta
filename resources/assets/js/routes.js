import VueRouter from 'vue-router';

const routes = [
    {
        path: '/',
        component: require('./views/SheetIndex'),
    },
    {
        path: '/sheets/:id',
        components: {
            default: require('./views/SheetView'),
            tabs: require('./views/SheetTabs'),
        },
        props: { default: true, tabs: true },
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
