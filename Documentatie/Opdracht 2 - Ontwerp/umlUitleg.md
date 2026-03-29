# UML Uitleg – Table Time

## Class diagram

### Ethiek

In het ontwerp is rekening gehouden met ethische aspecten door doelbewust gebruikersdata te verwerken. Data zoals e-mail en het aantal personen worden uitsluitend gebruikt voor het plaatsen en beheren van reserveringen. Er worden geen extra persoonsgegevens opgeslagen of gebruikt voor andere doeleinden, zoals reclame of profilering.

Daarnaast is er gekozen voor een duidelijke scheiding tussen wat gebruikers kunnen zien en doen en de onderliggende systeemlogica. Hierdoor wordt de kans op misbruik van data of functionaliteit beperkt.

### Privacy

Privacy is meegenomen door het beperken van de hoeveelheid data die wordt opgeslagen. In het class diagram is te zien dat alleen noodzakelijke gegevens worden opgeslagen binnen de `Reservering`- en `Gebruiker`-klassen. Er worden geen gevoelige persoonsgegevens opgeslagen die niet strikt noodzakelijk zijn voor het functioneren van het systeem.

Voor authenticatie en gebruikersinformatie wordt een aparte `AuthDienst` gebruikt, waardoor gebruikersdata niet verspreid door het systeem aanwezig is. Dit verkleint de kans op datalekken en maakt het eenvoudiger om te voldoen aan privacywetgeving zoals de AVG.

### Security

Security is een belangrijk onderdeel van het ontwerp. Authenticatie is losgekoppeld van de rest van de applicatie door gebruik te maken van een interface (`IAuthDienst`). Hierdoor kan de beveiligingslogica centraal worden beheerd en in de toekomst eenvoudig worden aangepast, vervangen of uitgebreid zonder impact op andere onderdelen van het systeem.

---

## Sequence diagram

### Wat laat het diagram zien?

Het sequence diagram laat stap voor stap zien hoe een reservering wordt gedaan in de applicatie. Ik heb dit diagram gemaakt omdat het reserveringsproces de belangrijkste functie van de hele app is. Door dit diagram te maken werd duidelijk hoe de frontend, de backend en de database met elkaar communiceren.

### Hoe werkt de flow?

1. De **gebruiker** vult het reserveringsformulier in (datum, tijd, aantal mensen, email)
2. Het **ReservatieFormulier** (de Vue-pagina) stuurt deze data door naar de **APIDienst**
3. De **APIDienst** stuurt een verzoek (`VerzoekReserverings`) naar de **ReservatieDienst** in de backend
4. De **ReservatieDienst** gaat in een **loop** voor elk tijdslot controleren of er nog plek is in de **Database**
5. Op basis van de uitkomst zijn er twee mogelijkheden (**alternative**):
   - **Er is plek** → de reservering wordt opgeslagen in de database, de gebruiker krijgt een bevestiging
   - **Er is geen plek** → de gebruiker krijgt een foutmelding

### Waarom een loop en alternative?

De **loop** is gekozen omdat een reservering meerdere tijdsloten kan beslaan als de duur langer is dan een half uur. Voor elk tijdslot moet de beschikbaarheid apart worden gecontroleerd.

Het **alternative** blok laat zien dat het systeem altijd reageert — of de reservering nu lukt of niet. Dit sluit aan op de user story: *"Als gebruiker wil ik een tafel kunnen boeken, zodat het restaurant weet wanneer wij komen en hoe laat."* De gebruiker moet altijd terugkoppeling krijgen.

### Koppeling met de user stories

Dit diagram sluit direct aan op twee user stories:
- *"Als gebruiker wil ik een tafel kunnen boeken door de datum, tijd, aantal gasten en email"* — dit is de volledige flow die het diagram beschrijft
- *"Als beheerder wil ik niet te veel reserveringen krijgen"* — de capaciteitscheck in de loop zorgt hiervoor
