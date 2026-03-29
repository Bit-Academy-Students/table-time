# Verbetervoorstellen – Johan Tol | TableTime Backend

Dit document beschrijft verbetervoorstellen op basis van de testresultaten, de oplevering en een persoonlijke reflectie op het project.

---

## 1. Verbetervoorstellen op basis van testen

### 1.1 Capaciteitscheck niet getest met overlappende reserveringen

De `validateReservationData()` methode in `ReservationService` bevat de capaciteitscheck — de kern van de reserveringslogica. Toch is er geen test die dit direct controleert. De huidige tests mocken de service volledig weg in de controller-tests, waardoor de capaciteitslogica nooit echt wordt geraakt in de testsuite.

**Concreet probleem:** als er een bug zit in de overlap-berekening, slagen alle huidige tests nog steeds.

**Verbetering:**
Een test toevoegen in `reservationServiceTest.php` waarbij de `ReservationRepository` mock een bestaande reservering teruggeeft die overlapt, en controleren dat de service een `InvalidArgumentException` gooit met het bericht `"Maximum capacity exceeded"`.

**Koppeling aan testconclusie:** In het testdocument is dit al benoemd als ontbrekend scenario.

---

### 1.2 Geen test voor `updateReservation()` in de service

De `updateReservation()` methode in de service is alleen indirect getest via de controller. De service-test bevat alleen `testCreate` en `testCreateException`. Hierdoor is het gedrag bij een `PUT`-verzoek op serviceniveau niet gevalideerd.

**Concreet probleem:** als de sanitize of de restaurantlookup in `updateReservation()` faalt, wordt dit niet gevangen door de testsuite.

**Verbetering:**
Twee tests toevoegen in `reservationServiceTest.php`:
- Succesvol updaten met geldige data
- Updaten met niet-bestaand restaurantId gooit exception

---

### 1.3 Ongeldige datumformaten niet getest

`sanitizeReservationData()` valideert het datumformaat via een regex, maar dit is nooit getest. Als iemand een datum stuurt als `"11-12-2025"` of `"morgen"`, gooit de functie een `InvalidArgumentException` — maar dit pad is nooit bewezen te werken.

**Concreet probleem:** een ongeldige datumstring kan onverwacht gedrag geven als de regex niet goed matcht.

**Verbetering:**
Test toevoegen met invoer `"11-12-2025 12:00"` (verkeerd formaat) en controleren dat de exception correct wordt gegooid.

---

## 2. Verbetervoorstellen op basis van oplevering

### 2.1 Wachtwoorden worden opgeslagen als plain text

In `RestaurantEntity` is het `wachtwoord` veld een gewone string:

```php
#[ORM\Column(length: 255)]
private string $wachtwoord;
```

Er is geen wachtwoordhashing toegepast. Dit betekent dat als de database wordt gelekt, alle wachtwoorden direct leesbaar zijn.

**Concreet probleem:** dit is een directe schending van beveiligingsnormen (OWASP: Sensitive Data Exposure). Het raakt user story US-07 (*"Als eigenaar wil ik kunnen inloggen"*) — de authenticatie is functioneel maar onveilig.

**Verbetering:**
`password_hash()` gebruiken bij het opslaan en `password_verify()` bij het inloggen, of Symfony's ingebouwde `PasswordHasher` component inzetten.

---

### 2.2 `findAll()` in de capaciteitscheck is inefficiënt

In `validateReservationData()` wordt `$this->ReservationRepository->findAll()` aangeroepen. Dit haalt **alle reserveringen van alle restaurants** op uit de database, ook al is alleen het opgegeven restaurant en tijdvak relevant.

**Concreet probleem:** bij een groot aantal reserveringen (100+) wordt dit een performance-knelpunt. Dit raakt US-05 (*"Als eigenaar wil ik een dashboard"*) — het dashboard en reserveringssysteem worden trager naarmate de applicatie groeit.

**Verbetering:**
Een query bouwen in de `ReservationRepository` die alleen reserveringen ophaalt voor het opgegeven restaurant en tijdvak:
```php
->andWhere('r.RestaurantId = :restaurantId')
->andWhere('r.StartDate < :endDate')
->andWhere('r.EndDate > :startDate')
```

---

### 2.3 `getReservationsByRestaurant()` werkt waarschijnlijk niet correct

In `ReservationService` staat:

```php
public function getReservationsByRestaurant(int $restaurantId): array
{
    return $this->ReservationRepository->findBy(['RestaurantId' => $restaurantId]);
}
```

Het veld `RestaurantId` in de entity is echter een `RestaurantEntity` object (via `@ManyToOne`), niet een integer. Doctrine's `findBy()` werkt hier niet correct met een integer als zoekwaarde.

**Concreet probleem:** deze methode geeft waarschijnlijk altijd een lege array terug, waardoor het filteren van reserveringen per restaurant niet werkt. Dit raakt US-05 (dashboard eigenaar).

**Verbetering:**
De query aanpassen zodat Doctrine het juiste veld gebruikt:
```php
return $this->ReservationRepository->findBy(['RestaurantId' => $restaurantId]);
// moet worden:
->andWhere('r.RestaurantId = :id')->setParameter('id', $restaurantId)
```

---

## 3. Verbetervoorstellen op basis van reflectie

### 3.1 Commit messages zijn niet informatief

Terugkijkend op de git-history staan er commits zoals `"."`, `"johan"` en kleine fixes zonder omschrijving. Dit maakt het moeilijk om terug te kijken wat er per commit is veranderd, en is een probleem als iemand anders de code moet begrijpen of een bug moet traceren.

**Concreet probleem:** bij het opsporen van de bug rondom de reserveringsvalidatie moest ik meerdere commits doorkijken zonder te weten welke de relevante wijziging bevatte.

**Verbetering:**
In een volgend project verplicht mezelf om elke commit te beschrijven in de vorm: `[onderdeel]: wat is er veranderd en waarom`. Bijvoorbeeld: `reservationService: capaciteitscheck toegevoegd bij overlappende tijdsloten`.

---

### 3.2 Te lang zelfstandig gewerkt zonder te communiceren

Tijdens het project heb ik meerdere keren lang zelfstandig aan een onderdeel gewerkt voordat ik communiceerde met het team. Dit leidde er een keer toe dat ik een oplossing bouwde voor een probleem dat een teamgenoot al had opgelost.

**Concreet probleem:** dubbel werk en onduidelijkheid over wie welk stuk bouwt.

**Verbetering:**
Bij elke standup concreet benoemen waar ik mee bezig ben en actief vragen of iemand anders hier ook mee bezig is. Taken ook explicieter in GitHub Projects bijhouden zodat overlap zichtbaar is.

---

### 3.3 Geen aandacht voor wachtwoordbeveiliging tijdens ontwikkeling

De keuze om wachtwoorden als plain text op te slaan was een bewuste keuze om snel te kunnen werken. Achteraf gezien had ik dit direct goed moeten implementeren, want veiligheid is geen nice-to-have.

**Concreet probleem:** de authenticatie werkt functioneel, maar is onveilig in een productieomgeving.

**Verbetering:**
Security eerder in het ontwikkelproces meenemen, ook in een schoolproject. Symfony's `PasswordHasher` had weinig extra tijd gekost en had het direct correct gemaakt.

---

## 4. Planning verbetervoorstellen

| Nr | Verbetervoorstel | Werkzaamheden | Geschatte tijd |
|----|-----------------|---------------|----------------|
| 1.1 | Test capaciteitscheck | Test schrijven in `reservationServiceTest.php` | 1 uur |
| 1.2 | Test `updateReservation()` service | 2 tests schrijven | 1 uur |
| 1.3 | Test ongeldig datumformaat | 1 test schrijven | 30 min |
| 2.1 | Wachtwoord hashing | Symfony PasswordHasher implementeren | 2 uur |
| 2.2 | `findAll()` vervangen door gerichte query | Repository methode toevoegen | 1 uur |
| 2.3 | `getReservationsByRestaurant()` fixen | Query aanpassen in repository | 30 min |
| 3.1 | Betere commit messages | Werkafspraak (geen code) | n.v.t. |
| 3.2 | Communicatie verbeteren | Procesafspraak (geen code) | n.v.t. |
| 3.3 | Wachtwoordbeveiliging | Zie 2.1 | (zie 2.1) |

**Totale geschatte tijd:** ±6 uur
**Buffer:** 1 uur

---

## Conclusie

De meest kritieke verbetervoorstellen zijn **2.1** (plain text wachtwoorden) en **2.2** (inefficiënte query), omdat die direct invloed hebben op de veiligheid en schaalbaarheid van de applicatie in productie. De testverbeteringen (1.1 t/m 1.3) zijn belangrijk om de betrouwbaarheid van de testsuite te garanderen — op dit moment slagen alle tests, maar de kern van de reserveringslogica (de capaciteitscheck) is niet getest.
