/**
 * External imports
 */
import { __ } from '@eventespresso/i18n';
import { GeneratedDatetimeRow } from './generated-datetime-row';
import { RemEditorButtons } from './rem-editor-buttons';
import { WithHoverText } from '../components/hover-text';

export const GeneratedDatetimes = ( { datetimes } ) => {
	const datetimeRows = [];
	const l = datetimes.length;
	for ( let i = 0; i < l; i++ ) {
		if ( datetimes[ i ] instanceof Date ) {
			datetimeRows.push( <GeneratedDatetimeRow index={ i } date={ datetimes[ i ] } /> );
		}
	}
	return (
		<div id={ 'generated-datetimes-div' } className={ 'rem-form-row' }>
			<h3 className={ 'repeats-div-heading' }>{ __( 'Datetimes for this Recurrence Pattern', 'event_espresso' ) }</h3>
			<div className={ 'px-0 pt-3 border rounded' }>
				<div className="px-3">
					<div className="col-sm-8 offset-sm-2">
						<div className="form-group">
							<ul className="generated-datetimes-list">{ datetimeRows }</ul>
						</div>
						<WithHoverText
							htmlId={ 'add-datetime-button' }
							htmlClass={ 'add-datetime-button' }
							hoverText={ __( 'add a Datetime that could not be resolved using the Recurrence Pattern Editor', 'event_espresso' ) }
						>
							<button
								id={ 'add-datetime-button' }
								className={ 'button button-primary' }
								value={ __( 'Add Individual Datetime', 'event_espresso' ) }
								onClick={ ( e ) => ( e.preventDefault() ) }
							>
								<span className={ 'dashicons dashicons-plus' }></span>
								<span className={ 'button-text' }>{ __( 'Add Individual Datetime', 'event_espresso' ) }</span>
							</button>
						</WithHoverText>
					</div>
					<RemEditorButtons />
				</div>
			</div>
			<input type={ 'hidden' } id={ 'rem-generated-datetimes-json' } />
		</div>
	);
};

// green-button
