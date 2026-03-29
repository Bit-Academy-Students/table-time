# Use Case Diagram – TableTime

> Mermaid ondersteunt geen native use case diagrammen. Teken dit diagram na in draw.io (of Lucidchart) op basis van de structuur hieronder. Sla de afbeelding op als `use-case-diagram.png` in deze map.

---

## Structuur

**Systeem:** TableTime

**Actoren:**
| Actor | Beschrijving |
|-------|-------------|
| Bezoeker | Iemand die een restaurant wil reserveren |
| Restauranteigenaar | Eigenaar die zijn restaurant beheert |
| Systeem (API) | De backend die validatie en opslag afhandelt |

---

## Use cases per actor

### Bezoeker
- Restaurants bekijken
- Restaurant selecteren
- Reservering plaatsen *(include: Tijdslot controleren)*
- Reservering bekijken
- Reservering aanpassen
- Reservering annuleren

### Restauranteigenaar
- Registreren
- Inloggen
- Dashboard bekijken
- Reserveringen inzien
- Reservering aanpassen
- Reservering annuleren
- Maximale capaciteit instellen

### Systeem (API)
- Tijdslot controleren *(include bij: Reservering plaatsen)*
- Capaciteit valideren *(include bij: Reservering plaatsen)*
- Authenticatie uitvoeren *(include bij: Inloggen)*

---

## Relaties

| Van | Relatie | Naar |
|-----|---------|------|
| Reservering plaatsen | `<<include>>` | Tijdslot controleren |
| Reservering plaatsen | `<<include>>` | Capaciteit valideren |
| Inloggen | `<<include>>` | Authenticatie uitvoeren |
| Reservering aanpassen | `<<extend>>` | Reservering plaatsen |

---

## Toelichting

### Waarom een use case diagram?

Het use case diagram geeft een overzicht van alle functionaliteiten die het systeem biedt vanuit het perspectief van de gebruiker. Het maakt direct duidelijk wie wat kan doen binnen TableTime, zonder in technische details te treden.

### Include-relaties

De `<<include>>` relaties bij *Reservering plaatsen* zijn bewust gekozen: de tijdslot- en capaciteitscheck zijn **verplichte** stappen die altijd worden uitgevoerd. Zonder deze checks kan een reservering niet worden geplaatst.

### Koppeling met user stories

| Use case | User story |
|----------|-----------|
| Reservering plaatsen | US-01 – *"Als bezoeker wil ik een tafel boeken"* |
| Maximale capaciteit instellen | US-06 – *"Als eigenaar wil ik de capaciteit instellen"* |
| Inloggen | US-07 – *"Als eigenaar wil ik kunnen inloggen"* |
| Registreren | US-08 – *"Als eigenaar wil ik mijn restaurant registreren"* |
| Dashboard bekijken | US-05 – *"Als eigenaar wil ik een dashboard"* |
