<!--
/**
 * Bestandsnaam: ContactPage.vue
 *
 * Beschrijving:
 * Dit script verzorgt de logica voor de contactpagina.
 * Het bevat de formulierstatus en de functionaliteit
 * voor het verzenden van e-mails via EmailJS.
 *
 * Auteur: Keano Broekman
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.3.0
 * - Laatste wijziging: <datum invullen>
 * - Beheer: Git
 */
-->
<script setup>
/*
 * Importeert de navigatiecomponent voor desktopgebruik
 */
import NavBar from "../components/NavBar.vue";

/*
 * Importeert de navigatiecomponent voor mobiel gebruik
 */
import NavbarMobile from "../components/NavbarMobile.vue";

/*
 * Importeert de footercomponent
 */
import Footer from "../components/Footer.vue";

/*
 * Vue Composition API:
 * ref wordt gebruikt voor reactieve formulierdata
 */
import { ref } from "vue";

/*
 * EmailJS library voor het verzenden van e-mails
 * zonder eigen backend
 */
import * as emailjs from "@emailjs/browser";

/**
 * Reactief formulierobject
 *
 * Bevat alle invoervelden van het contactformulier
 * en wordt gebruikt bij het verzenden van de e-mail.
 */
const form = ref({
  name: "",
  email: "",
  phone: "",
  message: ""
});

/**
 * Verstuurt het contactformulier via EmailJS
 *
 * Deze functie wordt aangeroepen bij het submitten
 * van het formulier. De gegevens uit het reactieve
 * formulierobject worden doorgestuurd naar EmailJS.
 *
 * Bij succes ontvangt de gebruiker een bevestiging.
 * Bij een foutmelding wordt deze gelogd en getoond.
 */
const sendEmail = () => {
  emailjs
    .send(
      "service_iki89mq",      // EmailJS service ID
      "template_ryo3sgk",     // EmailJS template ID
      {
        from_name: form.value.name,
        from_email: form.value.email,
        phone: form.value.phone,
        message: form.value.message,
        to_email: "alexanderzoet@gmail.com", // Ontvangend e-mailadres
      },
      "BR1aGpy3A5r2nJ38Z"      // EmailJS public key
    )
    .then(() => {
      // Bevestiging bij succesvol verzenden
      alert("Email succesvol verstuurd!");
    })
    .catch((error) => {
      // Foutafhandeling bij mislukte verzending
      console.error("Email error:", error);
      alert("Er ging iets mis bij het versturen.");
    });
};
</script>


<template>
  <NavBar />
  <NavbarMobile />

  <main class="flex flex-col items-center pt-[100px] min-h-screen bg-gray-50">
    <div class="w-full max-w-4xl px-6">
      <div class="text-center mb-8">
        <h2 class="text-3xl font-bold">Contact Pagina</h2>
        <p class="mt-2 text-gray-600">
          Voor als je contact met een van ons wil opnemen voor vragen
        </p>
      </div>

      <section class="flex flex-col md:flex-row gap-8 items-start justify-center">
        <div class="w-full md:w-1/2">
          <div class="bg-white shadow-lg rounded-2xl p-6">
            <h3 class="text-xl font-semibold mb-4">Stuur ons een bericht</h3>

            <form @submit.prevent="sendEmail" class="flex flex-col gap-4">
              <label class="block text-sm font-medium">Naam</label>
              <input
                v-model="form.name"
                type="text"
                placeholder="Jouw naam"
                class="w-full border rounded-lg p-3"
                required
              />

              <label class="block text-sm font-medium">E-mail</label>
              <input
                v-model="form.email"
                type="email"
                placeholder="naam@voorbeeld.com"
                class="w-full border rounded-lg p-3"
                required
              />

              <label class="block text-sm font-medium">Telefoonnummer</label>
              <input
                v-model="form.phone"
                type="tel"
                placeholder="+31 6 12 34 56 78"
                class="w-full border rounded-lg p-3"
              />

              <label class="block text-sm font-medium">Bericht</label>
              <textarea
                v-model="form.message"
                rows="5"
                placeholder="Typ je bericht hier..."
                class="w-full border rounded-lg p-3"
                required
              ></textarea>

              <button
                type="submit"
                class="mt-2 w-full"
              >
                Verstuur
              </button>
            </form>
          </div>
        </div>

        <aside class="w-full md:w-1/3">
          <div class="bg-white shadow rounded-2xl p-6">
            <h4 class="font-semibold mb-2">Contactgegevens</h4>
            <p class="flex flex-col gap-2.5 text-sm text-gray-600 mb-4">
              Of bel ons: <br />
              <strong>+31 6 43144484</strong>
              <strong>keanobroekman@gmail.com</strong>
            </p>
          </div>
        </aside>
      </section>
    </div>
  </main>
  <Footer />
</template>
