<script setup>
import Footer from '../components/Footer.vue';
import NavBar from '../components/NavBar.vue';
import { ref } from 'vue';
import NavbarMobile from '../components/NavbarMobile.vue';

const active = ref('Guests');

</script>

<template>
    <NavBar />
    <NavbarMobile />
    <main class="flex flex-col items-center max- pt-[100px]">
        <section class="flex flex-col justify-center max-[768px]:flex-col w-[80%] mb-[100px]">
            <div class="flex flex-col justify-center items-center text-center h-full">
                <h1 class="--h1Aboutus">"voor gasten soepel, voor restaurants helder"</h1>
                <img src="../assets/img/logo TableTImes.png" alt="About Us Image" class="w-[300px] max-[768px]:w-[50%]" />
            </div>
            <div class="flex flex-col justify-center items-center text-center h-full">
                <h2>Ons verhaal</h2>
                <p>Table​‍​‌‍​‍‌ Time is ontwikkeld om minder ingewikkeld te zijn dan het heden. Reserveren hoort
                    simpelweg niet zo lastig te zijn als het nu is. Niet eerst moeten zoeken, bellen, of wéér een keer
                    wat anders lezen gewoon direct een tafeltje claimen en klaar.
                    Bezoekers kunnen in een paar klikken een tafeltje reserveren, zelf hun boeking aanpassen of
                    annuleren, en aan de hand van duidelijke vragen alvast informatie achterlaten. Net zoals je gewend
                    bent, zonder lastig te doen of voor verrassingen komen te staan.
                    Restaurants krijgen precies datgene wat ze nú missen: regie. Zelf bepalen ze wanneer ze geopend
                    zijn, de gewenste tijdsloten, hoeveel tafels er beschikbaar zijn en wat het maximale aantal gasten
                    is dat per dag binnen mag komen. Vanaf hun dashboard regelen ze alles: reserveringen, de menukaart,
                    aanbiedingen en eventuele wijzigingen.
                    Table Time zorgt ervoor dat voormalige voor nemers dezelfde bestemming bereiken: een vlotte gang van
                    zaken, minder gedoe achter de schermen en meer aandacht voor wat écht belangrijk is lekker eten,
                    blije gasten en een strak overzicht in je ​‍​‌‍​‍‌agenda.</p>
                    <img src="../assets/img/AboutMe.png" alt="About Us Image" class="max-[768px]" />

            </div>
        </section>
    </main>
    <Footer />
</template>