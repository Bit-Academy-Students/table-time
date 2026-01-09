import { describe, it, expect } from 'vitest';
import {
  parseDateToDecimal,
  formatTimeFromDate,
  formatDateTimeForAPI,
  assignColumns
} from './reservationUtils';

describe('Date & time utilities', () => {

  it('zet tijd om naar decimaal', () => {
    expect(parseDateToDecimal('2025-01-01 12:30')).toBe(12.5);
  });

  it('formatteert tijd correct', () => {
    expect(formatTimeFromDate('2025-01-01 18:45')).toBe('18:45');
  });

  it('formatteert Date object voor API', () => {
    const d = new Date(2025, 0, 1, 9, 5);
    expect(formatDateTimeForAPI(d)).toBe('2025-01-01 09:05:00');
  });

});

describe('assignColumns', () => {

  it('plaatst overlappende reserveringen in aparte kolommen', () => {
    const res = [
      { startDate: '2025-01-01 12:00', endDate: '2025-01-01 13:00' },
      { startDate: '2025-01-01 12:30', endDate: '2025-01-01 13:30' }
    ];

    const result = assignColumns(res);

    expect(result[0].columnIndex).toBe(0);
    expect(result[1].columnIndex).toBe(1);
  });

  it('plaatst niet-overlappende reserveringen in dezelfde kolom', () => {
    const res = [
      { startDate: '2025-01-01 12:00', endDate: '2025-01-01 13:00' },
      { startDate: '2025-01-01 13:00', endDate: '2025-01-01 14:00' }
    ];

    const result = assignColumns(res);

    expect(result[0].columnIndex).toBe(0);
    expect(result[1].columnIndex).toBe(0);
  });

});
