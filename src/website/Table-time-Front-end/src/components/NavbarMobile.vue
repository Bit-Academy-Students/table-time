<!--
/**
 * Bestandsnaam: NavbarMobile.vue
 *
 * Beschrijving:
 * Dit component toont de mobiele navigatiebalk van de applicatie.
 * De navigatiebalk bevat het bedrijfslogo en een hamburgermenu.
 * Bij het klikken op het hamburgermenu schuift het menu uit en toont
 * links naar Home, Reserveren, Over ons, Contact en Inloggen.
 *
 * Auteur: Alexander Zoet
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.2.0
 * - Laatste wijziging: 16 december 2025
 * - Beheer: Git
 */
-->
<template>
  <header class="flex justify-center">
    <nav class="max-[768px]:flex max-[768px]:justify-center min-[768px]:hidden mt-4 z-50">
      <div class="flex flex-row justify-between items-center w-full">
        <div class="flex items-center justify-between">
          <div class="w-[50px]"><img src="../assets/img/logo TableTImes.png" alt="Table Times Logo"></div>
        </div>

        <button class="--hamburgerBtn" @click="toggleMenu">
          <span class="text-black bg-black block h-1 rounded transition-all duration-300"
            :class="{ 'rotate-45 translate-y-2': isMenuOpen }"></span>
          <span class="text-black bg-black block h-1 rounded transition-all duration-300"
            :class="{ '-rotate-45 -translate-y-2': isMenuOpen }"></span>
        </button>
      </div>
    </nav>
  </header>

  <transition name="slide-side">
    <ul v-if="isMenuOpen" class="--navbarMenuMobile">
      <li><a @click="closeMenu" href="/">Home</a></li>
      <li><a @click="closeMenu" href="/RestaurantList">Restaurants</a></li>
      <li><a @click="closeMenu" href="/AboutUs">Over ons</a></li>
      <li><a @click="closeMenu" href="/contact">Contact</a></li>

      <li v-if="!isLoggedIn"><a @click="closeMenu" href="/restaurant/login">Inloggen</a></li>
      <li v-if="!isLoggedIn"><a @click="closeMenu" href="/register">Registreren</a></li>

      <li v-if="isLoggedIn && restaurantId">
        <a @click="closeMenu" :href="`/restaurant/${restaurantId}/dashboard`">Dashboard</a>
      </li>
      <li v-if="isLoggedIn">
        <a @click="closeMenu" href="#" @click.prevent="logout" class="text-red-600 hover:text-red-800">
          Uitloggen
        </a>
      </li>
    </ul>
  </transition>
</template>

<script setup>
/*
 * Importeert ref voor reactiviteit en useRouter voor navigatie
 */
import { ref } from "vue"
import { useRouter } from "vue-router"

const router = useRouter()

/*
 * Variabelen voor gebruiker en menu status
 */
const user = ref(null)
const isMenuOpen = ref(false)

/*
 * Toggle functie voor het openen en sluiten van het menu
 */
const toggleMenu = () => (isMenuOpen.value = !isMenuOpen.value)

/*
 * Sluit het mobiele menu
 */
const closeMenu = () => (isMenuOpen.value = false)
</script>
<script>
export default {
  data() {
    return {
      isLoggedIn: false,
      restaurantName: '',
      restaurantId: null
    };
  },

  mounted() {
    // Controleer inlogstatus bij laden van component
    this.checkLoginStatus();

    // Luister naar storage veranderingen (bijvoorbeeld in ander tabblad)
    window.addEventListener('storage', this.checkLoginStatus);
  },

  beforeUnmount() {
    window.removeEventListener('storage', this.checkLoginStatus);
  },

  methods: {
    /**
     * Controleert de inlogstatus van het restaurant
     * en vult relevante data zoals restaurantName en restaurantId.
     */
    checkLoginStatus() {
      const loggedIn = localStorage.getItem('isLoggedIn');
      const restaurantData = localStorage.getItem('restaurantData');

      this.isLoggedIn = loggedIn === 'true';

      if (this.isLoggedIn && restaurantData) {
        try {
          const data = JSON.parse(restaurantData);
          this.restaurantName = data.naam || '';
          this.restaurantId = data.id || null;
        } catch (e) {
          console.error('Error parsing restaurant data:', e);
          this.logout();
        }
      }
    },

    /**
     * Logt het restaurant uit door alle lokale gegevens te verwijderen
     * en stuurt de gebruiker terug naar de homepagina.
     */
    logout() {
      localStorage.removeItem('isLoggedIn');
      localStorage.removeItem('restaurantData');

      this.isLoggedIn = false;
      this.restaurantName = '';
      this.restaurantId = null;

      this.$router.push('/');
    }
  }
};
</script>
<style scoped>
/* Desktop fade/slide animatie */
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.slide-fade-enter-to,
.slide-fade-leave-from {
  opacity: 1;
  transform: translateY(0);
}

/* Mobile slide from top animatie */
.slide-side-enter-active,
.slide-side-leave-active {
  transition: all 0.3s ease;
}

.slide-side-enter-from {
  transform: translateY(-100%);
}

.slide-side-enter-to {
  transform: translateY(0);
}

.slide-side-leave-from {
  transform: translateY(0);
}

.slide-side-leave-to {
  transform: translateY(-100%);
}
</style>
