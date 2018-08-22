/**
 * External imports
 */
import { Component } from 'react';
import { isEmpty, isArray } from 'lodash';
import { __ } from '@eventespresso/i18n';
import moment from 'moment';
import { RRule, RRuleSet } from 'rrule';
import { Modal } from '@wordpress/components';

// const { Modal } = wp.components;

/**
 * Internal dependencies
 */
import { RRulePatternEditor } from './rrule-pattern-editor';
import ExtraDatetimes from './extra-datetimes';
import { GeneratedDatetimes } from './generated-datetimes';
import { RemEditorButtons } from './rem-editor-buttons';
import {
	PATTERN_TYPE_RECURRENCE,
	PATTERN_TYPE_EXCLUSION,
} from './constants';
import './style.css';

/**
 * RecurringEventsManagerApp
 *
 * @constructor
 * @param {Object} eventDate    JSON object defining the Event Date
 */
export class EditDatetimeRecurrence extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			eventDate: {},
			rRuleString: '',
			exRuleString: '',
			rDates: [],
			exDates: [],
			datetimes: [],
		};
	}

	/**
	 * @function
	 * @param {string} rRuleString
	 */
	onRecurrenceChange = rRuleString => {
		rRuleString = rRuleString.target ?
			rRuleString.target.value :
			rRuleString;
		// console.log( '' );
		// console.log( 'EditDatetimeRecurrence.onRecurrenceChange() rRuleString:', rRuleString );
		if ( this.state.rRuleString !== rRuleString ) {
			this.setState( ( prevState ) => (
				{
					rRuleString: rRuleString,
					datetimes: this.generateDatetimes(
						rRuleString,
						prevState.exRuleString,
						prevState.rDates,
						prevState.exDates
					),
				}
			) );
		}
		// console.log( ' > this.state:', this.state );
	};

	/**
	 * @function
	 * @param {string} exRuleString
	 */
	onExclusionChange = ( exRuleString ) => {
		exRuleString = exRuleString.target ?
			exRuleString.target.value :
			exRuleString;
		// console.log( '' );
		// console.log( 'EditDatetimeRecurrence.onExclusionChange() exRuleString:', exRuleString );
		if ( this.state.exRuleString !== exRuleString ) {
			this.setState( ( prevState ) => (
				{
					exRuleString: exRuleString,
					datetimes: this.generateDatetimes(
						prevState.rRuleString,
						exRuleString,
						prevState.rDates,
						prevState.exDates
					),
				}
			) );
		}
		// console.log( ' > this.state:', this.state );
	};

	/**
	 * @function
	 * @param {Object} rDate
	 */
	onRDateChange = ( rDate ) => {
		this.setState( ( prevState ) => {
			console.log( 'onRDateChange()', rDate );
			const rDates = isArray( prevState.rDates ) ?
				prevState.rDates :
				[ rDate ];
			rDates.push( rDate );
			console.log( 'onRDateChange() rDates', rDates );
			return (
				{
					rDates: rDates,
					datetimes: this.generateDatetimes(
						prevState.rRuleString,
						prevState.exRuleString,
						rDates,
						prevState.exDates
					),
				}
			);
		} );
	};

	/**
	 * @function
	 * @param {Object} exDate
	 */
	onExDateChange = ( exDate ) => {
		this.setState( ( prevState ) => {
			console.log( 'onExDateChange()', exDate );
			const exDates = isArray( prevState.exDates ) ?
				prevState.exDates :
				[ exDate ];
			exDates.push( exDate );
			console.log( 'onExDateChange() exDates', exDates );
			return (
				{
					exDates: exDates,
					datetimes: this.generateDatetimes(
						prevState.rRuleString,
						prevState.exRuleString,
						prevState.rDates,
						exDates
					),
				}
			);
		} );
	};

	/**
	 * @function
	 * @param {string} rRuleString
	 * @param {string} exRuleString
	 * @param {Array}  rDates
	 * @param {Array}  exDates
	 * @return {Array} array of Date objects
	 */
	generateDatetimes = (
		rRuleString,
		exRuleString,
		rDates,
		exDates
	) => {
		if ( ! rRuleString ) {
			return [];
		}
		console.log( 'generateDatetimes() rRuleString', rRuleString );
		// const ruleString = 'DTSTART=20181101T120000Z;FREQ=WEEKLY;INTERVAL=1;BYDAY=MO,WE,FR;COUNT=4;WKST=SU';
		// const rrule = RRule.fromString( ruleString );
		// console.log( 'rrule.toString()', rrule.toString() );
		// console.log( rrule.all() );
		const rruleSet = new RRuleSet();
		rruleSet.rrule( RRule.fromString( rRuleString ) );
		if ( exRuleString ) {
			console.log( 'generateDatetimes() exRuleString', exRuleString );
			rruleSet.exrule( RRule.fromString( exRuleString ) );
		}
		if ( isArray( rDates ) && ! isEmpty( rDates ) ) {
			console.log( 'generateDatetimes() rDates', rDates );
			rDates.map(
				function( rDate ) {
					if ( rDate instanceof Date ) {
						console.log( 'generateDatetimes() rDate', rDate );
						rruleSet.rdate( rDate );
					}
				}
			);
		}
		if ( isArray( exDates ) && ! isEmpty( exDates ) ) {
			console.log( 'generateDatetimes() exDates', exDates );
			exDates.map(
				function( exDate ) {
					if ( exDate instanceof Date ) {
						console.log( 'generateDatetimes() exDate', exDate );
						rruleSet.exdate( exDate );
					}
				}
			);
		}

		let dateCount = 183; // ~6 months of events if repeated DAILY
		switch ( this.getRecurrenceFrequency( rRuleString ) ) {
			case 'YEARLY':
				dateCount = 5; // 5 years if repeated YEARLY
				break;
			case 'MONTHLY':
				dateCount = 36; // 3 years if repeated MONTHLY
				break;
			case 'WEEKLY':
				dateCount = 104; // 2 years if repeated WEEKLY
				break;
		}
		return rruleSet.all( function( date, i ) {
			return i < dateCount;
		} );
	};

	/**
	 * @function
	 * @param {string} rRuleString
	 * @return {string} recurrence frequency
	 */
	getRecurrenceFrequency = ( rRuleString ) => {
		let freq = 'DAILY';
		if ( rRuleString.indexOf( 'FREQ=YEARLY' ) !== - 1 ) {
			freq = 'YEARLY';
		} else if ( rRuleString.indexOf( 'FREQ=MONTHLY' ) !== - 1 ) {
			freq = 'MONTHLY';
		} else if ( rRuleString.indexOf( 'FREQ=WEEKLY' ) !== - 1 ) {
			freq = 'WEEKLY';
		}
		return freq;
	};

	/**
	 * @function
	 * @param {Object} eventDate
	 */
	addDatetime = ( eventDate ) => {
		console.log( ' >>> CLICK <<< ADD EVENT DATE' );
		this.onRDateChange( this.parseDate( eventDate ) );
	};

	/**
	 * @function
	 * @param {Object} eventDate
	 */
	deleteDatetime = ( eventDate ) => {
		console.log( ' >>> CLICK <<< DELETE EVENT DATE' );
		this.onExDateChange( this.parseDate( eventDate ) );
	};

	/**
	 * @function
	 * @param {Object} eventDate
	 * @return {Object} eventDate
	 */
	handleExtraDatetimeChange = ( eventDate ) => {
		console.log( ' >>> CLICK <<< EXTRA EVENT DATE CHANGE' );
		eventDate = this.parseDate( eventDate );
		return eventDate;
	};

	/**
	 * @function
	 * @param {Object} data
	 */
	saveAllTheThings = ( data ) => {
		console.log( ' >>> CLICK <<< SAVE ALL THE THINGS data', data );
	};

	/**
	 * @function
	 * @param {Object} eventDate
	 * @return {Object} eventDate
	 */
	parseDate = ( eventDate ) => {
		console.log( 'parseDate' );
		eventDate = eventDate.target ? eventDate.target.value : eventDate;
		console.log( ' - eventDate', eventDate );
		console.log( ' - eventDate instanceof Date', eventDate instanceof Date );
		eventDate = eventDate instanceof Date ?
			eventDate :
			moment( eventDate ).toDate();
		console.log( ' - eventDate', eventDate );
		console.log( ' - eventDate instanceof Date', eventDate instanceof Date );
		return eventDate;
	};

	render() {
		const { editorOpen, eventDate, toggleEditor } = this.props;
		return editorOpen && eventDate && eventDate.id ? (
			<Modal
				title={ __( 'Event Date Recurrence Pattern Editor', 'event_espresso' ) }
				className={ 'ee-edit-event-date-recurrence' }
				onRequestClose={ toggleEditor }
				closeButtonLabel={ __( 'close settings', 'event_espresso' ) }
			>
				<RRulePatternEditor
					id={ eventDate.id }
					type={ PATTERN_TYPE_RECURRENCE }
					rruleString={ this.state.rRuleString }
					onChange={ this.onRecurrenceChange }
					initialOpen={ true }
				/>
				<RRulePatternEditor
					id={ eventDate.id }
					type={ PATTERN_TYPE_EXCLUSION }
					rruleString={ this.state.exRuleString }
					onChange={ this.onExclusionChange }
				/>
				<ExtraDatetimes
					id={ eventDate.id }
					type={ PATTERN_TYPE_RECURRENCE }
					datetimes={ this.state.rDates }
					addDatetime={ this.addDatetime }
					deleteDatetime={ this.deleteDatetime }
					handleChange={ this.handleExtraDatetimeChange }
				/>
				<ExtraDatetimes
					id={ eventDate.id }
					type={ PATTERN_TYPE_EXCLUSION }
					datetimes={ this.state.exDates }
					addDatetime={ this.deleteDatetime }
					deleteDatetime={ this.addDatetime }
					handleChange={ this.handleExtraDatetimeChange }
				/>
				<GeneratedDatetimes
					id={ eventDate.id }
					datetimes={ this.state.datetimes }
					freq={
						this.getRecurrenceFrequency(
							this.state.rRuleString
						)
					}
					onClick={ this.deleteDatetime }
				/>
				<RemEditorButtons
					id={ eventDate.id }
					onSubmit={ this.saveAllTheThings }
					onCancel={ toggleEditor }
				/>
			</Modal>
		) :
			null;
	}
}

/*
 id="recurring-events"
* @param {string} modalOverlayParent		DOM element id where modal portal should be injected.
* @param {string} openModalDashicon		What icon to use for the toggle button that opens the modal dialog.
* @param {string} closeModalDashicon		What icon to use for the toggle button that closes the modal dialog.
* @param {string} openHoverTextPosition	Hover text position for the toggle button that opens the modal dialog.
* @param {string} closeHoverTextPosition	Hover text position for the toggle button that closes the modal dialog.
*/
