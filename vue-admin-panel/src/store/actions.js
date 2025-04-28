import axiosClient from "../utils/axios";

export const login = async ({commit}, user) => {
    const {data} = await axiosClient.post('/login', user);
    // console.log(data);
    commit('setUser', data.user);
    commit('setToken', data.token);

    return data;
}

export const getUser = async ({commit}) => {
    const {data} = await axiosClient.get('/user');
    commit('setUser', data);

    return data;
}

export const logout = async ({commit}) => {
    const response = await axiosClient.post('/logout');
    commit('setUser', {});
    commit('setToken', null);

    return response;
}

export const getProducts = async ({commit, state}, {url = null, search = '', per_page, sort_by, sort_to} = {}) => {
    commit('setProducts', [true]);

    url = url || '/products';

    const params = {
        per_page: state.products.limit,
    }

    try {
        const {data} = await axiosClient.get(url, {params: {...params, per_page, search, sort_by, sort_to}});
        // console.log(data);
        commit('setProducts', [false, data]);

        return data;
    } catch (error) {
        commit('setProducts', [false]);
        // console.log(error);
    }
}

/*export const getProduct = async ({ commit }, id) => {
    return await axiosClient.get(`/products/${id}`);
}*/

export const createProduct = ({commit}, product) => {
    // debugger;
    if (product.image instanceof File) {
        const formData = new FormData();
        formData.append('title', product.title);
        formData.append('description', product.description);
        formData.append('image', product.image);
        formData.append('price', product.price);
        product = formData;
    }

    return axiosClient.post('/products', product);
}

export const updateProduct = async ({commit}, product) => {
    const id = product.id;

    if (product.image instanceof File) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('title', product.title);
        formData.append('description', product.description);
        formData.append('image', product.image);
        formData.append('price', product.price);
        formData.append('_method', 'PUT');
        product = formData;
    } else {
        product = {...product, _method: 'PUT'};
    }

    return await axiosClient.post(`/products/${id}`, product);
}

export const deleteProduct = async ({commit}, id) => {
    return await axiosClient.delete(`/products/${id}`);
}

export const getOrders = async ({commit, state}, {url = null, search = '', per_page, sort_by, sort_to} = {}) => {
    commit('setOrders', [true]);

    url = url || '/orders';

    const params = {
        per_page: state.orders.limit,
    }

    try {
        const {data} = await axiosClient.get(url, {params: {...params, per_page, search, sort_by, sort_to}});
        // console.log(data);
        commit('setOrders', [false, data]);

        return data;
    } catch (error) {
        commit('setOrders', [false]);
        // console.log(error);
    }
}

export const getOrder = async ({commit}, id) => {
    return await axiosClient.get(`/orders/${id}`);
}

export const getUsers = async ({commit, state}, {url = null, search = '', per_page, sort_by, sort_to} = {}) => {
    commit('setUsers', [true]);

    url = url || '/users';

    const params = {
        per_page: state.users.limit,
    }

    try {
        const {data} = await axiosClient.get(url, {params: {...params, per_page, search, sort_by, sort_to}});
        commit('setUsers', [false, data]);

        return data;
    } catch (error) {
        commit('setUsers', [false])
    }
}

/*
export const getSelectedUser = async ({commit}, id) => {
    return await axiosClient.get(`/users/${id}`);
}*/

export const createUser = async ({commit}, user) => {
    return axiosClient.post('/users', user)
}

export const updateUser = async ({commit}, user) => {
    return axiosClient.put(`/users/${user.id}`, user)
}

export const deleteUser = async ({commit}, id) => {
    return axiosClient.delete(`/users/${id}`)
}