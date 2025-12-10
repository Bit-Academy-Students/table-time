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