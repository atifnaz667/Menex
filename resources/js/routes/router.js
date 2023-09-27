import { createRouter, createWebHistory } from "vue-router";
import AlternativeLayout from '../layouts/AlternativeLayout.vue';
import DefaultLayout from '../layouts/DefaultLayout.vue';

const routes = [
    {
        path: "/",
        component: DefaultLayout, // Use the default layout for the home route
        children: [
            {
                path: "",
                component: () => import("../pages/Home.vue"),
                meta: {layout: 'default', title: "Home" },
            },
            {
                path: "/about",
                component: () => import("../pages/About.vue"),
                meta: {layout: 'default', title: "About" },
            },
            // Add more routes with DefaultLayout here
        ],
    },
    {
        path: "/alternative",
        component: AlternativeLayout, // Use the 'AlternativeLayout.vue' component for the 'alternative' layout
        children: [
            {
                path: "",
                component: () => import("../pages/AlternativeHome.vue"),
                meta: {layout: 'alternative', title: "Alternative Home" },
            },
            // Add more routes with AlternativeLayout here
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
