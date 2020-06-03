/**
 * External dependencies
 */
import { Component } from 'react';
import { isEmpty, isArray } from 'lodash';
import { __ } from '@eventespresso/i18n';
import moment from 'moment';
import { RRule, RRuleSet } from 'rrule';

/**
 * WordPress dependencies
 */
import { Modal } from '@wordpress/components';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import { RRulePatternEditor } from './rrule-pattern-editor';
import ExtraDatetimes from './extra-datetimes';
import { GeneratedDatetimes } from './generated-datetimes';
import { RemEditorButtons } from './rem-editor-buttons';
import { DATA_STORE_KEY_REM } from '../data-stores';
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
class EditDatetimeRecurrence extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			eventDate: {},
			datetimes: [],
		};
	}

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
		const {
			editorOpen,
			eventDate,
			toggleEditor,
			rRule,
			exRule,
			rDates,
			exDates,
			onRecurrenceChange,
			onExclusionChange,
			addRdate,
			deleteRdate,
			addExDate,
			deleteExDate,
		} = this.props;

		console.log( 'EditDatetimeRecurrence this.props', this.props );

		return editorOpen && eventDate.hasOwnProperty( 'id' ) ? (
			<Modal
				title={ __( 'Event Date Recurrence Pattern Editor', 'event_espresso' ) }
				className={ 'ee-edit-event-date-recurrence' }
				onRequestClose={ toggleEditor }
				closeButtonLabel={ __( 'close settings', 'event_espresso' ) }
			>
				<RRulePatternEditor
					id={ eventDate.id }
					type={ PATTERN_TYPE_RECURRENCE }
					rruleString={ rRule }
					onChange={ onRecurrenceChange }
					initialOpen={ true }
				/>
				<RRulePatternEditor
					id={ eventDate.id }
					type={ PATTERN_TYPE_EXCLUSION }
					rruleString={ exRule }
					onChange={ onExclusionChange }
				/>
				<ExtraDatetimes
					id={ eventDate.id }
					type={ PATTERN_TYPE_RECURRENCE }
					datetimes={ rDates }
					addDatetime={ addRdate }
					deleteDatetime={ deleteRdate }
				/>
				<ExtraDatetimes
					id={ eventDate.id }
					type={ PATTERN_TYPE_EXCLUSION }
					datetimes={ exDates }
					addDatetime={ addExDate }
					deleteDatetime={ deleteExDate }
				/>
				<GeneratedDatetimes
					id={ eventDate.id }
					datetimes={ this.state.datetimes }
					freq={
						this.getRecurrenceFrequency( rRule )
					}
					onClick={ addExDate }
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

export default compose(
	withSelect( ( select, ownProps ) => {
		const {
			getRRule,
			getExRule,
			getRDates,
			getExDates,
		} = select( DATA_STORE_KEY_REM );
		const { eventDate } = ownProps;
		// console.log( 'EditDatetimeRecurrence withSelect() ownProps', ownProps );
		return eventDate.hasOwnProperty( 'id' ) ?
			{
				rRule: getRRule( eventDate ),
				exRule: getExRule( eventDate ),
				rDates: getRDates( eventDate ),
				exDates: getExDates( eventDate ),
			} : {};
	} ),
	withDispatch( ( dispatch, ownProps ) => {
		const {
			addRrule,
			resetRrule,
			addExRule,
			resetExRule,
			addRdate,
			deleteRdate,
			addExDate,
			deleteExDate,
		} = dispatch( DATA_STORE_KEY_REM );
		const { eventDate } = ownProps;
		// console.log( 'EditDatetimeRecurrence withDispatch() ownProps', ownProps );
		return {
			onRecurrenceChange( rRuleString ) {
				rRuleString = rRuleString.target ?
					rRuleString.target.value :
					rRuleString;
				return rRuleString ?
					addRrule( eventDate, rRuleString ) :
					resetRrule( eventDate );
			},
			onExclusionChange( exRuleString ) {
				exRuleString = exRuleString.target ?
					exRuleString.target.value :
					exRuleString;
				return exRuleString ?
					addExRule( eventDate, exRuleString ) :
					resetExRule( eventDate );
			},
			addRdate( rDate ) {
				return addRdate( eventDate, rDate );
			},
			deleteRdate( rDate ) {
				return deleteRdate( eventDate, rDate );
			},
			addExDate( exDate ) {
				return addExDate( eventDate, exDate );
			},
			deleteExDate( exDate ) {
				return deleteExDate( eventDate, exDate );
			},
		};
	} ),
)( EditDatetimeRecurrence );
