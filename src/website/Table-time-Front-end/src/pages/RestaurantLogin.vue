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

        <!-- Link naar registratiepagina -->
        <p class="text-gray-600">
            Nog geen account? 
            <router-link to="/restaurant-register" class="text-[#03CAED] hover:underline font-semibold">
                Registreer hier
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
                email: '',
                wachtwoord: ''
            },
            message: '',
            messageType: ''
        };
    },
    methods: {
        async submitLogin() {
            try {
                // Validatie
                if (!this.form.email || !this.form.wachtwoord) {
                    this.showMessage('Vul alle velden in', 'error');
                    return;
                }

                console.log('Inloggen met:', this.form.email);

                // Verstuur naar de API
                const response = await fetch("http://localhost:8080/Restaurants/authenticate", {
                    method: "POST",
                    headers: { 
                        "Content-Type": "application/json" 
                    },
                    body: JSON.stringify({
                        email: this.form.email,
                        wachtwoord: this.form.wachtwoord
                    })
                });

                // Controleer of het request succesvol was
                if (!response.ok) {
                    if (response.status === 401) {
                        throw new Error('Onjuiste inloggegevens');
                    }
                    const errorText = await response.text();
                    console.error('Server error response:', errorText);
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Login success:', data);

                // Sla restaurant gegevens op (bijvoorbeeld in localStorage of Vuex/Pinia)
                localStorage.setItem('restaurantData', JSON.stringify(data.Restaurant));
                localStorage.setItem('isLoggedIn', 'true');

                this.showMessage('Succesvol ingelogd! Je wordt doorgestuurd...', 'success');

                // Wacht 1 seconde en ga naar dashboard met restaurant ID
                setTimeout(() => {
                    this.$router.push(`/restaurant/${data.Restaurant.id}/dashboard`);
                }, 1000);

            } catch (error) {
                console.error('Error:', error);
                this.showMessage(error.message || 'Er is iets misgegaan bij het inloggen', 'error');
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