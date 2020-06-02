/**
 * External imports
 */
import { Component } from 'react';
import { isArray } from 'lodash';
import { PanelBody, PanelRow } from '@wordpress/components';
import { __ } from '@eventespresso/i18n';
// import { SettingsPanel } from '@eventespresso/components';

/**
 * Internal dependencies
 */
import ExtraDatetime from './extra-datetime';
import { PATTERN_TYPE_RECURRENCE } from './constants';

class ExtraDatetimes extends Component {
	/**
	 * @function
	 * @param {Array} datetimes
	 * @param {Function} addDatetime
	 * @param {Function} deleteDatetime
	 * @param {Function} handleChange
	 * @return {string} rendered list of generated datetimes
	 */
	getDatetimesList = (
		datetimes,
		addDatetime,
		deleteDatetime,
		handleChange
	) => {
		const dates = datetimes.map(
			function( extraDate, index ) {
				return <li key={ index }>
					<ExtraDatetime
						extraDate
						handleChange={ handleChange }
						addDateHandler={ addDatetime }
						deleteDateHandler={ deleteDatetime }
						options={ {
							dateFormat: 'YYYY-MM-DD',
							locale: 'en_US',
						} }
						index={ index }
						datetimeCount={ this.props.datetimes.length }
					/>
				</li>;
			},
			this
		);		return (
			<ul>{ dates }</ul>
		);
	};

	render() {
		let { id } = this.props;
		const {
			type,
			datetimes,
			addDatetime,
			deleteDatetime,
			handleChange,
		} = this.props;
		// console.log( '' );
		// console.log( 'ExtraDatetimes', datetimes );
		if ( ! isArray( datetimes ) ) {
			return null;
		}
		const label = type === PATTERN_TYPE_RECURRENCE ?
			__( 'Extra Dates to Add', 'event_espresso' ) :
			__( 'Exceptions to Remove', 'event_espresso' );
		id = type === PATTERN_TYPE_RECURRENCE ?
			'add-dates-rrule-generator-wrapper-' + id :
			'remove-dates-rrule-generator-wrapper-' + id;
		const className = type === PATTERN_TYPE_RECURRENCE ?
			'add-dates-rrule-generator-wrapper rrule-generator-wrapper' :
			'remove-dates-rrule-generator-wrapper rrule-generator-wrapper';
		return (
			<PanelBody
				title={ label }
				id={ id }
				className={ className }
				initialOpen={ datetimes.length > 0 }
			>
				<PanelRow className={ 'extra-dates-form rem-form-row' }>
					<div className={ 'px-0 pt-3 border rounded' }>
						<div className="px-3">
							<div className="col-sm-6 offset-sm-2">
								{ this.getDatetimesList(
									datetimes,
									addDatetime,
									deleteDatetime,
									handleChange
								) }
							</div>
						</div>
					</div>
				</PanelRow>
			</PanelBody>
		);
	}
}

export default ExtraDatetimes;
