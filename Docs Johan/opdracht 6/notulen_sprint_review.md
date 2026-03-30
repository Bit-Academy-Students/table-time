# Notulen sprint review – TableTime

**Aanwezig:** Alexander Zoet, Johan Tol, Keano Broekman, Rayan Karmimmech, Ties Noordhuis (opdrachtgever / coach)
**Duur:** ongeveer 15 minuten

---

## Wat we hebben gedemonstreerd

We hebben aan Ties laten zien wat we de afgelopen sprint hebben gemaakt. Als eerste de reserveringspagina — je vult als bezoeker een datum, tijdslot, aantal personen en je e-mailadres in en dan wordt de reservering opgeslagen in de database.

Daarna lieten we het dashboard zien voor de restauranteigenaar. Ties vroeg of we twee overlappende reserveringen konden tonen en dat lukte. De tijdlijn werkte goed en nieuwe reserveringen kwamen direct tevoorschijn.

We lieten ook zien dat je een reservering kunt aanpassen (datum, tijd, duur, personen) en verwijderen. Na verwijdering verdwijnt die meteen uit de tijdlijn.

Ties vroeg ook of we konden testen wat er gebeurt als een tijdslot vol is. De backend gaf de goede foutmelding terug (`Maximum capacity exceeded for selected time slot`), maar de frontend toonde nog de verkeerde foutmelding. Ook als je de duur aanpast worden tijdsloten die vol zijn soms nog niet meteen geblokkeerd. Dat moet nog opgelost.

Keano had een contactpagina gemaakt met een formulier dat een e-mail stuurt naar het restaurant, maar die was nog niet gepusht naar GitHub. Ties zei dat als iets niet op GitHub staat, het niet telt. Alexander had een About Us pagina gebouwd maar die was nog niet helemaal goed uitgelijnd op desktop.

---

## Feedback van Ties

Ties was blij dat de kern van het product werkt: reserveringen aanmaken, aanpassen en verwijderen werkt allemaal en dat was zijn grootste punt. Hij vond het jammer dat het contactformulier niet gedemonstreerd kon worden. Ook wil hij dat meerdere restaurants beter werken en dat de bug met de tijdslot-selectie opgelost wordt voor de presentatie.

Verder wil hij een README in de repository zodat iemand anders het project makkelijk kan opstarten, en hij wil dat het scrumboard up-to-date is.

---

## Presentatie vrijdag 12 december om 13:00

De eindpresentatie is op vrijdag 12 december om 13:00. Ties heeft aangegeven wat hij wil zien:

1. Probleemomschrijving – welk probleem lost TableTime op voor restaurants en bezoekers?
2. Product demo – vertel er een verhaal bij, bijvoorbeeld: kerstdiner met 50 man, je zoekt een restaurant, je reserveert, maar een andere familie heeft net het laatste plekje gepakt
3. Technische uitdagingen – wat was moeilijk en hoe hebben jullie het opgelost?
4. Features in actie – laat alles zien wat werkt
5. Learnings – wat nemen jullie mee van dit project?

---

## Afspraken

### Afspraak 1 – Bug tijdslot-selectie fixen

**Wat:** Als je eerst een tijdslot selecteert en dan de duur verandert, kunnen tijdsloten die vol zijn nog steeds geselecteerd worden. Dit moet opgelost worden.
**Wie:** Alexander Zoet
**Wanneer:** Voor vrijdag 12 december om 13:00
**Waarom:** Ties benoemde dit expliciet als iets wat opgelost moet zijn voor de presentatie
**Bewijs:** Commit `9148e2e` – "fixed reservation bugs" (11 december 2025)

---

### Afspraak 2 – Contactpagina pushen

**Wat:** De contactpagina met het e-mailformulier pushen naar de main branch zodat het zichtbaar is in de repository
**Wie:** Keano Broekman
**Wanneer:** Direct na de meeting
**Waarom:** Ties zei: "als het niet op GitHub staat, bestaat het niet" — alleen gemerged werk telt als af
**Bewijs:** Commit `8b8a5e0` – Merge branch 'Feature/contactpage' into main

---

### Afspraak 3 – README schrijven

**Wat:** Een README.md schrijven met uitleg over welke dependencies nodig zijn, welke commando's je moet draaien om het project lokaal op te starten, de structuur van het project en openstaande taken
**Wie:** Alexander Zoet, rest van het team helpt mee
**Wanneer:** Voor vrijdag 12 december om 13:00
**Waarom:** Ties wil dat een nieuwe developer het project zonder extra uitleg kan opstarten
**Bewijs:** Commit `983275e` – "Add setup guide for TableTime project using Docker" (6 januari 2026), commit `21c5869` – "Enhance README with project details and setup guide" (7 januari 2026)

---

### Afspraak 4 – Scrumboard bijwerken

**Wat:** Alle taken in GitHub Projects bijwerken zodat de status klopt met wat er echt af is
**Wie:** Heel team
**Wanneer:** Direct na de meeting
**Waarom:** Ties zag nog open taken staan die al afgerond waren
**Bewijs:** GitHub Projects – https://github.com/orgs/Bit-Academy-Students/projects/11

---

### Afspraak 5 – Eindpresentatie voorbereiden

**Wat:** Een presentatie maken met de structuur die Ties heeft aangegeven: probleemomschrijving, demo met verhaal, technische uitdagingen, features in actie en learnings
**Wie:** Heel team
**Wanneer:** Vrijdag om 13:00
**Waarom:** Ties heeft de structuur en inhoud zelf aangegeven
**Bewijs:** Eindpresentatie heeft plaatsgevonden op de afgesproken datum

---

De sprint review ging goed. De kern van het product werkt en Ties was daar tevreden mee. Alle afspraken zijn voor de presentatie nagekomen.
