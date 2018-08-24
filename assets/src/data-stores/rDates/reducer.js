
/**
 * Internal dependencies
 */
import { ADD_RDATE, DELETE_RDATE } from './actions';
import { findStoreById, addDate, removeDate, getNewState } from '../utils';

/**
 * @function
 * @param {Object} state  previous state
 * @param {Object} action requested state mutation
 * @return {Object} new state
 */
export const rDatesReducer = ( state = {}, action ) => {
	let store = {};
	switch ( action.type ) {
		case ADD_RDATE:
			store = findStoreById( state, action.id );
			return getNewState(
				state,
				action.id,
				{
					id: action.id,
					rDates: addDate( store.rDates, action.date ),
				}
			);
		case DELETE_RDATE:
			store = findStoreById( state, action.id );
			// console.log( 'store', store );
			return getNewState(
				state,
				action.id,
				{
					id: action.id,
					rDates: removeDate( store.rDates, action.date ),
				}
			);
		default:
			return state;
	}
};
