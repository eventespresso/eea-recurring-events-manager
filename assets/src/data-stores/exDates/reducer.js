/**
 * External dependencies
 */
import { filter, find } from 'lodash';

/**
 * Internal dependencies
 */
import { ADD_EXDATE, DELETE_EXDATE } from './actions';
import { datesStringsMatch } from '../../helpers/validators';

export const STORE_KEY_EXDATES = 'exDates';

/**
 * @function
 * @param {Object} state  previous state
 * @param {Object} action requested state mutation
 * @return {Object} new state
 */
export const exDatesReducer = ( state = {}, action ) => {
	switch ( action.type ) {
		case ADD_EXDATE:
			// check if date already exists in collection
			if ( find( state.STORE_KEY_EXDATES, function( exDate ) {
				return datesStringsMatch( exDate, action.date );
			} ) ) {
				return state;
			}
			// if not than add it
			return {
				...state,
				STORE_KEY_EXDATES: [ ...state.STORE_KEY_EXDATES, action.date ],
			};
		case DELETE_EXDATE:
			const exDates = filter( state.STORE_KEY_EXDATES, function( exDate ) {
				// remove exDates that match the incoming date
				return datesStringsMatch( exDate, action.date );
			} );
			return {
				...state,
				STORE_KEY_EXDATES: exDates,
			};
		default:
			return state;
	}
};
