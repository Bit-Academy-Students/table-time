<script setup>
import Footer from '../components/Footer.vue';
import GuestText from '../components/homePageText/GuestText.vue';
import RestaurantOwnersText from '../components/homePageText/RestaurantOwnersText.vue';
import NavBar from '../components/NavBar.vue';
import { ref } from 'vue';
import NavbarMobile from '../components/NavbarMobile.vue';

const active = ref('Guests');

</script>

<template>
    <NavBar />
    <NavbarMobile />
    <main class="flex flex-col items-center max- pt-[100px]">
        <section class="flex flex-row justify-center max-[768px]:flex-col w-[80%] mb-[100px]">
            <div class="w-[50%] max-[768px]:w-full">
                <h2>Table Time</h2>
                <p>Table Time is een moderne webapp waarmee klanten eenvoudig tafels kunnen reserveren, aanpassen of
                    annuleren
                    bij
                    hun favoriete restaurant. Restaurant­eigenaren beheren moeiteloos hun beschikbare tafels, tijdsloten
                    en
                    capaciteit allemaal in één overzichtelijk dashboard.</p>
            </div>
            <div class="w-[50%] max-[768px]:w-full flex justify-end flex-wrap">
                <img src="./assets/img/homepageIMg.png" alt="">
            </div>
        </section>
        <h1 class="flex justify-center">Hoe werkt het</h1>
        <div class="flex flex-row gap-5 bg-white p-2 rounded-[10px] mb-[100px] max-[768px]:mb-[50px]">
            <button
            class="max-[768px]:w-[120px] max-[768px]:text-[12px]"
            :class="{ 'bg-[#FF8000]' : active === 'Guests' }"
            @click="active = 'Guests'">Gasten</button>
            <button
            class="max-[768px]:w-[120px] max-[768px]:text-[12px]"
            :class="{ 'bg-[#FF8000]' : active === 'restaurantOwners' }"
            @click="active = 'restaurantOwners'">Restaurant­eigenaren</button>
        </div>
        <section v-if="active === 'Guests'"
        id="Guests" class="flex flex-col justify-center w-[80%]">
        <GuestText />
        </section>
        <section v-if="active === 'restaurantOwners'"
         id="restaurantOwners" class="flex flex-col justify-center w-[80%]">
         <RestaurantOwnersText  />
        </section>
    </main>
    <Footer />
</template>
