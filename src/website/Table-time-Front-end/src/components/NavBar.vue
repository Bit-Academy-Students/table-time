<template>
  <header class="max-[768px]:hidden flex justify-center mt-4 z-100">
    <nav class="navbar">
      <ul>
        <li class="w-[50px]"><img src="../assets/img/logo TableTImes.png" alt=""></li>
      </ul>
      <ul class="flex-row flex gap-2">
        <li><a href="/">Home</a></li>
        <li><a href="/RestaurantList">Restaurants</a></li>
      </ul>
      <ul class="flex-row flex gap-2">
        <li><a href="/AboutUs">Over ons</a></li>
        <li><a href="/contact">Contact</a></li>
        
        <!-- Toon verschillende opties afhankelijk van login status -->
        <li v-if="!isLoggedIn"><a href="/restaurant/login">Inloggen</a></li>
        <li v-if="!isLoggedIn"><a href="/register">Registreren</a></li>
        
        <!-- FIX: Gebruik restaurantId in plaats van data.Restaurant.id -->
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
    this.checkLoginStatus();
    
    // Luister naar storage changes (voor als er in een ander tabblad wordt ingelogd)
    window.addEventListener('storage', this.checkLoginStatus);
  },
  beforeUnmount() {
    window.removeEventListener('storage', this.checkLoginStatus);
  },
  methods: {
    checkLoginStatus() {
      const loggedIn = localStorage.getItem('isLoggedIn');
      const restaurantData = localStorage.getItem('restaurantData');
      
      this.isLoggedIn = loggedIn === 'true';
      
      if (this.isLoggedIn && restaurantData) {
        try {
          const data = JSON.parse(restaurantData);
          this.restaurantName = data.naam || '';
          this.restaurantId = data.id || null;  // ✅ Bewaar het ID
        } catch (e) {
          console.error('Error parsing restaurant data:', e);
          // Bij een parse error, log uit voor de veiligheid
          this.logout();
        }
      }
    },
    logout() {
      // Verwijder login gegevens
      localStorage.removeItem('isLoggedIn');
      localStorage.removeItem('restaurantData');
      
      // Update de UI
      this.isLoggedIn = false;
      this.restaurantName = '';
      this.restaurantId = null;  // ✅ Reset ook het ID
      
      // Redirect naar home
      this.$router.push('/');
    }
  }
};
</script>