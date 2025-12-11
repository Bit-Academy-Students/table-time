import { createRouter, createWebHistory } from 'vue-router';

// Pagina-componenten importeren
import Home from '../pages/Home.vue';
import Reservations from '../pages/Reservations.vue';
import AllReservations from '../pages/ReservationDashboard.vue';
import Login from '../pages/Login.vue';
import ContactPage from '../pages/ContactPage.vue';
import AboutMe from '../pages/AboutUs.vue';

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
        component: ContactPage,
    },
    {
        path: '/allReservations',
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
      name: 'about-me',
      component: AboutMe,
    },
];


const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
