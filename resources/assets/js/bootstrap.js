// Import Axios for async http
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest'
};

// Import Moment lib for nice and relative dates.
import moment from 'moment';
window.moment = moment;

// Import slug for slugging strings for prettier urls
import slug from 'slug';
window.slug = slug;


// Import Vue to create our awesome spa.
import Vue from 'vue';
import VueRouter from 'vue-router';
import SheetCard from './components/SheetCard';
import RelativeDate from './components/RelativeDate';


window.Vue = Vue;
window.Vue.use(VueRouter);
Vue.component('sheet-card', SheetCard);
Vue.component('relative-date', RelativeDate);


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
