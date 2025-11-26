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
      
    const date = form.date.value;          // "2025-11-26"
    const time = form.time.value;          // "14:00"
    const duration = form.duration.value;  // "01:30" (hh:mm)
    const amountPeople = form.amountPeople.value; // e.g. "3"

    const [hours, minutes] = time.split(':').map(Number);
    const [durHours, durMinutes] = duration.split(':').map(Number);

    // Create start date object
    const startDateObj = new Date(date);
    startDateObj.setHours(hours, minutes);

    // Create end date object
    const endDateObj = new Date(startDateObj);
    endDateObj.setHours(endDateObj.getHours() + durHours);
    endDateObj.setMinutes(endDateObj.getMinutes() + durMinutes);

    // Helper to format date as "YYYY-MM-DD HH:mm"
    function formatDate(d) {
      const pad = (n) => String(n).padStart(2, '0');
      return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
    }

    const body = {
      startDate: formatDate(startDateObj),
      endDate: formatDate(endDateObj),
      amountPeople: Number(amountPeople),
    };
    

    fetch('http://localhost:8080/Reservations', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(body),
    })
      .then((data) => {
        console.log('Success:', data);
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  });
});

fetch('http://localhost:8080/Reservations')
  .then((response) => response.json())
  .then((data) => {
    console.log('Reservations:', data);
  })
  .catch((error) => {
    console.error('Error fetching reservations:', error);
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