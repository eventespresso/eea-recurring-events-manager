/**
 * Internal dependencies
 */
import './style.css';

/**
 * WithHoverText
 * Adds a text element that is only displayed when the provided child entity is hovered over
 *
 * @function
 * @param {string} htmlId     Used to generate an HTML "id" attribute. "-hover-text" is appended.
 * @param {string} htmlClass  Used to generate an HTML "class" attribute. "-hover-text" is appended.
 * @param {string} hoverText  The actual text to be displayed when the child entity is hovered over
 * @param {string} children   The child entity that gets wrapped with the hover elements
 * @return {string}           The child entity wrapped with the hover elements
 */
export const WithHoverText = ( { htmlId, htmlClass, hoverText, children } ) => (
	<div id={ `${ htmlId }-hover-text` } className={ `${ htmlClass }-hover-text ee-hover-text` }>
		<div className="ee-hover-text-content">{ children }</div>
		<div className={ 'ee-hover-text-notice-wrapper' }>
			<div className="ee-hover-text-notice ee-small-shadow">
				{ hoverText }
				<span className={ 'ee-hover-text-pointer ee-small-text-shadow' }>&#9700;</span>
			</div>
		</div>
	</div>
);
