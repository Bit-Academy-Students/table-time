import { createRouter, createWebHistory } from 'vue-router';

// Pagina-componenten importeren
import Home from '../pages/Home.vue';
import Reservations from '../pages/Reservations.vue';
import DashboardReservations from '../pages/ReservationDashboard.vue';

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
    path: '/dashboard/reservations',
    name: 'reservation',
    component: DashboardReservations,
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
