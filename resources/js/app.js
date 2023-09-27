import { createApp } from 'vue';
import App from './App.vue'; // Import your root Vue component
import determineLayout from './determineLayout';
import router from './routes/router'; // Import your Vue Router configuration

const app = createApp(App);

// Mount the Vue instance to the element with the ID 'app' in your Blade template
app.use(router);
app.mount('#app');
