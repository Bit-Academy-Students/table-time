# Sprint Planning – TableTime

**Projectduur:** 5 sprints van 1 week
**Start:** 12 november 2025
**Team:** Alexander Zoet, Johan Tol, Keano Broekman, Rayan Karmimmech
**Tool:** GitHub Projects → https://github.com/orgs/Bit-Academy-Students/projects/11

---

## Overzicht per sprint

| Sprint | Periode | Hoofddoel |
|--------|---------|-----------|
| Sprint 0 | 10 – 12 nov | Planning, user stories, DoD, Docker setup |
| Sprint 1 | 12 – 20 nov | Basis backend + frontend opzetten |
| Sprint 2 | 21 – 28 nov | Reserveringsformulier + API koppeling |
| Sprint 3 | 28 nov – 4 dec | Login, dashboard, foutafhandeling |
| Sprint 4 | 4 – 11 dec | Afronding, testen, bugfixes |
| Sprint 5 | 11 – 18 dec | Eindpresentatie, resterende features |

---

## Sprint 0 – Voorbereiding

**Doel:** Projectstart, teamafspraken, technische keuzes vastleggen

| Taak | Wie | Uren |
|------|-----|------|
| Projectomschrijving schrijven | Johan | 1 |
| User stories opstellen (MoSCoW) | Heel team | 2 |
| Definition of Done bepalen | Heel team | 1 |
| Docker opzetten (PHP + Nginx + DB) | Johan + Alexander | 3 |
| Git repository inrichten + branches | Johan | 1 |

---

## Sprint 1 – Basis opzetten

**Doel:** Werkende backend structuur + eerste frontend pagina's

| User Story | Taak | Wie | Uren |
|-----------|------|-----|------|
| US-06 | RestaurantEntity + Repository aanmaken | Johan | 3 |
| US-06 | Maximale capaciteit toevoegen aan restaurant | Johan | 2 |
| US-01 | ReservationEntity + Repository aanmaken | Johan | 3 |
| US-01 | Basis API endpoints (GET/POST reserveringen) | Johan | 4 |
| – | Homepage design (Vue + Figma) | Alexander | 4 |
| – | Navbar + routing opzetten | Alexander | 2 |

**Sprint 1 capaciteit:** ~18 uur
**Verwacht klaar:** US-06 (deels), eerste backend endpoints

---

## Sprint 2 – Reserveringsformulier

**Doel:** Klant kan een reservering plaatsen via de frontend

| User Story | Taak | Wie | Uren |
|-----------|------|-----|------|
| US-01 | Capaciteitscheck in ReservationService | Johan | 4 |
| US-01 | API endpoint POST /Reservations afronden | Johan | 2 |
| US-08 | RestaurantController (registratie) | Johan | 3 |
| US-01 | Reserveringsformulier bouwen (Vue) | Alexander | 5 |
| US-02 | Kalender + tijdsloten in frontend | Alexander | 4 |
| – | Error handling backend | Keano + Rayan | 4 |

**Prioriteitswijziging na sprint 1 review:**
> De opdrachtgever gaf aan dat de homepage niet de prioriteit is — het reserveringsproces wel. De homepage-taken zijn naar sprint 5 verschoven. De reserveringstaak is geprioriteerd.

**Sprint 2 capaciteit:** ~22 uur
**Verwacht klaar:** US-01 (backend + frontend), US-08 (deels)

---

## Sprint 3 – Login + Dashboard

**Doel:** Restauranteigenaar kan inloggen en zijn dashboard bekijken

| User Story | Taak | Wie | Uren |
|-----------|------|-----|------|
| US-07 | Authenticatie endpoint (/Restaurants/authenticate) | Johan | 3 |
| US-07 | Wachtwoord toevoegen aan RestaurantEntity | Johan | 2 |
| US-05 | Reserveringen filteren per restaurant | Johan | 2 |
| US-03 | PUT /Reservations/{id} endpoint | Johan | 2 |
| US-04 | DELETE /Reservations/{id} endpoint | Johan | 1 |
| US-05 | Dashboard tijdlijn bouwen | Alexander | 6 |
| US-07 | Loginpagina + routing | Alexander + Johan | 3 |
| – | Error handling frontend | Rayan | 3 |

**Sprint 3 capaciteit:** ~22 uur
**Verwacht klaar:** US-03, US-04, US-07

---

## Sprint 4 – Testen + Bugfixes

**Doel:** Code stabiliseren en unit tests schrijven

| Taak | Wie | Uren |
|------|-----|------|
| Unit tests ReservationController | Johan | 4 |
| Unit tests ReservationService | Johan | 3 |
| Unit tests ReservationEntity | Johan | 1 |
| Unit tests frontend (isTimeFull, reservationUtils) | Alexander | 4 |
| Doc-blocks toevoegen aan alle PHP bestanden | Johan | 2 |
| Bugfixes reserveringslogica | Johan | 2 |

**Sprint 4 capaciteit:** ~16 uur

---

## Sprint 5 – Eindpresentatie

**Doel:** Resterende features + eindpresentatie

| Taak | Wie | Uren |
|------|-----|------|
| Kalenderweergave verbeteren | Johan + Alexander | 4 |
| Contactpagina bouwen | Keano | 2 |
| README + Docker instructies bijwerken | Johan | 1 |
| Portfolio documenten afronden | Heel team | 2 |
| Eindpresentatie voorbereiden | Heel team | 2 |

---

## Scrumboard

Zie GitHub Projects voor de actuele staat van het scrumboard:
https://github.com/orgs/Bit-Academy-Students/projects/11
