/**
 * WordPress dependencies
 */
import { registerStore, combineReducers } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { remActions } from './actions';
import { rRuleReducer } from './rRule/reducer';
import { exRuleReducer } from './exRule/reducer';
import { rDatesReducer } from './rDates/reducer';
import { exDatesReducer } from './exDates/reducer';

export const remStore = registerStore(
	'espressoRemStore',
	combineReducers( {
		rRuleReducer,
		exRuleReducer,
		rDatesReducer,
		exDatesReducer,
	} ),
	remActions
);
