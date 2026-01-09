<!--
/**
 * Bestandsnaam: RestaurantLogin.vue
 *
 * Beschrijving:
 * Dit component verzorgt het inlogproces voor restauranteigenaren.
 * Het valideert invoer, communiceert met de backend-authenticatie API
 * en slaat de sessiegegevens lokaal op.
 *
 * Functionaliteiten:
 * - Inloggen via e-mail en wachtwoord
 * - Afhandeling van foutmeldingen (401 / server errors)
 * - Opslaan van loginstatus en restaurantgegevens in localStorage
 * - Automatische redirect naar het juiste dashboard
 *
 * Auteur: Alexander Zoet
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Laatste wijziging: 16 december 2025
 * - 1.2.0
 * - Beheer: Git

 */
-->

<script setup>
/*
 * Layoutcomponenten voor consistente navigatie
 */
import NavBar from '../components/NavBar.vue';
import NavbarMobile from '../components/NavbarMobile.vue';
import Footer from '../components/Footer.vue';

/*
 * Vue Router instance voor navigatie
 */
import { useRouter } from 'vue-router';

const router = useRouter();
</script>


<script>
export default {

    /**
     * Lokale component state
     */
    data() {
        return {
            /*
             * Inlogformulier data
             */
            form: {
                email: '',
                wachtwoord: ''
            },

            /*
             * Feedbackbericht voor gebruiker
             */
            message: '',
            messageType: '' // 'success' | 'error'
        };
    },

    /**
     * Methoden
     */
    methods: {

        /**
         * Verwerkt het inlogformulier
         * - Valideert invoer
         * - Stuurt loginrequest naar backend
         * - Slaat sessiedata op
         * - Redirect naar dashboard
         */
        async submitLogin() {
            try {
                // Basisvalidatie
                if (!this.form.email || !this.form.wachtwoord) {
                    this.showMessage('Vul alle velden in', 'error');
                    return;
                }

                console.log('Inlogpoging voor:', this.form.email);

                // API request
                const response = await fetch(
                    "http://localhost:8080/Restaurants/authenticate",
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            email: this.form.email,
                            wachtwoord: this.form.wachtwoord
                        })
                    }
                );

                // HTTP error handling
                if (!response.ok) {
                    if (response.status === 401) {
                        throw new Error('Onjuiste inloggegevens');
                    }

                    const errorText = await response.text();
                    console.error('Serverfout:', errorText);
                    throw new Error(`Server error (${response.status})`);
                }

                const data = await response.json();
                console.log('Login succesvol:', data);

                /*
                 * Persistente loginstatus
                 * (wordt gebruikt door dashboard-auth check)
                 */
                localStorage.setItem(
                    'restaurantData',
                    JSON.stringify(data.Restaurant)
                );
                localStorage.setItem('isLoggedIn', 'true');

                this.showMessage(
                    'Succesvol ingelogd! Je wordt doorgestuurd...',
                    'success'
                );

                // Redirect naar restaurantdashboard
                setTimeout(() => {
                    this.$router.push(
                        `/restaurant/${data.Restaurant.id}/dashboard`
                    );
                }, 1000);

            } catch (error) {
                console.error('Loginfout:', error);
                this.showMessage(
                    error.message || 'Er is iets misgegaan bij het inloggen',
                    'error'
                );
            }
        },

        /**
         * Toont een feedbackbericht aan de gebruiker
         *
         * @param {string} msg - Berichttekst
         * @param {'success'|'error'} type - Type bericht
         */
        showMessage(msg, type) {
            this.message = msg;
            this.messageType = type;

            // Automatisch verbergen bij foutmeldingen
            if (type === 'error') {
                setTimeout(() => {
                    this.message = '';
                    this.messageType = '';
                }, 5000);
            }
        }
    }
};
</script>
<template>
    <NavBar />
    <NavbarMobile />
    <main class="flex justify-center flex-col items-center gap-8 pt-[100px] min-h-[calc(100vh-200px)]">
        <h2>Restaurant Inloggen</h2>
        <form @submit.prevent="submitLogin" class="flex flex-col gap-4 justify-center items-center w-[400px] bg-white p-10 rounded-xl">
            <input 
                v-model="form.email" 
                type="email" 
                placeholder="E-mail" 
                class="w-full p-3 border rounded"
                required
            />
            <input 
                v-model="form.wachtwoord" 
                type="password" 
                placeholder="Wachtwoord" 
                class="w-full p-3 border rounded"
                required
            />
            <button 
                type="submit" 
                class="w-full bg-[#03CAED] text-white p-3 rounded hover:bg-[#02B5D5] transition"
            >
                Inloggen
            </button>
        </form>

        <div v-if="message" class="mt-4 p-4 rounded" :class="messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
            {{ message }}
        </div>

        <p class="text-gray-600">
            Nog geen account? 
            <router-link to="/restaurant-register" class="text-[#03CAED] hover:underline font-semibold">
                Registreer hier
            </router-link>
        </p>
    </main>
    <Footer />
</template>
