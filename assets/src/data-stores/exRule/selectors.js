/**
 * Internal dependencies
 */
import { findStoreById } from '../utils';
import { assertObjectHasId } from '../../helpers/validators';

export const getExRule = ( state, eventDate ) => {
	assertObjectHasId( eventDate );
	const store = findStoreById( state, eventDate.id );
	return store.exRule;
};
