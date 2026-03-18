# Uml

## Ethiek

In het ontwerp is rekening gehouden met ethische aspecten door doelbewust gebruikersdata te verwerken. Data zoals e-mail en het aantal personen worden uitsluitend gebruikt voor het plaatsen en beheren van reserveringen. Er worden geen extra persoonsgegevens opgeslagen of gebruikt voor andere doeleinden, zoals reclame of profilering.

Daarnaast is er gekozen voor een duidelijke scheiding tussen wat gebruikers kunnen zien en doen en de onderliggende systeemlogica. Hierdoor wordt de kans op misbruik van data of functionaliteit beperkt.

## Privacy

Privacy is meegenomen door het beperken van de hoeveelheid data die wordt opgeslagen. In het class diagram is te zien dat alleen noodzakelijke gegevens worden opgeslagen binnen de `Reservering`- en `Gebruiker`-klassen. Er worden geen gevoelige persoonsgegevens opgeslagen die niet strikt noodzakelijk zijn voor het functioneren van het systeem.

Voor authenticatie en gebruikersinformatie wordt een aparte `AuthDienst` gebruikt, waardoor gebruikersdata niet verspreid door het systeem aanwezig is. Dit verkleint de kans op datalekken en maakt het eenvoudiger om te voldoen aan privacywetgeving zoals de AVG.

## Security

Security is een belangrijk onderdeel van het ontwerp. Authenticatie is losgekoppeld van de rest van de applicatie door gebruik te maken van een interface (`IAuthDienst`). Hierdoor kan de beveiligingslogica centraal worden beheerd en in de toekomst eenvoudig worden aangepast, vervangen of uitgebreid zonder impact op andere onderdelen van het systeem.

# sequence

