import { createRouter, createWebHistory } from 'vue-router';

// Pagina-componenten importeren
import Home from '../pages/Home.vue';
import Reservations from '../pages/Reservations.vue';
import Login from '../pages/Login.vue';

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
    path: '/login',
    name: 'login',
    component: Login,
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
