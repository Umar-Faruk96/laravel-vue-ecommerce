import axiosClient from "../utils/axios";

export const setUser = (state, user) => {
    // debugger;
    state.user.data = user;
}

export const setToken = (state, token) => {
    state.user.token = token;

    if (token) {
        axiosClient.defaults.headers.common.Authorization = `Bearer ${token}`;
        sessionStorage.setItem('auth_token', token);
    } else {
        delete axiosClient.defaults.headers.common.Authorization;
        sessionStorage.removeItem('auth_token');
    }
}

export const setProducts = (state, { loading, products }) => {
    state.products.loading = loading;
    state.products.data = products.data;
    state.products.meta = products.meta;
}