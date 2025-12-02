<script>
import NavBar from "../components/NavBar.vue";
import NavbarMobile from '../components/NavbarMobile.vue';

export default {
  components: { NavBar, NavbarMobile },

  data() {
    return {
      form: {
        date: "",
        time: "",
        duration: "01:00",
        amountPeople: 1,
        email: "",
      },
      currentYear: new Date().getFullYear(),
      currentMonth: new Date().getMonth(), // 0 = januari
      selectedDay: null,
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

      // lege vakjes voor de eerste dag
      for (let i = 1; i < firstDay; i++) {
        days.push({ empty: true });
      }

      // echte dagen
      for (let d = 1; d <= total; d++) {
        const dateObj = new Date(year, month, d);
        const disabled = dateObj < new Date().setHours(0, 0, 0, 0);

        days.push({
          day: d,
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

    chooseDay(day) {
      if (day.disabled || day.empty) return;

      this.selectedDay = day.day;

      const m = String(this.currentMonth + 1).padStart(2, "0");
      const d = String(day.day).padStart(2, "0");

      this.form.date = `${this.currentYear}-${m}-${d}`;
    },

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
          `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}`;

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
        console.error("Fout bij het verwerken van de reservering:", err);
        alert("Er is iets misgegaan bij het verwerken van de reservering.");
      }
    },
  },
};
</script>

<template>
  <NavBar />
  <NavbarMobile />

  <main class="flex justify-center mt-6">
    <section class="w-[420px] h-[300vh]">

      <h2 class="text-2xl font-semibold mb-4">Tafel reserveren</h2>

      <div class="bg-white border-[#03CAED] border-2 z-10 p-4 rounded-xl shadow mb-8">
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
          <div
            v-for="(day, i) in daysInMonth"
            :key="i"
            class="h-12 flex flex-col items-center justify-center z-20 rounded-lg text-sm relative"
            :class="{
              'bg-gray-100 text-gray-400': day.disabled,
              'bg-[#03CAED] text-white': selectedDay === day.day,
              'hover:bg-gray-200 cursor-pointer': !day.disabled && !day.empty
            }"
            @click="chooseDay(day)"
          >
            <span v-if="!day.empty">{{ day.day }}</span>
            <span v-if="day.discount" class="absolute bottom-1 bg-[#FF8000] text-white text-[10px] px-1 rounded">
              {{ day.discount }}
            </span>
          </div>
        </div>
      </div>

      <div class="bg-white p-4 rounded-xl shadow mb-8">
        <h3 class="font-semibold mb-2">Kies tijd</h3>
        <div class="grid grid-cols-4 gap-2">
          <button
            v-for="t in ['12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30']"
            :key="t"
            @click="form.time = t"
            class="p-2 border w-[75px] rounded text-sm"
            :class="form.time === t ? 'bg-[#FF8000] text-white' : 'hover:bg-gray-200'"
          >
            {{ t }}
          </button>
        </div>
      </div>

      <div class="bg-white p-4 rounded-xl shadow mb-8">
        <h3 class="font-semibold mb-2">Duur</h3>
        <select v-model="form.duration" class="w-full p-2 border rounded">
          <option value="01:00">1 uur</option>
          <option value="01:30">1.5 uur</option>
          <option value="02:00">2 uur</option>
        </select>
      </div>

      <div class="bg-white p-4 rounded-xl shadow mb-8">
        <h3 class="font-semibold mb-2">Aantal personen</h3>
        <input
          type="number"
          v-model="form.amountPeople"
          class="w-full p-2 border rounded"
          min="1"
        />
      </div>

      <div class="bg-white p-4 rounded-xl shadow mb-8">
        <h3 class="font-semibold mb-2">Email</h3>
        <input
          type="email"
          v-model="form.email"
          class="w-full p-2 border rounded"
        />
      </div>



      <form @submit.prevent="submitReservation">
        <button type="submit">
          Reserveren
        </button>
      </form>

    </section>
  </main>
</template>
