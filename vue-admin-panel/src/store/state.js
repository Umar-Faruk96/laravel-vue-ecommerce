const state = {
    user: {
        token: sessionStorage.getItem('auth_token') || null,
        data: {},
    },
    products: { loading: false, data: [], meta: {} },
};

export default state;