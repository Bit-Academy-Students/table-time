<script>
import NavBar from "../components/NavBar.vue";
import NavbarMobile from "../components/NavbarMobile.vue";

export default {
  components: { NavBar, NavbarMobile },

  data() {
    return {
      reservations: []
    };
  },

  async mounted() {
    try {
      const res = await fetch("http://localhost:8080/Reservations");
      const text = await res.text();

      try {
        const data = JSON.parse(text);

        // Direct opslaan, geen aanpassingen
        this.reservations = data.Reservations;

      } catch {
        console.warn("Server stuurde geen JSON terug:", text);
        alert("Something went wrong while parsing reservation data.");
      }

    } catch (err) {
      console.error("Error fetching reservations:", err);
      alert("Something went wrong while loading reservations.");
    }
  }
};
</script>

<template>
  <NavBar />
  <NavbarMobile />

  <main class="flex justify-center mt-6">
    <section class="w-[420px]">
      <h2 class="text-2xl font-semibold mb-[200px]">All Reservations</h2>

      <div>
        <p v-for="r in reservations" :key="r.id" class="mb-4">
          Reservation ID: {{ r.id }}<br>
          Restaurant: {{ r.restaurant }}<br>
          Start Date: {{ r.startDate.date }}<br>
          End Date: {{ r.endDate.date }}<br>
          Amount of People: {{ r.amountPeople }}<br>
          Email: {{ r.email }}
        </p>
      </div>
    </section>
  </main>
</template>
