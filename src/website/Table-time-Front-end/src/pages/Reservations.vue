<script setup>
import NavBar from "../components/NavBar.vue";
import NavbarMobile from "../components/NavbarMobile.vue";
</script>

<script>
export default {
  data() {
    return {
      reservations: [],

      form: {
        date: "",
        time: "",
        duration: "01:00",
        amountPeople: null, // let user invullen eerst
        email: "",
      },

      currentYear: new Date().getFullYear(),
      currentMonth: new Date().getMonth(),
      selectedDay: null,

      capacity: 60,

      loadingDay: false,
      loadingDayDate: null,

      // de timeslots die je beschikbaar wilt tonen (pas aan wanneer nodig)
      timeSlots: [
        "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30",
        "16:00", "16:30", "17:00", "17:30", "18:00", "18:30", "19:00", "19:30",
        "20:00", "20:30", "21:00", "21:30"
      ],
    };
  },

  computed: {
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

      // lege vakjes
      for (let i = 1; i < firstDay; i++) {
        days.push({ empty: true });
      }

      for (let d = 1; d <= total; d++) {
        const dateString = `${year}-${String(month + 1).padStart(2, "0")}-${String(d).padStart(2, "0")}`;

        const dateObj = new Date(dateString);
        dateObj.setHours(0, 0, 0, 0);

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Als gebruiker nog geen aantal personen invult -> disable alle dagen
        if (!this.form.amountPeople || Number(this.form.amountPeople) <= 0) {
          days.push({
            empty: false,
            day: d,
            fullDate: dateString,
            disabled: true,
            discount: d >= 26 ? "-50%" : null,
          });
          continue;
        }

        // Voor de dag: bepaal of er minstens 1 timeslot beschikbaar is
        const anySlotHasSpace = this.timeSlots.some((slot) => {
          // bouw tijd-check start voor die slot
          const startCheck = new Date(`${dateString}T${slot}:00`);

          // als startCheck in verleden -> niet beschikbaar
          const now = new Date();
          if (startCheck.getTime() < now.getTime()) return false;

          // bereken endCheck op basis van duration
          const [durH, durM] = this.form.duration.split(":").map(Number);
          const endCheck = new Date(startCheck);
          endCheck.setHours(endCheck.getHours() + durH);
          endCheck.setMinutes(endCheck.getMinutes() + durM);

          // bereken overlappingPeople voor die slot
          const overlappingPeople = this.reservations
            .filter((r) => {
              const start = new Date(r.startDate.date);
              const end = new Date(r.endDate.date);
              // overlap check
              return startCheck < end && endCheck > start;
            })
            .reduce((sum, r) => sum + r.amountPeople, 0);

          // als deze slot ruimte heeft voor jouw aantal -> beschikbaar
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

  methods: {
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

    /**
     * When user clicks a day:
     * - if empty or definitely disabled -> return
     * - else: quick validation (simulate a small check) and set date/time
     * - loader used only while this quick check runs
     */
    chooseDay(day) {
      if (day.empty) return;

      // prevent selecting days that are clearly disabled
      if (day.disabled) {
        // if it's disabled because all slots are full, we do a quick re-check with loader
        // sometimes UI and reservations may be slightly out of sync, so try a fast recheck
        this.loadingDay = true;
        this.loadingDayDate = day.fullDate;

        // quick async recheck (very short) to determine final state
        setTimeout(() => {
          const anySlotHasSpace = this._checkDayHasSpace(day.fullDate);
          this.loadingDay = false;
          this.loadingDayDate = null;

          if (!anySlotHasSpace) {
            alert("Sorry — voor deze dag is momenteel geen beschikbare tijdslot meer voor dat aantal personen.");
            return;
          } else {
            // rare case: it became available again -> let user select
            this._selectDay(day);
          }
        }, 150); // korte loader; alleen zichtbaar als echt nodig
        return;
      }

      // normale flow: day is selectable
      this._selectDay(day);
    },

    // helper om daadwerkelijk de dag als gekozen te markeren
    _selectDay(day) {
      this.selectedDay = day.day;

      const m = String(this.currentMonth + 1).padStart(2, "0");
      const d = String(day.day).padStart(2, "0");

      this.form.date = `${this.currentYear}-${m}-${d}`;
      this.form.time = ""; // reset tijd
    },

    // controleert (synchroon) voor een volledige dag of er minstens 1 slot ruimte heeft
    _checkDayHasSpace(dateString) {
      // snelle guard: als amountPeople niet ingevuld -> geen ruimte
      if (!this.form.amountPeople || Number(this.form.amountPeople) <= 0) return false;

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
            const start = new Date(r.startDate.date);
            const end = new Date(r.endDate.date);
            return startCheck < end && endCheck > start;
          })
          .reduce((sum, r) => sum + r.amountPeople, 0);

        return (overlappingPeople + Number(this.form.amountPeople)) <= this.capacity;
      });
    },

    // ------------------------------------------
    // CHECK OF TIJD VOL / GEWEEST / OVERLAPT
    // ------------------------------------------
    isTimeFull(time) {
      if (!this.form.date) return true;
      if (!this.form.amountPeople || Number(this.form.amountPeople) <= 0) return true;

      // gefixeerde timestamp zonder timezone-bugs
      const startCheck = new Date(`${this.form.date}T${time}:00`);

      const now = new Date();

      // FULL FIX — tijd in verleden = disabled
      if (startCheck.getTime() < now.getTime()) {
        return true;
      }

      // bereken eindtijd van dit tijdslot
      const [durH, durM] = this.form.duration.split(":").map(Number);
      const endCheck = new Date(startCheck);
      endCheck.setHours(endCheck.getHours() + durH);
      endCheck.setMinutes(endCheck.getMinutes() + durM);

      // check overlapping met reserveringen
      const overlappingPeople = this.reservations
        .filter((r) => {
          const start = new Date(r.startDate.date);
          const end = new Date(r.endDate.date);

          // slot overlapt wanneer:
          return startCheck < end && endCheck > start;
        })
        .reduce((sum, r) => sum + r.amountPeople, 0);

      // als dit boven capaciteit komt → tijdslot dicht
      // LET OP: we voegen jouw aantal personen toe aan overlappingPeople om te checken of het past
      return (overlappingPeople + Number(this.form.amountPeople)) > this.capacity;
    },


    //-------------------------------------------
    // LOAD RESERVATIONS
    //-------------------------------------------
    loadReservations() {
      fetch("http://localhost:8080/Reservations")
        .then((res) => res.json())
        .then((data) => {
          // verwacht data.Reservations (zoals in jouw originele)
          this.reservations = data.Reservations || [];
        })
        .catch((e) => {
          console.error("Fout bij laden:", e);
          this.reservations = [];
        });
    },

    //-------------------------------------------
    // JOUW EXACTE SUBMIT METHODE (NIET AANGEPAST)
    //-------------------------------------------
    submitReservation() {
      try {
        const [hours, minutes] = this.form.time.split(":").map(Number);
        const [durHours, durMinutes] = this.form.duration.split(":").map(Number);

        const start = new Date(this.form.date);
        start.setHours(hours, minutes);

        const end = new Date(start);
        end.setHours(end.getHours() + durHours);
        end.setMinutes(end.getMinutes() + durMinutes);

        const pad = (n) => String(n).padStart(2, "0");
        const fmt = (d) =>
          `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(
            d.getHours()
          )}:${pad(d.getMinutes())}`;

        const body = {
          startDate: fmt(start),
          endDate: fmt(end),
          amountPeople: Number(this.form.amountPeople),
          email: this.form.email,
        };

        console.log("Reservering verzenden:", body);

        fetch("http://localhost:8080/Reservations", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(body),
        })
          .then(async (res) => {
            const text = await res.text();
            try {
              const json = JSON.parse(text);
              console.log("Reservering succesvol:", json);
              alert("Reservering succesvol aangemaakt!");
              // herlaad reserveringen zodat UI up-to-date is
              this.loadReservations();
            } catch {
              console.warn("Server stuurde geen JSON terug:");
              console.log(text);
              alert("Er is iets misgegaan bij het maken van de reservering.");
            }
          })
          .catch((err) => {
            console.error("Fout bij reserveren:", err);
            alert("Er is iets misgegaan bij het maken van de reservering.");
          });
      } catch (err) {
        console.error("Fout bij het verwerken:", err);
        alert("Er is iets misgegaan bij het verwerken van de reservering.");
      }
    },
  },

  mounted() {
    this.loadReservations();
  },
};
</script>

<template>
  <NavBar />
  <NavbarMobile />

  <main class="flex justify-center pt-[100px]">
    <section class="w-[420px] h-auto pb-12">

      <h2 class="text-2xl font-semibold mb-4">Tafel reserveren</h2>
      <div class="bg-white p-4 rounded-xl shadow mb-4">
        <h3 class="font-semibold mb-2">Aantal personen</h3>
        <input type="number" v-model.number="form.amountPeople" class="w-full p-2 border rounded" min="1" />
        <p v-if="!form.amountPeople || form.amountPeople <= 0" class="text-xs text-gray-500 mt-2">
          Vul eerst het aantal personen in om beschikbare dagen te zien.
        </p>
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
            <span v-if="day.discount" class="absolute bottom-1 bg-[#FF8000] text-white text-[10px] px-1 rounded">
              {{ day.discount }}
            </span>

            <!-- Loader overlay voor individuele dag wanneer nodig -->
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
        <h3 class="font-semibold mb-2">Duur</h3>
        <select v-model="form.duration" class="w-full p-2 border rounded">
          <option value="01:00">1 uur</option>
          <option value="01:30">1.5 uur</option>
          <option value="02:00">2 uur</option>
        </select>
      </div>



      <div class="bg-white p-4 rounded-xl shadow mb-4">
        <h3 class="font-semibold mb-2">Email</h3>
        <input type="email" v-model="form.email" class="w-full p-2 border rounded" />
      </div>

      <form @submit.prevent="submitReservation">
        <button type="submit" class="w-full bg-[#03CAED] text-white p-3 rounded">
          Reserveren
        </button>
      </form>

    </section>
  </main>
</template>

<style scoped>
/* eenvoudige loader style (kan je vervangen/met tailwind verbeteren) */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>
