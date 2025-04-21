const state = {
    user: {
        token: sessionStorage.getItem('auth_token') || null,
        data: {},
    },
    products: {
        loading: false,
        data: [],
        links: [],
        page: 1,
        limit: null,
        from: null,
        to: null,
        total: null
    },
    orders: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
};

export default state;