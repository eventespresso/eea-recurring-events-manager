/**
 * External dependencies
 */
import { filter, find } from 'lodash';

/**
 * Internal dependencies
 */
import { ADD_RDATE, DELETE_RDATE } from './actions';
import { datesStringsMatch } from '../../helpers/validators';

export const STORE_KEY_RDATES = 'rDates';

/**
 * @function
 * @param {Object} state  previous state
 * @param {Object} action requested state mutation
 * @return {Object} new state
 */
export const rDatesReducer = ( state = {}, action ) => {
	switch ( action.type ) {
		case ADD_RDATE:
			// check if incoming date already exists in collection
			if ( find( state.STORE_KEY_RDATES, function( rDate ) {
				return datesStringsMatch( rDate, action.date );
			} ) ) {
				return state;
			}
			// if not than add it
			return {
				...state,
				STORE_KEY_RDATES: [ ...state.STORE_KEY_RDATES, action.date ],
			};
		case DELETE_RDATE:
			// remove rDates that match the incoming date
			const rDates = filter( state.STORE_KEY_RDATES, function( rDate ) {
				return datesStringsMatch( rDate, action.date );
			} );
			return {
				...state,
				STORE_KEY_RDATES: rDates,
			};
		default:
			return state;
	}
};
