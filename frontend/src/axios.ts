import axios from 'axios';

axios.defaults.baseURL = 'http://localhost:8000';

const api = axios.create();

api.interceptors.request.use(config => {
  const token = localStorage.getItem('auth_token');

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
}, error => {
  return Promise.reject(error);
});

export default api;