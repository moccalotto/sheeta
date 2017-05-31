import './bootstrap';
import router from './routes';

window.eventBus = new Vue({
    data: {
        user: null,
        loggedIn: false,
    },
});

window.eventBus.$on('auth.state', (user) => {
    console.log(user);
    eventBus.user = user;
    eventBus.loggedIn = !! user;
});

window.flash = (type, message, timeout) =>  {
    window.eventBus.$emit('flash', { type, message, timeout });
};

const app = new Vue({
    el: '#app',

    router
});
