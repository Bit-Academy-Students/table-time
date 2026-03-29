# Code Uitleg – Johan Tol | TableTime

Dit document legt uit welke code ik (Johan Tol) heb geschreven, wat elk bestand doet en hoe de code werkt.

---

## Overzicht van mijn bijdrage

Ik was verantwoordelijk voor de **volledige backend reserveringslogica** van het project. De frontend is geschreven door Alexander Zoet.

Mijn code staat in:
```
src/api/src/Reservations/
├── ReservationController/ReservationController.php   ← HTTP-verzoeken afhandelen
├── ReservationEntity/ReservationEntity.php           ← Datamodel reservering
├── ReservationRepository/ReservationRepository.php   ← Database-toegang
└── ReservationService/ReservationService.php         ← Business logic

src/api/tests/unitTests/reservations/
├── reservationControllerTest.php   ← Tests voor de controller
├── reservationServiceTest.php      ← Tests voor de service
└── reservationEntityTest.php       ← Tests voor de entity
```

---

## 1. ReservationEntity.php

**Locatie:** `src/api/src/Reservations/ReservationEntity/ReservationEntity.php`

Dit is het datamodel van een reservering. Doctrine ORM gebruikt dit om de tabel in de database aan te maken en te beheren.

### Velden

| Veld | Type | Beschrijving |
|------|------|-------------|
| `id` | int | Unieke sleutel, automatisch gegenereerd |
| `Email` | string | E-mailadres van de bezoeker |
| `RestaurantId` | ManyToOne | Koppeling naar het restaurant (foreign key) |
| `StartDate` | datetime | Begin van de reservering |
| `EndDate` | datetime | Einde van de reservering |
| `AmountPeople` | int | Aantal personen |

### Relatie met Restaurant

De `@ManyToOne` annotatie legt vast dat meerdere reserveringen aan één restaurant kunnen hangen. De `@JoinColumn` zorgt dat Doctrine de kolom `RestaurantId` als foreign key aanmaakt in de database.

```php
#[ManyToOne(targetEntity: RestaurantEntity::class, inversedBy: "Reservations")]
#[JoinColumn(name: "RestaurantId", referencedColumnName: "id")]
private ?RestaurantEntity $RestaurantId;
```

---

## 2. ReservationService.php

**Locatie:** `src/api/src/Reservations/ReservationService/ReservationService.php`

De service bevat alle business logic. De controller roept de service aan — de service weet hoe reserveringen worden aangemaakt, gevalideerd en opgeslagen.

### sanitizeReservationData()

Controleert of de ingestuurde datum het juiste formaat heeft (`YYYY-MM-DD HH:mm`). Dit voorkomt dat ongeldige strings in de database terechtkomen. Bij fout gooit de methode een `InvalidArgumentException`.

```php
preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/', $data['startDate'])
```

### validateReservationData()

Bevat alle business rules:
1. startDate en endDate moeten aanwezig zijn
2. startDate moet vóór endDate liggen
3. amountPeople moet een positief geheel getal zijn
4. email moet een geldig formaat hebben
5. restaurantId moet verwijzen naar een bestaand restaurant
6. De capaciteitscheck: alle overlappende reserveringen bij hetzelfde restaurant worden opgeteld. Als het totaal de `maxCapacity` overschrijdt, wordt de reservering geweigerd.

**Overlap-check:**
```php
$isOverlapping = (
    ($reservation->getStartDate() > $data['startDate'] && $reservation->getStartDate() < $data['endDate']) ||
    ($reservation->getEndDate() > $data['startDate'] && $reservation->getEndDate() < $data['endDate']) ||
    ($reservation->getStartDate() <= $data['startDate'] && $reservation->getEndDate() >= $data['endDate'])
);
```

Een reservering overlapt als ze begint of eindigt binnen het gevraagde tijdvak, of het gevraagde tijdvak volledig omsluit.

### createReservation()

Roept `sanitize` → `validate` aan, haalt het restaurant op via de repository en slaat de nieuwe reservering op.

### updateReservation()

Werkt een bestaande reservering bij. Alleen de meegegeven velden worden gewijzigd (partial update).

### deleteReservation()

Zoekt de reservering op en verwijdert hem. Geeft `false` terug als hij niet bestaat.

---

## 3. ReservationController.php

**Locatie:** `src/api/src/Reservations/ReservationController/ReservationController.php`

De controller verwerkt HTTP-verzoeken en geeft JSON-responses terug. Hij weet niets van de database — dat delegeert hij aan de service.

### Endpoints

| Methode | URL | Beschrijving |
|---------|-----|-------------|
| GET | `/Reservations` | Alle reserveringen ophalen |
| GET | `/Reservations/{id}` | Één reservering ophalen |
| POST | `/Reservations` | Nieuwe reservering aanmaken |
| PUT | `/Reservations/{id}` | Reservering aanpassen |
| DELETE | `/Reservations/{id}` | Reservering verwijderen |

### Validatie in de controller

De controller controleert alleen of verplichte velden aanwezig zijn in de request. De business logic zit in de service.

```php
if (!isset($data['restaurantId'])) {
    return new JsonResponse(
        ['error' => 'restaurantId is verplicht'],
        Response::HTTP_BAD_REQUEST
    );
}
```

### Null-check restaurant

In `FindAll` en `FindById` wordt gecontroleerd of het restaurant van een reservering nog bestaat. Dit voorkomt dat de applicatie crasht bij verwijderde restaurants.

```php
'restaurant' => $restaurant ? [
    'id' => $restaurant->getId(),
    ...
] : null
```

---

## 4. Tests

**Locatie:** `src/api/tests/unitTests/reservations/`
**Framework:** PHPUnit
**Tests uitvoeren:**
```bash
docker exec -it php bash
cd api
php bin/phpunit
```

### reservationControllerTest.php (5 tests)

| Test | Wat het test |
|------|-------------|
| `testCreate` | Succesvolle aanmaak geeft 201 Created + reserveringsdata |
| `testCreateException` | Ontbrekend restaurantId geeft 400 Bad Request |
| `testDelete` | Verwijderen geeft `{"response": "deleted"}` |
| `testDeleteNotFound` | Verwijderen van niet-bestaande reservering geeft 404 |
| `testUpdate` | Succesvol aanpassen geeft 200 OK + bijgewerkte data |

De service wordt gemockt (`createMock`) zodat de controller los van de database getest kan worden.

### reservationServiceTest.php (2 tests)

| Test | Wat het test |
|------|-------------|
| `testCreate` | Reservering aanmaken met geldige data slaagt |
| `testCreateException` | Reservering zonder restaurantId gooit exception met bericht "Restaurant ID is required" |

### reservationEntityTest.php (2 tests)

| Test | Wat het test |
|------|-------------|
| `testEmail` | getId() geeft het juist ingestelde id terug |
| `testRestaurant` | getRestaurant() geeft het juist ingestelde restaurant terug |

**Totaal: 9 tests**
