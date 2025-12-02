import { createRouter, createWebHistory } from 'vue-router';

// Pagina-componenten importeren
import Home from '../Home.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/contact',
        name: 'home',
        component: Home,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
