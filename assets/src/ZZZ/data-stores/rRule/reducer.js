/**
 * Internal dependencies
 */
import { ADD_RRULE, RESET_RRULE } from './actions';
import { getNewState } from '../utils';

/**
 * @function
 * @param {Object} state  previous state
 * @param {Object} action requested state mutation
 * @return {Object} new state
 */
export const rRuleReducer = ( state = [], action ) => {
	switch ( action.type ) {
		case ADD_RRULE:
		case RESET_RRULE:
			return getNewState(
				state,
				action.id,
				{ id: action.id, rRule: action.rule }
			);
		default:
			return state;
	}
};

