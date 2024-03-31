import axios from "axios";

axios.defaults.withCredentials = true;
axios.defaults.baseURL = import.meta.env.APP_API_URL;
// axios.defaults.headers.common['Authorization'] = `Bearer ${document.cookie.split(';')[0].split('=')[1]}`;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Access-Control-Max-Age'] = 3600;
axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';

