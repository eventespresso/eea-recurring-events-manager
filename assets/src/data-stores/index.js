/**
 * WordPress dependencies
 */
import { registerStore, combineReducers } from '@wordpress/data';

/**
 * Internal dependencies
 */
// reducers
import { rRuleReducer } from './rRule/reducer';
import { exRuleReducer } from './exRule/reducer';
import { rDatesReducer } from './rDates/reducer';
import { exDatesReducer } from './exDates/reducer';
// actions
import { addRrule, resetRrule } from './rRule/actions';
import { addExRule, resetExRule } from './exRule/actions';
import { addRdate, deleteRdate } from './rDates/actions';
import { addExDate, deleteExDate } from './exDates/actions';
// selectors
import { getRRule } from './rRule/selectors';
import { getExRule } from './exRule/selectors';
import { getRDates } from './rDates/selectors';
import { getExDates } from './exDates/selectors';

export const remStore = registerStore(
	'espressoRemStore',
	combineReducers( {
		rRuleReducer,
		exRuleReducer,
		rDatesReducer,
		exDatesReducer,
	} ),
	// actions
	{
		addRrule,
		resetRrule,
		addExRule,
		resetExRule,
		addRdate,
		deleteRdate,
		addExDate,
		deleteExDate,
	},
	// selectors
	{
		getRRule,
		getExRule,
		getRDates,
		getExDates,
	}
);
