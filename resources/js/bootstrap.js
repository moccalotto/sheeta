// Import Axios for async http
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
};

// Import Moment lib for nice and relative dates.
import moment from 'moment';
window.moment = moment;

import lodash from 'lodash';
window._ = lodash;

// Import Vue to create our awesome spa.
import Vue from 'vue';
import VueRouter from 'vue-router';

window.Vue = Vue;
window.Vue.use(VueRouter);
Vue.component('relative-date', require('./components/RelativeDate'));
Vue.component('item-paginator', require('./components/ItemPaginator'));
Vue.component('flash-list', require('./components/FlashList'));
Vue.component('auth-box', require('./components/AuthBox'));


Vue.component('pulse-loader', require('vue-spinner/src/PulseLoader'));


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
