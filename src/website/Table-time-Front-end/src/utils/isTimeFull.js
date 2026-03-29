// utils/isTimeFull.js
// Controleert of een tijdslot vol is op basis van bestaande reserveringen en capaciteit
// Geeft true terug als het slot niet beschikbaar is, false als er nog plek is
export function isTimeFull({
    date,           // Datum als string (bijv. "2025-01-01")
    time,           // Tijd als string (bijv. "12:00")
    duration,       // Duur als string (bijv. "01:30")
    reservations,   // Lijst van bestaande reserveringen van dit restaurant
    capacity,       // Maximale capaciteit van het restaurant
    amountPeople,   // Aantal mensen van de nieuwe reservering
    now = new Date() // Huidige tijd (kan worden overschreven in tests)
  }) {
    // Als verplichte velden ontbreken, beschouw het slot als vol (veiligheidscheck)
    if (!date || !amountPeople || !duration) return true;

    // Maak een Date-object van de gewenste starttijd
    const start = new Date(`${date}T${time}:00`);

    // Tijdsloten in het verleden zijn niet beschikbaar
    if (start < now) return true;

    // Bereken de eindtijd door de duur op te tellen bij de starttijd
    const [h, m] = duration.split(":").map(Number);
    const end = new Date(start);
    end.setHours(end.getHours() + h);
    end.setMinutes(end.getMinutes() + m);

    // Tel hoeveel mensen er al geboekt zijn in het gewenste tijdvak
    // Een reservering overlapt als die begint vóór onze eindtijd EN eindigt na onze starttijd
    const used = reservations
      .filter(r => {
        const s = new Date(r.startDate.replace(' ', 'T')); // Zet backend-format om naar Date-object
        const e = new Date(r.endDate.replace(' ', 'T'));
        return start < e && end > s; // Overlap-check
      })
      .reduce((sum, r) => sum + r.amountPeople, 0); // Tel alle mensen op uit overlappende reserveringen

    // Geeft true (vol) als het totaal aantal mensen de capaciteit overschrijdt
    return used + Number(amountPeople) > capacity;
}
