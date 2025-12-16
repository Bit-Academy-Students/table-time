/**
 * Bestandsnaam: router.js
 *
 * Beschrijving:
 * Dit bestand configureert de Vue Router voor de applicatie.
 * Het definieert alle beschikbare routes en koppelt deze
 * aan de bijbehorende pagina-componenten.
 *
 * Auteur: Alexander Zoet
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.0
 * - Beheer: Git
 */

import { createRouter, createWebHistory } from 'vue-router';

/*
 * Pagina-componenten
 * Elke import vertegenwoordigt een afzonderlijke view
 */
import Home from '../pages/Home.vue';
import AllReservations from '../pages/ReservationDashboard.vue';
import Login from '../pages/Login.vue';
import RestaurantLogin from '../pages/RestaurantLogin.vue';
import ContactPage from '../pages/ContactPage.vue';
import AboutMe from '../pages/AboutUs.vue';
import RestaurantInfo from '../pages/RestaurantInfo.vue';
import RestaurantList from '../pages/RestaurantList.vue';

/**
 * Routeconfiguratie
 *
 * Elke route bevat:
 * - path: URL-pad
 * - name: unieke routenaam (gebruikt voor navigatie)
 * - component: Vue component die wordt gerenderd
 */
const routes = [
    {
        /*
         * Homepage
         */
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        /*
         * Contactpagina
         */
        path: '/contact',
        name: 'contact',
        component: ContactPage,
    },
    {
        /*
         * Dashboard voor restauranteigenaren
         * Bevat een dynamische restaurant-ID
         */
        path: '/restaurant/:id/dashboard',
        name: 'all-reservations',
        component: AllReservations,
    },
    {
        /*
         * Algemene registratie / login pagina
         */
        path: '/register',
        name: 'login',
        component: Login,
    },
    {
        /*
         * Inlogpagina specifiek voor restaurants
         */
        path: '/restaurant/login',
        name: 'RestaurantLogin',
        component: RestaurantLogin,
    },
    {
        /*
         * Over ons pagina
         */
        path: '/aboutUs',
        name: 'about-me',
        component: AboutMe,
    },
    {
        /*
         * Overzichtspagina met alle restaurants
         */
        path: '/RestaurantList',
        name: 'RestaurantList',
        component: RestaurantList,
    },
    {
        /*
         * Detailpagina van een specifiek restaurant
         * Wordt gebruikt voor informatie en reserveringen
         */
        path: '/restaurant/:id',
        name: 'restaurantInfo',
        component: RestaurantInfo,
    },
];

/**
 * Router instantie
 *
 * - createWebHistory: gebruikt de HTML5 history API
 * - routes: hierboven gedefinieerde routes
 */
const router = createRouter({
    history: createWebHistory(),
    routes,
});

/*
 * Exporteer router zodat deze kan worden gebruikt
 * in main.js
 */
export default router;
