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
export const ADD_EXRULE = 'ADD_EXRULE';
export const RESET_EXRULE = 'RESET_EXRULE';

/**
 * @function
 * @param {Object} eventDate
 * @param {string} exRruleString
 * @return {Object} action
 */
export const addExRule = ( eventDate, exRruleString ) => {
	return assertObjectHasId( eventDate ) && assertIsString( exRruleString ) ?
		{ type: ADD_EXRULE, id: eventDate.id, rule: exRruleString } :
		{ type: VALIDATION_ERROR };
};

/**
 * @function
 * @param {Object} eventDate
 * @return {Object} action
 */
export const resetExRule = ( eventDate ) => {
	return assertObjectHasId( eventDate ) ?
		{ type: RESET_EXRULE, id: eventDate.id, rule: '' } :
		{ type: VALIDATION_ERROR };
};
