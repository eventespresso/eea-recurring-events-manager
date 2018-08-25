/**
 * Internal dependencies
 */
import { findStoreById } from '../utils';

/**
 * @function
 * @param {Object} state
 * @param {Object} eventDate
 * @return {Array} exDates
 */
export const getExDates = ( state, eventDate ) => {
	state = state.hasOwnProperty( 'exDates' ) ? state.exDates : state;
	const store = findStoreById( state, eventDate.id );
	return store.hasOwnProperty( 'exDates' ) ? store.exDates : [];
};
