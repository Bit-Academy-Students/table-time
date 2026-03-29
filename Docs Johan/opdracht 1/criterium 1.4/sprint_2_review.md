# Sprint 2 Review – TableTime

## Einde sprint 2 / begin sprint 3

---

## Doelen sprint 2 – terugblik

| Taak | Status | Toelichting |
|------|--------|-------------|
| Capaciteitscheck in ReservationService | ✅ Klaar | Overlappende reserveringen worden opgeteld en gecontroleerd tegen maxcapacity |
| POST /Reservations endpoint afronden | ✅ Klaar | Volledige validatie + foutmeldingen bij ontbrekende velden |
| RestaurantController (registratie) | ✅ Klaar | Restaurant kan zich aanmelden via POST /Restaurants |
| Reserveringsformulier (frontend) | ✅ Klaar | Alexander heeft het formulier afgerond |
| Kalender + tijdsloten | ✅ Klaar | Tijdsloten worden grijs als ze vol zijn |
| Error handling backend | ✅ Klaar | Keano en Rayan hebben validaties doorgevoerd |

---

## Demo

De reserveringspagina is functioneel. Een bezoeker kan:
- Een datum selecteren
- Een beschikbaar tijdslot kiezen
- Het aantal personen en een e-mailadres invullen
- De reservering bevestigen

De backend weigert reserveringen als de capaciteit vol is. Dit is getest via Postman.

**Prioriteitswijziging sprint 2:**
Na de sprint 1 review gaf de opdrachtgever aan dat de homepage niet de prioriteit is — het reserveren wel. We hebben de homepage-taken verschoven naar sprint 5 en de reserveringstaak geprioriteerd. Dit bleek de juiste keuze: het reserveringsformulier is nu werkend.

---

## Doelen sprint 3

- Inlogpagina afronden zodat restauranteigenaren kunnen inloggen: **Alexander + Johan**
- Wachtwoord toevoegen aan `RestaurantEntity` voor authenticatie: **Johan**
- Authenticatie-endpoint (`/Restaurants/authenticate`) bouwen: **Johan**
- Dashboard tijdlijn verbeteren: **Alexander**
- Error handling frontend: **Rayan**

---

## Aandachtspunten

- De loginpagina was nog niet klaar aan het einde van sprint 2. Dit heeft hogere prioriteit in sprint 3.
- De reserveringsfiltering per restaurant (voor het dashboard) moet nog worden gebouwd.
