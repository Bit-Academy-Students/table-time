export function parseDateToDecimal(dateString) {
    const [, time] = dateString.split(" ");
    const [h, m] = time.split(":").map(Number);
    return h + m / 60;
  }
  
  export function formatTimeFromDate(dateString) {
    const [, time] = dateString.split(" ");
    return time.slice(0, 5);
  }
  
  function pad(n) {
    return String(n).padStart(2, "0");
  }
  
  export function formatDateTimeForAPI(dateObj) {
    return `${dateObj.getFullYear()}-${pad(dateObj.getMonth()+1)}-${pad(dateObj.getDate())} ${pad(dateObj.getHours())}:${pad(dateObj.getMinutes())}:00`;
  }
  
  export function assignColumns(resList) {
    const sorted = [...resList].sort(
      (a, b) => parseDateToDecimal(a.startDate) - parseDateToDecimal(b.startDate)
    );
  
    const columns = [];
  
    sorted.forEach(res => {
      const start = parseDateToDecimal(res.startDate);
      let placed = false;
  
      for (let i = 0; i < columns.length; i++) {
        const last = columns[i][columns[i].length - 1];
        if (start >= parseDateToDecimal(last.endDate)) {
          columns[i].push(res);
          res.columnIndex = i;
          placed = true;
          break;
        }
      }
  
      if (!placed) {
        columns.push([res]);
        res.columnIndex = columns.length - 1;
      }
    });
  
    return sorted;
  }
  