/**
 * Internal dependencies
 */
import { findStoreById } from '../utils';
import { assertObjectHasId } from '../../helpers/validators';

export const getRRule = ( state, eventDate ) => {
	assertObjectHasId( eventDate );
	const store = findStoreById( state, eventDate.id );
	return store.rRule;
};
