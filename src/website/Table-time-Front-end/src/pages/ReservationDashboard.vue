<script>
export default {
  props: ["reservations"],

  data() {
    return {
      view: "month", // month | week | day
      startHour: 7,
      endHour: 23
    };
  },

  computed: {
    hours() {
      let h = [];
      for (let i = this.startHour; i <= this.endHour; i++) {
        h.push(i);
      }
      return h;
    },

    // Maak een lijst dagen afhankelijk van VIEW
    days() {
      if (!this.reservations.length) return [];

      // alle datums verzamelen
      const allDates = this.reservations.map(r =>
        r.startDate.date.split(" ")[0]
      );

      // unieke dagen
      const unique = [...new Set(allDates)].sort();

      if (this.view === "day") return unique.slice(0, 1);
      if (this.view === "week") return unique.slice(0, 7);
      return unique; // maand
    },

    // Reserveringen per dag gesorteerd
    reservationsByDay() {
      let map = {};
      this.days.forEach(d => (map[d] = []));

      this.reservations.forEach(r => {
        const d = r.startDate.date.split(" ")[0];
        if (map[d]) map[d].push(r);
      });

      return map;
    }
  },

  methods: {
    // positie & grootte berekenen voor CSS grid
    blockStyle(res) {
      const start = res.startDate.date.split(" ")[1].split(":");
      const end = res.endDate.date.split(" ")[1].split(":");

      const startHour = parseInt(start[0]);
      const endHour = parseInt(end[0]);

      const colStart = startHour - this.startHour + 1;
      const colEnd = endHour - this.startHour + 1;

      return {
        gridColumn: `${colStart} / ${colEnd}`,
        backgroundColor: "#60A5FA", // lichtblauw
        color: "white",
        padding: "4px",
        borderRadius: "6px"
      };
    }
  }
};
</script>

<template>
  <div class="p-4 w-full">

    <!-- SWITCH -->
    <div class="flex gap-2 mb-4">
      <button
        class="px-3 py-1 rounded bg-gray-200"
        @click="view = 'day'"
      >Day</button>

      <button
        class="px-3 py-1 rounded bg-gray-200"
        @click="view = 'week'"
      >Week</button>

      <button
        class="px-3 py-1 rounded bg-gray-200"
        @click="view = 'month'"
      >Month</button>
    </div>

    <!-- HEADER HOURS -->
    <div class="grid"
         :style="{
            gridTemplateColumns: '150px repeat(' + (endHour-startHour) + ', 1fr)'
         }"
    >
      <div></div>

      <div
        v-for="h in hours"
        :key="h"
        class="text-xs text-gray-600 text-center"
      >
        {{ h }}:00
      </div>
    </div>

    <!-- DAYS ROWS -->
    <div v-for="day in days" :key="day"
      class="grid border-b py-4 items-center"
      :style="{
        gridTemplateColumns: '150px repeat(' + (endHour-startHour) + ', 1fr)'
      }">
      
      <!-- DAY LABEL -->
      <div class="font-semibold text-gray-700">{{ day }}</div>

      <!-- BLOCKS -->
      <div
        class="relative col-span-full grid"
        :style="{ gridTemplateColumns: 'repeat(' + (endHour-startHour) + ', 1fr)' }"
      >
        <div
          v-for="r in reservationsByDay[day]"
          :key="r.id"
          class="absolute h-6"
          :style="blockStyle(r)"
        >
          {{ r.email }} ({{ r.amountPeople }}p)
        </div>
      </div>

    </div>
  </div>
</template>
