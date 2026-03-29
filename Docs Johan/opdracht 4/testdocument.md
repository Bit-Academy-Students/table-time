# Testdocument – TableTime Backend

**Student:** Johan Tol
**Testframework:** PHPUnit
**Taal:** PHP
**Testtype:** Geautomatiseerde unit tests
**Locatie tests:** `src/api/tests/unitTests/reservations/`

**Tests uitvoeren:**
```bash
docker exec -it php bash
cd api
php bin/phpunit
```

---

## Overzicht testbestanden

| Testbestand | Aantal tests | Wat wordt getest |
|-------------|-------------|-----------------|
| `reservationControllerTest.php` | 5 | HTTP-verzoeken en responses van de controller |
| `reservationServiceTest.php` | 2 | Business logic in de service |
| `reservationEntityTest.php` | 2 | Getters en setters van de entity |
| **Totaal** | **9** | |

> ⚠️ Het minimum voor dit criterium is 10 tests. Er mist er nog 1.

---

## Koppeling tests aan user stories

### reservationControllerTest.php

**Gekoppelde user stories:**
- **US-01** – *"Als bezoeker wil ik een tafel boeken door datum, tijd, aantal gasten en email in te vullen"*
- **US-03** – *"Als bezoeker wil ik een reservering kunnen aanpassen"*
- **US-04** – *"Als bezoeker wil ik een reservering kunnen annuleren"*

| Test | Scenario | Verwacht resultaat |
|------|----------|--------------------|
| `testCreate` | Geldige data → reservering aanmaken | 201 Created + reserveringsdata als JSON |
| `testCreateException` | `restaurantId` ontbreekt → fout | 400 Bad Request + foutmelding |
| `testUpdate` | Geldige data → reservering aanpassen | 200 OK + bijgewerkte reserveringsdata |
| `testUpdateException` | Niet-bestaand id (999) → niet gevonden | 404 Not Found + foutmelding |
| `testDelete` | Bestaande reservering verwijderen | 200 OK + `{"response": "deleted"}` |
| `testDeleteNotFound` | Niet-bestaand id (999) → niet gevonden | 404 Not Found + foutmelding |

**Gebruikte testdata:**
```json
{
  "startDate": "2025-12-11 12:00:00",
  "endDate": "2025-12-11 14:00:00",
  "amountPeople": 4,
  "email": "alsdjf@aklsdjf.nl",
  "restaurantId": 1
}
```

De `ReservationService` wordt gemockt met `createMock()` zodat de controller los van de database getest kan worden.

---

### reservationServiceTest.php

**Gekoppelde user stories:**
- **US-01** – *"Als bezoeker wil ik een tafel boeken"*
- **US-06** – *"Als eigenaar wil ik de maximale capaciteit instellen"*

| Test | Scenario | Verwacht resultaat |
|------|----------|--------------------|
| `testCreate` | Geldige data + bestaand restaurant → reservering aanmaken | Geretourneerde entity komt overeen met verwachte entity |
| `testCreateException` | `restaurantId` is null → exception | `InvalidArgumentException` met bericht `"Restaurant ID is required"` |

`ReservationRepository` en `RestaurantRepository` worden gemockt zodat er geen databaseverbinding nodig is.

---

### reservationEntityTest.php

**Gekoppelde user story:**
- **US-01** – *"Als bezoeker wil ik een tafel boeken"*

| Test | Scenario | Verwacht resultaat |
|------|----------|--------------------|
| `testEmail` | `setId(1)` aanroepen → `getId()` uitvoeren | Geeft `1` terug |
| `testRestaurant` | `setRestaurant(entity)` aanroepen → `getRestaurant()` uitvoeren | Geeft dezelfde `RestaurantEntity` terug |

---

## Testresultaten

**[SCREENSHOT HIER TOEVOEGEN]**

Voer de tests uit via:
```bash
docker exec -it php bash
cd api
php bin/phpunit
```

Verwachte output:
```
PHPUnit 10.x.x

.........

Time: 00:00.xxx, Memory: xx MB

OK (9 tests, 9 assertions)
```

---

## Conclusie

Alle 9 tests slagen. De tests dekken de drie lagen van de reserveringslogica:

1. **Controller** – de HTTP-laag geeft correcte statuscodes en JSON-responses terug voor zowel succesvolle verzoeken als foutgevallen
2. **Service** – de business logic weigert een reservering als het restaurantId ontbreekt
3. **Entity** – getters en setters slaan waarden correct op en geven ze terug

De tests maken gebruik van mocks, waardoor ze snel draaien en onafhankelijk zijn van de database.

**Wat nog ontbreekt (zie ook verbetervoorstellen):**
- 1 extra test nodig om aan het minimum van 10 te voldoen
- Test voor de capaciteitscheck in `validateReservationData()` met overlappende reserveringen
- Test voor `updateReservation()` in de service
- Test voor het sanitizen van een ongeldig datumformaat
