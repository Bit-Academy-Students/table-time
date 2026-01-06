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
