import { createRouter, createWebHistory } from 'vue-router';

// Pagina-componenten importeren
import Home from '../Home.vue';
import Reservations from '../pages/Reservations.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
  },
  {
    path: '/reservations',
    name: 'reservations',
    component: Reservations,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
