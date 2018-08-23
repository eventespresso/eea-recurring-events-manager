import { addRdate, deleteRdate, ADD_RDATE, DELETE_RDATE } from '../actions';
import { rDatesReducer } from '../reducer';
import {
	TEST_DATE,
	TEST_DATE_2,
	TEST_STATE,
	TEST_DATE_STRING,
	TEST_DATE_STRING_2,
} from '../../../helpers/tests';

test( 'addRdate() returns ADD_RDATE action', () => {
	expect( addRdate( TEST_DATE ) ).toEqual(
		{ type: ADD_RDATE, date: TEST_DATE }
	);
} );

test( 'addRdate() throws TypeError when nothing passed', () => {
	expect( () => {
		addRdate();
	} ).toThrow( TypeError );
} );

test( 'addRdate() throws expected Date error when nothing passed', () => {
	expect( () => {
		addRdate();
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'addRdate() throws TypeError when non-string passed', () => {
	expect( () => {
		addRdate( [] );
	} ).toThrow( TypeError );
} );

test( 'addRdate() throws expected Date error when non-string passed', () => {
	expect( () => {
		addRdate( [] );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'deleteRdate() returns DELETE_RDATE action', () => {
	expect( deleteRdate( TEST_DATE ) ).toEqual(
		{ type: DELETE_RDATE, date: TEST_DATE }
	);
} );

test( 'deleteRdate() throws TypeError when nothing passed', () => {
	expect( () => {
		deleteRdate();
	} ).toThrow( TypeError );
} );

test( 'deleteRdate() throws expected Date error when nothing passed', () => {
	expect( () => {
		deleteRdate();
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'deleteRdate() throws TypeError when non-string passed', () => {
	expect( () => {
		deleteRdate( [] );
	} ).toThrow( TypeError );
} );

test( 'deleteRdate() throws expected Date error when non-string passed', () => {
	expect( () => {
		deleteRdate( [] );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'rDatesReducer() works with addRdate() action and empty state', () => {
	expect(
		rDatesReducer( { rDates: [] }, addRdate( TEST_DATE ) )
	).toEqual( { rDates: [ TEST_DATE ] } );
} );

test( 'rDatesReducer() works with addRdate() action and valid initial state',
	() => {
		expect(
			rDatesReducer( { rDates: [ TEST_DATE ] }, addRdate( TEST_DATE_2 ) )
		).toEqual( { rDates: [ TEST_DATE, TEST_DATE_2 ] } );
	}
);

test( 'rDatesReducer() works with addRdate() action and full initial state',
	() => {
		const NEW_STATE = TEST_STATE;
		NEW_STATE.rDates = [ TEST_DATE, TEST_DATE_2 ];
		expect(
			rDatesReducer( TEST_STATE, addRdate( TEST_DATE_2 ) )
		).toEqual( NEW_STATE );
	}
);

test( 'rDatesReducer() and addRdate() does not add duplicates', () => {
	expect( rDatesReducer( { rDates: [ TEST_DATE ] }, addRdate( TEST_DATE ) )
	).toEqual( { rDates: [ TEST_DATE ] } );
} );

test( 'rDatesReducer() and addRdate() does not add new Date with same value',
	() => {
		expect(
			rDatesReducer(
				{ rDates: [ TEST_DATE ] },
				addRdate( new Date( TEST_DATE_STRING ) )
			)
		).toEqual( { rDates: [ TEST_DATE ] } );
	}
);

test( 'rDatesReducer() works with deleteRdate() action and valid initial state',
	() => {
		expect(
			rDatesReducer( { rDates: [ TEST_DATE ] }, deleteRdate( TEST_DATE ) )
		).toEqual( { rDates: [] } );
	}
);

test(
	'rDatesReducer() works with deleteRdate() action and new Date with same value',
	() => {
		expect(
			rDatesReducer(
				{ rDates: [ TEST_DATE ] },
				deleteRdate( new Date( TEST_DATE_STRING ) )
			)
		).toEqual( { rDates: [] } );
	}
);

test( 'rDatesReducer() and deleteRdate() does not delete dates it should not',
	() => {
		expect(
			rDatesReducer(
				{ rDates: [ TEST_DATE ] },
				deleteRdate( TEST_DATE_2 )
			)
		).toEqual( { rDates: [ TEST_DATE ] } );
	}
);

test(
	'rDatesReducer() and deleteRdate() does not delete dates when supplied' +
	' with new Date with different values',
	() => {
		expect(
			rDatesReducer(
				{ rDates: [ TEST_DATE ] },
				deleteRdate( new Date( TEST_DATE_STRING_2 ) )
			)
		).toEqual( { rDates: [ TEST_DATE ] } );
	}
);

test( 'rDatesReducer() works with deleteRdate() action and full initial state',
	() => {
		const NEW_STATE = TEST_STATE;
		NEW_STATE.rDates = [];
		expect(
			rDatesReducer( TEST_STATE, deleteRdate( TEST_DATE ) )
		).toEqual( NEW_STATE );
	}
);
