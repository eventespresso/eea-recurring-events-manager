/**
 * External imports
 */
import { __ } from '@eventespresso/i18n';

export const RemEditorButtons = () => {
	return (
		<div className={ 'rem-editor-buttons-div' }>
			<button
				id={ 'rem-submit-button' }
				className={ 'button button-primary' }
				value={ __( 'Submit', 'event_espresso' ) }
			>
				{ __( 'Submit', 'event_espresso' ) }
			</button>
			<button
				id={ 'rem-cancel-button' }
				className={ 'button button-secondary' }
				value={ __( 'Cancel', 'event_espresso' ) }
			>
				{ __( 'Cancel', 'event_espresso' ) }
			</button>
		</div>
	);
};
