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

export const setUsers = (state, [loading, data = null]) => {
    if (data) {
        state.users = {
            ...state.users,
            data: data.data,
            links: data.meta?.links,
            page: data.meta.current_page,
            limit: data.meta.per_page,
            from: data.meta.from,
            to: data.meta.to,
            total: data.meta.total
        }
    }

    state.users.loading = loading;
}

export const setCustomers = (state, [loading, data = null]) => {
    if (data) {
        state.customers = {
            ...state.customers,
            data: data.data,
            links: data.meta?.links,
            page: data.meta.current_page,
            limit: data.meta.per_page,
            from: data.meta.from,
            to: data.meta.to,
            total: data.meta.total
        }
    }

    state.customers.loading = loading;
}

export const setCountries = (state, data = null) => {
    if (data) {
        state.countries = data.data;
    }
}

export const showToast = (state, message) => {
    state.toast.visible = true;
    state.toast.message = message;
}

export const closeToast = (state) => {
    state.toast.visible = false;
    state.toast.timeout = null;
    state.toast.percent = 0;
    clearInterval(state.toast.interval);
    state.toast.message = '';
}