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
            tabs: {
                template: `<div class="hero-foot">
                <div class="container">
                    <nav class="tabs is-boxed">
                        <ul>
                            <li class="is-active">
                                <a href="#">View</a>
                            </li>
                            <li>
                                <a href="#">Design</a>
                            </li>
                            <li>
                                <a href="#">Settings</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>`
            }
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
