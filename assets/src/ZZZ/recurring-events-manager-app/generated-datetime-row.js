/**
 * External imports
 */
import moment from 'moment';
import { Component, Fragment } from 'react';
import { __ } from '@eventespresso/i18n';
import { IconButton } from '@wordpress/components';

export class GeneratedDatetimeRow extends Component {
	render() {
		return (
			<Fragment>
				{ this.props.number + ' ' + this.props.date.toString() }
				<div className={ 'generated-datetime-trash-div' }>
					<IconButton
						tooltip={ __( 'Add to Exceptions.', 'event_espresso' ) }
						label={ __( 'Add to Exceptions', 'event_espresso' ) }
						icon={ 'trash' }
						onClick={ this.props.onClick }
						value={ moment( this.props.date ).format() }
					/>
				</div>
			</Fragment>
		);
	}
}
