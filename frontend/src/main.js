import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import useComponents from './utils/useComponents';

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
// state management
import { createPinia } from 'pinia';
// axios config
import '@/utils/useAxios';
// vuetify
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as directives from 'vuetify/directives';
import * as components from 'vuetify/components';
import '@mdi/font/css/materialdesignicons.css';


const app = createApp(App);

const pinia = createPinia();

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
    }
});

Object.values(useComponents).forEach(component => {
    app.component(component.name ?? component.__name, component);
})

app.use(router).use(pinia).use(vuetify).mount('#app')