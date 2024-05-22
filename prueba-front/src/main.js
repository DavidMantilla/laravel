import { createApp } from 'vue'
import store from './store/index'
import App from './App.vue'
import router from './router/index'




createApp(App)
.use(router)
.use(store)
.mount('#app')

