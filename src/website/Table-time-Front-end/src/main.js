import { createApp } from 'vue'
import App from './Home.vue'
import router from './router';


import './assets/css/main.css'



createApp(App)
  .use(router)
  .mount('#app');