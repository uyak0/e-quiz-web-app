import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.config.globalProperties.getStorageItem = (key) => {
  if (localStorage.getItem(key)) {
    return localStorage.getItem(key)
  } else {
    return sessionStorage.getItem(key)
  }
}

app.mount('#app')
