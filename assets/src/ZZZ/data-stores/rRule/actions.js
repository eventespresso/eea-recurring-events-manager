/**
 * Internal dependencies
 */
import {
	assertIsString,
	assertObjectHasId,
	VALIDATION_ERROR,
} from '../../helpers/validators';

/**
 * action types
 */
export const ADD_RRULE = 'ADD_RRULE';
export const RESET_RRULE = 'RESET_RRULE';

/**
 * @function
 * @param {Object} eventDate
 * @param {string} rRuleString
 * @return {Object} action
 */
export const addRrule = ( eventDate, rRuleString ) => {
	return assertObjectHasId( eventDate ) && assertIsString( rRuleString ) ?
		{ type: ADD_RRULE, id: eventDate.id, rule: rRuleString } :
		{ type: VALIDATION_ERROR };
};

/**
 * @function
 * @param {Object} eventDate
 * @return {Object} action
 */
export const resetRrule = ( eventDate ) => {
	return assertObjectHasId( eventDate ) ?
		{ type: RESET_RRULE, id: eventDate.id, rule: '' } :
		{ type: VALIDATION_ERROR };
};
