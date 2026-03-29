# Code Uitleg – Alexander Zoet | Table Time

Dit document legt uit welke code ik (Alexander Zoet) heb geschreven, wat elke bestand doet, en hoe de code werkt. Dit is bedoeld als voorbereiding op een code-uitlegmoment.

---

## Overzicht van mijn bijdrage

Ik was verantwoordelijk voor de **volledige frontend** van het project. De backend (PHP/Symfony) is geschreven door mijn teamgenoten Johan, Keano en Rayan.

Mijn code staat in:
```
src/website/Table-time-Front-end/src/
├── pages/              ← de pagina's van de website
├── components/         ← herbruikbare stukken (navbar, footer)
├── utils/              ← hulpfuncties voor de reserveringslogica
└── router/index.js     ← regelt welke pagina je ziet bij welke URL
```

---

## 1. reservationUtils.js

**Locatie:** `src/website/Table-time-Front-end/src/utils/reservationUtils.js`

Dit bestand bevat hulpfuncties voor datum- en tijdverwerking. Ze worden gebruikt in het dashboard en op de reserveringspagina.

### Functies

**`parseDateToDecimal(dateString)`**
Zet een tijdstring om naar een decimaal getal.
- Input: `"2025-01-01 12:30"`
- Output: `12.5`
- Waarom: De tijdlijn in het dashboard rekent met decimalen. 12:30 is 12 uur en een half, dus 12.5.

**`formatTimeFromDate(dateString)`**
Haalt alleen de tijd (HH:mm) uit een volledige datetime-string.
- Input: `"2025-01-01 18:45:00"`
- Output: `"18:45"`
- Waarom: De backend geeft datums in lang formaat terug, maar ik wil alleen de tijd laten zien.

**`formatDateTimeForAPI(dateObj)`**
Zet een JavaScript Date-object om naar het formaat dat de backend verwacht.
- Input: `new Date(2025, 0, 1, 9, 5)`
- Output: `"2025-01-01 09:05:00"`
- Waarom: JavaScript Date-objecten hebben een ander formaat dan wat de Symfony API verwacht.

**`assignColumns(resList)`**
Dit is de meest complexe functie. Het verdeelt reserveringen over kolommen zodat overlappende reserveringen naast elkaar staan in het dashboard.
- Als reservering A en B tegelijk lopen, komen ze in kolom 0 en kolom 1
- Als reservering C na A maar voor B eindigt, past C in kolom 0 (na A)
- Waarom: Zonder dit zouden overlappende reserveringen over elkaar heen staan in de tijdlijn

**Hoe `assignColumns` werkt stap voor stap:**
1. Sorteer alle reserveringen op starttijd (vroegste eerst)
2. Loop door elke reservering
3. Kijk of er al een kolom is waar deze reservering achter past (niet overlapt)
4. Zo ja: zet hem in die kolom
5. Zo nee: maak een nieuwe kolom aan
6. Sla het kolomnummer op als `columnIndex`

---

## 2. isTimeFull.js

**Locatie:** `src/website/Table-time-Front-end/src/utils/isTimeFull.js`

Deze functie controleert of een tijdslot nog beschikbaar is voor een nieuwe reservering.

### Hoe werkt het?

De functie krijgt deze informatie mee:
- `date` – de gewenste datum
- `time` – het gewenste tijdstip
- `duration` – hoe lang de reservering duurt
- `reservations` – lijst van alle bestaande reserveringen
- `capacity` – de maximale capaciteit van het restaurant
- `amountPeople` – hoeveel mensen mee willen

**Stap voor stap:**
1. Check of alle verplichte velden ingevuld zijn (anders: geblokkeerd)
2. Check of de starttijd niet in het verleden ligt (anders: geblokkeerd)
3. Bereken de eindtijd: start + duur
4. Filter alle bestaande reserveringen die overlappen met dit tijdvak
5. Tel het totaal aantal mensen van die overlappende reserveringen op
6. Geeft `true` (vol) terug als `bestaande mensen + nieuwe mensen > capaciteit`

**Overlap-check:**
Een reservering overlapt als:
- Ze begint vóór onze eindtijd **EN**
- Ze eindigt na onze starttijd

Dit wordt gecontroleerd met: `start < e && end > s`

---

## 3. ReservationDashboard.vue

**Locatie:** `src/website/Table-time-Front-end/src/pages/ReservationDashboard.vue`

Dit is het dashboard voor restauranteigenaren. Hier kunnen ze hun reserveringen per dag bekijken, aanpassen of annuleren.

### Hoe werkt het?

**Authenticatie:**
Bij het laden van de pagina controleert `checkAuth()` of de ingelogde eigenaar wel toegang heeft tot dit specifieke dashboard. Zo niet, wordt die doorgestuurd naar de loginpagina of hun eigen dashboard.

**Data laden:**
- `loadRestaurant()` haalt de restaurantgegevens op van de backend
- `loadReservations()` haalt alle reserveringen op en filtert op het huidige restaurant

**Tijdlijn:**
- De tijdlijn gaat van 8:00 tot 23:30
- Elke `80px` in hoogte is één uur
- De positie van een reservering wordt berekend met `reservationStyle(r)`:
  - `top` = (starttijd - 8) × 80 pixels naar beneden
  - `left` = 120 + (kolomnummer × 140) pixels naar rechts
  - `height` = (eindtijd - starttijd) × 80 pixels hoog

**Drawer (zijpaneel):**
Als je op een reservering klikt, opent `openDrawerFor(r)` een zijpaneel. Daarin kan je:
- De datum, tijd, duur, personen aanpassen
- De reservering annuleren

Bij het aanpassen wordt `isTimeFullForEdit()` gebruikt — dit is dezelfde logica als `isTimeFull.js` maar past de huidige reservering zelf uit de check zodat je je eigen tijdslot kan wijzigen.

**Opslaan:**
`saveChanges()` berekent de nieuwe start- en eindtijd en stuurt een `PUT` request naar de backend.

**Verwijderen:**
`deleteReservation()` stuurt een `DELETE` request na een bevestigingsmelding.

---

## 4. RestaurantInfo.vue

**Locatie:** `src/website/Table-time-Front-end/src/pages/RestaurantInfo.vue`

Dit is de publieke pagina van een restaurant waar klanten een reservering kunnen maken.

### Hoe werkt het?

**Kalender:**
De kalender wordt opgebouwd door `daysInMonth`. Voor elke dag in de huidige maand wordt berekend:
- Is de dag al voorbij? Dan is hij uitgeschakeld
- Is er minstens één tijdslot met nog plek? Dan is hij beschikbaar
- Zo niet: uitgeschakeld

De beschikbaarheidscheck per dag werkt hetzelfde als `isTimeFull.js` — voor elk tijdslot (12:00, 12:30, etc.) kijkt het of er plek is gegeven het aantal personen en de duur.

**Tijdsloten:**
Na het selecteren van een dag verschijnen de tijdknoppen. Elk knopje roept `isTimeFull(time)` aan. Als het vol is, wordt de knop grijs en uitgeschakeld.

**Reservering versturen:**
`submitReservation()` bouwt de start- en eindtijd op en stuurt een `POST` request naar `/Reservations`. Als het lukt, gaat de gebruiker terug naar de homepage.

**Watchers:**
Als de gebruiker het aantal personen of de duur aanpast, reset de kalender automatisch. Dit voorkomt dat je een datum selecteert die bij een andere groepsgrootte geldt.

---

## 5. RestaurantList.vue

**Locatie:** `src/website/Table-time-Front-end/src/pages/RestaurantList.vue`

Laat alle restaurants zien die zich hebben aangemeld.

### Hoe werkt het?
- Bij laden: `fetch` naar `/Restaurants` in de backend
- Toont een laad-indicator terwijl de data opgehaald wordt
- Zet de restaurants in een responsief grid (1 kolom op mobiel, 3 op desktop)
- Klikken op een restaurant gaat naar `/restaurant/:id`

---

## 6. NavBar.vue

**Locatie:** `src/website/Table-time-Front-end/src/components/NavBar.vue`

De navigatiebalk bovenaan de pagina.

### Hoe werkt het?
- Toont de menu-links: Home, Restaurants, Over Ons, Contact
- Controleert bij laden of er iemand ingelogd is via `localStorage.getItem('isLoggedIn')`
- Als je ingelogd bent als restauranteigenaar: toont "Dashboard" en "Uitloggen"
- Als je niet ingelogd bent: toont "Inloggen" en "Registreren"
- Luistert naar `storage`-events zodat de navbar ook bijwerkt als je in een ander tabblad inlogt

---

## 7. RestaurantLogin.vue

**Locatie:** `src/website/Table-time-Front-end/src/pages/RestaurantLogin.vue`

De inlogpagina voor restauranteigenaren.

### Hoe werkt het?
- Stuurt de inloggegevens naar `/Restaurants/authenticate` in de backend
- Bij succes: slaat `isLoggedIn` en `restaurantData` op in `localStorage`
- Stuurt door naar het dashboard van het betreffende restaurant
- Bij fout: toont een foutmelding

---

## 8. Registration.vue

**Locatie:** `src/website/Table-time-Front-end/src/pages/Registration.vue`

Het registratieformulier voor nieuwe restaurants.

### Hoe werkt het?
- Verzamelt naam, email, wachtwoord, locatie, telefoonnummer en capaciteit
- Stuurt een `POST` request naar `/Restaurants`
- Bij succes: bevestigingsmelding en doorsturen naar loginpagina

---

## 9. router/index.js

**Locatie:** `src/website/Table-time-Front-end/src/router/index.js`

Dit bestand regelt welke pagina je ziet bij welke URL.

### Routes
| URL | Pagina |
|-----|--------|
| `/` | Home.vue |
| `/RestaurantList` | RestaurantList.vue |
| `/restaurant/:id` | RestaurantInfo.vue (reserveringspagina) |
| `/restaurant/:id/dashboard` | ReservationDashboard.vue |
| `/register` | Registration.vue |
| `/restaurant/login` | RestaurantLogin.vue |
| `/aboutUs` | AboutUs.vue |
| `/contact` | ContactPage.vue |

De `:id` is een dynamische parameter — dit is het ID van het restaurant. Zo kan elke restaurant zijn eigen pagina en dashboard hebben.

---

## 10. isTimeFull.test.js & reservationUtils.test.js

**Locatie:** `src/website/Table-time-Front-end/src/utils/`

Dit zijn de unit tests die ik heb geschreven voor de reserveringslogica. Ze draaien met Vitest.

**Tests uitvoeren:**
```bash
cd src/website/Table-time-Front-end
npx vitest run
```

De tests controleren onder andere:
- Dat je niet in het verleden kan reserveren
- Dat de capaciteitscheck correct werkt
- Dat overlappende reserveringen goed worden geteld
- Dat de tijdlijn-kolommen correct worden toegewezen

Zie `../Opdracht 4 - Testen/tests.md` voor de volledige uitleg per test.
