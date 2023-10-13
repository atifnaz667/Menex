import { createRouter, createWebHistory } from "vue-router";
import WebsiteLayout from '../layouts/WebsiteLayout.vue';
import PanelsLayout from '../layouts/PanelsLayout.vue';
import BlankLayout from '../layouts/BlankLayout.vue';

const routes = [

    {
        path: "/",
        component: WebsiteLayout,
        children: [
            {
                path: "/",
                component: () => import("../pages/Home.vue"),
                meta: {layout: 'WebsiteLayout', title: "Home" },
            },

        ],
    },
    {
        path: "/",
        component: PanelsLayout,
        children: [

            {
                path: "/dashboard",
                component: () => import("../pages/panels/Dashboard.vue"),
                meta: {layout: 'PanelsLayout', title: "Dashboard" , requiresAuth: true },
            },
            {
                path: "/categories",
                component: () => import("../pages/category/List.vue"),
                meta: {layout: 'PanelsLayout', title: "Categories Page" },
            },
            {
                path: "/add/blog",
                component: () => import("../pages/blog/Add.vue"),
                meta: {layout: 'PanelsLayout', title: "Blogs Page" },
            },

        ],
    },
    {
        path: "/",
        component: BlankLayout,
        children: [

            {
                path: "/login",
                component: () => import("../pages/auth/Login.vue"),
                meta: {layout: 'BlankLayout', title: "About", requiresAuth: false },
            },

        ],
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    let accessToken = localStorage.getItem('accessToken');
    let isAuthenticated = false/* Check if the user is authenticated */;
    if (accessToken) {
        isAuthenticated = true;
    }

    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
    }else if (to.path === '/login' && isAuthenticated) {
        // If the user is already authenticated and tries to access the login page, redirect to dashboard
        next('/dashboard');
    } else {
      next(); // Proceed to the next route
    }
});


export default router;
