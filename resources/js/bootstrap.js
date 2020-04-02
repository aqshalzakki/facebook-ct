window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {}

window.axios = require('axios');
window.axios.defaults.baseURL = 'http://localhost:8000/api'
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
