<!--
/**
 * Bestandsnaam: AboutUs.vue
 *
 * Beschrijving:
 * Deze view toont de "Over ons"-pagina van de applicatie.
 * De pagina bevat algemene informatie over Table Time, inclusief
 * het verhaal achter het platform en de visie voor gasten en restaurants.
 *
 * Auteur: Rayan Karmimmech 
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.0.0
 * - Laatste wijziging: 19 December 2025
 * - Beheer: Git
 */
-->
<script setup>
/*
 * Importeert de desktop navigatiebalk component
 */
import NavBar from '../components/NavBar.vue';

/*
 * Importeert de mobiele navigatiebalk component
 */
import NavbarMobile from '../components/NavbarMobile.vue';

/*
 * Importeert de footer component
 */
import Footer from '../components/Footer.vue';
</script>

<template>
    <NavBar />

    <NavbarMobile />

    <main class="flex flex-col items-center pt-[100px]">
        <section class="flex flex-col justify-center max-[768px]:flex-col w-[80%] mb-[100px]">

            <div class="flex flex-col justify-center items-center text-center h-full">
                <h1 class="--h1Aboutus">
                    "voor gasten soepel, voor restaurants helder"
                </h1>

                <img src="../assets/img/logo TableTImes.png" alt="Table Time logo"
                    class="w-[300px] max-[768px]:w-[50%]" />
            </div>

            <div class="flex flex-col justify-center items-center text-center h-full">
                <h2>Ons verhaal</h2>

                <p>
                    Table Time is ontwikkeld om minder ingewikkeld te zijn dan het heden.
                    Reserveren hoort simpelweg niet zo lastig te zijn als het nu is.
                    Niet eerst moeten zoeken, bellen, of wéér een keer wat anders lezen,
                    gewoon direct een tafeltje claimen en klaar.

                    Bezoekers kunnen in een paar klikken een tafeltje reserveren,
                    zelf hun boeking aanpassen of annuleren, en aan de hand van
                    duidelijke vragen alvast informatie achterlaten.

                    Restaurants krijgen precies datgene wat ze nu missen: regie.
                    Zelf bepalen zij openingstijden, tijdsloten, beschikbare tafels
                    en het maximale aantal gasten per dag.

                    Vanuit het dashboard beheren restaurants reserveringen,
                    de menukaart, aanbiedingen en wijzigingen.

                    Table Time zorgt voor een vlotte gang van zaken,
                    minder administratief gedoe en meer focus op wat
                    echt belangrijk is: lekker eten en blije gasten.
                </p>

                <img src="../assets/img/AboutMe.png" alt="Over ons afbeelding" class="max-[768px]" />
            </div>

        </section>
    </main>

    <Footer />
</template>
