import { createRouter, createWebHistory } from 'vue-router';

// Pagina-componenten importeren
import Home from '../pages/Home.vue';
import Reservations from '../pages/Reservations.vue';
import AllReservations from '../pages/AllReservations.vue';

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
  {
    path: '/all-reservations',
    name: 'all-reservations',
    component: AllReservations,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
