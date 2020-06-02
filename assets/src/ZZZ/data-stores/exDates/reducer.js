/**
 * Internal dependencies
 */
import { ADD_EXDATE, DELETE_EXDATE } from './actions';
import { findStoreById, addDate, removeDate, getNewState } from '../utils';

/**
 * @function
 * @param {Object} state  previous state
 * @param {Object} action requested state mutation
 * @return {Object} new state
 */
export const exDatesReducer = ( state = [], action ) => {
	let store = {};
	switch ( action.type ) {
		case ADD_EXDATE:
			store = findStoreById( state, action.id );
			return getNewState(
				state,
				action.id,
				{
					id: action.id,
					exDates: addDate( store.exDates, action.date ),
				}
			);
		case DELETE_EXDATE:
			store = findStoreById( state, action.id );
			return getNewState(
				state,
				action.id,
				{
					id: action.id,
					exDates: removeDate( store.exDates, action.date ),
				}
			);
		default:
			return state;
	}
};
