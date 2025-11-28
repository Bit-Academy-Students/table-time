<script>
import NavBar from "../components/NavBar.vue";
import NavbarMobile from '../components/NavbarMobile.vue';

export default {
  components: { NavBar },
 
};

  fetch("http://localhost:8080/Reservations", {
    method: "GET",
    headers: { "Content-Type": "application/json" },
  })
    .then(async (res) => {
      const text = await res.text();
      try {
        const data = JSON.parse(text);
        let div = document.getElementsByTagName("div")[0];
        data.Reservations.forEach(reservation => {
          let p = document.createElement("p");
          p.textContent = `Reservering ID: ${reservation.id}, Naam: ${reservation.naam}, Datum: ${reservation.datum}, Tijd: ${reservation.tijd}, Aantal Personen: ${reservation.aantal_personen}`;
          div.appendChild(p);
        });
      } catch {
        console.warn("Server stuurde geen JSON terug:");
        console.log(text);
        alert("Er is iets misgegaan bij het maken van de reservering.");
      }
    })
    .catch((err) => {
      console.error("Fout bij reserveren:", err);
      alert("Er is iets misgegaan bij het vinden van de reservering.");
    });
</script>

<template>
  <NavBar />
  <NavbarMobile />

  <main class="flex justify-center mt-6">
    <section class="w-[420px] h-[300vh]">

      <h2 class="text-2xl font-semibold mb-4">alle reserveringen</h2>

      <div>
      </div>

    </section>
  </main>
</template>
