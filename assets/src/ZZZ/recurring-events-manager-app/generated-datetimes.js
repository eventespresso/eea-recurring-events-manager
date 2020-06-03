/**
 * External imports
 */
import { Component } from 'react';
import JwPagination from 'jw-react-pagination';
import { __ } from '@eventespresso/i18n';
import { GeneratedDatetimeRow } from './generated-datetime-row';
import { PanelBody, PanelRow } from '@wordpress/components';

/**
 * GeneratedDatetimes
 *
 * @constructor
 * @param {Array} datetimes array of ALL datetimes
 * @param {string} freq    	recurrence pattern frequency
 */
export class GeneratedDatetimes extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			datetimes: props.datetimes,
			datetimesPage: [],
		};
	}

	/**
	 * @function
	 * @param {Array} datetimes
	 * @param {Array} datetimesPage
	 * @param {Function} onClick
	 * @return {Array} array of GeneratedDatetimeRow list items
	 */
	generateDatetimeRows = ( datetimes, datetimesPage, onClick ) => {
		// console.log( '' );
		// console.log( 'generateDatetimeRows datetimes', datetimes );
		// console.log( 'generateDatetimeRows datetimesPage', datetimesPage );
		return datetimesPage.map(
			( date, index ) => {
				if ( datetimesPage[ index ] instanceof Date ) {
					let number = datetimes.indexOf( date );
					number++;
					return (
						<li key={ index }>
							<GeneratedDatetimeRow
								date={ date }
								number={ number }
								onClick={ onClick }
							/>
						</li>
					);
				}
			}
		);
	};

	/**
	 * @function
	 * @param {Array} datetimesPage
	 */
	onPaginationChange = datetimesPage => {
		// update local state with new page of items
		this.setState( { datetimesPage } );
	};

	/**
	 * @function
	 * @param {string} freq
	 * @param {number} count
	 * @return {string} rendered warning message
	 */
	maxEventDatesWarning = ( freq, count ) => {
		let warning = '';
		switch ( freq ) {
			case 'YEARLY':
				warning = count >= 5 ?
					'The number of Event Dates has been capped at 5' +
					' for YEARLY recurrence patterns' :
					'';
				break;
			case 'MONTHLY':
				warning = count >= 24 ?
					'The number of Event Dates has been capped at 24' +
					' for MONTHLY recurrence patterns (2 years)' :
					'';
				break;
			case 'WEEKLY':
				warning = count >= 52 ?
					'The number of Event Dates has been capped at 52' +
					' for WEEKLY recurrence patterns (1 year)' :
					'';
				break;
			case 'DAILY':
				warning = count >= 92 ?
					'The number of Event Dates has been capped at 92' +
					' for DAILY recurrence patterns (~3 months)' :
					'';
				break;
		}
		return warning && (
			<p className={ 'rem-max-event-dates-warning' }>{ warning }</p>
		);
	};

	render() {
		const { datetimes, freq } = this.props;
		// console.log( 'GeneratedDatetimes datetimes.length', datetimes.length );
		return (
			<PanelBody
				title={
					__(
						'Datetimes for this Recurrence Pattern',
						'event_espresso'
					)
				}
				className={
					'generated-datetimes-rrule-generator-wrapper ' +
					'rrule-generator-wrapper'
				}
				initialOpen={ datetimes.length > 0 }
				opened={ datetimes.length > 0 }
			>
				<PanelRow className={ 'generated-datetimes-div rem-form-row' }>
					<div className={ 'px-0 pt-3 border rounded' }>
						<div className="px-3">
							<div className="col-sm-8 offset-sm-2">
								<div className="form-group">
									<ul className="generated-datetimes-list">
										{
											this.generateDatetimeRows(
												datetimes,
												this.state.datetimesPage,
												this.props.onClick
											)
										}
									</ul>
									<div className="rem-gdp-div">
										<JwPagination
											items={ datetimes }
											onChangePage={
												this.onPaginationChange
											}
										/>
									</div>
									<div className="rem-gdp-div">
										{ this.maxEventDatesWarning(
											freq,
											datetimes.length
										) }
									</div>
								</div>
							</div>
						</div>
					</div>
					<input
						type={ 'hidden' }
						id={ 'rem-generated-datetimes-json' }
					/>
				</PanelRow>
			</PanelBody>
		);
	}
}
