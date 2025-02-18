import axios from 'axios';
import store from '../store';
import router from '../router';

const axiosClient = axios.create({
    baseURL: `${import.meta.env.VITE_API_BASE_URL}/api`,
});

axiosClient.interceptors.request.use(config => {
    config.headers.Authorization = `Bearer ${store.state.user.token}`;
    return config;
});

axiosClient.interceptors.response.use(
    response => response,
    error => {
        // console.log(error.response);
        if (error.response.status === 401) {
            store.commit('setToken', null);
            router.push({ name: 'Login' });
        }
        throw error.response;
    }
);

export default axiosClient;