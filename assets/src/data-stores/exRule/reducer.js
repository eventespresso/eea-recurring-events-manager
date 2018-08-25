/**
 * Internal dependencies
 */
import { ADD_EXRULE, RESET_EXRULE } from './actions';
import { getNewState } from '../utils';

/**
 * @function
 * @param {Object} state  previous state
 * @param {Object} action requested state mutation
 * @return {Object} new state
 */
export const exRuleReducer = ( state = [], action ) => {
	switch ( action.type ) {
		case ADD_EXRULE:
		case RESET_EXRULE:
			return getNewState(
				state,
				action.id,
				{ id: action.id, exRule: action.rule }
			);
		default:
			return state;
	}
};
