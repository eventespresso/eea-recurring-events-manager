import {
	TEST_RRULE_1,
	TEST_RRULE_2,
	TEST_DATE_1,
	TEST_DATE_2,
	TEST_ID_1,
	TEST_ID_2,
	TEST_DATE_STRING_1,
	TEST_DATE_STRING_2,
	TEST_STORE_1,
	TEST_STATE,
} from '../../helpers/tests';
import {
	findStoreById,
	removeStoreById,
	initializeStore,
	getNewState,
	findDate,
	addDate,
	removeDate,
	dateStringsMatch,
} from '../utils';

// findStoreById
test( 'findStoreById() returns correct store', () => {
	expect( findStoreById( TEST_STATE, TEST_ID_1 ) ).toEqual( TEST_STORE_1 );
} );

test( 'findStoreById() returns empty object if store not found', () => {
	expect( findStoreById( [ TEST_STORE_1 ], TEST_ID_2 ) ).toEqual( {} );
} );

// removeStoreById
test( 'removeStoreById() returns empty array after removal', () => {
	expect( removeStoreById( [ TEST_STORE_1 ], TEST_ID_1 ) ).toEqual( [] );
} );

test( 'removeStoreById() returns same state if store not found', () => {
	expect(
		removeStoreById( [ TEST_STORE_1 ], TEST_ID_2 )
	).toEqual( [ TEST_STORE_1 ] );
} );

// initializeStore
test( 'initializeStore() returns empty store when passed nothing', () => {
	expect(
		initializeStore()
	).toEqual(
		{
			id: null,
			rRule: '',
			exRule: '',
			rDates: [],
			exDates: [],
		}
	);
} );

test( 'initializeStore() returns correct store when passed existing', () => {
	expect(
		initializeStore( TEST_STORE_1 )
	).toEqual(
		{
			id: TEST_ID_1,
			rRule: TEST_RRULE_1,
			exRule: '',
			rDates: [ TEST_DATE_1 ],
			exDates: [],
		}
	);
} );

test( 'initializeStore() returns correct store when passed existing plus data',
	() => {
		expect(
			initializeStore( TEST_STORE_1, { exRule: TEST_RRULE_2 } )
		).toEqual(
			{
				id: TEST_ID_1,
				rRule: TEST_RRULE_1,
				exRule: TEST_RRULE_2,
				rDates: [ TEST_DATE_1 ],
				exDates: [],
			}
		);
	}
);

// getNewState
test( 'getNewState() throws Type Error when passed nothing', () => {
	expect( () => {
		getNewState();
	} ).toThrow( TypeError );
} );

test( 'getNewState() throws StoreID Error when passed nothing', () => {
	expect( () => {
		getNewState();
	} ).toThrow(
		/A valid storeID is required in order to find a data store./
	);
} );

test( 'getNewState() throws Type Error when passed State but no Store ID',
	() => {
		expect( () => {
			getNewState( [ TEST_STORE_1 ] );
		} ).toThrow( TypeError );
	}
);

test( 'getNewState() throws StoreID Error when passed State but no Store ID',
	() => {
		expect( () => {
			getNewState( [ TEST_STORE_1 ] );
		} ).toThrow(
			/A valid storeID is required in order to find a data store./
		);
	}
);

test( 'getNewState() returns correct state when passed State and Store ID',
	() => {
		expect(
			getNewState( TEST_STATE, TEST_ID_1 )
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
					exDates: [],
				},
			]
		);
	}
);

test( 'getNewState() returns correct state when passed all parameters',
	() => {
		expect(
			getNewState( TEST_STATE, TEST_ID_1, { exRule: TEST_RRULE_2 } )
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
					exRule: TEST_RRULE_2,
					rDates: [ TEST_DATE_1 ],
					exDates: [],
				},
			]
		);
	}
);

// findDate
test( 'findDate() returns null if passed empty dates list',
	() => {
		expect(
			findDate( [] )
		).toEqual( null );
	}
);

test( 'findDate() throws TypeError if passed invalid Date',
	() => {
		expect( () => {
			findDate( [ TEST_DATE_1, TEST_DATE_2 ], 'Jan 1, 2001' );
		} ).toThrow( TypeError );
	}
);

test( 'findDate() returns null if date not found',
	() => {
		expect(
			findDate( [ TEST_DATE_1, TEST_DATE_2 ], new Date() )
		).toEqual( null );
	}
);

test( 'findDate() returns correct date',
	() => {
		expect(
			findDate(
				[ TEST_DATE_1, TEST_DATE_2 ],
				new Date( TEST_DATE_STRING_1 )
			)
		).toEqual( TEST_DATE_1 );
	}
);

// addDate
test( 'addDate() return empty array if passed nothing',
	() => {
		expect( addDate() ).toEqual( [] );
	}
);

test( 'addDate() throws TypeError if passed invalid Date',
	() => {
		expect( () => {
			addDate( [ TEST_DATE_1 ], 'Jan 1, 2001' );
		} ).toThrow( TypeError );
	}
);

test( 'addDate() returns same date list if passed date already exists',
	() => {
		expect(
			addDate( [ TEST_DATE_1, TEST_DATE_2 ], TEST_DATE_1 )
		).toEqual( [ TEST_DATE_1, TEST_DATE_2 ] );
	}
);

test( 'addDate() returns same date list if passed new date with existing value',
	() => {
		expect(
			addDate(
				[ TEST_DATE_1, TEST_DATE_2 ],
				new Date( TEST_DATE_STRING_1 )
			)
		).toEqual( [ TEST_DATE_1, TEST_DATE_2 ] );
	}
);

test( 'addDate() adds new dates correctly',
	() => {
		expect(
			addDate(
				[ TEST_DATE_1 ],
				new Date( TEST_DATE_STRING_2 )
			)
		).toEqual( [ TEST_DATE_1, TEST_DATE_2 ] );
	}
);

// removeDate
test( 'removeDate() return empty array if passed nothing',
	() => {
		expect( removeDate() ).toEqual( [] );
	}
);

test( 'removeDate() throws TypeError if passed invalid Date',
	() => {
		expect( () => {
			removeDate( [ TEST_DATE_1 ], 'Jan 1, 2001' );
		} ).toThrow( TypeError );
	}
);

test( 'removeDate() returns same date list if passed date does not exist',
	() => {
		expect(
			removeDate( [ TEST_DATE_1, TEST_DATE_2 ], new Date() )
		).toEqual( [ TEST_DATE_1, TEST_DATE_2 ] );
	}
);

test( 'removeDate() removes valid existing dates correctly',
	() => {
		expect(
			removeDate( [ TEST_DATE_1, TEST_DATE_2 ], TEST_DATE_1 )
		).toEqual( [ TEST_DATE_2 ] );
	}
);

test(
	'removeDate() removes existing dates correctly' +
	' if passed new date with existing value',
	() => {
		expect(
			removeDate(
				[ TEST_DATE_1, TEST_DATE_2 ],
				new Date( TEST_DATE_STRING_2 )
			)
		).toEqual( [ TEST_DATE_1 ] );
	}
);

// location: /assets/src/data-stores/test/index.js
