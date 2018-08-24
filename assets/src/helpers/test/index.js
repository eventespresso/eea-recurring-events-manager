import {
	assertIsDate,
	assertIsString,
	assertObjectHasId,
	idIsStringOrNumber,
	dateStringsMatch,
} from '../validators';
import {
	TEST_DATE_1,
	TEST_DATE_2, TEST_DATE_STRING_1,
} from '../tests';

// dateStringsMatch
test( 'dateStringsMatch() throws TypeError if passed invalid Date',
	() => {
		expect( () => {
			dateStringsMatch( TEST_DATE_1, 'Jan 1, 2001' );
		} ).toThrow( TypeError );
	}
);

test( 'dateStringsMatch() throws date Error if passed invalid Date',
	() => {
		expect( () => {
			dateStringsMatch( 'Jan 1, 2001', TEST_DATE_1 );
		} ).toThrow( /The supplied value was expected to be a Date object./ );
	}
);

test( 'dateStringsMatch() returns false if dates do not match',
	() => {
		expect(
			dateStringsMatch( TEST_DATE_1, TEST_DATE_2 )
		).toBe( false );
	}
);

test( 'dateStringsMatch() returns false if dates nearly identical',
	() => {
		expect(
			dateStringsMatch(
				TEST_DATE_1,
				new Date( '2022-02-22 02:22:22 am' )
			)
		).toBe( false );
	}
);

test( 'dateStringsMatch() returns true if dates match',
	() => {
		expect(
			dateStringsMatch( TEST_DATE_1, TEST_DATE_1 )
		).toBe( true );
	}
);

test( 'dateStringsMatch() returns true if separate date objects match',
	() => {
		expect(
			dateStringsMatch(
				TEST_DATE_1,
				new Date( TEST_DATE_STRING_1 )
			)
		).toBe( true );
	}
);

// assertIsDate
test( 'assertIsDate() throws TypeError when nothing passed', () => {
	expect( () => {
		assertIsDate();
	} ).toThrow( TypeError );
} );

test( 'assertIsDate() throws TypeError when non-date passed', () => {
	expect( () => {
		assertIsDate( 'Jan 1, 2001' );
	} ).toThrow( TypeError );
} );

test( 'assertIsDate() throws date error when non-date passed', () => {
	expect( () => {
		assertIsDate( { date: 'Jan 1, 2001' } );
	} ).toThrow( /The supplied value was expected to be a Date object./ );
} );

test( 'assertIsDate() returns true when Date object passed', () => {
	expect( assertIsDate( new Date() ) ).toBe( true );
} );

// assertIsString
test( 'assertIsString() throws TypeError when nothing passed', () => {
	expect( () => {
		assertIsString();
	} ).toThrow( TypeError );
} );

test( 'assertIsString() throws TypeError when non-string passed', () => {
	expect( () => {
		assertIsString( { date: 'Jan 1, 2001' } );
	} ).toThrow( TypeError );
} );

test( 'assertIsString() throws date error when non-string passed', () => {
	expect( () => {
		assertIsString( { date: 'Jan 1, 2001' } );
	} ).toThrow( /The supplied value was expected to be a string./ );
} );

test( 'assertIsString() returns true when string passed', () => {
	expect( assertIsString( 'Jan 1, 2001' ) ).toBe( true );
} );

// assertObjectHasId
test( 'assertObjectHasId() throws TypeError when nothing passed', () => {
	expect( () => {
		assertObjectHasId();
	} ).toThrow( TypeError );
} );

test( 'assertObjectHasId() throws TypeError when non-object passed', () => {
	expect( () => {
		assertObjectHasId( 'Jan 1, 2001' );
	} ).toThrow( TypeError );
} );

test( 'assertObjectHasId() throws TypeError when no id object passed', () => {
	expect( () => {
		assertObjectHasId( { date: 'Jan 1, 2001' } );
	} ).toThrow( TypeError );
} );

test( 'assertObjectHasId() throws id error when no id object passed', () => {
	expect( () => {
		assertObjectHasId( { date: 'Jan 1, 2001' } );
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'assertObjectHasId() throws TypeError when invalid id object passed',
	() => {
		expect( () => {
			assertObjectHasId( { id: {}, date: 'Jan 1, 2001' } );
		} ).toThrow( TypeError );
	}
);

test( 'assertObjectHasId() returns true when number id passed', () => {
	expect(
		assertObjectHasId( { id: 123, date: 'Jan 1, 2001' } )
	).toBe( true );
} );

test( 'assertObjectHasId() returns true when string id passed', () => {
	expect(
		assertObjectHasId( { id: '123', date: 'Jan 1, 2001' } )
	).toBe( true );
} );

// idIsStringOrNumber
test( 'idIsStringOrNumber() returns true when number id passed', () => {
	expect(
		idIsStringOrNumber( 123 )
	).toBe( true );
} );

test( 'idIsStringOrNumber() returns true when string id passed', () => {
	expect(
		idIsStringOrNumber( '123' )
	).toBe( true );
} );
test( 'idIsStringOrNumber() returns false when invalid id passed', () => {
	expect(
		idIsStringOrNumber( [] )
	).toBe( false );
} );
// location: /assets/src/helpers/test/index.js
