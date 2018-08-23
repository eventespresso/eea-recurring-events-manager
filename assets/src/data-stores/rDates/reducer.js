/**
 * External dependencies
 */
import { reject, find } from 'lodash';

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
			if ( find( state.rDates, function( rDate ) {
				return datesStringsMatch( rDate, action.date );
			} ) ) {
				return state;
			}
			// if not than add it
			return {
				...state,
				rDates: [ ...state.rDates, action.date ],
			};
		case DELETE_RDATE:
			// remove rDates that match the incoming date
			const rDates = reject( state.rDates, function( rDate ) {
				return datesStringsMatch( rDate, action.date );
			} );
			return {
				...state,
				rDates: rDates,
			};
		default:
			return state;
	}
};
