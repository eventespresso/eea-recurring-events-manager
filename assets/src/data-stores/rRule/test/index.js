import { addRrule, resetRrule, ADD_RRULE, RESET_RRULE } from '../actions';
import { rRuleReducer } from '../reducer';
import { getRRule } from '../selectors';
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
	TEST_STATE,
} from '../../../helpers/tests';

test( 'addRrule() returns ADD_RRULE action', () => {
	expect(
		addRrule( TEST_EVENT_DATE_1, TEST_RRULE_1 )
	).toEqual(
		{ type: ADD_RRULE, id: TEST_ID_1, rule: TEST_RRULE_1 }
	);
} );

test( 'addRrule() throws TypeError when nothing passed', () => {
	expect( () => {
		addRrule();
	} ).toThrow( TypeError );
} );

test( 'addRrule() throws id error when nothing passed', () => {
	expect( () => {
		addRrule();
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'addRrule() throws id error when bad id passed', () => {
	expect( () => {
		addRrule( [] );
	} ).toThrow(
		/The supplied object was expected to have a valid "id" property./
	);
} );

test( 'addRrule() throws string error when no rule passed', () => {
	expect( () => {
		addRrule( TEST_EVENT_DATE_1 );
	} ).toThrow( /The supplied value was expected to be a string./ );
} );

test( 'addRrule() throws TypeError when non-string rule passed', () => {
	expect( () => {
		addRrule( TEST_EVENT_DATE_1, [] );
	} ).toThrow( TypeError );
} );

test( 'addRrule() throws expected string error when non-string rule passed',
	() => {
		expect( () => {
			addRrule( TEST_EVENT_DATE_1, [] );
		} ).toThrow( /The supplied value was expected to be a string./ );
	}
);

test( 'resetRrule() returns RESET_RRULE action', () => {
	expect(
		resetRrule( TEST_EVENT_DATE_1 )
	).toEqual(
		{ type: RESET_RRULE, id: TEST_ID_1, rule: '' }
	);
} );

test( 'rRuleReducer() works with addRrule() action and empty state', () => {
	expect(
		rRuleReducer(
			[],
			addRrule( TEST_EVENT_DATE_1, TEST_RRULE_1 )
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
} );

test( 'rRuleReducer() works with addRrule() action and valid initial state',
	() => {
		expect(
			rRuleReducer(
				[ TEST_STORE_1 ],
				addRrule( TEST_EVENT_DATE_2, TEST_RRULE_2 )
			)
		).toEqual(
			[
				TEST_STORE_1,
				{
					id: TEST_ID_2,
					rRule: TEST_RRULE_2,
					exRule: '',
					rDates: [],
					exDates: [],
				},
			]
		);
	}
);

test( 'rRuleReducer() works with addRrule() action and full initial state',
	() => {
		expect(
			rRuleReducer(
				TEST_STATE,
				addRrule( TEST_EVENT_DATE_1, TEST_RRULE_2 )
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
					rRule: TEST_RRULE_2,
					exRule: '',
					rDates: [ TEST_DATE_1 ],
					exDates: [],
				},
			]
		);
	}
);

test( 'rRuleReducer() works with resetRrule() action and empty state', () => {
	expect(
		rRuleReducer( {}, resetRrule( TEST_EVENT_DATE_1 ) )
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

test( 'rRuleReducer() works with resetRrule() action and valid initial state',
	() => {
		expect(
			rRuleReducer(
				[ TEST_STORE_1 ],
				resetRrule( TEST_EVENT_DATE_1 )
			)
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
	}
);

test( 'rRuleReducer() works with resetRrule() action and full initial state',
	() => {
		expect(
			rRuleReducer(
				TEST_STATE,
				resetRrule( TEST_EVENT_DATE_1 )
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
					rRule: '',
					exRule: '',
					rDates: [ TEST_DATE_1 ],
					exDates: [],
				},
			]
		);
	}
);

// selectors getRRule
test( 'getRRule() returns correct RRule', () => {
	expect(
		getRRule( TEST_STATE, TEST_EVENT_DATE_1 )
	).toEqual( TEST_RRULE_1 );
} );

// location:  /assets/src/data-stores/rRule/test/index.js
