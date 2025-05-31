import {createRouter, createWebHistory} from 'vue-router';
import store from '../store';

const routes = [
    {
        path: '/',
        redirect: {name: 'app.dashboard'},
    },
    {
        path: '/app',
        name: 'app',
        redirect: {name: 'app.dashboard'},
        component: () => import('../components/AppLayout.vue'),
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: 'dashboard',
                name: 'app.dashboard',
                component: () => import('../views/Dashboard.vue'),
            },
            {
                path: 'products',
                name: 'app.products',
                component: () => import('../views/Products/Products.vue'),
            },
            {
                path: 'orders',
                name: 'app.orders',
                component: () => import('../views/Orders/Orders.vue'),
            },
            {
                path: 'orders/:id',
                name: 'app.orders.show',
                component: () => import('../views/Orders/OrderDetails.vue'),
            },
            {
                path: 'users',
                name: 'app.users',
                component: () => import('../views/Users/Users.vue'),
            }, {
                path: 'customers',
                name: 'app.customers',
                component: () => import('../views/Customers/Customers.vue'),
            },
            {
                path: 'customers/:id',
                name: 'app.customers.show',
                component: () => import('../views/Customers/CustomerDetails.vue'),
            },
            {
                path: 'reports',
                name: 'app.reports',
                component: () => import('../views/Reports.vue'),
            },
        ]
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../views/Login.vue'),
        meta: {
            requiresGuest: true,
        }
    },
    {
        path: '/request-password/:token',
        name: 'RequestPassword',
        component: () => import('../views/RequestPassword.vue'),
        meta: {
            requiresGuest: true,
        }
    },
    {
        path: '/forgot-password/:token',
        name: 'ForgotPassword',
        component: () => import('../views/ForgotPassword.vue'),
        meta: {
            requiresGuest: true,
        }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: () => import('../views/NotFound.vue'),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, _from, next) => {
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({name: 'Login'});
    } else if (to.meta.requiresGuest && store.state.user.token) {
        next({name: 'app.dashboard'});
    } else {
        next();
    }
});

export default router;