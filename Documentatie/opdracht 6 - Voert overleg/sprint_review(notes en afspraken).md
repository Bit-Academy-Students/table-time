# Notulen sprint review, TableTime

**Aanwezig:** Alexander Zoet, Johan Tol, Keano Broekman, Rayan Karmimmech, Ties Noordhuis (opdrachtgever, coach)

---

## Wat we hebben gedemonstreerd

We hebben Ties laten zien wat we tijdens de laatste sprint hebben gemaakt. Eerst lieten we de reserveringspagina zien. Bezoekers vullen een datum, tijdslot, aantal personen en hun e-mailadres in. De reservering wordt dan opgeslagen in de database.

Daarna toonden we het dashboard voor de restauranteigenaar. Ties vroeg of we twee overlappende reserveringen konden laten zien, en dat was mogelijk. De tijdlijn werkte goed, en nieuwe reserveringen verschenen na een tijdje wachten.

We lieten ook zien dat je een reservering kunt aanpassen (datum, tijd, duur, personen) en verwijderen. Na verwijdering verdwijnt de reservering na een tijdje wachten uit de tijdlijn.

Ties vroeg of we konden testen wat er gebeurt als een tijdslot vol is. De backend gaf de juiste foutmelding terug (`Maximum capacity exceeded for selected time slot`), maar de frontend toonde nog steeds de verkeerde foutmelding. Ook soms worden tijdsloten die vol zijn niet direct geblokkeerd wanneer je de duur aanpast. Dit moest nog worden opgelost.

Keano had een contactpagina gemaakt met een formulier dat een e-mail naar het restaurant stuurt, maar dat was nog niet naar GitHub gepusht. Ties zei dat als iets niet op GitHub staat, het niet bestaat. Ryan had een About Us-pagina gebouwd, maar die was nog niet goed uitgelijnd op desktop.

---

## Feedback van Ties

Ties was blij dat de basis van het product werkt: reserveringen aanmaken, aanpassen en verwijderen doen het allemaal, en dat was zijn belangrijkste punt. Hij vond het jammer dat we het contactformulier niet konden demonstreren. Tevens wil hij dat meerdere restaurants als functie werkend en dat de bug met de tijdslot-selectie voor de presentatie is opgelost.

Verder wil hij een README in de repository zodat iemand anders het project gemakkelijk kan opstarten, en hij wil dat het scrumboard actueel is.

---

## Presentatie vrijdag 12 december om 13:00

De eindpresentatie is op vrijdag 12 december om 13:00. Ties heeft aangegeven wat hij wil zien:

1. Probleemomschrijving: Welk probleem lost TableTime op voor restaurants en bezoekers?
2. Productdemo: vertel er een verhaal bij, bijvoorbeeld: Kerstdiner met 50 mensen, je zoekt een restaurant, je reserveert, maar een andere familie heeft net het laatste plekje gepakt.
3. Technische uitdagingen: wat was moeilijk en hoe hebben jullie het opgelost?
4. Features in actie: laat alles zien wat werkt.
5. Learnings: wat nemen jullie mee van dit project?

---

## Afspraken

### Afspraak 1 – Bug tijdslot-selectie fixen

**Wat:** Als je eerst een tijdslot selecteert en dan de duur verandert, kunnen volgeboekte tijdsloten nog steeds geselecteerd worden. Dit moet worden opgelost.  
**Wie:** Alexander Zoet  
**Wanneer:** Voor vrijdag 12 december om 13:00  
**Waarom:** Ties benoemde dit als iets dat opgelost moet zijn voor de presentatie.  
**Bewijs:** Commit `9148e2e` "fixed reservation bugs" (11 december 2025)  

---

### Afspraak 2 – Contactpagina pushen

**Wat:** De contactpagina met het e-mailformulier naar de main branch pushen zodat deze zichtbaar is in de repository.  
**Wie:** Keano Broekman  
**Wanneer:** Direct na de meeting  
**Waarom:** Ties zei: "Als het niet op GitHub staat, bestaat het niet." Alleen gemerged werk telt als af.  
**Bewijs:** Commit `8b8a5e0` Merge branch 'Feature/contactpage' into main  

---

### Afspraak 3 – README schrijven

**Wat:** Een README.md schrijven met uitleg over de benodigde dependencies, welke opdrachten je moet uitvoeren om het project lokaal op te starten, de projectstructuur en openstaande taken.  
**Wie:** Alexander Zoet, de rest van het team helpt mee.  
**Wanneer:** Voor vrijdag 12 december om 13:00  
**Waarom:** Ties wil dat een nieuwe developer het project zonder extra uitleg kan opstarten.  
**Bewijs:** Commit `983275e` "Add setup guide for TableTime project using Docker" (6 januari 2026), commit `21c5869` – "Enhance README with project details and setup guide" (7 januari 2026)  

---

### Afspraak 4 – Scrumboard bijwerken

**Wat:** Alle taken in GitHub Projects bijwerken zodat de status klopt met wat werkelijk is afgerond.  
**Wie:** Het hele team  
**Wanneer:** Direct na de meeting  
**Waarom:** Ties zag nog open taken die al opgelost waren.  
**Bewijs:** GitHub Projects – https://github.com/orgs/Bit-Academy-Students/projects/11  

---

### Afspraak 5 – Eindpresentatie voorbereiden

**Wat:** Een presentatie maken volgens de structuur die Ties heeft aangegeven: probleemomschrijving, demo met verhaal, technische uitdagingen, features in actie en learnings.  
**Wie:** Het hele team  
**Wanneer:** Vrijdag 12 december om 13:00  
**Waarom:** Ties heeft de structuur en inhoud zelf aangegeven.  
**Bewijs:** Eindpresentatie heeft plaatsgevonden op de afgesproken datum.  

---

De sprint review ging goed. De basis van het product werkt, en Ties was daar tevreden mee. Bijna Alle afspraken zijn nagekomen voor de presentatie.