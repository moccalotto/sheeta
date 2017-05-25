import './bootstrap';
import router from './routes';

window.eventBus = new Vue();

window.flash = (type, message, timeout) =>  {
    window.eventBus.$emit('flash', { type, message, timeout });
};

const app = new Vue({
    el: '#app',

    router
});
