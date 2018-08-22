/**
 * Internal dependencies
 */
import { assertIsString, VALIDATION_ERROR } from '../../helpers/validators';

/**
 * action types
 */
export const ADD_RRULE = 'ADD_RRULE';
export const RESET_RRULE = 'RESET_RRULE';

/**
 * @function
 * @param {string} rRuleString
 * @return {Object} action
 */
export const addRrule = ( rRuleString ) => {
	return assertIsString( rRuleString ) ?
		{ type: ADD_RRULE, rule: rRuleString } :
		{ type: VALIDATION_ERROR };
};

/**
 * @function
 * @return {Object} action
 */
export const resetRrule = () => {
	return { type: RESET_RRULE };
};
