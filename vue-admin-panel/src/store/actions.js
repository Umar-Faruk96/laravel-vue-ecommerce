import axiosClient from "../utils/axios";

export const login = async ({ commit }, user) => {
    const { data } = await axiosClient.post('/login', user);
    // console.log(data);
    commit('setUser', data.user);
    commit('setToken', data.token);
    return data;
}

export const getUser = async ({ commit }) => {
    const { data } = await axiosClient.get('/user');
    commit('setUser', data);
    return data;
}

export const logout = async ({ commit }) => {
    const response = await axiosClient.post('/logout');
    commit('setUser', {});
    commit('setToken', null);
    return response;
}

export const getProducts = async ({ commit }, { perPage, search, pageUrl = null, sortField, sortDirection } = {}) => {
    commit('setProducts', { loading: true, products: [] });

    pageUrl = pageUrl || '/products';

    try {
        const { data } = await axiosClient.get(pageUrl, { params: { per_page: perPage, search, sort_by: sortField, sort_to: sortDirection } });

        commit('setProducts', { loading: false, products: data });
        return data;
    } catch (error) {
        commit('setProducts', { loading: false, products: [] });
        // console.log(error);
    }
}

export const getProduct = async ({ commit }, id) => {
    return await axiosClient.get(`/products/${id}`);
}

export const createProduct = ({ commit }, product) => {
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

export const updateProduct = async ({ commit }, product) => {
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
        product = { ...product, _method: 'PUT' };
    }

    return await axiosClient.post(`/products/${id}`, product);
}

export const deleteProduct = async ({ commit }, id) => {
    return await axiosClient.delete(`/products/${id}`);
}