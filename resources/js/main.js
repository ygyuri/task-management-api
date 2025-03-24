import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { createPinia } from 'pinia';
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/reset.css';
import Notifications from '@kyvg/vue3-notification';


// Create Vue app instance
const app = createApp(App);

// Use necessary plugins
app.use(router);
app.use(createPinia());
app.use(Antd);
app.use(Notifications); // Alternative to vue-toastification

// Mount the app to #app in welcome.blade.php
app.mount('#app');

