<!--
/**
 * Bestandsnaam: RestaurantList.vue
 *
 * Beschrijving:
 * Dit component is verantwoordelijk voor het ophalen en weergeven
 * van een overzichtslijst met alle geregistreerde restaurants.
 *
 * Functionaliteiten:
 * - Ophalen van restaurantdata via de backend API
 * - Weergave van een laadindicator tijdens het ophalen
 * - Navigatie naar de detail- en reserveringspagina van een restaurant
 *
 * Auteur: Alexander Zoet
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Laatste wijziging: 16 december 2025
 * - 1.0.1
  * - Beheer: Git
 */
-->

<script setup>
/*
 * Globale layoutcomponenten
 * Zorgen voor consistente navigatie en footer op de pagina
 */
import NavBar from '../components/NavBar.vue';
import NavbarMobile from '../components/NavbarMobile.vue';
import Footer from '../components/Footer.vue';
</script>


<script>
export default {
    /**
     * Componentnaam
     */
    name: 'RestaurantList',

    /**
     * Lokale state
     */
    data() {
        return {
            /*
             * Lijst met restaurants afkomstig van de backend
             */
            restaurants: [],

            /*
             * Laadstatus voor UI feedback
             */
            loading: false
        };
    },

    /**
     * Methoden
     */
    methods: {
        /**
         * Haalt alle restaurants op van de backend API
         * Zet een loading-indicator tijdens het ophalen
         */
        loadRestaurants() {
            this.loading = true;

            fetch("http://localhost:8080/Restaurants")
                .then(res => res.json())
                .then(data => {
                    /*
                     * API kan restaurants teruggeven
                     * genest onder 'Restaurants'
                     */
                    this.restaurants = data.Restaurants || [];
                })
                .catch(error => {
                    console.error("Fout bij laden restaurants:", error);
                    this.restaurants = [];
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        /**
         * Navigeert naar de detailpagina van een restaurant
         *
         * @param {number} id - ID van het geselecteerde restaurant
         */
        goToRestaurant(id) {
            this.$router.push(`/restaurant/${id}`);
        }
    },

    /**
     * Lifecycle hook
     * Wordt uitgevoerd zodra het component gemount is
     */
    mounted() {
        this.loadRestaurants();
    }
};
</script>

<template name="RestaurantList">
    <NavBar />
    <NavbarMobile />

    <main class="p-[100px]">
        <h1 class="text-3xl font-bold mb-8">Onze Restaurants</h1>

        <div v-if="loading" class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-[#02c9ef]"></div>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="restaurant in restaurants" :key="restaurant.id" @click="goToRestaurant(restaurant.id)"
                class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition cursor-pointer border-2 border-transparent hover:border-[#03CAED]">
                <h2 class="text-2xl font-bold text-[#03CAED] mb-2">{{ restaurant.naam }}</h2>
                <p class="text-gray-600 mb-1">📍 {{ restaurant.locatie }}</p>
                <p class="text-gray-600 mb-1">📞 {{ restaurant.telefoonnummer }}</p>
                <p class="text-gray-600 mb-1">👥 Max capaciteit: {{ restaurant.maxcapacity }}</p>
                <p class="text-gray-500 text-sm mb-4">✉️ {{ restaurant.email }}</p>

                <button class="w-full bg-[#03CAED] text-white py-2 rounded-lg hover:bg-[#02a8c4] transition">
                    Bekijk & Reserveer →
                </button>
            </div>
        </div>
    </main>
    <Footer />
</template>
