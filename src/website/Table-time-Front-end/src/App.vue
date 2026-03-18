<script setup>
import NavBar from './components/NavBar.vue';
import NavbarMobile from './components/NavbarMobile.vue';
import Footer from './components/Footer.vue';

</script>

<template>
    <NavBar />
    <NavbarMobile />
    <main class="flex flex-col items-center pt-[100px] w-full">
        <router-view />
    </main>
    <Footer />
</template>
