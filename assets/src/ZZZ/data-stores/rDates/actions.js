/**
 * Internal dependencies
 */
import {
	assertIsDate,
	assertObjectHasId,
	VALIDATION_ERROR,
} from '../../helpers/validators';

/**
 * action types
 */
export const ADD_RDATE = 'ADD_RDATE';
export const DELETE_RDATE = 'DELETE_RDATE';

/**
 * @function
 * @param {Object} eventDate
 * @param {Date} date
 * @return {Object} action
 */
export const addRdate = ( eventDate, date ) => {
	return assertObjectHasId( eventDate ) && assertIsDate( date ) ?
		{ type: ADD_RDATE, id: eventDate.id, date: date } :
		{ type: VALIDATION_ERROR };
};

/**
 * @function
 * @param {Object} eventDate
 * @param {Date} date
 * @return {Object} action
 */
export const deleteRdate = ( eventDate, date ) => {
	return assertObjectHasId( eventDate ) && assertIsDate( date ) ?
		{ type: DELETE_RDATE, id: eventDate.id, date: date } :
		{ type: VALIDATION_ERROR };
};
