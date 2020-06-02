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
export const ADD_EXDATE = 'ADD_EXDATE';
export const DELETE_EXDATE = 'DELETE_EXDATE';

/**
 * @function
 * @param {Object} eventDate
 * @param {Date} date
 * @return {Object} action
 */
export const addExDate = ( eventDate, date ) => {
	return assertObjectHasId( eventDate ) && assertIsDate( date ) ?
		{ type: ADD_EXDATE, id: eventDate.id, date: date } :
		{ type: VALIDATION_ERROR };
};

/**
 * @function
 * @param {Object} eventDate
 * @param {Date} date
 * @return {Object} action
 */
export const deleteExDate = ( eventDate, date ) => {
	return assertObjectHasId( eventDate ) && assertIsDate( date ) ?
		{ type: DELETE_EXDATE, id: eventDate.id, date: date } :
		{ type: VALIDATION_ERROR };
};
