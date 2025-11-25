<template>
    <header class="flex justify-center">
        <nav class="max-[768px]:flex max-[768px]:justify-center min-[768px]:hidden mt-4 z-50">
            <div class="flex flex-row justify-between items-center w-full">
                <div class="flex items-center justify-between">
                    <div class="w-[50px]"><img src="../assets/img/logo TableTImes.png" alt=""></div>
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
        <ul v-if="isMenuOpen"
            class="--navbarMenuMobile">
            <li> <a @click="() => { closeMenu() }"
                    class="transition-colors hover:cursor-pointer"
                    href="/">
                    Home
                </a></li>
            <li><a @click="() => { closeMenu() }"
                    class="transition-colors hover:cursor-pointer"
                    href="/reserveren">Reserveren</a></li>
                    <li>
                        <a class="hover:cursor-pointer">Over ons</a> 
                    </li>
            <li><a @click="() => { closeMenu() }"
                    class="transition-colors hover:cursor-pointer"
                    href="/contact">Contact</a></li>
            <li><a class="hover:cursor-pointer">Inloggen</a>
            </li>
        </ul>
    </transition>
</template>

<script setup>
import { ref } from "vue"
import { useRouter } from "vue-router"

const router = useRouter()
const user = ref(null)
const isMenuOpen = ref(false)

const toggleMenu = () => (isMenuOpen.value = !isMenuOpen.value)
const closeMenu = () => (isMenuOpen.value = false)
</script>

<style scoped>
/* Desktop fade/slide */
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

/* Mobile slide from left */
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