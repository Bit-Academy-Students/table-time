import { describe, it, expect } from 'vitest'
import { isTimeFull } from './isTimeFull'

describe('isTimeFull', () => {

  it('geeft true terug als verplichte data ontbreekt', () => {
    const result = isTimeFull({
      date: null,
      time: '12:00',
      duration: '01:00',
      reservations: [],
      capacity: 10,
      amountPeople: 2
    })

    expect(result).toBe(true)
  })

  it('geeft true terug als starttijd in het verleden ligt', () => {
    const result = isTimeFull({
      date: '2025-01-01',
      time: '10:00',
      duration: '01:00',
      reservations: [],
      capacity: 10,
      amountPeople: 2,
      now: new Date('2025-01-01T11:00:00')
    })

    expect(result).toBe(true)
  })

  it('geeft false terug als er genoeg capaciteit is', () => {
    const result = isTimeFull({
      date: '2025-01-01',
      time: '12:00',
      duration: '01:00',
      reservations: [],
      capacity: 10,
      amountPeople: 2,
      now: new Date('2025-01-01T10:00:00')
    })

    expect(result).toBe(false)
  })

  it('geeft true terug als capaciteit wordt overschreden door bestaande reserveringen', () => {
    const result = isTimeFull({
      date: '2025-01-01',
      time: '12:00',
      duration: '01:00',
      reservations: [
        {
          startDate: '2025-01-01 12:00',
          endDate: '2025-01-01 13:00',
          amountPeople: 8
        }
      ],
      capacity: 10,
      amountPeople: 3,
      now: new Date('2025-01-01T10:00:00')
    })

    expect(result).toBe(true)
  })

  it('negeert niet-overlappende reserveringen', () => {
    const result = isTimeFull({
      date: '2025-01-01',
      time: '14:00',
      duration: '01:00',
      reservations: [
        {
          startDate: '2025-01-01 12:00',
          endDate: '2025-01-01 13:00',
          amountPeople: 8
        }
      ],
      capacity: 10,
      amountPeople: 2,
      now: new Date('2025-01-01T10:00:00')
    })

    expect(result).toBe(false)
  })

})
