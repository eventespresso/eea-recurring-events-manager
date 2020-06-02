// RRules
export const TEST_RRULE_1 = 'FREQ=WEEKLY;DTSTART=20120201T093000Z';
export const TEST_RRULE_2 = 'FREQ=WEEKLY;DTSTART=20120201T093000Z;INTERVAL=2;BYDAY=FR';
// Twos day and Fools Day
export const TEST_DATE_STRING_1 = '2022-02-22 02:22 am';
export const TEST_DATE_STRING_2 = '2024-04-01 12:00 am';
export const TEST_DATE_1 = new Date( TEST_DATE_STRING_1 );
export const TEST_DATE_2 = new Date( TEST_DATE_STRING_2 );
// example IDs
export const TEST_ID_1 = '123';
export const TEST_ID_2 = '456';
// example event dates
export const TEST_EVENT_DATE_1 = { id: TEST_ID_1, date: TEST_DATE_1 };
export const TEST_EVENT_DATE_2 = { id: TEST_ID_2, date: TEST_DATE_2 };
// example event date stores
export const TEST_STORE_1 = {
	id: TEST_ID_1,
	rRule: TEST_RRULE_1,
	exRule: '',
	rDates: [ TEST_DATE_1 ],
	exDates: [],
};
export const TEST_STORE_2 = {
	id: TEST_ID_2,
	rRule: TEST_RRULE_1,
	exRule: TEST_RRULE_2,
	rDates: [ TEST_DATE_1 ],
	exDates: [ TEST_DATE_2 ],
};
// example state
export const TEST_STATE = [];
TEST_STATE.push( TEST_STORE_1 );
TEST_STATE.push( TEST_STORE_2 );
