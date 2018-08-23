import { addExRule, resetExRule, ADD_EXRULE, RESET_EXRULE } from '../actions';
import { exRuleReducer } from '../reducer';
import { TEST_RRULE, TEST_RRULE_2, TEST_STATE } from '../../../helpers/tests';

test( 'addExRule() returns ADD_EXRULE action', () => {
	expect( addExRule( TEST_RRULE ) ).toEqual(
		{ type: ADD_EXRULE, rule: TEST_RRULE }
	);
} );

test( 'addExRule() throws TypeError when nothing passed', () => {
	expect( () => {
		addExRule();
	} ).toThrow( TypeError );
} );

test( 'addExRule() throws expected string error when nothing passed', () => {
	expect( () => {
		addExRule();
	} ).toThrow( /The supplied value was expected to be a string./ );
} );

test( 'addExRule() throws TypeError when non-string passed', () => {
	expect( () => {
		addExRule( [] );
	} ).toThrow( TypeError );
} );

test( 'addExRule() throws expected string error when non-string passed', () => {
	expect( () => {
		addExRule( [] );
	} ).toThrow( /The supplied value was expected to be a string./ );
} );

test( 'resetExRule() returns RESET_EXRULE action', () => {
	expect( resetExRule() ).toEqual( { type: RESET_EXRULE } );
} );

test( 'exRuleReducer() works with addExRule() action and empty state', () => {
	expect(
		exRuleReducer( {}, addExRule( TEST_RRULE ) )
	).toEqual( { exRule: TEST_RRULE } );
} );

test( 'exRuleReducer() works with addExRule() action and valid initial state',
	() => {
		expect(
			exRuleReducer( { exRule: TEST_RRULE }, addExRule( TEST_RRULE_2 ) )
		).toEqual( { exRule: TEST_RRULE_2 } );
	}
);

test( 'exRuleReducer() works with addExRule() action and full initial state',
	() => {
		const NEW_STATE = TEST_STATE;
		NEW_STATE.exRule = TEST_RRULE_2;
		expect(
			exRuleReducer( TEST_STATE, addExRule( TEST_RRULE_2 ) )
		).toEqual( NEW_STATE );
	}
);

test( 'exRuleReducer() works with resetExRule() action and empty state', () => {
	expect(
		exRuleReducer( {}, resetExRule() )
	).toEqual( { exRule: null } );
} );

test( 'exRuleReducer() works with resetExRule() action and valid initial state',
	() => {
		expect(
			exRuleReducer( { exRule: TEST_RRULE }, resetExRule() )
		).toEqual( { exRule: null } );
	}
);

test( 'exRuleReducer() works with resetExRule() action and full initial state',
	() => {
		const NEW_STATE = TEST_STATE;
		NEW_STATE.exRule = null;
		expect(
			exRuleReducer( TEST_STATE, resetExRule() )
		).toEqual( NEW_STATE );
	}
);

