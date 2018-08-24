import { addExRule, resetExRule, ADD_EXRULE, RESET_EXRULE } from '../actions';
import { exRuleReducer } from '../reducer';

import {
	TEST_RRULE_1,
	TEST_RRULE_2,
	TEST_DATE_1,
	TEST_DATE_2,
	TEST_ID_1,
	TEST_ID_2,
	TEST_EVENT_DATE_1,
	TEST_EVENT_DATE_2,
	TEST_STORE_1,
	TEST_STORE_2,
	TEST_STATE,
} from '../../../helpers/tests';

test( 'addExRule() returns ADD_EXRULE action', () => {
	expect(
		addExRule( TEST_EVENT_DATE_1, TEST_RRULE_1 )
	).toEqual(
		{ type: ADD_EXRULE, id: TEST_ID_1, rule: TEST_RRULE_1 }
	);
} );

test( 'addExRule() throws TypeError when nothing passed', () => {
	expect( () => {
		addExRule();
	} ).toThrow( TypeError );
} );

test( 'addExRule() throws id error when nothing passed', () => {
	expect( () => {
		addExRule();
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'addExRule() throws id error when bad id passed', () => {
	expect( () => {
		addExRule( [] );
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'addExRule() throws string error when no rule passed', () => {
	expect( () => {
		addExRule( TEST_EVENT_DATE_1 );
	} ).toThrow( /The supplied value was expected to be a string./ );
} );

test( 'addExRule() throws TypeError when non-string rule passed', () => {
	expect( () => {
		addExRule( TEST_EVENT_DATE_1, [] );
	} ).toThrow( TypeError );
} );

test( 'addExRule() throws expected string error when non-string rule passed',
	() => {
		expect( () => {
			addExRule( TEST_EVENT_DATE_1, [] );
		} ).toThrow( /The supplied value was expected to be a string./ );
	}
);

test( 'resetExRule() returns RESET_EXRULE action', () => {
	expect(
		resetExRule( TEST_EVENT_DATE_1 )
	).toEqual(
		{ type: RESET_EXRULE, id: TEST_ID_1, rule: '' }
	);
} );

test( 'exRuleReducer() works with addExRule() action and empty state', () => {
	expect(
		exRuleReducer(
			[],
			addExRule( TEST_EVENT_DATE_1, TEST_RRULE_1 )
		)
	).toEqual(
		[
			{
				id: TEST_ID_1,
				rRule: '',
				exRule: TEST_RRULE_1,
				rDates: [],
				exDates: [],
			},
		]
	);
} );

test( 'exRuleReducer() works with addExRule() action and valid initial state',
	() => {
		expect(
			exRuleReducer(
				[ TEST_STORE_1 ],
				addExRule( TEST_EVENT_DATE_2, TEST_RRULE_1 )
			)
		).toEqual(
			[
				TEST_STORE_1,
				{
					id: TEST_ID_2,
					rRule: '',
					exRule: TEST_RRULE_1,
					rDates: [],
					exDates: [],
				},
			]
		);
	}
);

test( 'exRuleReducer() works with addExRule() action and full initial state',
	() => {
		expect(
			exRuleReducer(
				TEST_STATE,
				addExRule( TEST_EVENT_DATE_1, TEST_RRULE_2 )
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
					exRule: TEST_RRULE_2,
					rDates: [ TEST_DATE_1 ],
					exDates: [],
				},
			]
		);
	}
);

test( 'exRuleReducer() works with resetExRule() action and empty state', () => {
	expect(
		exRuleReducer( {}, resetExRule( TEST_EVENT_DATE_1 ) )
	).toEqual(
		[
			{
				id: TEST_ID_1,
				rRule: '',
				exRule: '',
				rDates: [],
				exDates: [],
			},
		]
	);
} );

test( 'exRuleReducer() works with resetExRule() action and valid initial state',
	() => {
		expect(
			exRuleReducer(
				[ TEST_STORE_2 ],
				resetExRule( TEST_EVENT_DATE_2 )
			)
		).toEqual(
			[
				{
					id: TEST_ID_2,
					rRule: TEST_RRULE_1,
					exRule: '',
					rDates: [ TEST_DATE_1 ],
					exDates: [ TEST_DATE_2 ],
				},
			]
		);
	}
);

test( 'exRuleReducer() works with resetExRule() action and full initial state',
	() => {
		expect(
			exRuleReducer(
				TEST_STATE,
				resetExRule( TEST_EVENT_DATE_2 )
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
					exRule: '',
					rDates: [ TEST_DATE_1 ],
					exDates: [ TEST_DATE_2 ],
				},
			]
		);
	}
);

// location: /assets/src/data-stores/exRule/test/index.js

// test( 'addExRule() returns ADD_EXRULE action', () => {
// 	expect( addExRule( TEST_RRULE_1 ) ).toEqual(
// 		{ type: ADD_EXRULE, rule: TEST_RRULE_1 }
// 	);
// } );
//
// test( 'addExRule() throws TypeError when nothing passed', () => {
// 	expect( () => {
// 		addExRule();
// 	} ).toThrow( TypeError );
// } );
//
// test( 'addExRule() throws expected string error when nothing passed', () => {
// 	expect( () => {
// 		addExRule();
// 	} ).toThrow( /The supplied value was expected to be a string./ );
// } );
//
// test( 'addExRule() throws TypeError when non-string passed', () => {
// 	expect( () => {
// 		addExRule( [] );
// 	} ).toThrow( TypeError );
// } );
//
// test( 'addExRule() throws expected string error when non-string passed', () => {
// 	expect( () => {
// 		addExRule( [] );
// 	} ).toThrow( /The supplied value was expected to be a string./ );
// } );
//
// test( 'resetExRule() returns RESET_EXRULE action', () => {
// 	expect( resetExRule() ).toEqual( { type: RESET_EXRULE } );
// } );
//
// test( 'exRuleReducer() works with addExRule() action and empty state', () => {
// 	expect(
// 		exRuleReducer( {}, addExRule( TEST_RRULE_1 ) )
// 	).toEqual( { exRule: TEST_RRULE_1 } );
// } );
//
// test( 'exRuleReducer() works with addExRule() action and valid initial state',
// 	() => {
// 		expect(
// 			exRuleReducer( { exRule: TEST_RRULE_1 }, addExRule( TEST_RRULE_2 ) )
// 		).toEqual( { exRule: TEST_RRULE_2 } );
// 	}
// );
//
// test( 'exRuleReducer() works with addExRule() action and full initial state',
// 	() => {
// 		const NEW_STATE = TEST_STATE;
// 		NEW_STATE.exRule = TEST_RRULE_2;
// 		expect(
// 			exRuleReducer( TEST_STATE, addExRule( TEST_RRULE_2 ) )
// 		).toEqual( NEW_STATE );
// 	}
// );
//
// test( 'exRuleReducer() works with resetExRule() action and empty state', () => {
// 	expect(
// 		exRuleReducer( {}, resetExRule() )
// 	).toEqual( { exRule: null } );
// } );
//
// test( 'exRuleReducer() works with resetExRule() action and valid initial state',
// 	() => {
// 		expect(
// 			exRuleReducer( { exRule: TEST_RRULE_1 }, resetExRule() )
// 		).toEqual( { exRule: null } );
// 	}
// );
//
// test( 'exRuleReducer() works with resetExRule() action and full initial state',
// 	() => {
// 		const NEW_STATE = TEST_STATE;
// 		NEW_STATE.exRule = null;
// 		expect(
// 			exRuleReducer( TEST_STATE, resetExRule() )
// 		).toEqual( NEW_STATE );
// 	}
// );

