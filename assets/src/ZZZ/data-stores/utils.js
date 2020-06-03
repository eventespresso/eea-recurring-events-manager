/**
 * External dependencies
 */
import { find, reject, isArray } from 'lodash';
import { __ } from '@eventespresso/i18n';

/**
 * Internal dependencies
 */
import {
	dateStringsMatch,
	assertObjectHasId,
	idIsStringOrNumber,
} from '../helpers/validators';

/**
 * @function
 * @param {Object} state
 * @param {string} storeID
 * @return {Object|null} store if found or null
 */
export const findStoreById = ( state, storeID ) => {
	if ( ! idIsStringOrNumber( storeID ) ) {
		throw new TypeError(
			__(
				'A valid storeID is required in order to find a data store.',
				'event_espresso'
			)
		);
	}
	state = isArray( state ) ? state : [];
	const newStore = find( state, function( store ) {
		assertObjectHasId( store );
		return store.id === storeID;
	} );
	return newStore ? newStore : {};
};

/**
 * @function
 * @param {Object} state
 * @param {string} storeID
 * @return {Array} new state or empty array
 */
export const removeStoreById = ( state, storeID ) => {
	if ( ! idIsStringOrNumber( storeID ) ) {
		throw new TypeError(
			__(
				'A valid storeID is required in order to remove a data store.',
				'event_espresso'
			)
		);
	}
	const newState = reject( state, function( store ) {
		assertObjectHasId( store );
		return store.id === storeID;
	} );
	return newState ? newState : [];
};

/**
 * @function
 * @param {Object} oldStore
 * @param {Object} newData
 * @return {Object} new store
 */
export const initializeStore = ( oldStore, newData ) => {
	const newStore = {
		id: null,
		rRule: '',
		exRule: '',
		rDates: [],
		exDates: [],
	};
	return { ...newStore, ...oldStore, ...newData };
};

/**
 * @function
 * @param {Array}           state
 * @param {string|number}   storeID
 * @param {Object}          newData
 * @return {Array}          new state
 */
export const getNewState = ( state = [], storeID, newData ) => {
	// console.log( 'getNewState' );
	// console.log( 'state', state );
	// console.log( 'storeID', storeID );
	// console.log( 'newData', newData );
	// first, find existing data store
	const oldStore = findStoreById( state, storeID );
	// console.log( 'oldStore', oldStore );
	// then create a new state object without the above data store
	const newState = removeStoreById( state, storeID );
	// console.log( 'newState', newState );
	// then initialize new store using data copied from existing
	// plus new data from incoming action
	const newStore = initializeStore( oldStore, newData );
	// console.log( 'newStore', newStore );
	// return new state object with new data store
	return [ ...newState, newStore ];
};

/**
 * @function
 * @param {Array}       dates
 * @param {Date}    	dateToMatch
 * @return {Date|null}  Date object if found, else null
 */
export const findDate = ( dates = [], dateToMatch ) => {
	dates = isArray( dates ) ? dates : [];
	// check if incoming date already exists in collection
	const matchedDate = find( dates, function( date ) {
		return dateStringsMatch( date, dateToMatch );
	} );
	return matchedDate ? matchedDate : null;
};

/**
 * @function
 * @param {Array} 	dates
 * @param {Date}    dateToAdd
 * @return {Array}  array of dates
 */
export const addDate = ( dates = [], dateToAdd ) => {
	dates = isArray( dates ) ? dates : [];
	// check if incoming date already exists in collection
	if ( findDate( dates, dateToAdd ) instanceof Date ) {
		return dates;
	}
	return dateToAdd instanceof Date ? [ ...dates, dateToAdd ] : dates;
};

/**
 * @function
 * @param {Array}       dates
 * @param {Date}    	dateToMatch
 * @return {Date|null}  Date object if found, else null
 */
export const removeDate = ( dates = [], dateToMatch ) => {
	// console.log( 'dates', dates );
	dates = isArray( dates ) ? dates : [];
	// console.log( 'dates', dates );
	// console.log( 'dateToMatch', dateToMatch );
	// check if incoming date already exists in collection
	const newDates = reject( dates, function( date ) {
		// console.log( 'date', date );
		// console.log( 'dateStringsMatch', dateStringsMatch( date, dateToMatch ) );
		return dateStringsMatch( date, dateToMatch );
	} );
	return newDates ? newDates : [];
};
