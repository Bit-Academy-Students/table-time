<template>
  <NavBar />
  <NavbarMobile />
  <main class="p-[100px]">

    <!-- Restaurant Info Header -->
    <div v-if="restaurant" class="mb-6 bg-white rounded-xl shadow-lg p-6">
      <h1 class="text-3xl font-bold text-[#03CAED] mb-2">Dashboard: {{ restaurant.naam }}</h1>
      <div class="flex gap-6 text-gray-600">
        <span>📍 {{ restaurant.locatie }}</span>
        <span>👥 Capaciteit: {{ restaurant.maxcapacity }}</span>
        <span>✉️ {{ restaurant.email }}</span>
      </div>
    </div>

    <div class="flex justify-between items-center mb-6">
      <button @click="prevDay" class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
        ← Vorige dag
      </button>

      <h2 class="text-2xl font-semibold">
        Reserveringen op {{ selectedDate }}
      </h2>

      <button @click="nextDay" class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
        Volgende dag →
      </button>
    </div>

    <!-- Terug naar restaurant info knop -->
    <div class="mb-4">
      <button 
        @click="router.push(`/restaurant/${restaurantId}`)"
        class="px-4 py-2 bg-[#03CAED] text-white rounded-lg hover:bg-[#02a8c4] transition"
      >
        ← Terug naar restaurant pagina
      </button>
    </div>

    <!-- LOADER overlay -->
    <div v-if="isLoading"
      class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[999]">
      <div class="flex flex-col items-center">
        <div class="animate-spin rounded-full h-20 w-20 border-t-4 border-b-4 border-[#02c9ef]"></div>
        <p class="text-white mt-4 text-xl">Laden...</p>
      </div>
    </div>

    <!-- Timeline -->
    <div class="relative bg-gray-300 rounded-lg" style="min-height: 1240px;">
      <div v-for="(t, i) in timeLabels" :key="i"
        class="absolute flex items-center"
        :style="{ top: (i * 80) + 'px', left:'0', height:'80px', width:'100%' }">
        <div class="w-[100px] text-right pr-10 text-[28px] text-[#03CAED]">
          {{ t }}
        </div>
        <div style="flex:1; height: 3px; background: orange;"></div>
      </div>

      <!-- Reservations -->
      <div v-for="r in reservationsForSelectedDay"
        :key="r.id"
        :style="reservationStyle(r)"
        @click="openDrawerFor(r)"
        class="hover:opacity-90 transition">
        <div class="text-[20px] overflow-hidden">
          {{ r.email }}
        </div>
        <div style="font-size:20px; align-self:flex-end;">
          {{ r.amountPeople }}p
        </div>
      </div>

      <!-- Geen reserveringen message -->
      <div v-if="reservationsForSelectedDay.length === 0" 
        class="absolute inset-0 flex items-center justify-center">
        <p class="text-gray-500 text-xl">Geen reserveringen op deze dag</p>
      </div>
    </div>

    <!-- RIGHT DRAWER -->
    <div v-if="showDrawer" class="fixed inset-0 z-[176] flex" aria-hidden="false">
      <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeDrawer"></div>

      <aside class="ml-auto bg-white shadow-xl h-full w-[620px] p-6 transform transition-transform relative z-10 overflow-y-auto">
        <div class="flex justify-between items-start mb-4">
          <h2 class="text-xl font-semibold">Reservering aanpassen</h2>
          <button @click="closeDrawer" class="text-2xl font-bold hover:text-red-500 transition">×</button>
        </div>

        <p class="mb-4 text-gray-700">
          <b>Email:</b> {{ editEmail }}
        </p>

        <label class="block mb-4">
          <span class="font-semibold">Datum:</span>
          <input v-model="editDate" type="date" class="border p-2 w-full mt-1 rounded" />
        </label>

        <label class="block font-semibold mb-2">Tijd</label>
        <div class="grid grid-cols-4 gap-2 mb-4">
          <button
            v-for="t in timeSlots"
            :key="t"
            @click="!isTimeFullForEdit(t) && (editTime = t)"
            class="p-2 border rounded text-sm transition"
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

        <label class="block mb-4">
          <span class="font-semibold">Duur:</span>
          <select v-model="editDuration" class="border p-2 w-full mt-1 rounded">
            <option value="01:00">1 uur</option>
            <option value="01:30">1.5 uur</option>
            <option value="02:00">2 uur</option>
          </select>
        </label>

        <label class="block mb-4">
          <span class="font-semibold">Personen:</span>
          <input 
            v-model="editPeople" 
            type="number" 
            min="1" 
            :max="capacity"
            class="border p-2 w-full mt-1 rounded" 
          />
        </label>

        <div class="flex justify-between mt-6 gap-4">
          <button
            @click="deleteReservation"
            class="bg-red-500 text-white px-6 py-3 rounded hover:bg-red-600 transition flex items-center justify-center flex-1"
            :disabled="loadingDelete"
          >
            <span v-if="!loadingDelete">Annuleren</span>
            <div v-else class="w-5 h-5 border-4 border-t-transparent border-white rounded-full animate-spin"></div>
          </button>

          <button
            @click="saveChanges"
            class="bg-[#03CAED] text-white px-6 py-3 rounded hover:bg-[#02a8c4] transition flex items-center justify-center flex-1"
            :disabled="loadingSave"
          >
            <span v-if="!loadingSave">Opslaan</span>
            <div v-else class="w-5 h-5 border-4 border-t-transparent border-white rounded-full animate-spin"></div>
          </button>
        </div>
      </aside>
    </div>

  </main>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import NavBar from "../components/NavBar.vue";
import NavbarMobile from "../components/NavbarMobile.vue";

const router = useRouter();
const route = useRoute();

const startHour = 8;
const endHour = 23.5;

const reservations = ref([]);
const selectedDate = ref(new Date().toISOString().split("T")[0]);
const restaurant = ref(null);
const restaurantId = ref(Number(route.params.id));

// Drawer / popup
const showDrawer = ref(false);
const selectedReservation = ref(null);

// Edit form
const editDate = ref("");
const editTime = ref("");
const editDuration = ref("01:00");
const editPeople = ref("");
const editEmail = ref("");

// Loaders
const isLoading = ref(false);
const loadingSave = ref(false);
const loadingDelete = ref(false);

// Tijdslots
const timeSlots = [
  "12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30",
  "16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30",
  "20:00","20:30","21:00","21:30"
];

const capacity = computed(() => restaurant.value?.maxcapacity || 60);

// -------------------- Load restaurant info --------------------
function loadRestaurant() {
  fetch(`http://localhost:8080/Restaurants/${restaurantId.value}`)
    .then(res => res.json())
    .then(data => {
      restaurant.value = data.Restaurant || data;
    })
    .catch(e => {
      console.error("Fout bij laden restaurant:", e);
    });
}

// -------------------- Load reservations voor specifiek restaurant --------------------
function loadReservations() {
  isLoading.value = true;
  
  fetch(`http://localhost:8080/Reservations`)
    .then((r) => r.json())
    .then((data) => {
      const allReservations = data.Reservations || [];
      
      // Filter alleen reserveringen van dit specifieke restaurant
      reservations.value = allReservations.filter(r => 
        r.restaurant && r.restaurant.id === restaurantId.value
      );
    })
    .catch((e) => {
      console.error("Fout bij laden:", e);
      reservations.value = [];
    })
    .finally(() => {
      isLoading.value = false;
    });
}

onMounted(() => {
  if (!restaurantId.value) {
    alert("Geen restaurant geselecteerd");
    router.push('/restaurants');
    return;
  }
  loadRestaurant();
  loadReservations();
});

// -------------------- Date/time helpers --------------------
function parseDateToDecimal(dateString) {
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
  return `${dateObj.getFullYear()}-${pad(dateObj.getMonth()+1)}-${pad(dateObj.getDate())} ${pad(dateObj.getHours())}:${pad(dateObj.getMinutes())}:00`;
}

// -------------------- Timeline column algorithm --------------------
function assignColumns(resList) {
  const sorted = [...resList].sort((a, b) =>
    parseDateToDecimal(a.startDate) - parseDateToDecimal(b.startDate)
  );

  const columns = [];

  sorted.forEach(res => {
    const start = parseDateToDecimal(res.startDate);
    const end = parseDateToDecimal(res.endDate);

    let assigned = false;

    for (let i = 0; i < columns.length; i++) {
      const last = columns[i][columns[i].length - 1];
      const lastEnd = parseDateToDecimal(last.endDate);

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
    r.startDate.startsWith(selectedDate.value)
  );
  return assignColumns(today);
});

// -------------------- Reservation styles --------------------
function reservationStyle(r) {
  const start = parseDateToDecimal(r.startDate);
  const end = parseDateToDecimal(r.endDate);

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

// -------------------- Drawer open/close & prefill --------------------
function openDrawerFor(res) {
  selectedReservation.value = res;
  editDate.value = res.startDate.split(" ")[0];
  editTime.value = formatTimeFromDate(res.startDate);
  
  const start = new Date(res.startDate);
  const end = new Date(res.endDate);
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

// -------------------- isTimeFull checker --------------------
function isTimeFullForEdit(time) {
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
      if (!selectedReservation.value) return true;
      if (r.id === selectedReservation.value.id) return false;

      const start = new Date(r.startDate);
      const end = new Date(r.endDate);
      return startCheck < end && endCheck > start;
    })
    .reduce((sum, r) => sum + r.amountPeople, 0);

  return (overlappingPeople + Number(editPeople.value)) > capacity.value;
}

// -------------------- Save / Delete --------------------
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
        alert("Reservering succesvol aangepast!");
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
  if (!confirm("Weet je zeker dat je deze reservering wilt annuleren?")) return;
  
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
      alert("Reservering succesvol geannuleerd!");
    })
    .catch((e) => {
      console.error("Fout bij verwijderen:", e);
      alert("Verwijderen is mislukt.");
    })
    .finally(() => {
      loadingDelete.value = false;
    });
}

// -------------------- Navigation days --------------------
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

<style scoped>
@keyframes spin {
  to { transform: rotate(360deg); }
}
.animate-spin {
  animation: spin 1s linear infinite;
}
</style>