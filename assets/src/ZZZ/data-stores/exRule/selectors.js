/**
 * Internal dependencies
 */
import { findStoreById } from '../utils';

/**
 * @function
 * @param {Object} state
 * @param {Object} eventDate
 * @return {string} exRule
 */
export const getExRule = ( state, eventDate ) => {
	state = state.hasOwnProperty( 'exRule' ) ? state.exRule : state;
	const store = findStoreById( state, eventDate.id );
	return store.hasOwnProperty( 'exRule' ) ? store.exRule : '';
};
