<!--
/**
 * Bestandsnaam: NavBar.vue
 *
 * Beschrijving:
 * Dit component toont de desktop navigatiebalk van de applicatie.
 * De navigatiebalk bevat het bedrijfslogo, links naar belangrijke pagina's
 * en toont dynamisch login/registratie of dashboard/uitloggen afhankelijk van
 * de inlogstatus van een restaurant.
 *
 * Auteur: Alexander Zoet
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.0
 * - Laatste wijziging: <datum invullen>
 * - Beheer: Git
 */
-->
<template>
  <header class="max-[768px]:hidden flex justify-center mt-4 z-100">
    <nav class="navbar">
      <ul>
        <li class="w-[50px]"><img src="../assets/img/logo TableTImes.png" alt="Table Times Logo"></li>
      </ul>

      <ul class="flex-row flex gap-2">
        <li><a href="/">Home</a></li>
        <li><a href="/RestaurantList">Restaurants</a></li>
      </ul>

      <ul class="flex-row flex gap-2">
        <li><a href="/AboutUs">Over ons</a></li>
        <li><a href="/contact">Contact</a></li>

        <li v-if="!isLoggedIn"><a href="/restaurant/login">Inloggen</a></li>
        <li v-if="!isLoggedIn"><a href="/register">Registreren</a></li>

        <li v-if="isLoggedIn && restaurantId">
          <a :href="`/restaurant/${restaurantId}/dashboard`">Dashboard</a>
        </li>
        <li v-if="isLoggedIn">
          <a href="#" @click.prevent="logout" class="text-red-600 hover:text-red-800">
            Uitloggen
          </a>
        </li>
      </ul>
    </nav>
  </header>
</template>

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
