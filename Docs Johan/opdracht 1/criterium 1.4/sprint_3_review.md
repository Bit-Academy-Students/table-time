# Sprint 3 Review – TableTime

## Einde sprint 3 / begin sprint 4

---

## Doelen sprint 3 – terugblik

| Taak | Status | Toelichting |
|------|--------|-------------|
| Wachtwoord toevoegen aan RestaurantEntity | ✅ Klaar | Wachtwoordveld + authenticatie logica toegevoegd |
| Authenticatie-endpoint (/Restaurants/authenticate) | ✅ Klaar | Login werkt voor restauranteigenaren |
| PUT /Reservations/{id} (aanpassen) | ✅ Klaar | Reservering kan worden bijgewerkt |
| DELETE /Reservations/{id} (annuleren) | ✅ Klaar | Reservering kan worden verwijderd |
| Reserveringen filteren per restaurant | ✅ Klaar | Dashboard toont alleen reserveringen van ingelogd restaurant |
| Dashboard tijdlijn | ✅ Klaar | Alexander heeft de tijdlijn afgerond |
| Loginpagina frontend | ✅ Klaar | Eigenaar kan inloggen en wordt doorgestuurd naar dashboard |

---

## Demo

De applicatie heeft nu een werkende authenticatieflow:
1. Restauranteigenaar logt in op `/restaurant/login`
2. Na succesvolle login wordt restaurantdata opgeslagen in `localStorage`
3. Eigenaar ziet zijn eigen dashboard met reserveringen per dag

Afwegingen gemaakt in sprint 3:
- De capaciteitscheck-notities van de opdrachtgever (max 40 personen, lunch 1 uur, diner 2 uur) zijn verwerkt als richtlijn voor het reserveringsformulier in de frontend. De backend validatie was al aanwezig.
- We hebben bewust gekozen om authenticatie via `localStorage` te doen i.p.v. sessies, omdat dit eenvoudiger te implementeren was binnen de sprinttijd.

---

## Doelen sprint 4

- Unit tests schrijven voor de reserveringslogica (backend + frontend): **Johan + Alexander**
- Doc-blocks toevoegen aan alle PHP-bestanden: **Johan**
- Bugfixes op basis van testresultaten: **Johan**
- Kalender verbeteren (niet-beschikbare tijden grijs): **Alexander**

---

## Taakverdeling sprint 5

| Teamlid | Taak |
|---------|------|
| Johan | Kalender (backend ondersteuning) |
| Alexander | Kalender (frontend) |
| Keano | Contactpagina |
| Rayan | Nog te bepalen |
