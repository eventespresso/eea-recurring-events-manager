/**
 * Internal dependencies
 */
import { assertIsDate, VALIDATION_ERROR } from '../../helpers/validators';

/**
 * action types
 */
export const ADD_EXDATE = 'ADD_EXDATE';
export const DELETE_EXDATE = 'DELETE_EXDATE';

/**
 * @function
 * @param {Date} date
 * @return {Object} action
 */
export const addExDate = ( date ) => {
	return assertIsDate( date ) ?
		{ type: ADD_EXDATE, date: date } :
		{ type: VALIDATION_ERROR };
};

/**
 * @function
 * @param {Date} date
 * @return {Object} action
 */
export const deleteExDate = ( date ) => {
	return assertIsDate( date ) ?
		{ type: DELETE_EXDATE, date: date } :
		{ type: VALIDATION_ERROR };
};
