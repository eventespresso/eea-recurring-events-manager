// RRules
export const TEST_RRULE = 'FREQ=WEEKLY;DTSTART=20120201T093000Z';
export const TEST_RRULE_2 = 'FREQ=WEEKLY;DTSTART=20120201T093000Z;INTERVAL=2;BYDAY=FR';
// Twos day and Fools Day
export const TEST_DATE_STRING = '2022-02-22 02:22 am';
export const TEST_DATE_STRING_2 = '2024-04-01 12:00 am';
export const TEST_DATE = new Date( TEST_DATE_STRING );
export const TEST_DATE_2 = new Date( TEST_DATE_STRING_2 );
// example state
export const TEST_STATE = {
	rRule: 'FREQ=WEEKLY;DTSTART=20120201T093000Z',
	exRule: 'FREQ=WEEKLY;DTSTART=20120201T093000Z',
	rDates: [ TEST_DATE ],
	exDates: [ TEST_DATE_2 ],
};
