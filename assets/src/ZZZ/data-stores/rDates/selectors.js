/**
 * Internal dependencies
 */
import { findStoreById } from '../utils';

/**
 * @function
 * @param {Object} state
 * @param {Object} eventDate
 * @return {Array} rDates
 */
export const getRDates = ( state, eventDate ) => {
	state = state.hasOwnProperty( 'rDates' ) ? state.rDates : state;
	const store = findStoreById( state, eventDate.id );
	return store.hasOwnProperty( 'rDates' ) ? store.rDates : [];
};
