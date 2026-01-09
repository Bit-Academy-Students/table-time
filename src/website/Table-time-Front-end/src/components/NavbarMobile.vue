<!--
/**
 * Bestandsnaam: NavbarMobile.vue
 *
 * Beschrijving:
 * Dit component toont de mobiele navigatiebalk van de applicatie.
 * De navigatiebalk bevat het bedrijfslogo en een hamburgermenu.
 * Bij het klikken op het hamburgermenu schuift het menu uit en toont
 * links naar Home, Reserveren, Over ons, Contact en Inloggen.
 *
 * Auteur: Alexander Zoet
 * Bedrijf: Unc B.V.
 *
 * Versiebeheer:
 * - Versie: 1.2.0
 * - Laatste wijziging: 16 december 2025
 * - Beheer: Git
 */
-->
<template>
    <header class="flex justify-center">
        <nav class="max-[768px]:flex max-[768px]:justify-center min-[768px]:hidden mt-4 z-50">
            <div class="flex flex-row justify-between items-center w-full">
                <div class="flex items-center justify-between">
                    <div class="w-[50px]"><img src="../assets/img/logo TableTImes.png" alt="Table Times Logo"></div>
                </div>

                <button class="--hamburgerBtn" @click="toggleMenu">
                    <span class="text-black bg-black block h-1 rounded transition-all duration-300"
                        :class="{ 'rotate-45 translate-y-2': isMenuOpen }"></span>
                    <span class="text-black bg-black block h-1 rounded transition-all duration-300"
                        :class="{ '-rotate-45 -translate-y-2': isMenuOpen }"></span>
                </button>
            </div>
        </nav>
    </header>

    <transition name="slide-side">
        <ul v-if="isMenuOpen" class="--navbarMenuMobile">
            <li><a @click="closeMenu" class="transition-colors hover:cursor-pointer" href="/">Home</a></li>
            <li><a @click="closeMenu" class="transition-colors hover:cursor-pointer" href="/reservations">Reserveren</a></li>
            <li><a class="hover:cursor-pointer">Over ons</a></li>
            <li><a @click="closeMenu" class="transition-colors hover:cursor-pointer" href="/contact">Contact</a></li>
            <li><a class="hover:cursor-pointer">Inloggen</a></li>
        </ul>
    </transition>
</template>

<script setup>
/*
 * Importeert ref voor reactiviteit en useRouter voor navigatie
 */
import { ref } from "vue"
import { useRouter } from "vue-router"

const router = useRouter()

/*
 * Variabelen voor gebruiker en menu status
 */
const user = ref(null)
const isMenuOpen = ref(false)

/*
 * Toggle functie voor het openen en sluiten van het menu
 */
const toggleMenu = () => (isMenuOpen.value = !isMenuOpen.value)

/*
 * Sluit het mobiele menu
 */
const closeMenu = () => (isMenuOpen.value = false)
</script>

<style scoped>
/* Desktop fade/slide animatie */
.slide-fade-enter-active,
.slide-fade-leave-active {
    transition: all 0.3s ease;
}
.slide-fade-enter-from,
.slide-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
.slide-fade-enter-to,
.slide-fade-leave-from {
    opacity: 1;
    transform: translateY(0);
}

/* Mobile slide from top animatie */
.slide-side-enter-active,
.slide-side-leave-active {
    transition: all 0.3s ease;
}
.slide-side-enter-from {
    transform: translateY(-100%);
}
.slide-side-enter-to {
    transform: translateY(0);
}
.slide-side-leave-from {
    transform: translateY(0);
}
.slide-side-leave-to {
    transform: translateY(-100%);
}
</style>
