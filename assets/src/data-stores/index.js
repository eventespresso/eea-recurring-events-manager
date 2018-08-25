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

export const DATA_STORE_KEY_REM = 'espressoRemStore';

export const remStore = registerStore(
	DATA_STORE_KEY_REM,
	{
		reducer: combineReducers(
			{
				rRule: rRuleReducer,
				exRule: exRuleReducer,
				rDates: rDatesReducer,
				exDates: exDatesReducer,
			}
		),
		// actions
		actions: {
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
		selectors: {
			getRRule,
			getExRule,
			getRDates,
			getExDates,
		},
	}
);
