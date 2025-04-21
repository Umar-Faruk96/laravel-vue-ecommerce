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

export const setProducts = (state, [loading, data = null]) => {
    if (data) {
        state.products = {
            ...state.products,
            data: data.data,
            links: data.meta?.links,
            page: data.meta.current_page,
            limit: data.meta.per_page,
            from: data.meta.from,
            to: data.meta.to,
            total: data.meta.total
        }
    }

    state.products.loading = loading;
}

export const setOrders = (state, [loading, data = null]) => {
    if (data) {
        state.orders = {
            ...state.orders,
            data: data.data,
            links: data.meta?.links,
            page: data.meta.current_page,
            limit: data.meta.per_page,
            from: data.meta.from,
            to: data.meta.to,
            total: data.meta.total
        }
    }

    state.orders.loading = loading;
}