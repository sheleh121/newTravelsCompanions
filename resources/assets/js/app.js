
require('./bootstrap');

import App from './views/App'
import Auth from './auth.js';
import Echo from "laravel-echo"
import VueRouter from 'vue-router';
import router from './routes.js';
import Api from './api.js';
import Global from './global.js';
import VueAuthImage from 'vue-auth-image';


window.Vue = require('vue');
window.api = new Api();
window.auth = new Auth();
window.io = require('socket.io-client');
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
    auth: {headers: {Authorization: "Bearer " + auth.token}}
});
Vue.use(VueRouter);
Vue.use(VueAuthImage);
window.Event = new Vue;


Vue.component('location-component', require('./components/LocationComponent.vue'));

Vue.component('travel-user-component', require('./components/travels/edit/TravelUserComponent.vue'));

Vue.component('travel-component', require('./components/travels/TravelComponent.vue'));

Vue.component('register-component', require('./components/auth/RegisterComponent.vue'));
Vue.component('forgot-component', require('./components/auth/ForgotPasswordComponent.vue'));

Vue.component('chat-messages-component', require('./components/chat/MessagesComponent.vue'));


Vue.component('images-component', require('./components/images/ImagesComponent.vue'));
Vue.component('images-carousel-component', require('./components/images/CarouselComponent.vue'));

Vue.directive('phone', {
    bind(el) {
        el.oninput = function(e) {
            if (!e.isTrusted) {
                return;
            }
            let x = this.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            this.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');

        }
    }
});

const app = new Vue({
    el: '#app',
    data: Global,
    components: { App },
    router,
});


