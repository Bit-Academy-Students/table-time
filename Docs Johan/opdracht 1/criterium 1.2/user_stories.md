# User Stories – TableTime

Alle user stories volgen het format: *"Als [rol] wil ik [actie] zodat [doel]"*
Prioriteit is bepaald via MoSCoW. Tijdsindicatie is in uren (geschat).

---

## Must Have

### US-01 – Reservering plaatsen
**Als** bezoeker
**wil ik** een tafel kunnen boeken door datum, tijd, aantal gasten en e-mail in te vullen
**zodat** het restaurant weet wanneer wij komen en met hoeveel personen

| Eigenschap | Waarde |
|-----------|--------|
| Prioriteit | Must Have |
| Tijdsindicatie | 8 uur |
| Sprint | Sprint 1 / 2 |

**Acceptatiecriteria:**
- [ ] Bezoeker kan datum selecteren uit een kalender
- [ ] Bezoeker kan een tijdslot kiezen
- [ ] Bezoeker vult aantal personen in (minimaal 1)
- [ ] Bezoeker vult een geldig e-mailadres in
- [ ] Na bevestiging wordt de reservering opgeslagen in de database
- [ ] Bij volle capaciteit verschijnt een foutmelding

---

### US-02 – Reservering bekijken
**Als** bezoeker
**wil ik** mijn reserveringen kunnen bekijken
**zodat** ik weet wat ik heb geboekt en wanneer

| Eigenschap | Waarde |
|-----------|--------|
| Prioriteit | Must Have |
| Tijdsindicatie | 4 uur |
| Sprint | Sprint 2 |

**Acceptatiecriteria:**
- [ ] Bezoeker ziet een overzicht van zijn reserveringen
- [ ] Per reservering is datum, tijd, restaurant en aantal personen zichtbaar
- [ ] Reserveringen worden gefilterd op het ingelogde account of e-mail

---

### US-03 – Reservering aanpassen
**Als** bezoeker
**wil ik** een bestaande reservering kunnen aanpassen
**zodat** ik datum, tijd of aantal personen kan wijzigen als dat nodig is

| Eigenschap | Waarde |
|-----------|--------|
| Prioriteit | Must Have |
| Tijdsindicatie | 5 uur |
| Sprint | Sprint 3 |

**Acceptatiecriteria:**
- [ ] Bezoeker kan datum, tijd en aantal personen wijzigen
- [ ] Bij aanpassing wordt de capaciteit opnieuw gecontroleerd
- [ ] Als het nieuwe tijdslot vol is, krijgt de bezoeker een melding
- [ ] Succesvol aangepaste reservering is direct zichtbaar

---

### US-04 – Reservering annuleren
**Als** bezoeker
**wil ik** een reservering kunnen annuleren
**zodat** ik een plekje vrijmaak als ik niet meer kom

| Eigenschap | Waarde |
|-----------|--------|
| Prioriteit | Must Have |
| Tijdsindicatie | 3 uur |
| Sprint | Sprint 3 |

**Acceptatiecriteria:**
- [ ] Bezoeker ziet een annuleerknop bij zijn reservering
- [ ] Er verschijnt een bevestigingsvraag voor het annuleren
- [ ] Na annulering is de reservering verwijderd uit het systeem
- [ ] De vrijgekomen capaciteit is direct beschikbaar voor andere bezoekers

---

### US-05 – Restaurant dashboard
**Als** restauranteigenaar
**wil ik** een dashboard met alle reserveringen van mijn restaurant
**zodat** ik de dagelijkse bezetting kan inzien en plannen

| Eigenschap | Waarde |
|-----------|--------|
| Prioriteit | Must Have |
| Tijdsindicatie | 8 uur |
| Sprint | Sprint 2 / 3 |

**Acceptatiecriteria:**
- [ ] Eigenaar ziet alleen reserveringen van zijn eigen restaurant
- [ ] Reserveringen worden getoond per dag in een tijdlijn
- [ ] Per reservering zijn datum, tijd, aantal personen en e-mail zichtbaar
- [ ] Eigenaar kan een reservering aanpassen of annuleren

---

### US-06 – Maximale capaciteit instellen
**Als** restauranteigenaar
**wil ik** de maximale capaciteit van mijn restaurant kunnen instellen
**zodat** er nooit meer reserveringen worden geaccepteerd dan het restaurant aankan

| Eigenschap | Waarde |
|-----------|--------|
| Prioriteit | Must Have |
| Tijdsindicatie | 3 uur |
| Sprint | Sprint 1 |

**Acceptatiecriteria:**
- [ ] Eigenaar kan bij registratie een maximale capaciteit invoeren
- [ ] De capaciteitscheck wordt uitgevoerd bij elke nieuwe reservering
- [ ] Overlappende reserveringen worden bij de check bij elkaar opgeteld
- [ ] Als de capaciteit vol is, weigert het systeem nieuwe reserveringen

---

## Should Have

### US-07 – Inloggen als restauranteigenaar
**Als** restauranteigenaar
**wil ik** kunnen inloggen met e-mail en wachtwoord
**zodat** alleen ik toegang heb tot mijn eigen dashboard en gegevens

| Eigenschap | Waarde |
|-----------|--------|
| Prioriteit | Should Have |
| Tijdsindicatie | 5 uur |
| Sprint | Sprint 3 |

**Acceptatiecriteria:**
- [ ] Eigenaar vult e-mail en wachtwoord in op de loginpagina
- [ ] Bij onjuiste gegevens verschijnt een foutmelding
- [ ] Na succesvol inloggen wordt de eigenaar doorgestuurd naar zijn dashboard
- [ ] Eigenaar ziet uitsluitend zijn eigen reserveringen in het dashboard

---

### US-08 – Restaurant registreren
**Als** restauranteigenaar
**wil ik** mijn restaurant kunnen registreren
**zodat** bezoekers mijn restaurant kunnen vinden en een reservering kunnen plaatsen

| Eigenschap | Waarde |
|-----------|--------|
| Prioriteit | Should Have |
| Tijdsindicatie | 4 uur |
| Sprint | Sprint 2 |

**Acceptatiecriteria:**
- [ ] Eigenaar kan naam, locatie, e-mail, telefoonnummer en capaciteit invullen
- [ ] Na registratie is het restaurant zichtbaar in de restaurantenlijst
- [ ] E-mail wordt gevalideerd op correct formaat
- [ ] Dubbele registraties worden geblokkeerd

---

## Could Have

### US-09 – Herinnering bij reservering
**Als** bezoeker
**wil ik** een herinnering ontvangen voor mijn reservering
**zodat** ik niet vergeet dat ik een tafel heb geboekt

| Eigenschap | Waarde |
|-----------|--------|
| Prioriteit | Could Have |
| Tijdsindicatie | 6 uur |
| Sprint | Niet gerealiseerd |

**Acceptatiecriteria:**
- [ ] Bezoeker ontvangt een e-mail 24 uur voor de reservering
- [ ] De e-mail bevat datum, tijd, restaurant en aantal personen

---

## Won't Have

### US-10 – Google Maps integratie
**Als** bezoeker
**wil ik** de locatie van het restaurant op een kaart zien
**zodat** ik makkelijk de route kan vinden

| Eigenschap | Waarde |
|-----------|--------|
| Prioriteit | Won't Have |
| Tijdsindicatie | n.v.t. |
| Sprint | n.v.t. |
