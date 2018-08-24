/**
 * External dependencies
 */
import { isString, isNumber } from 'lodash';
import { __ } from '@eventespresso/i18n';

/**
 * constants
 */
export const VALIDATION_ERROR = 'validation-error';

/**
 * @function
 * @param {Date} date1
 * @param {Date} date2
 * @return {boolean} returns true if supplied dates match
 *                     when converted to strings
 */
export const dateStringsMatch = ( date1, date2 ) => {
	return assertIsDate( date1 ) &&
		assertIsDate( date2 ) &&
		date1.toString() === date2.toString();
};

/**
 * @function
 * @param {Date} date
 * @return {boolean} returns true if supplied value is a Date object
 *                     otherwise throws a TypeError
 */
export const assertIsDate = ( date ) => {
	if ( date instanceof Date ) {
		return true;
	}
	throw new TypeError(
		__(
			'The supplied value was expected to be a Date object.',
			'event_espresso'
		)
	);
};

/**
 * @function
 * @param {string} string
 * @return {boolean} returns true if date is a string
 *                     otherwise throws a TypeError
 */
export const assertIsString = ( string ) => {
	if ( isString( string ) ) {
		return true;
	}
	throw new TypeError(
		__(
			'The supplied value was expected to be a string.',
			'event_espresso'
		)
	);
};

/**
 * @function
 * @param {Object} object
 * @return {boolean} returns true if eventDate has an "id" property
 *                     that is either a string or number
 *                     otherwise throws a TypeError
 */
export const assertObjectHasId = ( object ) => {
	if ( typeof object !== 'undefined' &&
		object.hasOwnProperty( 'id' ) &&
		idIsStringOrNumber( object.id )
	) {
		return true;
	}
	throw new TypeError(
		__(
			'The supplied object was expected to have a valid "id" property.',
			'event_espresso'
		)
	);
};

/**
 * @function
 * @param {string|number} id
 * @return {boolean} returns true if id  is either a string or number
 */
export const idIsStringOrNumber = ( id ) => {
	return isString( id ) || isNumber( id );
};
