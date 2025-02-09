import axios from 'axios';
window.axios = axios;

// Get the CSRF token from the meta tag
const token = document.head.querySelector('meta[name="csrf-token"]')?.content;

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
} else {
    console.error('CSRF token not found');
}

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
