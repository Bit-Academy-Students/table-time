<script setup>
import { ref, computed, onMounted, watch } from "vue";
import NavBar from "../components/NavBar.vue";
import NavbarMobile from "../components/NavbarMobile.vue";

const startHour = 8;
const endHour = 23.5;

const reservations = ref([]);
const selectedDate = ref(new Date().toISOString().split("T")[0]);

// Drawer / popup (we gebruiken een rechter drawer)
const showDrawer = ref(false);
const selectedReservation = ref(null);

// edit form binnen drawer
const editDate = ref("");
const editTime = ref("");
const editDuration = ref("01:00");
const editPeople = ref("");
const editEmail = ref("");

// Loaders
const isLoading = ref(false); // voor initial load + generale acties
const loadingSave = ref(false);
const loadingDelete = ref(false);

// tijdslots (exact hetzelfde als jouw reserveringsformulier)
const timeSlots = [
  "12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30",
  "16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30",
  "20:00","20:30","21:00","21:30"
];

const capacity = 60;

// -------------------- load reservations --------------------
function loadReservations() {
  isLoading.value = true;
  fetch("http://localhost:8080/Reservations")
    .then((r) => r.json())
    .then((data) => {
      reservations.value = data.Reservations || [];
    })
    .catch((e) => {
      console.error("Fout bij laden:", e);
      reservations.value = [];
    })
    .finally(() => {
      isLoading.value = false;
    });
}
onMounted(loadReservations);

// -------------------- date/time helpers --------------------
function parseDateToDecimal(dateString) {
  // "2025-01-01 14:00:00.000000"
  const [, time] = dateString.split(" ");
  const [h, m] = time.split(":").map(Number);
  return h + m / 60;
}

function formatTimeFromDate(dateString) {
  const [, time] = dateString.split(" ");
  const [h, m] = time.split(":");
  return `${h}:${m}`;
}

function pad(n) { return String(n).padStart(2, "0"); }

function formatDateTimeForAPI(dateObj) {
  return `${dateObj.getFullYear()}-${pad(dateObj.getMonth()+1)}-${pad(dateObj.getDate())} ${pad(dateObj.getHours())}:${pad(dateObj.getMinutes())}`;
}

// -------------------- timeline column algorithm --------------------
function assignColumns(resList) {
  const sorted = [...resList].sort((a, b) =>
    parseDateToDecimal(a.startDate.date) - parseDateToDecimal(b.startDate.date)
  );

  const columns = [];

  sorted.forEach(res => {
    const start = parseDateToDecimal(res.startDate.date);
    const end = parseDateToDecimal(res.endDate.date);

    let assigned = false;

    for (let i = 0; i < columns.length; i++) {
      const last = columns[i][columns[i].length - 1];
      const lastEnd = parseDateToDecimal(last.endDate.date);

      if (start >= lastEnd) {
        columns[i].push(res);
        res.columnIndex = i;
        assigned = true;
        break;
      }
    }

    if (!assigned) {
      columns.push([res]);
      res.columnIndex = columns.length - 1;
    }
  });

  return sorted;
}

const reservationsForSelectedDay = computed(() => {
  const today = reservations.value.filter(r =>
    r.startDate.date.startsWith(selectedDate.value)
  );
  return assignColumns(today);
});

// -------------------- reservation styles (unchanged) --------------------
function reservationStyle(r) {
  const start = parseDateToDecimal(r.startDate.date);
  const end = parseDateToDecimal(r.endDate.date);

  const top = (start - startHour) * 80;
  const height = (end - start) * 80;

  const columnWidth = 130;
  const gap = 10;

  return {
    position: "absolute",
    top: top + "px",
    left: (120 + r.columnIndex * (columnWidth + gap)) + "px",
    width: columnWidth + "px",
    height: height + "px",
    background: "#02c9ef",
    borderRadius: "6px",
    color: "white",
    padding: "8px",
    fontSize: "20px",
    display: "flex",
    flexDirection: "column",
    justifyContent: "space-between",
    cursor: "pointer",
    zIndex: 10
  };
}

// -------------------- drawer open/close & prefill --------------------
function openDrawerFor(res) {
  selectedReservation.value = res;
  // pre-fill the edit form using same format as your frontend
  editDate.value = res.startDate.date.split(" ")[0];
  editTime.value = formatTimeFromDate(res.startDate.date);
  // compute duration between start and end
  const start = new Date(res.startDate.date);
  const end = new Date(res.endDate.date);
  const diffMinutes = Math.round((end - start) / 60000);
  if (diffMinutes === 60) editDuration.value = "01:00";
  else if (diffMinutes === 90) editDuration.value = "01:30";
  else editDuration.value = "02:00";
  editPeople.value = res.amountPeople;
  editEmail.value = res.email;

  showDrawer.value = true;
}

function closeDrawer() {
  showDrawer.value = false;
  selectedReservation.value = null;
}

// -------------------- isTimeFull checker (excludes currently edited reservation) --------------------
function isTimeFullForEdit(time) {
  // matches your frontend logic but excludes the currently edited reservation
  if (!editDate.value) return true;
  if (!editPeople.value || Number(editPeople.value) <= 0) return true;

  const startCheck = new Date(`${editDate.value}T${time}:00`);
  const now = new Date();
  if (startCheck.getTime() < now.getTime()) return true;

  const [durH, durM] = editDuration.value.split(":").map(Number);
  const endCheck = new Date(startCheck);
  endCheck.setHours(endCheck.getHours() + durH);
  endCheck.setMinutes(endCheck.getMinutes() + durM);

  const overlappingPeople = reservations.value
    .filter((r) => {
      if (!selectedReservation.value) return true; // in case
      if (r.id === selectedReservation.value.id) return false; // exclude itself

      const start = new Date(r.startDate.date);
      const end = new Date(r.endDate.date);
      return startCheck < end && endCheck > start;
    })
    .reduce((sum, r) => sum + r.amountPeople, 0);

  return (overlappingPeople + Number(editPeople.value)) > capacity;
}

// -------------------- save / delete --------------------
function saveChanges() {
  if (!selectedReservation.value) return;
  loadingSave.value = true;

  try {
    const [hours, minutes] = editTime.value.split(":").map(Number);
    const [durHours, durMinutes] = editDuration.value.split(":").map(Number);

    const start = new Date(editDate.value);
    start.setHours(hours, minutes, 0, 0);

    const end = new Date(start);
    end.setHours(end.getHours() + durHours);
    end.setMinutes(end.getMinutes() + durMinutes);

    const body = {
      startDate: formatDateTimeForAPI(start),
      endDate: formatDateTimeForAPI(end),
      amountPeople: Number(editPeople.value),
      email: editEmail.value
    };

    fetch(`http://localhost:8080/Reservations/${selectedReservation.value.id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(body)
    })
      .then((res) => {
        if (!res.ok) throw new Error("Niet succesvol");
        return res.json().catch(() => ({}));
      })
      .then(() => {
        loadReservations();
        closeDrawer();
      })
      .catch((e) => {
        console.error("Fout bij opslaan:", e);
        alert("Opslaan is mislukt.");
      })
      .finally(() => {
        loadingSave.value = false;
      });
  } catch (err) {
    console.error(err);
    loadingSave.value = false;
  }
}

function deleteReservation() {
  if (!selectedReservation.value) return;
  loadingDelete.value = true;

  fetch(`http://localhost:8080/Reservations/${selectedReservation.value.id}`, {
    method: "DELETE"
  })
    .then((res) => {
      if (!res.ok) throw new Error("Niet succesvol");
      return res.text().catch(() => "");
    })
    .then(() => {
      loadReservations();
      closeDrawer();
    })
    .catch((e) => {
      console.error("Fout bij verwijderen:", e);
      alert("Verwijderen is mislukt.");
    })
    .finally(() => {
      loadingDelete.value = false;
    });
}

// -------------------- navigation days --------------------
function nextDay() {
  const d = new Date(selectedDate.value);
  d.setDate(d.getDate() + 1);
  selectedDate.value = d.toISOString().split("T")[0];
}

function prevDay() {
  const d = new Date(selectedDate.value);
  d.setDate(d.getDate() - 1);
  selectedDate.value = d.toISOString().split("T")[0];
}

const timeLabels = computed(() => {
  const labels = [];
  for (let h = startHour; h <= endHour; h++) {
    const hour = Math.floor(h);
    if (h % 1 === 0) labels.push(`${hour}:00`);
  }
  return labels;
});
</script>

<template>
  <NavBar />
  <NavbarMobile />
  <main class="p-[100px]">

    <div class="flex justify-between items-center mb-6">
      <button @click="prevDay">
        Vorige dag
      </button>

      <h1 class="text-2xl font-semibold">
        Reserveringen op {{ selectedDate }}
      </h1>

      <button @click="nextDay">
        Volgende dag
      </button>
    </div>

    <!-- LOADER overlay -->
    <div v-if="isLoading"
      class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-999">

      <div class="flex flex-col items-center">
        <div
          class="animate-spin rounded-full h-20 w-20 border-t-4 border-b-4 border-[#02c9ef]">
        </div>
        <p class="text-white mt-4 text-xl">Laden...</p>
      </div>
    </div>

    <div class="relative bg-gray-300">

      <div v-for="(t, i) in timeLabels" :key="i"
        class="absolute flex items-center"
        :style="{ top: (i * 80) + 'px', left:'0', height:'80px', width:'100%' }">

        <div class="w-[100px] text-right pr-10 text-[28px] text-[#03CAED]">
          {{ t }}
        </div>

        <div style="flex:1; height: 3px; background: orange;"></div>
      </div>

      <div v-for="r in reservationsForSelectedDay"
        :key="r.id"
        :style="reservationStyle(r)"
        @click="openDrawerFor(r)">

        <div class="text-[20px] overflow-hidden">
          {{ r.email }}
        </div>

        <div style="font-size:20px; align-self:flex-end;">
          {{ r.amountPeople }}p
        </div>

      </div>
    </div>

    <!-- RIGHT DRAWER -->
    <div
      v-if="showDrawer"
      class="fixed inset-0 z-176 flex"
      aria-hidden="false"
    >
      <!-- overlay -->
      <div class="fixed inset-0" @click="closeDrawer"></div>

      <!-- drawer panel -->
      <aside
        class="ml-auto bg-white shadow-xl h-full w-[620px] p-6 transform transition-transform"
        style="will-change: transform;"
      >
        <div class="flex justify-between items-start">
          <h2 class="text-xl font-semibold mb-2">Reservering aanpassen</h2>
          <button @click="closeDrawer" class="text-sm w-[50px]">X</button>
        </div>

        <p class="mb-2 text-gray-700">
          <b>Email:</b> {{ editEmail }}
        </p>

        <!-- Datum -->
        <label class="block mb-2">
          Datum:
          <input v-model="editDate" type="date" class="border p-2 w-full" />
        </label>

        <!-- Tijdslots: exact zoals reserveringsformulier -->
        <label class="block font-semibold mb-2">Tijd</label>
        <div class="grid grid-cols-4 gap-2 mb-4">
          <button
            v-for="t in timeSlots"
            :key="t"
            @click="!isTimeFullForEdit(t) && (editTime = t)"
            class="p-2 border w-[75px] rounded text-sm"
            :class="{
              'bg-[#FF8000] text-white': editTime === t,
              'hover:bg-gray-200 cursor-pointer': !isTimeFullForEdit(t),
              'bg-gray-100 text-gray-400 cursor-not-allowed': isTimeFullForEdit(t)
            }"
            :disabled="isTimeFullForEdit(t)"
          >
            {{ t }}
          </button>
        </div>

        <!-- Duur -->
        <label class="block mb-2">
          Duur:
          <select v-model="editDuration" class="border p-2 w-full mb-4">
            <option value="01:00">1 uur</option>
            <option value="01:30">1.5 uur</option>
            <option value="02:00">2 uur</option>
          </select>
        </label>

        <!-- Personen -->
        <label class="block mb-4">
          Personen:
          <input v-model="editPeople" type="number" min="1" class="border p-2 w-full" />
        </label>

        <!-- Actieknoppen -->
        <div class="flex justify-between mt-4">
          <button
            @click="deleteReservation"
            class="bg-red-500 text-white px-4 py-2 rounded flex items-center"
            :disabled="loadingDelete"
          >
            <span v-if="!loadingDelete">Reservering annuleren</span>
            <div v-else class="w-5 h-5 border-4 border-t-transparent rounded-full animate-spin"></div>
          </button>

          <button
            @click="saveChanges"
            class="bg-[#03CAED] text-white px-4 py-2 rounded flex items-center"
            :disabled="loadingSave"
          >
            <span v-if="!loadingSave">Opslaan</span>
            <div v-else class="w-5 h-5 border-4 border-t-transparent rounded-full animate-spin"></div>
          </button>
        </div>
      </aside>
    </div>

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
