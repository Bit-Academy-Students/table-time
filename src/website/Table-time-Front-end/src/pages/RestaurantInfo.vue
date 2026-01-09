
<script setup>
/**
 * Bestandsnaam: RestaurantInfo.vue
 *
 * Beschrijving:
 * Dit component toont publieke restaurantinformatie en faciliteert
 * het plaatsen van een reservering door eindgebruikers.
 *
 * Functionaliteiten:
 * - Ophalen en tonen van restaurantgegevens
 * - Dynamische kalender met beschikbaarheidscontrole
 * - Capaciteitscontrole per tijdslot
 * - Reservering aanmaken via backend API
 *
 * Auteur: Alexander Zoet
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 2.7.0
 * - Laatste wijziging: 16 december 2025
 *  * - Beheer: Git
 */
/*
 * Globale layoutcomponenten
 * Worden gebruikt voor consistente navigatie en footer
 */
import NavBar from '../components/NavBar.vue';
import NavbarMobile from '../components/NavbarMobile.vue';
import Footer from '../components/Footer.vue';
</script>


<script>
export default {
    /**
     * Lokale state van het component
     */
    data() {
        return {
            /*
             * Restaurantgegevens
             */
            restaurant: null,
            restaurantId: null,

            /*
             * Alle reserveringen van dit restaurant
             */
            reservations: [],

            /*
             * UI-status bij verzenden reservering
             */
            submitting: false,

            /*
             * Reserveringsformulier
             */
            form: {
                date: "",
                time: "",
                duration: "01:00",
                amountPeople: null,
                email: "",
            },

            /*
             * Kalenderstatus
             */
            currentYear: new Date().getFullYear(),
            currentMonth: new Date().getMonth(),
            selectedDay: null,

            /*
             * Loading-indicator bij klikken op volle dag
             */
            loadingDay: false,
            loadingDayDate: null,

            /*
             * Beschikbare tijdslots
             */
            timeSlots: [
                "12:00","12:30","13:00","13:30","14:00","14:30",
                "15:00","15:30","16:00","16:30","17:00","17:30",
                "18:00","18:30","19:00","19:30","20:00","20:30",
                "21:00","21:30"
            ],
        };
    },

    /**
     * Afgeleide data
     */
    computed: {
        /**
         * Maximale capaciteit van het restaurant
         */
        capacity() {
            return this.restaurant?.maxcapacity || 60;
        },

        /**
         * Naam van de huidige maand (NL)
         */
        monthName() {
            return new Date(this.currentYear, this.currentMonth)
                .toLocaleString("nl-NL", { month: "long" });
        },

        /**
         * Kalenderstructuur inclusief:
         * - lege dagen
         * - disabled status
         * - kortingsindicatie
         */
        daysInMonth() {
            const year = this.currentYear;
            const month = this.currentMonth;
            const total = new Date(year, month + 1, 0).getDate();
            const firstDay = new Date(year, month, 1).getDay() || 7;
            const days = [];

            // Lege vakjes vóór de eerste dag
            for (let i = 1; i < firstDay; i++) {
                days.push({ empty: true });
            }

            for (let d = 1; d <= total; d++) {
                const dateString = `${year}-${String(month + 1).padStart(2, "0")}-${String(d).padStart(2, "0")}`;
                const dateObj = new Date(dateString);
                dateObj.setHours(0, 0, 0, 0);

                const today = new Date();
                today.setHours(0, 0, 0, 0);

                /*
                 * Zonder ingevuld formulier is elke dag disabled
                 */
                if (!this.form.amountPeople || Number(this.form.amountPeople) <= 0 || !this.form.duration) {
                    days.push({
                        empty: false,
                        day: d,
                        fullDate: dateString,
                        disabled: true,
                        discount: d >= 26 ? "-50%" : null,
                    });
                    continue;
                }

                /*
                 * Controle of er minimaal één beschikbaar tijdslot is
                 */
                const anySlotHasSpace = this.timeSlots.some((slot) => {
                    const startCheck = new Date(`${dateString}T${slot}:00`);
                    if (startCheck < new Date()) return false;

                    const [durH, durM] = this.form.duration.split(":").map(Number);
                    const endCheck = new Date(startCheck);
                    endCheck.setHours(endCheck.getHours() + durH);
                    endCheck.setMinutes(endCheck.getMinutes() + durM);

                    const overlappingPeople = this.reservations
                        .filter(r => r.restaurant?.id === this.restaurantId)
                        .filter(r => {
                            const start = new Date(r.startDate.replace(' ', 'T'));
                            const end = new Date(r.endDate.replace(' ', 'T'));
                            return startCheck < end && endCheck > start;
                        })
                        .reduce((sum, r) => sum + r.amountPeople, 0);

                    return overlappingPeople + Number(this.form.amountPeople) <= this.capacity;
                });

                days.push({
                    empty: false,
                    day: d,
                    fullDate: dateString,
                    disabled: dateObj < today || !anySlotHasSpace,
                    discount: d >= 26 ? "-50%" : null,
                });
            }

            return days;
        },
    },

    /**
     * Watchers resetten de selectie
     * zodra duur of groepsgrootte wijzigt
     */
    watch: {
        'form.duration'() {
            this.selectedDay = null;
            this.form.date = "";
            this.form.time = "";
        },
        'form.amountPeople'() {
            this.selectedDay = null;
            this.form.date = "";
            this.form.time = "";
        }
    },

    /**
     * Methoden
     */
    methods: {
        /**
         * Haalt restaurantgegevens op
         */
        loadRestaurant() {
            fetch(`http://localhost:8080/Restaurants/${this.restaurantId}`)
                .then(res => res.json())
                .then(data => {
                    this.restaurant = data.Restaurant || data;
                })
                .catch(e => console.error("Fout bij laden restaurant:", e));
        },

        /**
         * Haalt alle reserveringen van dit restaurant op
         */
        loadReservations() {
            fetch(`http://localhost:8080/Reservations`)
                .then(res => res.json())
                .then(data => {
                    this.reservations = (data.Reservations || [])
                        .filter(r => r.restaurant?.id === this.restaurantId);
                })
                .catch(() => this.reservations = []);
        },

        /**
         * Navigatie tussen maanden
         */
        prevMonth() {
            this.currentMonth === 0
                ? (this.currentMonth = 11, this.currentYear--)
                : this.currentMonth--;
        },

        nextMonth() {
            this.currentMonth === 11
                ? (this.currentMonth = 0, this.currentYear++)
                : this.currentMonth++;
        },

        /**
         * Selecteert een dag in de kalender
         */
        chooseDay(day) {
            if (day.empty) return;

            if (day.disabled) {
                this.loadingDay = true;
                this.loadingDayDate = day.fullDate;

                setTimeout(() => {
                    this.loadingDay = false;
                    this.loadingDayDate = null;
                    alert("Geen beschikbaar tijdslot voor deze dag.");
                }, 150);

                return;
            }

            this.selectedDay = day.day;
            this.form.date = day.fullDate;
            this.form.time = "";
        },

        /**
         * Controleert of een tijdslot vol is
         */
        isTimeFull(time) {
            if (!this.form.date || !this.form.amountPeople || !this.form.duration) return true;

            const start = new Date(`${this.form.date}T${time}:00`);
            if (start < new Date()) return true;

            const [h, m] = this.form.duration.split(":").map(Number);
            const end = new Date(start);
            end.setHours(end.getHours() + h);
            end.setMinutes(end.getMinutes() + m);

            const used = this.reservations
                .filter(r => {
                    const s = new Date(r.startDate.replace(' ', 'T'));
                    const e = new Date(r.endDate.replace(' ', 'T'));
                    return start < e && end > s;
                })
                .reduce((sum, r) => sum + r.amountPeople, 0);

            return used + Number(this.form.amountPeople) > this.capacity;
        },

        /**
         * Verstuurt een nieuwe reservering naar de backend
         */
        submitReservation() {
            this.submitting = true;

            try {
                const start = new Date(`${this.form.date}T${this.form.time}:00`);
                const [h, m] = this.form.duration.split(":").map(Number);
                const end = new Date(start);
                end.setHours(end.getHours() + h);
                end.setMinutes(end.getMinutes() + m);

                const pad = n => String(n).padStart(2, "0");
                const fmt = d =>
                    `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}:00`;

                fetch("http://localhost:8080/Reservations", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        restaurantId: this.restaurantId,
                        startDate: fmt(start),
                        endDate: fmt(end),
                        amountPeople: Number(this.form.amountPeople),
                        email: this.form.email,
                    }),
                })
                .then(res => {
                    if (!res.ok) throw new Error();
                    alert("Reservering succesvol aangemaakt!");
                    this.$router.push("/");
                })
                .catch(() => alert("Reserveren mislukt"))
                .finally(() => this.submitting = false);

            } catch {
                alert("Ongeldige invoer");
                this.submitting = false;
            }
        },
    },

    /**
     * Initialisatie bij laden van de pagina
     */
    mounted() {
        this.restaurantId = Number(this.$route.params.id);
        this.loadRestaurant();
        this.loadReservations();
    },
};
</script>

<template>
    <NavBar />
    <NavbarMobile />

    <main class="flex flex-col items-center pt-[100px] px-4">

        <section v-if="restaurant" class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-8 mb-8">
            <h1 class="text-4xl font-bold text-[#03CAED] mb-4">{{ restaurant.naam }}</h1>
            <div class="space-y-2 text-lg">
                <p class="text-gray-700">📍 <strong>Locatie:</strong> {{ restaurant.locatie }}</p>
                <p class="text-gray-700">📞 <strong>Telefoon:</strong> {{ restaurant.telefoonnummer }}</p>
                <p class="text-gray-700">👥 <strong>Max capaciteit:</strong> {{ restaurant.maxcapacity }} personen</p>
                <p class="text-gray-700">✉️ <strong>Email:</strong> {{ restaurant.email }}</p>
            </div>
        </section>

        <section class="w-[420px] h-auto pb-12">
            <h2 class="text-2xl font-semibold mb-4">Tafel reserveren bij {{ restaurant?.naam }}</h2>

            <div class="bg-white p-4 rounded-xl shadow mb-4">
                <h3 class="font-semibold mb-2">Aantal personen</h3>
                <input type="number" v-model.number="form.amountPeople" class="w-full p-2 border rounded" min="1"
                    :max="restaurant?.maxcapacity" />
                <p v-if="!form.amountPeople || form.amountPeople <= 0" class="text-xs text-gray-500 mt-2">
                    Vul eerst het aantal personen in om beschikbare dagen te zien.
                </p>
                <p v-else-if="form.amountPeople > restaurant?.maxcapacity" class="text-xs text-red-500 mt-2">
                    Dit restaurant heeft een maximale capaciteit van {{ restaurant?.maxcapacity }} personen.
                </p>
            </div>

            <div class="bg-white p-4 rounded-xl shadow mb-4">
                <h3 class="font-semibold mb-2">Duur</h3>
                <select v-model="form.duration" class="w-full p-2 border rounded">
                    <option value="01:00">1 uur</option>
                    <option value="01:30">1.5 uur</option>
                    <option value="02:00">2 uur</option>
                </select>
            </div>

            <div class="bg-white border-[#03CAED] border-2 z-10 p-4 rounded-xl shadow mb-8 relative">
                <h3 class="text-center text-lg font-semibold mb-4">
                    {{ monthName.charAt(0).toUpperCase() + monthName.slice(1) }} {{ currentYear }}
                </h3>

                <div class="flex justify-between mb-2 px-2">
                    <button @click="prevMonth" class="text-xl">‹</button>
                    <button @click="nextMonth" class="text-xl">›</button>
                </div>

                <div class="grid grid-cols-7 text-center text-gray-500 text-sm mb-2">
                    <span>ma</span><span>di</span><span>wo</span><span>do</span><span>vr</span><span>za</span><span>zo</span>
                </div>

                <div class="grid grid-cols-7 gap-2">
                    <div v-for="(day, i) in daysInMonth" :key="i"
                        class="h-12 flex flex-col items-center justify-center z-20 rounded-lg text-sm relative" :class="{
                            'bg-gray-100 text-gray-400': day.disabled,
                            'bg-[#03CAED] text-white': selectedDay === day.day && !day.disabled,
                            'hover:bg-gray-200 cursor-pointer': !day.disabled && !day.empty
                        }" @click="chooseDay(day)">
                        <span v-if="!day.empty">{{ day.day }}</span>
                        <span v-if="day.discount"
                            class="absolute bottom-1 bg-[#FF8000] text-white text-[10px] px-1 rounded">
                            {{ day.discount }}
                        </span>

                        <div v-if="loadingDay && loadingDayDate === day.fullDate"
                            class="absolute inset-0 flex items-center justify-center bg-white/70 rounded-lg">
                            <div class="w-6 h-6 border-4 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-2 mb-4">
                <button v-for="t in timeSlots" :key="t" @click="!isTimeFull(t) && (form.time = t)"
                    class="p-2 border w-[75px] rounded text-sm" :class="{
                        'bg-[#FF8000] text-white': form.time === t,
                        'hover:bg-gray-200 cursor-pointer': !isTimeFull(t),
                        'bg-gray-100 text-gray-400 cursor-not-allowed': isTimeFull(t)
                    }" :disabled="isTimeFull(t)">
                    {{ t }}
                </button>
            </div>

            <div class="bg-white p-4 rounded-xl shadow mb-4">
                <h3 class="font-semibold mb-2">Email</h3>
                <input type="email" v-model="form.email" class="w-full p-2 border rounded" />
            </div>

            <form @submit.prevent="submitReservation">
                <button type="submit" class="w-full bg-[#03CAED] text-white p-3 rounded hover:bg-[#02a8c4] transition"
                    :disabled="submitting">
                    <span v-if="!submitting">Reserveren</span>
                    <div v-else class="flex items-center justify-center">
                        <div class="w-5 h-5 border-4 border-t-transparent border-white rounded-full animate-spin mr-2">
                        </div>
                        Bezig...
                    </div>
                </button>
            </form>
        </section>
    </main>
    <Footer />
</template>



<style scoped>
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>