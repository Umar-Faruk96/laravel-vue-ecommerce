import { data } from "autoprefixer";

const state = {
    user: {
        token: sessionStorage.getItem("auth_token") || null,
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
        total: null,
    },
    orders: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
    },
    users: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
    },
    customers: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
    },
    countries: [],
    categories: {
        loading: false,
        data: [],
    },
    dateOptions: [
        { key: "1d", value: "Last Day" },
        { key: "1w", value: "Last Week" },
        { key: "2w", value: "Last 2 Weeks" },
        { key: "1m", value: "Last Month" },
        { key: "3m", value: "Last 3 Months" },
        { key: "6m", value: "Last 6 Months" },
        { key: "1y", value: "Last Year" },
        { key: "all", value: "All Time" },
    ],
    toast: {
        visible: false,
        delay: 5000,
        percent: 0,
        interval: null,
        timeout: null,
        message: null,
    },
};

export default state;
