import { addExDate, deleteExDate, ADD_EXDATE, DELETE_EXDATE } from '../actions';
import { exDatesReducer } from '../reducer';
import {
	TEST_RRULE_1,
	TEST_RRULE_2,
	TEST_DATE_1,
	TEST_DATE_2,
	TEST_DATE_STRING_1,
	TEST_DATE_STRING_2,
	TEST_ID_1,
	TEST_ID_2,
	TEST_EVENT_DATE_1,
	TEST_EVENT_DATE_2,
	TEST_STORE_1,
	TEST_STORE_2,
	TEST_STATE,
} from '../../../helpers/tests';

test( 'addExDate() returns ADD_EXDATE action', () => {
	expect( addExDate( TEST_EVENT_DATE_1, TEST_DATE_1 ) ).toEqual(
		{ type: ADD_EXDATE, id: TEST_ID_1, date: TEST_DATE_1 }
	);
} );

test( 'addExDate() throws TypeError when nothing passed', () => {
	expect( () => {
		addExDate();
	} ).toThrow( TypeError );
} );

test( 'addExDate() throws id error when nothing passed', () => {
	expect( () => {
		addExDate();
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'addExDate() throws id error when bad id passed', () => {
	expect( () => {
		addExDate( [] );
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'addExDate() throws expected Date error when no date passed', () => {
	expect( () => {
		addExDate( TEST_EVENT_DATE_1 );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'addExDate() throws TypeError when non-string passed for date', () => {
	expect( () => {
		addExDate( TEST_EVENT_DATE_1, [] );
	} ).toThrow( TypeError );
} );

test( 'addExDate() throws expected Date error when non-string passed', () => {
	expect( () => {
		addExDate( TEST_EVENT_DATE_1, [] );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'deleteExDate() returns DELETE_EXDATE action', () => {
	expect( deleteExDate( TEST_EVENT_DATE_1, TEST_DATE_1 ) ).toEqual(
		{ type: DELETE_EXDATE, id: TEST_ID_1, date: TEST_DATE_1 }
	);
} );

test( 'deleteExDate() throws TypeError when nothing passed', () => {
	expect( () => {
		deleteExDate();
	} ).toThrow( TypeError );
} );

test( 'deleteExDate() throws id error when nothing passed', () => {
	expect( () => {
		deleteExDate();
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'deleteExDate() throws id error when bad id passed', () => {
	expect( () => {
		deleteExDate( [] );
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'deleteExDate() throws expected Date error when nothing passed', () => {
	expect( () => {
		deleteExDate( TEST_EVENT_DATE_1 );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'deleteExDate() throws TypeError when non-string passed', () => {
	expect( () => {
		deleteExDate( TEST_EVENT_DATE_1, [] );
	} ).toThrow( TypeError );
} );

test( 'deleteExDate() throws expected Date error when non-string passed', () => {
	expect( () => {
		deleteExDate( TEST_EVENT_DATE_1, [] );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'exDatesReducer() works with addExDate() action and empty state', () => {
	expect(
		exDatesReducer( [], addExDate( TEST_EVENT_DATE_1, TEST_DATE_1 ) )
	).toEqual(
		[
			{
				id: TEST_ID_1,
				rRule: '',
				exRule: '',
				rDates: [],
				exDates: [ TEST_DATE_1 ],
			},
		]
	);
} );

test( 'exDatesReducer() works with addExDate() action and valid initial state',
	() => {
		expect(
			exDatesReducer(
				[ TEST_STORE_1 ],
				addExDate( TEST_EVENT_DATE_1, TEST_DATE_2 )
			)
		).toEqual(
			[
				{
					id: TEST_ID_1,
					rRule: TEST_RRULE_1,
					exRule: '',
					rDates: [ TEST_DATE_1 ],
					exDates: [ TEST_DATE_2 ],
				},
			]
		);
	}
);

test( 'exDatesReducer() works with addExDate() action and full initial state',
	() => {
		expect(
			exDatesReducer(
				TEST_STATE,
				addExDate( TEST_EVENT_DATE_1, TEST_DATE_2 )
			)
		).toEqual(
			[
				{
					id: TEST_ID_2,
					rRule: TEST_RRULE_1,
					exRule: TEST_RRULE_2,
					rDates: [ TEST_DATE_1 ],
					exDates: [ TEST_DATE_2 ],
				},
				{
					id: TEST_ID_1,
					rRule: TEST_RRULE_1,
					exRule: '',
					rDates: [ TEST_DATE_1 ],
					exDates: [ TEST_DATE_2 ],
				},
			]
		);
	}
);

test( 'exDatesReducer() and addExDate() does not add duplicates', () => {
	expect(
		exDatesReducer(
			[ TEST_STORE_2 ],
			addExDate( TEST_EVENT_DATE_2, TEST_DATE_2 )
		)
	).toEqual(
		[
			{
				id: TEST_ID_2,
				rRule: TEST_RRULE_1,
				exRule: TEST_RRULE_2,
				rDates: [ TEST_DATE_1 ],
				exDates: [ TEST_DATE_2 ],
			},
		]
	);
} );

test( 'exDatesReducer() and addExDate() does not add new Date with same value',
	() => {
		expect(
			exDatesReducer(
				[ TEST_STORE_2 ],
				addExDate( TEST_EVENT_DATE_2, new Date( TEST_DATE_STRING_2 ) )
			)
		).toEqual(
			[
				{
					id: TEST_ID_2,
					rRule: TEST_RRULE_1,
					exRule: TEST_RRULE_2,
					rDates: [ TEST_DATE_1 ],
					exDates: [ TEST_DATE_2 ],
				},
			]
		);
	}
);

test( 'exDatesReducer() works with deleteExDate() action and valid initial state',
	() => {
		expect(
			exDatesReducer(
				[ TEST_STORE_2 ],
				deleteExDate( TEST_EVENT_DATE_2, TEST_DATE_2 )
			)
		).toEqual(
			[
				{
					id: TEST_ID_2,
					rRule: TEST_RRULE_1,
					exRule: TEST_RRULE_2,
					rDates: [ TEST_DATE_1 ],
					exDates: [],
				},
			]
		);
	}
);

test(
	'exDatesReducer() works with deleteExDate() action and new Date with same value',
	() => {
		expect(
			exDatesReducer(
				[ TEST_STORE_2 ],
				deleteExDate( TEST_EVENT_DATE_2, new Date( TEST_DATE_STRING_2 ) )
			)
		).toEqual(
			[
				{
					id: TEST_ID_2,
					rRule: TEST_RRULE_1,
					exRule: TEST_RRULE_2,
					rDates: [ TEST_DATE_1 ],
					exDates: [],
				},
			]
		);
	}
);

test( 'exDatesReducer() and deleteExDate() does not delete dates it should not',
	() => {
		expect(
			exDatesReducer(
				[ TEST_STORE_2 ],
				deleteExDate( TEST_EVENT_DATE_2, TEST_DATE_1 )
			)
		).toEqual(
			[
				{
					id: TEST_ID_2,
					rRule: TEST_RRULE_1,
					exRule: TEST_RRULE_2,
					rDates: [ TEST_DATE_1 ],
					exDates: [ TEST_DATE_2 ],
				},
			]
		);
	}
);

test(
	'exDatesReducer() and deleteExDate() does not delete dates when supplied' +
	' with new Date with different values',
	() => {
		expect(
			exDatesReducer(
				[ TEST_STORE_2 ],
				deleteExDate( TEST_EVENT_DATE_2, new Date( TEST_DATE_STRING_1 ) )
			)
		).toEqual(
			[
				{
					id: TEST_ID_2,
					rRule: TEST_RRULE_1,
					exRule: TEST_RRULE_2,
					rDates: [ TEST_DATE_1 ],
					exDates: [ TEST_DATE_2 ],
				},
			]
		);
	}
);

test( 'exDatesReducer() works with deleteExDate() action and full initial state',
	() => {
		expect(
			exDatesReducer(
				TEST_STATE,
				deleteExDate( TEST_EVENT_DATE_2, TEST_DATE_2 )
			)
		).toEqual(
			[
				{
					id: TEST_ID_1,
					rRule: TEST_RRULE_1,
					exRule: '',
					rDates: [ TEST_DATE_1 ],
					exDates: [],
				},
				{
					id: TEST_ID_2,
					rRule: TEST_RRULE_1,
					exRule: TEST_RRULE_2,
					rDates: [ TEST_DATE_1 ],
					exDates: [],
				},
			]
		);
	}
);
