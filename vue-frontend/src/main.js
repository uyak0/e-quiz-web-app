import './assets/main.css'


import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
window.Pusher = Pusher;



const app = createApp(App)

app.use(createPinia())
app.use(router)

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.defaults.xsrfCookieName = 'XSRF-TOKEN';
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN';


app.mount('#app')



