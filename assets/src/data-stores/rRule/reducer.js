/**
 * Internal dependencies
 */
import { ADD_RRULE, RESET_RRULE } from './actions';

export const STORE_KEY_RRULE = 'rRule';

/**
 * @function
 * @param {Object} state  previous state
 * @param {Object} action requested state mutation
 * @return {Object} new state
 */
export const rRuleReducer = ( state = {}, action ) => {
	switch ( action.type ) {
		case ADD_RRULE:
			return { ...state, STORE_KEY_RRULE: action.rule };
		case RESET_RRULE:
			return { ...state, STORE_KEY_RRULE: null };
		default:
			return state;
	}
};