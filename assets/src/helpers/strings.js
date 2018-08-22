/**
 * @function
 * @param {string} string
 * @return {string} string but with first letter capitalized
 */
export function capitalizeFirstLetter( string ) {
	return string.charAt( 0 ).toUpperCase() + string.slice( 1 );
}
