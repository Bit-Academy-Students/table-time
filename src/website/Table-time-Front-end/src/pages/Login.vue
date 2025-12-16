<!--
/**
 * Bestandsnaam: Login.vue
 *
 * Beschrijving:
 * Dit bestand bevat de logica voor het registreren van een restaurant.
 * Het script verzorgt formulierbeheer, validatie, communicatie met de backend
 * en gebruikersfeedback via statusmeldingen.
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
<script setup>
/*
 * Importeert de navigatiecomponent voor desktopgebruik
 */
import NavBar from '../components/NavBar.vue';

/*
 * Importeert de navigatiecomponent voor mobiel gebruik
 */
import NavbarMobile from '../components/NavbarMobile.vue';

/*
 * Importeert de footercomponent
 */
import Footer from '../components/Footer.vue';

/*
 * Vue Router composable voor programmatische navigatie
 */
import { useRouter } from 'vue-router';

/*
 * Router-instantie voor navigatie na succesvolle registratie
 */
const router = useRouter();
</script>


<script>
export default {
    /**
     * Component state
     *
     * Bevat formulierdata voor restaurantregistratie
     * en statusmeldingen voor gebruikersfeedback.
     */
    data() {
        return {
            form: {
                naam: '',
                email: '',
                locatie: '',
                telefoonnummer: '',
                wachtwoord: '',
                maxCapacity: 50,
                afbeelding: ''
            },
            message: '',
            messageType: ''
        };
    },

    methods: {
        /**
         * Verwerkt het registratieformulier
         *
         * Valideert verplichte velden, stelt het request body object samen
         * en verstuurt de gegevens naar de backend API.
         *
         * Bij succes wordt een bevestigingsbericht getoond en de gebruiker
         * automatisch doorgestuurd naar de inlogpagina.
         */
        async submitForm() {
            try {
                // Basisvalidatie van verplichte velden
                if (!this.form.naam || !this.form.email || !this.form.wachtwoord) {
                    this.showMessage('Vul alle verplichte velden in', 'error');
                    return;
                }

                /*
                 * Samenstellen van het request body object
                 * Optionele velden worden als null verzonden
                 */
                const body = {
                    naam: this.form.naam,
                    email: this.form.email,
                    wachtwoord: this.form.wachtwoord,
                    locatie: this.form.locatie || null,
                    telefoonnummer: this.form.telefoonnummer
                        ? this.form.telefoonnummer.replace(/\s+/g, '')
                        : null,
                    maxCapacity: this.form.maxCapacity || 50,
                };

                console.log('Verzenden naar API:', body);

                // API-aanroep voor restaurantregistratie
                const response = await fetch("http://localhost:8080/Restaurants", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(body)
                });

                // Controle op HTTP-fouten
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP error ${response.status}: ${errorText}`);
                }

                await response.json();

                // Succesmelding en automatische redirect
                this.showMessage(
                    'Restaurant succesvol geregistreerd! Je wordt doorgestuurd naar de inlogpagina...',
                    'success'
                );

                setTimeout(() => {
                    this.$router.push('/restaurant/login');
                }, 2000);

            } catch (error) {
                console.error('Registratiefout:', error);
                this.showMessage(
                    'Er is iets misgegaan bij het registreren: ' + error.message,
                    'error'
                );
            }
        },

        /**
         * Toont een statusmelding aan de gebruiker
         *
         * @param {string} msg - De te tonen melding
         * @param {string} type - Type melding ('success' of 'error')
         */
        showMessage(msg, type) {
            this.message = msg;
            this.messageType = type;

            // Verberg foutmeldingen automatisch na 5 seconden
            if (type === 'error') {
                setTimeout(() => {
                    this.message = '';
                    this.messageType = '';
                }, 5000);
            }
        },

        /**
         * Reset het formulier naar de standaardwaarden
         */
        resetForm() {
            this.form = {
                naam: '',
                email: '',
                locatie: '',
                telefoonnummer: '',
                wachtwoord: '',
                maxCapacity: 50,
                afbeelding: ''
            };
        }
    }
};
</script>

<template>
    <NavBar />
    <NavbarMobile />
    <main class="flex justify-center flex-col items-center gap-8 pt-[100px]">
        <h2>Restaurant Registratie</h2>
        <form @submit.prevent="submitForm" class="flex bg-white p-10 rounded-xl flex-col gap-4 justify-center items-center w-[400px]">
            <input 
                v-model="form.naam" 
                type="text" 
                placeholder="Restaurant naam" 
                class="w-full p-3 border rounded"
                required
            />
            <input 
                v-model="form.email" 
                type="email" 
                placeholder="E-mail" 
                class="w-full p-3 border rounded"
                required
            />
            <input 
                v-model="form.locatie" 
                type="text" 
                placeholder="Locatie" 
                class="w-full p-3 border rounded"
            />
            <input 
                v-model="form.telefoonnummer" 
                type="tel" 
                placeholder="Telefoonnummer (bijv. 0612345678)" 
                class="w-full p-3 border rounded"
                maxlength="15"
            />
            <input 
                v-model="form.wachtwoord" 
                type="password" 
                placeholder="Wachtwoord" 
                class="w-full p-3 border rounded"
                required
            />
            <input 
                v-model.number="form.maxCapacity" 
                type="number" 
                placeholder="Maximum capaciteit" 
                class="w-full p-3 border rounded"
                min="1"
            />
            <button 
                type="submit" 
                class="w-full bg-[#03CAED] text-white p-3 rounded hover:bg-[#02B5D5] transition"
            >
                Registreren
            </button>
        </form>

        <div v-if="message" class="mt-4 mb-4 p-4 rounded" :class="messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
            {{ message }}
        </div>

        <!-- Link naar inlogpagina -->
        <p class="text-gray-600">
            Heb je al een account? 
            <router-link to="/restaurant/login" class="text-[#03CAED] hover:underline font-semibold">
                Log hier in
            </router-link>
        </p>
    </main>
    <Footer />
</template>




<style scoped>
input:focus {
    outline: none;
    border-color: #03CAED;
    box-shadow: 0 0 0 3px rgba(3, 202, 237, 0.1);
}
</style>