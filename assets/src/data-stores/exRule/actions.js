/**
 * Internal dependencies
 */
import { assertIsString, VALIDATION_ERROR } from '../../helpers/validators';

/**
 * action types
 */
export const ADD_EXRULE = 'ADD_EXRULE';
export const RESET_EXRULE = 'RESET_EXRULE';

/**
 * @function
 * @param {string} exRruleString
 * @return {Object} action
 */
export const addExRule = ( exRruleString ) => {
	return assertIsString( exRruleString ) ?
		{ type: ADD_EXRULE, rule: exRruleString } :
		{ type: VALIDATION_ERROR };
};

/**
 * @function
 * @return {Object} action
 */
export const resetExRule = () => {
	return { type: RESET_EXRULE };
};
