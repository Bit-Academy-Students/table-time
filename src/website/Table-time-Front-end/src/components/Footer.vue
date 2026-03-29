<!--
/**
 * Bestandsnaam: Footer.vue
 *
 * Beschrijving:
 * Dit component toont de footer van de applicatie.
 * De footer bevat het bedrijfslogo, navigatielinks naar belangrijke pagina's
 * en bedrijfsinformatie zoals copyright.
 *
 * Auteur: Alexander Zoet
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.1
 * - Laatste wijziging: 16 december 2025
 * - Beheer: Git
 */
-->
<template>
    <footer class="bg-white border-t-4 border-[#FF8000] flex flex-col justify-center p-4 mt-4">
        <div class="flex flex-row max-[768px]:flex-col justify-around p-4 mt-4">
            <ul>
                <li><img src="../assets/img/logo TableTImes.png" class="w-[300px]" alt=""></li>
            </ul>
            <ul class="max-[768px]:text-center">
                <li>
                    <h2>Pages</h2>
                </li>
                <li><a href="/">Home</a></li>
                <li><a href="/RestaurantList">Restaurants</a></li>
                <li><a href="/AboutUs">Over ons</a></li>
                <li><a href="/contact">Contact</a></li>
                <li><a href="/register">Register</a></li>
                <li><a href="/restaurant/login">Login</a></li>
            </ul>
        </div>

        <div class="border-t-2 border-[#FF8000]">
            <p class="flex justify-start items-center">{{new Date().getFullYear()}} Team Unc B.V.</p>
        </div>
    </footer>
</template>