/**
 * External dependencies
 */
import { createStore, combineReducers } from 'redux';

/**
 * Internal dependencies
 */
import { rRuleReducer, STORE_KEY_RRULE } from './rRule/reducer.js';
import { exRuleReducer, STORE_KEY_EXRULE } from './exRule/reducer.js';
import { rDatesReducer, STORE_KEY_RDATES } from './rDates/reducer.js';
import { exDatesReducer, STORE_KEY_EXDATES } from './exDates/reducer.js';

const remReducers = combineReducers( {
	rRuleReducer,
	exRuleReducer,
	rDatesReducer,
	exDatesReducer,
} );

const DEFAULT_STATE = {};
DEFAULT_STATE[ STORE_KEY_RRULE ] = '';
DEFAULT_STATE[ STORE_KEY_EXRULE ] = '';
DEFAULT_STATE[ STORE_KEY_RDATES ] = [];
DEFAULT_STATE[ STORE_KEY_EXDATES ] = [];

const remStore = createStore( remReducers, DEFAULT_STATE );
export default remStore;
