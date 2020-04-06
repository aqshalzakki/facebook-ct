import Vue from 'vue';
import router from './router';
import store from './store';
import App from './components/App'

import './bootstrap';

const app = new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');
