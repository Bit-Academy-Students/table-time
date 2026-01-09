// utils/isTimeFull.js
export function isTimeFull({
    date,
    time,
    duration,
    reservations,
    capacity,
    amountPeople,
    now = new Date()
  }) {
    if (!date || !amountPeople || !duration) return true;
  
    const start = new Date(`${date}T${time}:00`);
    if (start < now) return true;
  
    const [h, m] = duration.split(":").map(Number);
    const end = new Date(start);
    end.setHours(end.getHours() + h);
    end.setMinutes(end.getMinutes() + m);
  
    const used = reservations
      .filter(r => {
        const s = new Date(r.startDate.replace(' ', 'T'));
        const e = new Date(r.endDate.replace(' ', 'T'));
        return start < e && end > s;
      })
      .reduce((sum, r) => sum + r.amountPeople, 0);
  
    return used + Number(amountPeople) > capacity;
  }
  