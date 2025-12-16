<!--
/**
 * Bestandsnaam: RestaurantDashboard.vue
 *
 * Beschrijving:
 * Dit script verzorgt de volledige logica van het restaurantdashboard.
 * Het dashboard toont reserveringen per dag in een tijdlijn, biedt
 * mogelijkheden om reserveringen te bewerken of te annuleren en
 * controleert of de gebruiker geautoriseerd is.
 *
 * Auteur: Alexander Zoet
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.0
 * - Laatste wijziging: <datum invullen>
 * - Beheer: Git
 */
-->
<script setup>
/*
 * Vue Composition API utilities
 */
import { ref, computed, onMounted } from "vue";

/*
 * Vue Router voor navigatie en ophalen van routeparameters
 */
import { useRouter, useRoute } from "vue-router";

/*
 * Layout componenten
 */
import NavBar from "../components/NavBar.vue";
import NavbarMobile from "../components/NavbarMobile.vue";
import Footer from "../components/Footer.vue";

/*
 * Router instanties
 */
const router = useRouter();
const route = useRoute();

/*
 * Configuratie van de tijdlijn (start- en einduur)
 */
const startHour = 8;
const endHour = 23.5;

/*
 * State: reserveringen en restaurantgegevens
 */
const reservations = ref([]);
const restaurant = ref(null);
const restaurantId = ref(Number(route.params.id));

/*
 * Geselecteerde datum (standaard vandaag)
 */
const selectedDate = ref(new Date().toISOString().split("T")[0]);

/*
 * Drawer (detailbewerking reservering)
 */
const showDrawer = ref(false);
const selectedReservation = ref(null);

/*
 * Edit-formulier state
 */
const editDate = ref("");
const editTime = ref("");
const editDuration = ref("01:00");
const editPeople = ref("");
const editEmail = ref("");

/*
 * Loaders voor UX-feedback
 */
const isLoading = ref(false);
const loadingSave = ref(false);
const loadingDelete = ref(false);

/*
 * Beschikbare tijdslots voor reserveringen
 */
const timeSlots = [
  "12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30",
  "16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30",
  "20:00","20:30","21:00","21:30"
];

/**
 * Maximale capaciteit van het restaurant
 */
const capacity = computed(() => restaurant.value?.maxcapacity || 60);

/**
 * Controleert of de gebruiker geauthenticeerd is
 *
 * Verifieert of de ingelogde restaurant-ID overeenkomt
 * met het dashboard dat wordt bezocht.
 */
function checkAuth() {
  const isLoggedIn = localStorage.getItem('isLoggedIn');
  const restaurantData = localStorage.getItem('restaurantData');

  if (isLoggedIn !== 'true' || !restaurantData) {
    alert('Je moet inloggen om toegang te krijgen tot het dashboard');
    router.push('/restaurant/login');
    return false;
  }

  try {
    const data = JSON.parse(restaurantData);

    if (data.id !== restaurantId.value) {
      alert('Je hebt geen toegang tot dit dashboard');
      router.push(`/restaurant/${data.id}/dashboard`);
      return false;
    }

    return true;
  } catch (e) {
    console.error('Fout bij parsen restaurantdata:', e);
    router.push('/restaurant/login');
    return false;
  }
}

/**
 * Laadt restaurantgegevens vanuit de backend
 */
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

/**
 * Laadt alle reserveringen voor het geselecteerde restaurant
 */
function loadReservations() {
  isLoading.value = true;

  fetch(`http://localhost:8080/Reservations`)
    .then(r => r.json())
    .then(data => {
      const all = data.Reservations || [];
      reservations.value = all.filter(r =>
        r.restaurant && r.restaurant.id === restaurantId.value
      );
    })
    .catch(e => {
      console.error("Fout bij laden reserveringen:", e);
      reservations.value = [];
    })
    .finally(() => {
      isLoading.value = false;
    });
}

/*
 * Initialisatie bij laden van de pagina
 */
onMounted(() => {
  if (!restaurantId.value) {
    alert("Geen restaurant geselecteerd");
    router.push('/');
    return;
  }

  if (!checkAuth()) return;

  loadRestaurant();
  loadReservations();
});

/**
 * Hulpfuncties voor datum- en tijdverwerking
 */
function parseDateToDecimal(dateString) {
  const [, time] = dateString.split(" ");
  const [h, m] = time.split(":").map(Number);
  return h + m / 60;
}

function formatTimeFromDate(dateString) {
  const [, time] = dateString.split(" ");
  return time.slice(0, 5);
}

function pad(n) {
  return String(n).padStart(2, "0");
}

function formatDateTimeForAPI(dateObj) {
  return `${dateObj.getFullYear()}-${pad(dateObj.getMonth()+1)}-${pad(dateObj.getDate())} ${pad(dateObj.getHours())}:${pad(dateObj.getMinutes())}:00`;
}

/**
 * Verdeelt overlappende reserveringen over kolommen
 * zodat ze visueel naast elkaar worden weergegeven
 */
function assignColumns(resList) {
  const sorted = [...resList].sort(
    (a, b) => parseDateToDecimal(a.startDate) - parseDateToDecimal(b.startDate)
  );

  const columns = [];

  sorted.forEach(res => {
    const start = parseDateToDecimal(res.startDate);
    const end = parseDateToDecimal(res.endDate);
    let placed = false;

    for (let i = 0; i < columns.length; i++) {
      const last = columns[i][columns[i].length - 1];
      if (start >= parseDateToDecimal(last.endDate)) {
        columns[i].push(res);
        res.columnIndex = i;
        placed = true;
        break;
      }
    }

    if (!placed) {
      columns.push([res]);
      res.columnIndex = columns.length - 1;
    }
  });

  return sorted;
}

/**
 * Reserveringen gefilterd op geselecteerde datum
 */
const reservationsForSelectedDay = computed(() => {
  const today = reservations.value.filter(r =>
    r.startDate.startsWith(selectedDate.value)
  );
  return assignColumns(today);
});

/**
 * Berekent de inline CSS-styling van een reservering in de tijdlijn
 */
function reservationStyle(r) {
  const start = parseDateToDecimal(r.startDate);
  const end = parseDateToDecimal(r.endDate);

  return {
    position: "absolute",
    top: (start - startHour) * 80 + "px",
    left: (120 + r.columnIndex * 140) + "px",
    width: "130px",
    height: (end - start) * 80 + "px",
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

/**
 * Opent de drawer en vult het edit-formulier
 */
function openDrawerFor(res) {
  selectedReservation.value = res;
  editDate.value = res.startDate.split(" ")[0];
  editTime.value = formatTimeFromDate(res.startDate);
  editPeople.value = res.amountPeople;
  editEmail.value = res.email;

  const diff = (new Date(res.endDate) - new Date(res.startDate)) / 60000;
  editDuration.value = diff === 60 ? "01:00" : diff === 90 ? "01:30" : "02:00";

  showDrawer.value = true;
}

/**
 * Sluit de drawer
 */
function closeDrawer() {
  showDrawer.value = false;
  selectedReservation.value = null;
}

/**
 * Controleert of een tijdslot beschikbaar is
 */
function isTimeFullForEdit(time) {
  if (!editDate.value || !editPeople.value) return true;

  const startCheck = new Date(`${editDate.value}T${time}:00`);
  const now = new Date();
  if (startCheck < now) return true;

  const [h, m] = editDuration.value.split(":").map(Number);
  const endCheck = new Date(startCheck);
  endCheck.setHours(endCheck.getHours() + h);
  endCheck.setMinutes(endCheck.getMinutes() + m);

  const used = reservations.value
    .filter(r =>
      r.id !== selectedReservation.value?.id &&
      startCheck < new Date(r.endDate) &&
      endCheck > new Date(r.startDate)
    )
    .reduce((sum, r) => sum + r.amountPeople, 0);

  return used + Number(editPeople.value) > capacity.value;
}

/**
 * Slaat wijzigingen aan een reservering op
 */
function saveChanges() {
  if (!selectedReservation.value) return;
  loadingSave.value = true;

  const [h, m] = editTime.value.split(":").map(Number);
  const [dh, dm] = editDuration.value.split(":").map(Number);

  const start = new Date(editDate.value);
  start.setHours(h, m, 0);

  const end = new Date(start);
  end.setHours(end.getHours() + dh);
  end.setMinutes(end.getMinutes() + dm);

  fetch(`http://localhost:8080/Reservations/${selectedReservation.value.id}`, {
    method: "PUT",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      startDate: formatDateTimeForAPI(start),
      endDate: formatDateTimeForAPI(end),
      amountPeople: Number(editPeople.value),
      email: editEmail.value
    })
  })
    .then(() => {
      loadReservations();
      closeDrawer();
      alert("Reservering succesvol aangepast!");
    })
    .catch(() => alert("Opslaan mislukt"))
    .finally(() => loadingSave.value = false);
}

/**
 * Verwijdert een reservering
 */
function deleteReservation() {
  if (!selectedReservation.value) return;
  if (!confirm("Weet je zeker dat je deze reservering wilt annuleren?")) return;

  loadingDelete.value = true;

  fetch(`http://localhost:8080/Reservations/${selectedReservation.value.id}`, {
    method: "DELETE"
  })
    .then(() => {
      loadReservations();
      closeDrawer();
      alert("Reservering succesvol geannuleerd!");
    })
    .catch(() => alert("Verwijderen mislukt"))
    .finally(() => loadingDelete.value = false);
}

/**
 * Navigatie tussen dagen
 */
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

/**
 * Tijdlabels voor de tijdlijn
 */
const timeLabels = computed(() => {
  const labels = [];
  for (let h = startHour; h <= endHour; h++) {
    if (h % 1 === 0) labels.push(`${Math.floor(h)}:00`);
  }
  return labels;
});
</script>

<template>
  <NavBar />
  <NavbarMobile />
  <main class="p-[100px]">

    <div v-if="restaurant" class="mb-6 bg-white rounded-xl shadow-lg p-6">
      <h1 class="text-3xl font-bold text-[#03CAED] mb-2">Dashboard: {{ restaurant.naam }}</h1>
      <div class="flex gap-6 text-gray-600">
        <span>📍 {{ restaurant.locatie }}</span>
        <span>👥 Capaciteit: {{ restaurant.maxcapacity }}</span>
        <span>✉️ {{ restaurant.email }}</span>
      </div>
    </div>

    <div class="flex justify-between items-center mb-6">
      <button @click="prevDay">
        ← Vorige dag
      </button>

      <h2 class="text-2xl font-semibold">
        Reserveringen op {{ selectedDate }}
      </h2>

      <button @click="nextDay">
        Volgende dag →
      </button>
    </div>

    <div class="mb-4">
      <button 
        @click="router.push(`/restaurant/${restaurantId}`)"
        class="px-4 py-2 bg-[#03CAED] text-white rounded-lg hover:bg-[#02a8c4] transition"
      >
        ← Terug naar restaurant pagina
      </button>
    </div>

    <div v-if="isLoading"
      class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-999">
      <div class="flex flex-col items-center">
        <div class="animate-spin rounded-full h-20 w-20 border-t-4 border-b-4 border-[#02c9ef]"></div>
        <p class="text-white mt-4 text-xl">Laden...</p>
      </div>
    </div>

    <div class="relative rounded-lg" style="min-height: 1240px;">
      <div v-for="(t, i) in timeLabels" :key="i"
        class="absolute flex items-center"
        :style="{ top: (i * 80) + 'px', left:'0', height:'80px', width:'100%' }">
        <div class="w-[100px] font-jockey text-right pr-10 text-[28px] text-[#03CAED]">
          {{ t }}
        </div>
        <div style="flex:1; height: 3px; background: orange;"></div>
      </div>

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
    </div>

    <div v-if="showDrawer" class="fixed inset-0 z-[176] flex" aria-hidden="false">
      <div class="fixed inset-0" @click="closeDrawer"></div>

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
            class="w-[75px] p-2 border rounded text-sm transition"
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
  <Footer />
</template>



<style scoped>
@keyframes spin {
  to { transform: rotate(360deg); }
}
.animate-spin {
  animation: spin 1s linear infinite;
}
</style>