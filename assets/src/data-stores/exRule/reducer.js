/**
 * Internal dependencies
 */
import { ADD_EXRULE, RESET_EXRULE } from './actions';

export const STORE_KEY_EXRULE = 'exRule';

/**
 * @function
 * @param {Object} state  previous state
 * @param {Object} action requested state mutation
 * @return {Object} new state
 */
export const exRuleReducer = ( state = {}, action ) => {
	switch ( action.type ) {
		case ADD_EXRULE:
			return { ...state, STORE_KEY_EXRULE: action.rule };
		case RESET_EXRULE:
			return { ...state, STORE_KEY_EXRULE: null };
		default:
			return state;
	}
};
