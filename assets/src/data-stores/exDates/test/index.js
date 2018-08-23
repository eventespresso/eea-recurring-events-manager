import { addExDate, deleteExDate, ADD_EXDATE, DELETE_EXDATE } from '../actions';
import { exDatesReducer } from '../reducer';
import {
	TEST_DATE,
	TEST_DATE_2,
	TEST_STATE,
	TEST_DATE_STRING,
	TEST_DATE_STRING_2,
} from '../../../helpers/tests';

test( 'addExDate() returns ADD_EXDATE action', () => {
	expect( addExDate( TEST_DATE ) ).toEqual(
		{ type: ADD_EXDATE, date: TEST_DATE }
	);
} );

test( 'addExDate() throws TypeError when nothing passed', () => {
	expect( () => {
		addExDate();
	} ).toThrow( TypeError );
} );

test( 'addExDate() throws expected Date error when nothing passed', () => {
	expect( () => {
		addExDate();
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'addExDate() throws TypeError when non-string passed', () => {
	expect( () => {
		addExDate( [] );
	} ).toThrow( TypeError );
} );

test( 'addExDate() throws expected Date error when non-string passed', () => {
	expect( () => {
		addExDate( [] );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'deleteExDate() returns DELETE_EXDATE action', () => {
	expect( deleteExDate( TEST_DATE ) ).toEqual(
		{ type: DELETE_EXDATE, date: TEST_DATE }
	);
} );

test( 'deleteExDate() throws TypeError when nothing passed', () => {
	expect( () => {
		deleteExDate();
	} ).toThrow( TypeError );
} );

test( 'deleteExDate() throws expected Date error when nothing passed', () => {
	expect( () => {
		deleteExDate();
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'deleteExDate() throws TypeError when non-string passed', () => {
	expect( () => {
		deleteExDate( [] );
	} ).toThrow( TypeError );
} );

test( 'deleteExDate() throws expected Date error when non-string passed', () => {
	expect( () => {
		deleteExDate( [] );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'exDatesReducer() works with addExDate() action and empty state', () => {
	expect(
		exDatesReducer( { exDates: [] }, addExDate( TEST_DATE ) )
	).toEqual( { exDates: [ TEST_DATE ] } );
} );

test( 'exDatesReducer() works with addExDate() action and valid initial state',
	() => {
		expect(
			exDatesReducer( { exDates: [ TEST_DATE ] }, addExDate( TEST_DATE_2 ) )
		).toEqual( { exDates: [ TEST_DATE, TEST_DATE_2 ] } );
	}
);

test( 'exDatesReducer() works with addExDate() action and full initial state',
	() => {
		const NEW_STATE = TEST_STATE;
		NEW_STATE.exDates = [ TEST_DATE, TEST_DATE_2 ];
		expect(
			exDatesReducer( TEST_STATE, addExDate( TEST_DATE_2 ) )
		).toEqual( NEW_STATE );
	}
);

test( 'exDatesReducer() and addExDate() does not add duplicates', () => {
	expect( exDatesReducer( { exDates: [ TEST_DATE ] }, addExDate( TEST_DATE ) )
	).toEqual( { exDates: [ TEST_DATE ] } );
} );

test( 'exDatesReducer() and addExDate() does not add new Date with same value',
	() => {
		expect(
			exDatesReducer(
				{ exDates: [ TEST_DATE ] },
				addExDate( new Date( TEST_DATE_STRING ) )
			)
		).toEqual( { exDates: [ TEST_DATE ] } );
	}
);

test( 'exDatesReducer() works with deleteExDate() action and valid initial state',
	() => {
		expect(
			exDatesReducer( { exDates: [ TEST_DATE ] }, deleteExDate( TEST_DATE ) )
		).toEqual( { exDates: [] } );
	}
);

test(
	'exDatesReducer() works with deleteExDate() action and new Date with same value',
	() => {
		expect(
			exDatesReducer(
				{ exDates: [ TEST_DATE ] },
				deleteExDate( new Date( TEST_DATE_STRING ) )
			)
		).toEqual( { exDates: [] } );
	}
);

test( 'exDatesReducer() and deleteExDate() does not delete dates it should not',
	() => {
		expect(
			exDatesReducer( { exDates: [ TEST_DATE ] }, deleteExDate( TEST_DATE_2 ) )
		).toEqual( { exDates: [ TEST_DATE ] } );
	}
);

test(
	'exDatesReducer() and deleteExDate() does not delete dates when supplied' +
	' with new Date with different values',
	() => {
		expect(
			exDatesReducer(
				{ exDates: [ TEST_DATE ] },
				deleteExDate( new Date( TEST_DATE_STRING_2 ) )
			)
		).toEqual( { exDates: [ TEST_DATE ] } );
	}
);

test( 'exDatesReducer() works with deleteExDate() action and full initial state',
	() => {
		const NEW_STATE = TEST_STATE;
		NEW_STATE.exDates = [];
		expect(
			exDatesReducer( TEST_STATE, deleteExDate( TEST_DATE ) )
		).toEqual( NEW_STATE );
	}
);
