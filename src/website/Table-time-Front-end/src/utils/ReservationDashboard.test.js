import { describe, it, expect } from 'vitest';
import {
  parseDateToDecimal,
  formatTimeFromDate,
  formatDateTimeForAPI,
  assignColumns
} from './reservationUtils';

describe('parseDateToDecimal', () => {
  it('zet 12:30 om naar 12.5', () => {
    expect(parseDateToDecimal('2025-01-01 12:30')).toBe(12.5);
  });
});

describe('formatTimeFromDate', () => {
  it('haalt HH:mm uit datetime', () => {
    expect(formatTimeFromDate('2025-01-01 18:45')).toBe('18:45');
  });
});

describe('formatDateTimeForAPI', () => {
  it('formatteert correct voor backend', () => {
    const d = new Date('2025-01-01T09:05:00');
    expect(formatDateTimeForAPI(d)).toBe('2025-01-01 09:05:00');
  });
});

describe('assignColumns', () => {
  it('zet overlappende reserveringen naast elkaar', () => {
    const res = [
      { startDate: '2025-01-01 12:00', endDate: '2025-01-01 13:00' },
      { startDate: '2025-01-01 12:30', endDate: '2025-01-01 13:30' },
      { startDate: '2025-01-01 13:30', endDate: '2025-01-01 14:00' }
    ];

    const result = assignColumns(res);

    expect(result[0].columnIndex).toBe(0);
    expect(result[1].columnIndex).toBe(1);
    expect(result[2].columnIndex).toBe(0);
  });
});
