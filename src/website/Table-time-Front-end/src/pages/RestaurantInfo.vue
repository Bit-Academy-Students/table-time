<template name="RestaurantInfo">
    <NavBar />
    <NavbarMobile />

    <main class="flex flex-col items-center pt-[100px] px-4">

        <!-- Restaurant Info Card -->
        <section v-if="restaurant" class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-8 mb-8">
            <h1 class="text-4xl font-bold text-[#03CAED] mb-4">{{ restaurant.naam }}</h1>
            <div class="space-y-2 text-lg">
                <p class="text-gray-700">📍 <strong>Locatie:</strong> {{ restaurant.locatie }}</p>
                <p class="text-gray-700">📞 <strong>Telefoon:</strong> {{ restaurant.telefoonnummer }}</p>
                <p class="text-gray-700">👥 <strong>Max capaciteit:</strong> {{ restaurant.maxcapacity }} personen</p>
                <p class="text-gray-700">✉️ <strong>Email:</strong> {{ restaurant.email }}</p>
            </div>
        </section>

        <!-- Reserverings Formulier -->
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

            <!-- Kalender -->
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

            <!-- Tijdslots -->
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
</template>

<script>
export default {
    name: 'RestaurantInfo',
    data() {
        return {
            restaurant: null,
            restaurantId: null,
            reservations: [],
            submitting: false,

            form: {
                date: "",
                time: "",
                duration: "01:00",
                amountPeople: null,
                email: "",
            },

            currentYear: new Date().getFullYear(),
            currentMonth: new Date().getMonth(),
            selectedDay: null,

            loadingDay: false,
            loadingDayDate: null,

            timeSlots: [
                "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30",
                "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00", "19:30",
                "20:00", "20:30", "21:00", "21:30"
            ],
        };
    },

    computed: {
        capacity() {
            return this.restaurant?.maxcapacity || 60;
        },

        monthName() {
            return new Date(this.currentYear, this.currentMonth)
                .toLocaleString("nl-NL", { month: "long" });
        },

        daysInMonth() {
            const year = this.currentYear;
            const month = this.currentMonth;
            const total = new Date(year, month + 1, 0).getDate();
            const firstDay = new Date(year, month, 1).getDay() || 7;
            const days = [];

            for (let i = 1; i < firstDay; i++) {
                days.push({ empty: true });
            }

            for (let d = 1; d <= total; d++) {
                const dateString = `${year}-${String(month + 1).padStart(2, "0")}-${String(d).padStart(2, "0")}`;
                const dateObj = new Date(dateString);
                dateObj.setHours(0, 0, 0, 0);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

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

                const anySlotHasSpace = this.timeSlots.some((slot) => {
                    const startCheck = new Date(`${dateString}T${slot}:00`);
                    const now = new Date();
                    if (startCheck.getTime() < now.getTime()) return false;

                    const [durH, durM] = this.form.duration.split(":").map(Number);
                    const endCheck = new Date(startCheck);
                    endCheck.setHours(endCheck.getHours() + durH);
                    endCheck.setMinutes(endCheck.getMinutes() + durM);

                    const overlappingPeople = this.reservations
                        .filter((r) => {
                            if (!r.restaurant) return true;
                            return r.restaurant.id === this.restaurantId;
                        })
                        .filter((r) => {
                            const startStr = typeof r.startDate === 'string' ? r.startDate : r.startDate.date;
                            const endStr = typeof r.endDate === 'string' ? r.endDate : r.endDate.date;

                            const start = new Date(startStr.replace(' ', 'T'));
                            const end = new Date(endStr.replace(' ', 'T'));

                            return startCheck < end && endCheck > start;
                        })
                        .reduce((sum, r) => sum + r.amountPeople, 0);

                    return (overlappingPeople + Number(this.form.amountPeople)) <= this.capacity;
                });

                const disabled = dateObj < today || !anySlotHasSpace;

                days.push({
                    empty: false,
                    day: d,
                    fullDate: dateString,
                    disabled,
                    discount: d >= 26 ? "-50%" : null,
                });
            }

            return days;
        },
    },

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

    methods: {
        loadRestaurant() {
            fetch(`http://localhost:8080/Restaurants/${this.restaurantId}`)
                .then(res => res.json())
                .then(data => {
                    this.restaurant = data.Restaurant || data;
                    console.log("Restaurant geladen:", this.restaurant);
                })
                .catch(e => {
                    console.error("Fout bij laden restaurant:", e);
                });
        },

        loadReservations() {
            fetch(`http://localhost:8080/Reservations`)
                .then((res) => res.json())
                .then((data) => {
                    const allReservations = data.Reservations || [];

                    this.reservations = allReservations.filter(r => {
                        if (!r.restaurant) return true;
                        return r.restaurant.id === this.restaurantId;
                    });

                    console.log(`✅ Geladen reserveringen voor restaurant ${this.restaurantId}:`, this.reservations);
                })
                .catch((e) => {
                    console.error("Fout bij laden:", e);
                    this.reservations = [];
                });
        },

        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
        },

        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
        },

        chooseDay(day) {
            if (day.empty) return;

            if (day.disabled) {
                this.loadingDay = true;
                this.loadingDayDate = day.fullDate;

                setTimeout(() => {
                    const anySlotHasSpace = this._checkDayHasSpace(day.fullDate);
                    this.loadingDay = false;
                    this.loadingDayDate = null;

                    if (!anySlotHasSpace) {
                        alert("Sorry — voor deze dag is momenteel geen beschikbare tijdslot meer voor dat aantal personen en die duur.");
                        return;
                    } else {
                        this._selectDay(day);
                    }
                }, 150);
                return;
            }

            this._selectDay(day);
        },

        _selectDay(day) {
            this.selectedDay = day.day;
            const m = String(this.currentMonth + 1).padStart(2, "0");
            const d = String(day.day).padStart(2, "0");
            this.form.date = `${this.currentYear}-${m}-${d}`;
            this.form.time = "";
        },

        _checkDayHasSpace(dateString) {
            if (!this.form.amountPeople || Number(this.form.amountPeople) <= 0 || !this.form.duration) {
                return false;
            }

            const now = new Date();

            return this.timeSlots.some((slot) => {
                const startCheck = new Date(`${dateString}T${slot}:00`);
                if (startCheck.getTime() < now.getTime()) return false;

                const [durH, durM] = this.form.duration.split(":").map(Number);
                const endCheck = new Date(startCheck);
                endCheck.setHours(endCheck.getHours() + durH);
                endCheck.setMinutes(endCheck.getMinutes() + durM);

                const overlappingPeople = this.reservations
                    .filter((r) => {
                        const startStr = typeof r.startDate === 'string' ? r.startDate : r.startDate.date;
                        const endStr = typeof r.endDate === 'string' ? r.endDate : r.endDate.date;
                        const start = new Date(startStr.replace(' ', 'T'));
                        const end = new Date(endStr.replace(' ', 'T'));
                        return startCheck < end && endCheck > start;
                    })
                    .reduce((sum, r) => sum + r.amountPeople, 0);

                return (overlappingPeople + Number(this.form.amountPeople)) <= this.capacity;
            });
        },

        isTimeFull(time) {
            if (!this.form.date || !this.form.amountPeople || !this.form.duration) return true;

            const startCheck = new Date(`${this.form.date}T${time}:00`);
            const now = new Date();
            if (startCheck.getTime() < now.getTime()) return true;

            const [durH, durM] = this.form.duration.split(":").map(Number);
            const endCheck = new Date(startCheck);
            endCheck.setHours(endCheck.getHours() + durH);
            endCheck.setMinutes(endCheck.getMinutes() + durM);

            // DEBUG
            console.log(`Slot ${time} op ${this.form.date}:`);

            const overlappingPeople = this.reservations
                .filter(r => r.restaurant?.id === this.restaurantId)
                .filter((r) => {
                    const startStr = typeof r.startDate === 'string' ? r.startDate : r.startDate.date;
                    const endStr = typeof r.endDate === 'string' ? r.endDate : r.endDate.date;
                    const start = new Date(startStr.replace(' ', 'T'));
                    const end = new Date(endStr.replace(' ', 'T'));

                    // WIJZIGING: gebruik <= en >= om exacte grenzen NIET als overlap te tellen
                    const hasOverlap = startCheck < end && endCheck > start;

                    if (hasOverlap) {
                        console.log(`  - Overlap met reservering ${r.id}: ${startStr} tot ${endStr} (${r.amountPeople} personen)`);
                    }

                    return hasOverlap;
                })
                .reduce((sum, r) => sum + r.amountPeople, 0);

            const totaal = overlappingPeople + Number(this.form.amountPeople);
            const isFull = totaal > this.capacity;

            console.log(`  {overlappingPeople: ${overlappingPeople}, nieuwePeople: ${this.form.amountPeople}, totaal: ${totaal}, capacity: ${this.capacity}, isFull: ${isFull}}`);

            return isFull;
        },

        submitReservation() {
            this.submitting = true;

            try {
                if (!this.form.date || !this.form.time) throw new Error("Datum of tijd niet ingevuld");

                const [hours, minutes] = this.form.time.split(":").map(Number);
                const [durHours, durMinutes] = this.form.duration.split(":").map(Number);

                const start = new Date(`${this.form.date}T${this.form.time}:00`);
                const end = new Date(start);
                end.setHours(end.getHours() + durHours);
                end.setMinutes(end.getMinutes() + durMinutes);

                const pad = (n) => String(n).padStart(2, "0");
                const fmt = (d) =>
                    `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(
                        d.getHours()
                    )}:${pad(d.getMinutes())}:00`;

                const body = {
                    restaurantId: this.restaurantId,
                    startDate: fmt(start),
                    endDate: fmt(end),
                    amountPeople: Number(this.form.amountPeople),
                    email: this.form.email,
                };

                console.log("=== DEBUG INFO ===");
                console.log("restaurantId:", this.restaurantId, "type:", typeof this.restaurantId);
                console.log("startDate:", body.startDate);
                console.log("endDate:", body.endDate);
                console.log("amountPeople:", body.amountPeople, "type:", typeof body.amountPeople);
                console.log("email:", body.email);
                console.log("Volledige body:", JSON.stringify(body, null, 2));

                fetch("http://localhost:8080/Reservations", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(body),
                })
                    .then(async (res) => {
                        const text = await res.text();
                        console.log("Response status:", res.status);
                        console.log("Response body:", text);

                        if (!res.ok) {
                            // Probeer error message te lezen
                            try {
                                const errorData = JSON.parse(text);
                                console.error("Server error:", errorData);
                                alert(`Fout: ${errorData.error || 'Onbekende fout'}`);
                            } catch {
                                console.error("Raw error:", text);
                                alert("Er ging iets fout bij het reserveren");
                            }
                            throw new Error("Reservering mislukt");
                        }

                        try {
                            const json = JSON.parse(text);
                            console.log("Reservering succesvol:", json);
                            alert("Reservering succesvol aangemaakt!");
                            this.$router.push(`/`);
                        } catch {
                            console.warn("Server stuurde geen JSON terug:", text);
                            alert("Reservering aangemaakt!");
                            this.$router.push(`/`);
                        }
                    })
                    .catch((err) => {
                        console.error("Fout bij reserveren:", err);
                    })
                    .finally(() => {
                        this.submitting = false;
                    });

            } catch (err) {
                console.error("Fout bij het verwerken:", err);
                alert("Er is iets misgegaan bij het verwerken van de reservering.");
                this.submitting = false;
            }
        },
    },

    mounted() {
        this.restaurantId = Number(this.$route.params.id);
        this.loadRestaurant();
        this.loadReservations();
    },
};
</script>

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