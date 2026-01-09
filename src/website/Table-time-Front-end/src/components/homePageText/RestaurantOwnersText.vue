<!--
/**
 * Bestandsnaam: GuestText.vue
 *
 * Beschrijving:
 * Deze component toont het "Hoe het werkt"-gedeelte van de applicatie.
 * Het legt stap voor stap uit hoe een restaurantbeheerder zijn/haar restaurant kan laten registreren:
 * 1. Maak een account aan
 * 2. Bepaal capaciteit & tijdsloten
 * 3. Volg reserveringen live
 * 4. Beheer en bevestig
 * 5. Hou controle
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
            <h2 class="--h2Li">
                1.</h2>
            <div class="max-[768px]:w-[50%] flex flex-col justify-center">
                <h2 class="max-[768px]:text-[40px]">Maak een account aan</h2>
                <p>Voeg jouw restaurant en openingstijden toe.</p>
            </div>
        </div>
        <img src="../../assets/img/fillingIn.png" class="rounded-full" alt="">
    </div>
    <div class="--hWHContainer">
        <img src="../../assets/img/setTimeAndMaxGuests.png" class="max-[768px]:order-[3]" alt="">
        <div class="flex flex-row justify-between w-full">
            <div class="max-[768px]:w-[50%] flex flex-col justify-center">
                <h2 class="max-[768px]:text-[40px]">Bepaal capaciteit & tijdsloten</h2>
                <p>Stel zelf je tafels en maximale gasten in.</p>
            </div>
            <h2 class="--h2Li">
                2.</h2>
        </div>
    </div>
    <div class="--hWHContainer">
        <div class="flex flex-row justify-between w-full">
            <h2 class="--h2Li">
                3.</h2>
            <div class="max-[768px]:w-[50%] flex flex-col justify-center">
                <h2 class="max-[768px]:text-[40px]">Volg reserveringen live</h2>
                <p> Zie nieuwe boekingen direct in je dashboard.</p>
            </div>
        </div>
        <img src="../../assets/img/followReservationsLive.png" class="rounded-full" alt="">
    </div>
    <div class="--hWHContainer">
        <img src="../../assets/img/manageAndConfirm.png" class="max-[768px]:order-[3] rounded-full" alt="">
        <div class="flex flex-row justify-between w-full">
            <div class="max-[768px]:w-[50%] flex flex-col justify-center">
                <h2 class="max-[768px]:text-[40px]">Beheer en bevestig</h2>
                <p>Pas reserveringen aan of bevestig met één klik.</p>
            </div>
            <h2 class="--h2Li">
                4.</h2>
        </div>
    </div>
    <div class="--hWHContainer">
        <div class="flex flex-row justify-between w-full">
            <h2 class="--h2Li">
                5.</h2>
            <div class="max-[768px]:w-[50%] flex flex-col justify-center">
                <h2 class="max-[768px]:text-[40px]">Hou controle</h2>
                <p>Stuur herinneringen en houd overzicht op piekmomenten.</p>
            </div>
        </div>
        <img src="../../assets/img/manageReservation.png" class="rounded-full" alt="">
    </div>
</template>