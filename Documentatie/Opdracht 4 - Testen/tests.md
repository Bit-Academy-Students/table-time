# Unit Test Document – Table time

## Inleiding

Binnen het project **Table time** zijn geautomatiseerde unit tests opgesteld om de belangrijkste functionaliteiten rondom reserveringen te testen. De tests zijn geschreven met **Vitest** en richten zich op de front-end business logic in de Vue-applicatie.

Elke test is gekoppeld aan een user story uit Sprint 0, zodat duidelijk is welke functionele eisen worden getest.

---

## Testomgeving

- **Testframework:** Vitest
- **Programmeertaal:** JavaScript
- **Front-end:** Vue + Tailwind
- **Testtype:** Unit tests
- **Locatie tests:** `src/website/Table-time-Front-end/src/utils/`
- **Tests uitvoeren:** navigeer naar de frontend map en run `npx vitest run`

---

## Uitgevoerde testen – overzicht

```
✓ isTimeFull.test.js (5 tests)
✓ reservationUtils.test.js (5 tests)

Test Files: 2 passed
Tests:      10 passed
```

*(Zie screenshot hieronder voor de volledige terminal output)*

![**\[SCREENSHOT\]**](image.png)

---

## Koppeling testen aan user stories

### isTimeFull.test.js

**Gekoppelde user story:**
> *"Als gebruiker wil ik een tafel kunnen boeken door de datum, tijd, aantal gasten en email, zodat het restaurant weet wanneer wij komen en hoe laat."*

Deze tests controleren de logica die bepaalt of een tijdslot beschikbaar is. Dit is de kernfunctie van de reserveringslogica.

| Test | Wat het test | Type scenario |
|------|-------------|--------------|
| geeft true terug als verplichte data ontbreekt | Als datum, duur of aantal personen leeg is, is het slot geblokkeerd | Alternatief: formulier niet compleet |
| geeft true terug als starttijd in het verleden ligt | Je kan niet reserveren op een tijdstip dat al voorbij is | Alternatief: verlopen tijdslot |
| geeft false terug als er genoeg capaciteit is | Als er nog plek is, mag de reservering door | Hoofdscenario: normale reservering |
| geeft true terug als capaciteit wordt overschreden door bestaande reserveringen | Als andere boekingen de capaciteit vol maken, is het tijdslot geblokkeerd | Alternatief: restaurant is vol |
| negeert niet-overlappende reserveringen | Reserveringen op een ander tijdstip tellen niet mee voor de capaciteitscheck | Alternatief: reserveringen op andere tijden |

---

### reservationUtils.test.js

**Gekoppelde user story:**
> *"Als beheerder wil ik mijn eigen dashboard, zodat ik en de klanten hun reserveringen kunnen zien."*

Deze tests controleren de hulpfuncties die de tijdlijn in het dashboard laten werken. Zonder deze functies kan het dashboard geen reserveringen correct weergeven.

| Test | Wat het test | Type scenario |
|------|-------------|--------------|
| zet tijd om naar decimaal | "12:30" wordt 12.5 zodat de tijdlijn posities klopt | Hoofdscenario: weergave tijdlijn |
| formatteert tijd correct | Haalt HH:mm uit een volledige datetime-string voor weergave | Hoofdscenario: tijdweergave dashboard |
| formatteert Date object voor API | Een JavaScript Date wordt omgezet naar het formaat dat de backend verwacht | Hoofdscenario: data versturen naar API |
| plaatst overlappende reserveringen in aparte kolommen | Twee reserveringen op hetzelfde tijdstip staan naast elkaar in de tijdlijn | Hoofdscenario: meerdere reserveringen tegelijk |
| plaatst niet-overlappende reserveringen in dezelfde kolom | Reserveringen na elkaar staan in dezelfde kolom | Alternatief: reserveringen na elkaar |

---

## Conclusie

Alle 10 tests slagen. De tests laten zien dat de reserveringslogica correct werkt voor de belangrijkste situaties: een tijdslot is geblokkeerd als de capaciteit vol is, tijdsloten in het verleden zijn niet beschikbaar, en reserveringen die niet overlappen tellen niet mee voor de capaciteitscheck. Dit geeft vertrouwen dat de kern van het reserveringssysteem betrouwbaar werkt.

Er zijn nog scenario's die niet getest zijn, zoals wat er gebeurt als de datuminput een verkeerd formaat heeft. Dit is beschreven in het verbetervoorstellen document (`../Opdracht 5 - Verbetervoorstellen/verbeterVoorstellen.md`).
