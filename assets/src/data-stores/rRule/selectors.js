/**
 * Internal dependencies
 */
import { findStoreById } from '../utils';

/**
 * @function
 * @param {Object} state
 * @param {Object} eventDate
 * @return {string} rRule
 */
export const getRRule = ( state, eventDate ) => {
	state = state.hasOwnProperty( 'rRule' ) ? state.rRule : state;
	const store = findStoreById( state, eventDate.id );
	return store.hasOwnProperty( 'rRule' ) ? store.rRule : '';
};
