import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import useComponents from './utils/useComponents';
import { createPinia } from 'pinia';
import '@/utils/useAxios';
import 'bootstrap/dist/css/bootstrap.css';

const app = createApp(App);

const pinia = createPinia();

Object.values(useComponents).forEach(component => {
    app.component(component.name ?? component.__name, component);
})

app.use(router).use(pinia).mount('#app')