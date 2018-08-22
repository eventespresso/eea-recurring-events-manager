/**
 * External imports
 */
import { Component, Fragment } from 'react';
import DateTime from 'react-datetime';
// import moment from 'moment';
import { __ } from '@eventespresso/i18n';
import { Dashicon, IconButton, Tooltip } from '@wordpress/components';

class ExtraDatetime extends Component {
	actions = ( { lastItem, addDateHandler, deleteDateHandler } ) => {
		return lastItem ?
			this.addDate( addDateHandler ) :
			this.deleteDate( deleteDateHandler );
	};

	addDate = ( addDateHandler ) => {
		return (
			<Tooltip
				text={
					__(
						'Add Extra Event Date',
						'event_espresso'
					)
				}
			>
				<IconButton
					id={ 'add-datetime-button' }
					className={ 'components-button components-icon-button' }
					onClick={ addDateHandler }
				>
					<Dashicon icon={ 'insert' } />
				</IconButton>
			</Tooltip>
		);
	};

	deleteDate = ( deleteDateHandler ) => {
		return (
			<Tooltip
				text={ __( 'Delete this Event Date', 'event_espresso' ) }
			>
				<IconButton
					id={ 'delete-datetime-button' }
					className={ 'components-button components-icon-button' }
					onClick={ deleteDateHandler }
				>
					<Dashicon icon={ 'trash' } />
				</IconButton>
			</Tooltip>
		);
	};

	render() {
		const {
			extraDate,
			options,
			handleChange,
			datetimeCount,
			addDateHandler,
			deleteDateHandler,
		} = this.props;
		let { index } = this.props;
		index++;
		const calendarAttributes = {
			'aria-label': __(
				'Datetime picker for an extra date',
				'event_espresso'
			),
			value: extraDate.start,
			dateFormat: options.dateFormat,
			locale: options.locale,
			readOnly: true,
		};

		return (
			<Fragment>
				<DateTime
					{ ...calendarAttributes }
					inputProps={ { name: extraDate.name, readOnly: true } }
					timeFormat={ false }
					viewMode="days"
					closeOnSelect
					closeOnTab
					required
					onChange={ handleChange }
				/>
				{
					this.actions(
						index === datetimeCount,
						addDateHandler,
						deleteDateHandler
					)
				}
			</Fragment>
		);
	}
}

export default ExtraDatetime;
