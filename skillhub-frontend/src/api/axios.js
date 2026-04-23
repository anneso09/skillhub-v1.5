import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Injecte le token JWT automatiquement sur chaque requête
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('skillhub_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Gère l'expiration du token (401 = déconnexion automatique)
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('skillhub_token');
      localStorage.removeItem('skillhub_user');
      window.location.href = '/';
    }
    return Promise.reject(error);
  }
);

export default api;