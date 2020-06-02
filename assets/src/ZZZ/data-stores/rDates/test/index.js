import { addRdate, deleteRdate, ADD_RDATE, DELETE_RDATE } from '../actions';
import { rDatesReducer } from '../reducer';
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
	TEST_STORE_1,
	TEST_STATE,
} from '../../../helpers/tests';

test( 'addRdate() returns ADD_RDATE action', () => {
	expect( addRdate( TEST_EVENT_DATE_1, TEST_DATE_1 ) ).toEqual(
		{ type: ADD_RDATE, id: TEST_ID_1, date: TEST_DATE_1 }
	);
} );

test( 'addRdate() throws TypeError when nothing passed', () => {
	expect( () => {
		addRdate();
	} ).toThrow( TypeError );
} );

test( 'addRdate() throws id error when nothing passed', () => {
	expect( () => {
		addRdate();
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'addRdate() throws id error when bad id passed', () => {
	expect( () => {
		addRdate( [] );
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'addRdate() throws expected Date error when no date passed', () => {
	expect( () => {
		addRdate( TEST_EVENT_DATE_1 );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'addRdate() throws TypeError when non-string passed for date', () => {
	expect( () => {
		addRdate( TEST_EVENT_DATE_1, [] );
	} ).toThrow( TypeError );
} );

test( 'addRdate() throws expected Date error when non-string passed', () => {
	expect( () => {
		addRdate( TEST_EVENT_DATE_1, [] );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'deleteRdate() returns DELETE_RDATE action', () => {
	expect( deleteRdate( TEST_EVENT_DATE_1, TEST_DATE_1 ) ).toEqual(
		{ type: DELETE_RDATE, id: TEST_ID_1, date: TEST_DATE_1 }
	);
} );

test( 'deleteRdate() throws TypeError when nothing passed', () => {
	expect( () => {
		deleteRdate();
	} ).toThrow( TypeError );
} );

test( 'deleteRdate() throws id error when nothing passed', () => {
	expect( () => {
		deleteRdate();
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'deleteRdate() throws id error when bad id passed', () => {
	expect( () => {
		deleteRdate( [] );
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'deleteRdate() throws expected Date error when nothing passed', () => {
	expect( () => {
		deleteRdate( TEST_EVENT_DATE_1 );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'deleteRdate() throws TypeError when non-string passed', () => {
	expect( () => {
		deleteRdate( TEST_EVENT_DATE_1, [] );
	} ).toThrow( TypeError );
} );

test( 'deleteRdate() throws expected Date error when non-string passed', () => {
	expect( () => {
		deleteRdate( TEST_EVENT_DATE_1, [] );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'rDatesReducer() works with addRdate() action and empty state', () => {
	expect(
		rDatesReducer( [], addRdate( TEST_EVENT_DATE_1, TEST_DATE_1 ) )
	).toEqual(
		[
			{
				id: TEST_ID_1,
				rRule: '',
				exRule: '',
				rDates: [ TEST_DATE_1 ],
				exDates: [],
			},
		]
	);
} );

test( 'rDatesReducer() works with addRdate() action and valid initial state',
	() => {
		expect(
			rDatesReducer(
				[ TEST_STORE_1 ],
				addRdate( TEST_EVENT_DATE_1, TEST_DATE_2 )
			)
		).toEqual(
			[
				{
					id: TEST_ID_1,
					rRule: TEST_RRULE_1,
					exRule: '',
					rDates: [ TEST_DATE_1, TEST_DATE_2 ],
					exDates: [],
				},
			]
		);
	}
);

test( 'rDatesReducer() works with addRdate() action and full initial state',
	() => {
		expect(
			rDatesReducer(
				TEST_STATE,
				addRdate( TEST_EVENT_DATE_1, TEST_DATE_2 )
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
					rDates: [ TEST_DATE_1, TEST_DATE_2 ],
					exDates: [],
				},
			]
		);
	}
);

test( 'rDatesReducer() and addRdate() does not add duplicates', () => {
	expect(
		rDatesReducer(
			[ TEST_STORE_1 ],
			addRdate( TEST_EVENT_DATE_1, TEST_DATE_1 )
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
		]
	);
} );

test( 'rDatesReducer() and addRdate() does not add new Date with same value',
	() => {
		expect(
			rDatesReducer(
				[ TEST_STORE_1 ],
				addRdate( TEST_EVENT_DATE_1, new Date( TEST_DATE_STRING_1 ) )
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
			]
		);
	}
);

test( 'rDatesReducer() works with deleteRdate() action and valid initial state',
	() => {
		expect(
			rDatesReducer(
				[ TEST_STORE_1 ],
				deleteRdate( TEST_EVENT_DATE_1, TEST_DATE_1 )
			)
		).toEqual(
			[
				{
					id: TEST_ID_1,
					rRule: TEST_RRULE_1,
					exRule: '',
					rDates: [],
					exDates: [],
				},
			]
		);
	}
);

test(
	'rDatesReducer() works with deleteRdate() action and new Date with same value',
	() => {
		expect(
			rDatesReducer(
				[ TEST_STORE_1 ],
				deleteRdate( TEST_EVENT_DATE_1, new Date( TEST_DATE_STRING_1 ) )
			)
		).toEqual(
			[
				{
					id: TEST_ID_1,
					rRule: TEST_RRULE_1,
					exRule: '',
					rDates: [],
					exDates: [],
				},
			]
		);
	}
);

test( 'rDatesReducer() and deleteRdate() does not delete dates it should not',
	() => {
		expect(
			rDatesReducer(
				[ TEST_STORE_1 ],
				deleteRdate( TEST_EVENT_DATE_1, TEST_DATE_2 )
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
			]
		);
	}
);

test(
	'rDatesReducer() and deleteRdate() does not delete dates when supplied' +
	' with new Date with different values',
	() => {
		expect(
			rDatesReducer(
				[ TEST_STORE_1 ],
				deleteRdate( TEST_EVENT_DATE_1, new Date( TEST_DATE_STRING_2 ) )
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
			]
		);
	}
);

test( 'rDatesReducer() works with deleteRdate() action and full initial state',
	() => {
		expect(
			rDatesReducer(
				TEST_STATE,
				deleteRdate( TEST_EVENT_DATE_1, TEST_DATE_1 )
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
					rDates: [],
					exDates: [],
				},
			]
		);
	}
);

// location: /assets/src/data-stores/rDates/test/index.js
