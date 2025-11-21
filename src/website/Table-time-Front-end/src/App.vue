<script setup>
import NavBar from './components/NavBar.vue';

</script>

<template>
    <NavBar />
    <main class="flex flex-col items-center pt-[100px]">
        <section class="flex flex-row justify-center w-[80%] mb-[300px]">
            <div class="w-[50%]">
                <h2>Table Time</h2>
                <p>Table Time is een moderne webapp waarmee klanten eenvoudig tafels kunnen reserveren, aanpassen of
                    annuleren
                    bij
                    hun favoriete restaurant. Restaurant­eigenaren beheren moeiteloos hun beschikbare tafels, tijdsloten
                    en
                    capaciteit allemaal in één overzichtelijk dashboard.</p>
            </div>
            <div class="w-[50%] flex justify-end">
                <img src="./assets/img/homepageIMg.png" alt="">
            </div>
        </section>
        <h1 class="flex justify-center">Hoe werkt het</h1>
        <div class="flex flex-row gap-5 bg-white p-2 rounded-[10px] mb-[100px]">
            <button>Gasten</button>
            <button>Restaurant­eigenaren</button>
        </div>
        <section class="flex flex-col justify-center w-[80%]">
            <div class="w-full  border-t-4 border-[#FF8000] flex flex-row justify-between items-center gap-10 p-10">
                <div class="flex justify-between gap-10">
                    <h2 class="--h2Li">
                        1.</h2>
                    <div class="flex flex-col justify-center">
                        <h2>Kies je restaurant!</h2>
                        <p>Vind jouw favoriete plek in de lijst!</p>
                    </div>
                </div>
                    <img src="./assets/img/choosingRestaurant.png" class="rounded-full" alt="">
                </div>
                <div class="w-full   flex flex-row justify-between items-center gap-10">
                    <img src="./assets/img/choosingDateAndTime.png" alt="">
                    <div class="flex justify-between">
                        <div class="flex flex-col justify-center">
                            <h2>Selecteer datum & tijd</h2>
                            <p>Zie direct welke tafels vrij zijn.</p>
                        </div>
                        <h2 class="--h2Li">
                            2.</h2>
                    </div>
                </div>
                <div class="w-full  flex flex-row justify-between items-center gap-10">
                    <div class="flex justify-between">
                        <h2 class="--h2Li">
                            3.</h2>
                        <div class="flex flex-col justify-center">
                            <h2>Vul je gegevens in</h2>
                            <p>Geef aan met hoeveel personen je komt en voeg speciale wensen toe.</p>
                        </div>
                    </div>
                    <img src="./assets/img/fillingIn.png" class="rounded-full" alt="">
                </div>
                <div class="w-full flex flex-row justify-between items-center gap-10">
                    <img src="./assets/img/confirmEmail.png" class="rounded-full" alt="">
                    <div class="flex justify-between">
                        <div class="flex flex-col justify-end">
                            <h2>Bevestig</h2>
                            <p>Ontvang direct een bevestiging per e-mail.</p>
                        </div>
                        <h2 class="--h2Li">
                            4.</h2>
                    </div>
                </div>
                <div class="w-full flex flex-row justify-between items-center gap-10 p-4">
                    <div class="flex justify-between gap-10">
                    <h2 class="--h2Li">
                        5.</h2>
                    <div class="flex flex-col justify-center">
                        <h2>Beheer zelf</h2>
                        <p>Bekijk, wijzig of annuleer eenvoudig jouw reservering.</p>
                    </div>
                </div>
                    <img src="./assets/img/confirmEmail.png" class="rounded-full" alt="">
                </div>
        </section>
    </main>
</template>
