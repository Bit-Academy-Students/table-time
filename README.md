# TableTime – Projectdocumentatie

## Algemene informatie

- **Projectnaam:** TableTime  
- **Type project:** Webapplicatie  
- **Opdrachtgever:** Klant (restaurantplatform)  
- **Doelgroep:** Restaurantbezoekers en restauranteigenaren  

TableTime is ontwikkeld in opdracht van onze klant met als doel een centraal platform te creëren waar gebruikers eenvoudig reserveringen kunnen maken bij meerdere restaurants.

---

## Introductie

In opdracht van onze klant is het project **TableTime** gestart: een webapplicatie die zich richt op het centraliseren en vereenvoudigen van restaurantreserveringen. TableTime biedt gebruikers de mogelijkheid om meerdere restaurants te bekijken en eenvoudig een reservering te plaatsen op basis van datum, tijd en aantal personen.

De aanleiding voor dit project is het ontbreken van een overzichtelijk en schaalbaar reserveringssysteem waarin meerdere restaurants samenkomen. Bestaande oplossingen zijn vaak beperkt in flexibiliteit of niet geschikt om zowel bezoekers als restauranteigenaren efficiënt te ondersteunen binnen één platform.

Met TableTime wordt dit probleem opgelost door een gebruiksvriendelijke applicatie te ontwikkelen waarin bezoekers hun reserveringen kunnen plaatsen, terwijl restauranteigenaren volledige controle hebben over beschikbaarheid, tijdsloten en capaciteit. Hierbij staat duidelijkheid en eenvoud centraal voor beide doelgroepen.

Het project is opgezet met een toekomstgerichte visie, waarbij schaalbaarheid, onderhoudbaarheid en een consistente gebruikerservaring belangrijke uitgangspunten zijn.

---

## Projectdoel

Het doel van dit project is het realiseren van een betrouwbare en schaalbare webapplicatie waarmee gebruikers eenvoudig tafels kunnen reserveren bij verschillende restaurants. De applicatie moet bezoekers in staat stellen om restaurants te vergelijken en reserveringen te maken, aan te passen of te annuleren op een overzichtelijke manier.

Voor restauranteigenaren ligt het doel bij het bieden van een krachtig beheersysteem waarin zij hun openingstijden, tijdsloten en maximale capaciteit kunnen instellen. Hiermee wordt voorkomen dat restaurants overboekt raken en ontstaat er meer grip op de dagelijkse planning.

Daarnaast is het project gericht op het bouwen van een technisch solide fundament met moderne technologieën, zodat de applicatie eenvoudig te onderhouden en verder uit te breiden is in de toekomst.

---

## De gebruikte technieken

Voor de ontwikkeling van **TableTime** is gekozen voor moderne en bewezen technologieën die zorgen voor een schaalbare, onderhoudbare en toekomstbestendige applicatie. Hierbij is bewust onderscheid gemaakt tussen front-end, back-end en infrastructuur.

### Front-end

Voor de front-end is gebruikgemaakt van **Vue.js**. Vue is een modern JavaScript framework dat het mogelijk maakt om interactieve en gebruiksvriendelijke interfaces te bouwen. Door het component-gebaseerde karakter blijft de code overzichtelijk en herbruikbaar, wat de doorontwikkeling van de applicatie vereenvoudigt.

Voor de styling van de applicatie is **Tailwind CSS** ingezet. Tailwind is een utility-first CSS framework dat snelle ontwikkeling mogelijk maakt en zorgt voor een consistente en responsieve vormgeving op zowel desktop als mobiele apparaten.

### Back-end

De back-end van TableTime is ontwikkeld met **Symfony**. Symfony is een krachtig PHP-framework dat geschikt is voor het bouwen van schaalbare en veilige applicaties. Het framework biedt een duidelijke structuur en ondersteunt het bouwen van API’s waarmee de front-end efficiënt kan communiceren met de back-end.

Door het gebruik van Symfony is de applicatie eenvoudig uit te breiden met extra functionaliteiten en blijft het onderhoud overzichtelijk.

### Infrastructuur

Voor de ontwikkel- en runtime-omgeving is gebruikgemaakt van **Docker**. Docker maakt het mogelijk om de applicatie in containers te draaien, waardoor iedere ontwikkelaar werkt binnen dezelfde omgeving. Dit voorkomt configuratieproblemen en zorgt voor een stabiele en voorspelbare deployment.

Het gebruik van Docker draagt bij aan een efficiënter ontwikkelproces en maakt het eenvoudiger om de applicatie in de toekomst te schalen of uit te rollen naar andere omgevingen.

---

## Conclusie

Met TableTime wordt een solide basis neergezet voor een modern reserveringsplatform waarin meerdere restaurants samenkomen. Door de inzet van moderne technologieën en een duidelijke focus op gebruiksvriendelijkheid en schaalbaarheid, biedt het project een toekomstbestendige oplossing voor zowel bezoekers als restauranteigenaren.


# Stappenplan voor het opzetten van het TableTime project

Dit document beschrijft stap voor stap hoe je het TableTime project lokaal kunt opzetten met behulp van Docker.

---

## Stap 1 – Docker installeren

Download en installeer Docker Desktop via de officiële website:  
https://www.docker.com/products/docker-desktop/

We gaan ervan uit dat je **WSL** hebt geïnstalleerd.  
Zo niet, voer dan het volgende commando uit in je terminal:

```bash
wsl --install
```

---

## Stap 2 – Project clonen

Voer dit commando uit in de map waar je het project wilt plaatsen:

```bash
git clone https://github.com/Bit-Academy-Students/table-time.git
```

---

## Stap 3 – Docker containers opzetten (⚠️ cruciaal)

**LET OP:** Voer de onderstaande stappen **exact in deze volgorde** uit.  
Als je dit niet doet, krijg je errors.

Ga naar de Docker-map van het project:

```bash
cd table-time/Docker
```

Build de Docker containers zonder cache:

```bash
docker compose build --no-cache
```

Controleer of de containers draaien:

```bash
docker compose ps
```

Je zou onder andere de volgende services moeten zien:
- php  
- nginx  
- db  

Ga nu de PHP-container in:

```bash
docker compose exec bash
```

Ga naar de API-map en controleer de PHP-versie:

```bash
cd /var/www/html/api
php -v
```

Je zou hier **PHP 8.2.x** moeten zien.

Verwijder de bestaande dependencies en installeer ze opnieuw:

```bash
rm -rf vendor
composer install
```

Controleer of het volgende bestand bestaat:

```bash
ls vendor/myclabs/deep-copy/src/DeepCopy/deep_copy.php
```

Bestaat dit bestand? Dan is het probleem opgelost ✅

---

## Eindcheck

Open je browser en ga naar:

http://localhost:8080/Restaurants

Als deze pagina correct laadt, werkt het project zoals verwacht 🎉

---

## Klaar 🚀

Je TableTime project is nu succesvol opgezet.
