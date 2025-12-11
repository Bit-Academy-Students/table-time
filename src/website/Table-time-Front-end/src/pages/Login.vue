<template>
    <main class="flex justify-center flex-col items-center gap-8 mt-16">
        <h1>Login Pagina</h1>
        <form class="flex flex-col gap-4 justify-center items-center">
            <input type="text" name="naamRestaurant" placeholder="Restarant naam" />
            <input type="email" name="email" placeholder="E-mail" />
            <input type="text" name="phoneNumber" placeholder="Telefoonnummer" />
            <input type="password" name="password" placeholder="Wachtwoord" />
            <button><input type="submit" name="submit" value="Inloggen" /></button>
        </form>
    </main>
</template>
<script>
/*
 * @fileoverview Client-side form handler for the Login / Customer creation page.
 *
 * Responsibilities
 * 1. Form wiring - Attaches a submit handler to the login/registration form
 *    and prevents the default submit behaviour.
 * 2. Data collection & serialization - Reads form fields (restaurant name,
 *    email, phone number, password), converts/validates where appropriate,
 *    and serializes the payload for the backend.
 * 3. Network interaction - Sends a POST request to the backend endpoint
 *    (`/Customers`) using the browser `fetch` API and handles success/error
 *    logging.
 * 4. Error handling - Catches fetch/network errors and logs them to the
 *    console; intended for simple debugging during development.
 *
 * Behavior notes
 * - This file registers a DOMContentLoaded listener and mutates no external
 *   application state; it is intentionally minimal and uses the DOM API
 *   directly rather than a Vue reactive form model.
 * - The server endpoint is expected to accept JSON with the fields
 *   `{ nameRestaurant, email, phoneNumber, password }`.
 *
 * External dependencies
 * - Browser `fetch` API for network requests.
 * - Native DOM APIs for form selection and events.
 *
 * Module
 * @module LoginPage
 * @exports none (script attaches event handlers at runtime)
 */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const nameRestaurant = form.naamRestaurant.value
        const email = form.email.value
        const phoneNumber = form.phoneNumber.value
        const password = form.password.value

        const body = {
            nameRestaurant: nameRestaurant,
            email: email,
            phoneNumber: Number(phoneNumber),
            password: password
        }

        fetch("http://localhost:8080//Customers", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(body),

        }).then((data) => {
            console.log('Success:', data);
        })
            .catch((error) => {
                console.error('Error:', error);
            });
    });

});
</script>