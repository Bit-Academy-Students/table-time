<template>
    <main class="flex justify-center flex-col items-center gap-8 mt-16">
        <h1 class="text-2xl font-bold">Restaurant Registratie</h1>
        <form @submit.prevent="submitForm" class="flex flex-col gap-4 justify-center items-center w-[400px]">
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

        <div v-if="message" class="mt-4 p-4 rounded" :class="messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
            {{ message }}
        </div>
    </main>
</template>

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

                this.showMessage('Restaurant succesvol geregistreerd!', 'success');

                // Reset het formulier
                this.resetForm();

            } catch (error) {
                console.error('Error:', error);
                this.showMessage('Er is iets misgegaan bij het registreren: ' + error.message, 'error');
            }
        },

        showMessage(msg, type) {
            this.message = msg;
            this.messageType = type;

            // Verberg bericht na 5 seconden
            setTimeout(() => {
                this.message = '';
                this.messageType = '';
            }, 5000);
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