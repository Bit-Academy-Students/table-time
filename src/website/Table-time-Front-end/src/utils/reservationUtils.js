// Zet een datetime-string om naar een decimaal getal
// Voorbeeld: "2025-01-01 12:30" wordt 12.5
// Wordt gebruikt om de positie van een reservering in de tijdlijn te berekenen
export function parseDateToDecimal(dateString) {
    const [, time] = dateString.split(" "); // Pak alleen het tijdgedeelte (na de spatie)
    const [h, m] = time.split(":").map(Number); // Splits in uren en minuten
    return h + m / 60; // Zet minuten om naar decimaal (30 minuten = 0.5)
}

// Haalt alleen de tijd (HH:mm) uit een datetime-string
// Voorbeeld: "2025-01-01 18:45:00" wordt "18:45"
export function formatTimeFromDate(dateString) {
    const [, time] = dateString.split(" "); // Pak het tijdgedeelte
    return time.slice(0, 5); // Pak de eerste 5 tekens zodat je alleen HH:mm overhoudt
}

// Zorgt dat een getal altijd 2 cijfers heeft
// Voorbeeld: 9 wordt "09", 12 blijft "12"
// Wordt gebruikt bij het opmaken van datums
function pad(n) {
    return String(n).padStart(2, "0");
}

// Zet een JavaScript Date-object om naar het formaat dat de backend verwacht
// Voorbeeld: new Date(2025, 0, 1, 9, 5) wordt "2025-01-01 09:05:00"
export function formatDateTimeForAPI(dateObj) {
    return `${dateObj.getFullYear()}-${pad(dateObj.getMonth()+1)}-${pad(dateObj.getDate())} ${pad(dateObj.getHours())}:${pad(dateObj.getMinutes())}:00`;
}

// Verdeelt reserveringen over kolommen zodat ze naast elkaar staan in de tijdlijn
// Als twee reserveringen tegelijk lopen, krijgen ze een andere kolomindex
// Zo staan ze in het dashboard visueel naast elkaar in plaats van over elkaar
export function assignColumns(resList) {
    // Sorteer eerst op starttijd zodat we van vroeg naar laat werken
    const sorted = [...resList].sort(
      (a, b) => parseDateToDecimal(a.startDate) - parseDateToDecimal(b.startDate)
    );

    const columns = []; // Elke kolom is een lijst van reserveringen die na elkaar vallen

    sorted.forEach(res => {
      const start = parseDateToDecimal(res.startDate);
      let placed = false;

      // Kijk of deze reservering past achter de laatste in een bestaande kolom
      for (let i = 0; i < columns.length; i++) {
        const last = columns[i][columns[i].length - 1]; // Laatste reservering in kolom i
        if (start >= parseDateToDecimal(last.endDate)) {
          // Begint na de vorige → zet hem in deze kolom
          columns[i].push(res);
          res.columnIndex = i;
          placed = true;
          break;
        }
      }

      // Past in geen enkele kolom → maak een nieuwe kolom aan
      if (!placed) {
        columns.push([res]);
        res.columnIndex = columns.length - 1;
      }
    });

    return sorted;
}
