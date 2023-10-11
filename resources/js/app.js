import { createApp } from 'vue';
import App from './App.vue'; // Import your root Vue component
import router from './routes/router'; // Import your Vue Router configuration
import store from './store';

const app = createApp(App);

// Mount the Vue instance to the element with the ID 'app' in your Blade template
app.use(router);
app.use(store);

app.mount('#app');
