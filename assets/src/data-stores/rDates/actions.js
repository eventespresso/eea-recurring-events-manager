/**
 * Internal dependencies
 */
import { assertIsDate, VALIDATION_ERROR } from '../../helpers/validators';

/**
 * action types
 */
export const ADD_RDATE = 'ADD_RDATE';
export const DELETE_RDATE = 'DELETE_RDATE';

/**
 * @function
 * @param {Date} date
 * @return {Object} action
 */
export const addRdate = ( date ) => {
	return assertIsDate( date ) ?
		{ type: ADD_RDATE, date: date } :
		{ type: VALIDATION_ERROR };
};

/**
 * @function
 * @param {Date} date
 * @return {Object} action
 */
export const deleteRdate = ( date ) => {
	return assertIsDate( date ) ?
		{ type: DELETE_RDATE, date: date } :
		{ type: VALIDATION_ERROR };
};
