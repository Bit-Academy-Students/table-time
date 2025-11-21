<script>
import NavBar from '../components/NavBar.vue';

export default {
  components: {
    NavBar,
  },
};

// Script of making a request to the api
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const date = form.date.value;
    const time = form.time.value;
    const duration = form.duration.value;
    const amountPeople = form.amountPeople.value;

    const body = {
      startDate: `${date} ${time}`,
      endDate: `${date} ${time + duration}`,
      amountPeople: amountPeople,
    };
    

    fetch('http://127.0.0.1:8080/reservations', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(body),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log('Success:', data);
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  });
});
</script>

<template>
      <NavBar />
  <main class="flex justify-center">
    <section>
      <h2>Reservation</h2>
      <div>
        <form>
            <label for="date"><p>Date</p></label>
            <input type="date" name="date" placeholder="Date" required />
            <label for="time"><p>Time of day</p></label>
            <input type="time" name="time" placeholder="Time" required />
            <label for="duration"><p>Duration of the reservation</p></label>
            <input type="time" name="duration" placeholder="Duration" required />
            <label for="amountPeople"><p>How many people are you taking with you?</p></label>
            <input type="number" name="amountPeople" placeholder="Amount of people" value="1" required />
            <input type="submit" value="Make reservation" />
        </form>
      </div>
    </section>
  </main>
</template>