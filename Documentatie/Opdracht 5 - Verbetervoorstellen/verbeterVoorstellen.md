# Verbetervoorstellen & Planning – Reflectie na Oplevering

Dit document beschrijft de verbetervoorstellen die zijn opgesteld na het uitvoeren van testen, het opleveren van het product en persoonlijke reflectie op de codekwaliteit en architectuur. De voorstellen zijn gebaseerd op concrete analyse van de bestaande code en testresultaten.

---

## 1. Verbetervoorstellen op basis van testen

### 1. Invoervalidatie bij `parseDateToDecimal`
De functie `parseDateToDecimal` gaat ervan uit dat de input altijd het formaat `YYYY-MM-DD HH:mm` heeft.
Bij afwijkende invoer kan dit leiden tot runtime errors.

**Verbetering:**
Toevoegen van invoervalidatie en aanvullende tests voor ongeldige of incomplete datumstrings.

---

### 2. Defensieve afhandeling in `formatTimeFromDate`
De functie gebruikt directe string-slicing zonder te controleren of het tijdgedeelte bestaat.

**Verbetering:**
Controleren of de tijd aanwezig is voordat deze wordt verwerkt en tests toevoegen voor foutieve invoer.

---

### 3. Edge-case testen bij exacte overlap in `assignColumns`
De overlapcontrole beschouwt reserveringen met exact aansluitende tijden als niet-overlappend.

**Verbetering:**
Expliciete tests toevoegen om vast te leggen of dit gedrag functioneel gewenst is.

---

### 4. Testen met lege en ongesorteerde reserveringslijsten
Hoewel de lijst intern wordt gesorteerd, zijn deze scenario's niet expliciet getest.

**Verbetering:**
Unit tests toevoegen voor lege arrays en willekeurig gesorteerde invoerdata.

---

### 5. Capaciteitsgrens testen in `isTimeFull`
Het scenario waarin de capaciteit exact wordt bereikt (`used + amountPeople === capacity`) is niet getest.

**Verbetering:**
Edge-case test toevoegen om het gewenste gedrag expliciet vast te leggen.

---

## 2. Verbetervoorstellen op basis van oplevering

### 6. Asynchrone data-afhandeling
De huidige logica gaat ervan uit dat reserveringsdata direct beschikbaar is, terwijl deze in de praktijk via een API wordt opgehaald. Dit raakt de user story *"Als gebruiker wil ik een tafel kunnen boeken"* — als de data nog niet geladen is, kan de kalender verkeerde beschikbaarheid tonen.

**Verbetering:**
Fallback-logica en loading states implementeren voor situaties waarin data nog niet beschikbaar is.

---

### 7. Consistente datumverwerking
Datums worden op meerdere plekken handmatig geparsed, wat kan leiden tot inconsistent gedrag tussen browsers. Dit raakt alle user stories die te maken hebben met datum en tijd.

**Verbetering:**
Gebruik maken van één centrale date-parsing utility voor alle datum- en tijdsverwerkingen.

---

### 8. Performance-optimalisatie bij grote datasets
De overlap-logica in `assignColumns` maakt gebruik van geneste iteraties, wat bij grote datasets performanceproblemen kan veroorzaken. Dit raakt de user story *"Als beheerder wil ik mijn eigen dashboard"* — bij veel reserveringen kan het dashboard traag worden.

**Verbetering:**
Optimaliseren van de overlapdetectie of het beperken van rendering bij grote aantallen reserveringen.

---

## 3. Verbetervoorstellen op basis van reflectie

### Terugblik op het proces en mijn eigen rol

In dit project was ik verantwoordelijk voor de frontend. Ik heb de kalender gebouwd, het dashboard voor restauranteigenaren, en de reserveringslogica aan de frontend kant. In het begin kostte het me best veel tijd om goed in Vue te komen. Ik had bepaalde dingen sneller kunnen leren als ik eerder hulp had gevraagd in plaats van lang zelf te blijven zoeken.

Wat ik ook merkte is dat ik soms niet duidelijk communiceerde naar mijn teamgenoten wat ik aan het bouwen was. Dit zorgde er een keer voor dat er dubbele code was voor dezelfde functionaliteit. Als ik dat eerder had besproken had dat niet hoeven gebeuren.

De samenwerking met de opdrachtgever ging goed. Na sprint 1 kregen we de feedback dat de homepage niet de prioriteit was maar het reserveren wel. We hebben dat direct aangepast in onze planning, en dat werkte goed. Als ik terugkijk had ik dit soort gesprekken eerder kunnen initiëren in plaats van af te wachten.

### 9. Scheiding van validatie en business logic
In `isTimeFull` zijn validatie, datumverwerking en business rules gecombineerd in één functie. Terugkijkend had ik dit beter kunnen opdelen. Kleinere functies zijn makkelijker te testen en te begrijpen.

**Verbetering:**
Opsplitsen van verantwoordelijkheden om onderhoudbaarheid en testbaarheid te verbeteren.

---

### 10. Eerder communiceren met het team
Tijdens het project werkte ik soms te lang in mijn eentje aan een onderdeel. Hierdoor merkte ik pas laat dat iets al ergens anders werd gebouwd.

**Verbetering:**
In een volgend project wil ik bij elke standup concreter vertellen waar ik mee bezig ben en vragen of iemand anders daar ook aan werkt.

---

### 11. Documentatie van aannames
Aannames zoals geldig datumformaat, tijdzone en dataconsistentie zijn niet expliciet vastgelegd. Dit merkte ik achteraf toen ik de tests schreef — ik wist niet meer precies waarom bepaalde keuzes waren gemaakt.

**Verbetering:**
Technische documentatie uitbreiden zodat toekomstige ontwikkelaars (en ikzelf) sneller begrijpen hoe het systeem werkt.

---

## 4. Planning van verbetervoorstellen

| Nr | Verbetervoorstel | Werkzaamheden | Tijd |
|----|------------------|---------------|------|
| 1 | Invoervalidatie datum | Validatie + tests | 1,5 uur |
| 2 | Defensieve time parsing | Refactor + tests | 1 uur |
| 3 | Overlap edge-cases | Nieuwe testcases | 1 uur |
| 4 | Capaciteitsgrens testen | Edge-case test | 0,5 uur |
| 5 | Lege dataset testen | Unit tests | 0,5 uur |
| 6 | Async data fallback | UI + state handling | 2 uur |
| 7 | Centrale date parsing | Utility refactor | 1,5 uur |
| 8 | Performance optimalisatie | Analyse + refactor | 3 uur |
| 9 | Logica scheiden | Refactor functies | 2 uur |
| 10 | Communicatie verbeteren | Proces-aanpassing | n.v.t. |
| 11 | Documentatie | Technische documentatie | 1 uur |

**Totale geschatte tijd:** ±16,5 uur
**Buffer voor onvoorziene problemen:** 2 uur

---

## Conclusie

Hoewel alle huidige tests succesvol slagen, toont deze analyse aan dat er meerdere realistische scenario's, randgevallen en kwaliteitsverbeteringen mogelijk zijn. Door deze verbetervoorstellen systematisch te identificeren en te plannen, wordt de software robuuster, beter onderhoudbaar en beter voorbereid op gebruik in een productieomgeving.
