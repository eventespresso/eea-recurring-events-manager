import { addRrule, resetRrule, ADD_RRULE, RESET_RRULE } from '../actions';
import { rRuleReducer } from '../reducer';
import { TEST_RRULE, TEST_RRULE_2, TEST_STATE } from '../../../helpers/tests';

test( 'addRrule() returns ADD_RRULE action', () => {
	expect( addRrule( TEST_RRULE ) ).toEqual(
		{ type: ADD_RRULE, rule: TEST_RRULE }
	);
} );

test( 'addRrule() throws TypeError when nothing passed', () => {
	expect( () => {
		addRrule();
	} ).toThrow( TypeError );
} );

test( 'addRrule() throws expected string error when nothing passed', () => {
	expect( () => {
		addRrule();
	} ).toThrow( /The supplied value was expected to be a string./ );
} );

test( 'addRrule() throws TypeError when non-string passed', () => {
	expect( () => {
		addRrule( [] );
	} ).toThrow( TypeError );
} );

test( 'addRrule() throws expected string error when non-string passed', () => {
	expect( () => {
		addRrule( [] );
	} ).toThrow( /The supplied value was expected to be a string./ );
} );

test( 'resetRrule() returns RESET_RRULE action', () => {
	expect( resetRrule() ).toEqual( { type: RESET_RRULE } );
} );

test( 'rRuleReducer() works with addRrule() action and empty state', () => {
	expect(
		rRuleReducer( {}, addRrule( TEST_RRULE ) )
	).toEqual( { rRule: TEST_RRULE } );
} );

test( 'rRuleReducer() works with addRrule() action and valid initial state',
	() => {
		expect(
			rRuleReducer( { rRule: TEST_RRULE }, addRrule( TEST_RRULE_2 ) )
		).toEqual( { rRule: TEST_RRULE_2 } );
	}
);

test( 'rRuleReducer() works with addRrule() action and full initial state',
	() => {
		const NEW_STATE = TEST_STATE;
		NEW_STATE.rRule = TEST_RRULE_2;
		expect(
			rRuleReducer( TEST_STATE, addRrule( TEST_RRULE_2 ) )
		).toEqual( NEW_STATE );
	}
);

test( 'rRuleReducer() works with resetRrule() action and empty state', () => {
	expect(
		rRuleReducer( {}, resetRrule() )
	).toEqual( { rRule: null } );
} );

test( 'rRuleReducer() works with resetRrule() action and valid initial state',
	() => {
		expect(
			rRuleReducer( { rRule: TEST_RRULE }, resetRrule() )
		).toEqual( { rRule: null } );
	}
);

test( 'rRuleReducer() works with resetRrule() action and full initial state',
	() => {
		const NEW_STATE = TEST_STATE;
		NEW_STATE.rRule = null;
		expect(
			rRuleReducer( TEST_STATE, resetRrule() )
		).toEqual( NEW_STATE );
	}
);
