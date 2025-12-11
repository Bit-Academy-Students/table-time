<!-- ========================================
     1. RESTAURANT LIJST COMPONENT
     ======================================== -->
<!-- Bestand: views/RestaurantList.vue -->
<template name="RestaurantList">
    <NavBar />
    <NavbarMobile />
    
    <main class="p-[100px]">
      <h1 class="text-3xl font-bold mb-8">Onze Restaurants</h1>
      
      <!-- Loading state -->
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-[#02c9ef]"></div>
      </div>
      
      <!-- Restaurant grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="restaurant in restaurants" 
          :key="restaurant.id"
          @click="goToRestaurant(restaurant.id)"
          class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition cursor-pointer border-2 border-transparent hover:border-[#03CAED]"
        >
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
  </template>
  
  <script>
  // RestaurantList.vue script
  export default {
    name: 'RestaurantList',
    data() {
      return {
        restaurants: [],
        loading: false
      };
    },
    methods: {
      loadRestaurants() {
        this.loading = true;
        fetch("http://localhost:8080/Restaurants")
          .then(res => res.json())
          .then(data => {
            this.restaurants = data.Restaurants || [];
          })
          .catch(e => {
            console.error("Fout bij laden restaurants:", e);
            this.restaurants = [];
          })
          .finally(() => {
            this.loading = false;
          });
      },
      goToRestaurant(id) {
        this.$router.push(`/restaurant/${id}`);
      }
    },
    mounted() {
      this.loadRestaurants();
    }
  };
  </script>