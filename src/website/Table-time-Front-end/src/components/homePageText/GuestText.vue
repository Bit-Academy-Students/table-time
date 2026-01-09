<!--
/**
 * Bestandsnaam: GuestText.vue
 *
 * Beschrijving:
 * Deze component toont het "Hoe het werkt"-gedeelte van de applicatie.
 * Het legt stap voor stap uit hoe een gebruiker een reservering kan maken:
 * 1. Kies je restaurant
 * 2. Selecteer datum & tijd
 * 3. Vul je gegevens in
 * 4. Bevestig via e-mail
 * 5. Beheer zelf je reservering
 * Elke stap bevat een illustratieve afbeelding en korte uitleg.
 *
 * Auteur: Alexander Zoet
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.0
 * - Laatste wijziging: 16 december 2025
 * - Beheer: Git
 */
-->
<template>
    <div class="border-t-4 border-[#FF8000] --hWHContainer">
        <div class="flex flex-row justify-between w-full">
            <h2 class="--h2Li">1.</h2>
            <div class="max-[768px]:w-[50%] flex flex-col justify-center">
                <h2 class="max-[768px]:text-[40px]">Kies je restaurant!</h2>
                <p>Vind jouw favoriete plek in de lijst!</p>
            </div>
        </div>
        <img src="../../assets/img/choosingRestaurant.png" class="rounded-full" alt="Restaurant kiezen">
    </div>

    <div class="--hWHContainer">
        <img src="../../assets/img/choosingDateAndTime.png" class="max-[768px]:order-[3]" alt="Datum en tijd selecteren">
        <div class="flex flex-row justify-between w-full">
            <div class="max-[768px]:w-[50%] flex flex-col max-[768px]:order-[1] justify-center">
                <h2 class="max-[768px]:text-[40px]">Selecteer datum & tijd</h2>
                <p>Zie direct welke tafels vrij zijn.</p>
            </div>
            <h2 class="--h2Li max-[768px]:order-[2]">2.</h2>
        </div>
    </div>

    <div class="--hWHContainer">
        <div class="flex flex-row justify-between w-full">
            <h2 class="--h2Li">3.</h2>
            <div class="max-[768px]:w-[50%] flex flex-col justify-center">
                <h2 class="max-[768px]:text-[40px]">Vul je gegevens in</h2>
                <p>Geef aan met hoeveel personen je komt en voeg speciale wensen toe.</p>
            </div>
        </div>
        <img src="../../assets/img/fillingIn.png" class="rounded-full" alt="Gegevens invullen">
    </div>

    <div class="--hWHContainer">
        <img src="../../assets/img/confirmEmail.png" class="rounded-full max-[768px]:order-[3]" alt="Bevestiging">
        <div class="flex flex-row justify-between w-full">
            <div class="max-[768px]:w-[50%] flex flex-col justify-center">
                <h2 class="max-[768px]:text-[40px]">Bevestig</h2>
                <p>Ontvang direct een bevestiging per e-mail.</p>
            </div>
            <h2 class="--h2Li">4.</h2>
        </div>
    </div>

    <div class="--hWHContainer">
        <div class="flex flex-row justify-between w-full">
            <h2 class="--h2Li">5.</h2>
            <div class="max-[768px]:w-[50%] flex flex-col justify-center">
                <h2 class="max-[768px]:text-[40px]">Beheer zelf</h2>
                <p>Bekijk, wijzig of annuleer eenvoudig jouw reservering.</p>
            </div>
        </div>
        <img src="../../assets/img/manageReservation.png" class="rounded-full" alt="Reservering beheren">
    </div>
</template>
