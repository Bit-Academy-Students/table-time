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
            <input 
                v-model="form.afbeelding" 
                type="text" 
                placeholder="Afbeelding URL (optioneel)" 
                class="w-full p-3 border rounded"
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

<script setup>
import NavBar from '../components/NavBar.vue';
import NavbarMobile from '../components/NavbarMobile.vue';
import Footer from '../components/Footer.vue';
import { useRouter } from 'vue-router';

const router = useRouter();
</script>

<script>

export default {
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
        async submitForm() {
            try {
                // Validatie
                if (!this.form.naam || !this.form.email || !this.form.wachtwoord) {
                    this.showMessage('Vul alle verplichte velden in', 'error');
                    return;
                }

                // Maak het request body object
                const body = {
                    naam: this.form.naam,
                    email: this.form.email,
                    wachtwoord: this.form.wachtwoord,
                    locatie: this.form.locatie || null,
                    // Verwijder spaties en andere tekens uit telefoonnummer
                    telefoonnummer: this.form.telefoonnummer ? this.form.telefoonnummer.replace(/\s+/g, '') : null,
                    maxCapacity: this.form.maxCapacity || 50,
                    afbeelding: this.form.afbeelding || null
                };

                console.log('Verzenden naar API:', body);
                console.log('Request body as JSON:', JSON.stringify(body));

                // Verstuur naar de API
                const response = await fetch("http://localhost:8080/Restaurants", {
                    method: "POST",
                    headers: { 
                        "Content-Type": "application/json" 
                    },
                    body: JSON.stringify(body)
                });

                // Controleer of het request succesvol was
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server error response:', errorText);
                    throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
                }

                const data = await response.json();
                console.log('Success:', data);

                this.showMessage('Restaurant succesvol geregistreerd! Je wordt doorgestuurd naar de inlogpagina...', 'success');

                // Wacht 2 seconden en ga dan naar de inlogpagina
                setTimeout(() => {
                    this.$router.push('/restaurant-login');
                }, 2000);

            } catch (error) {
                console.error('Error:', error);
                this.showMessage('Er is iets misgegaan bij het registreren: ' + error.message, 'error');
            }
        },

        showMessage(msg, type) {
            this.message = msg;
            this.messageType = type;

            // Verberg bericht na 5 seconden (alleen bij error)
            if (type === 'error') {
                setTimeout(() => {
                    this.message = '';
                    this.messageType = '';
                }, 5000);
            }
        },

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

<style scoped>
input:focus {
    outline: none;
    border-color: #03CAED;
    box-shadow: 0 0 0 3px rgba(3, 202, 237, 0.1);
}
</style>