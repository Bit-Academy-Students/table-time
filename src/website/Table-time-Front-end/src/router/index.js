import { createRouter, createWebHistory } from 'vue-router';

// Pagina-componenten importeren
import Home from '../pages/Home.vue';
import Reservations from '../pages/Reservations.vue';
import AllReservations from '../pages/ReservationDashboard.vue';
import Login from '../pages/Login.vue';
<<<<<<< HEAD
import ContactPage from '../pages/ContactPage.vue';

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
        path: '/contact',
        name: 'contact',
        component: Reservations,
    },
    {
        path: '/all-reservations',
        name: 'all-reservations',
        component: AllReservations,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/ContactPage',
        name: 'ConactPage',
        component: ContactPage,
    },
=======
import AboutUs from '../pages/AboutUs.vue';
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
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/aboutUs',
    name: 'aboutUs',
    component: AboutUs,
  }
>>>>>>> 02792a7abfe425c03ae9e283254896cdddeabdd6
];


const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
