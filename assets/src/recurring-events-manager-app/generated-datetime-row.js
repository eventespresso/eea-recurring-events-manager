/**
 * External imports
 */
import { __ } from '@eventespresso/i18n';

/**
 * Internal dependencies
 */
import { WithHoverText } from '../components/hover-text';

export const GeneratedDatetimeRow = ( { index, date } ) => {
	return (
		<li key={ index }>
			{ date.toString() }
			<div className={ 'generated-datetime-trash-div' }>
				<WithHoverText
					htmlId={ 'generated-datetime-trash-' + index }
					htmlClass={ 'generated-datetime-trash' }
					hoverText={ __( 'remove datetime', 'event_espresso' ) }
				>
					<span className={ 'dashicons dashicons-trash' }></span>
				</WithHoverText>
			</div>
		</li>
	);
};
