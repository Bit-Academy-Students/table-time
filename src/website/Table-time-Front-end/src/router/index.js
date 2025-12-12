import { createRouter, createWebHistory } from 'vue-router';

// Pagina-componenten importeren
import Home from '../pages/Home.vue';
import AllReservations from '../pages/ReservationDashboard.vue';
import Login from '../pages/Login.vue';
import RestaurantLogin from '../pages/RestaurantLogin.vue';
import ContactPage from '../pages/ContactPage.vue';
import AboutMe from '../pages/AboutUs.vue';
import RestaurantInfo from '../pages/RestaurantInfo.vue';
import RestaurantList from '../pages/RestaurantList.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/contact',
        name: 'contact',
        component: ContactPage,
    },
    {
        path: '/restaurant/:id/dashboard',
        name: 'all-reservations',
        component: AllReservations,
    },
    {
        path: '/register',
        name: 'login',
        component: Login,
    },
    {
        path: '/restaurant/login',
        name: 'RestaurantLogin',
        component: RestaurantLogin,
    },
    {
      path: '/aboutUs',
      name: 'about-me',
      component: AboutMe,
    },
    {
      path: '/RestaurantList',
      name: 'RestaurantList',
      component: RestaurantList,
    },
    {
      path: '/restaurant/:id',
      name: 'restaurantInfo',
      component: RestaurantInfo,
    },
];


const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
