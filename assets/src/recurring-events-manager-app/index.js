/**
 * External imports
 */
import ReactDOM from 'react-dom';
import { Component } from 'react';
import { RRule, RRuleSet } from 'rrule';
import { __ } from '@eventespresso/i18n';

/**
 * Internal imports
 */
import './style.css';
import { RRulePatternEditor } from './rrule-pattern-editor';
import { GeneratedDatetimes } from './generated-datetimes';
import {
	PATTERN_TYPE_RECURRENCE,
	PATTERN_TYPE_EXCLUSION,
} from './constants';

/**
 * RecurringEventsManagerApp
 *
 * @constructor
 * @param {string} recurrenceRRuleString 	RRule string that defines the recurrences
 * @param {string} exclusionRRuleString 	RRule string that defines the exclusions
 */
class RecurringEventsManagerApp extends Component {
	constructor( props ) {
		super( props );
		// console.log( '' );
		// console.log( 'RecurringEventsManagerApp props: ' );
		// console.log( props );
		this.state = {
			recurrenceRRuleString: props.recurrenceRRuleString,
			exclusionRRuleString: props.exclusionRRuleString,
			datetimes: this.generateDatetimes( props.recurrenceRRuleString, props.exclusionRRuleString ),
		};
	}

	onRecurrenceChange = ( recurrenceRRuleString ) => {
		if (
			recurrenceRRuleString &&
			this.state.recurrenceRRuleString !== recurrenceRRuleString
		) {
			// console.log( '' );
			// console.log( ' *** RecurringEventsManagerApp.onRecurrenceChange() *** ' );
			this.setState(
				{
					recurrenceRRuleString: recurrenceRRuleString,
					datetimes: this.generateDatetimes( recurrenceRRuleString, this.state.exclusionRRuleString ),
				}
			);
		}
	};

	onExclusionChange = ( exclusionRRuleString ) => {
		if (
			exclusionRRuleString &&
			this.state.exclusionRRuleString !== exclusionRRuleString
		) {
			// console.log( '' );
			// console.log( ' *** RecurringEventsManagerApp.onExclusionChange() *** ' );
			this.setState(
				{
					exclusionRRuleString: exclusionRRuleString,
					datetimes: this.generateDatetimes( this.state.recurrenceRRuleString, exclusionRRuleString ),
				}
			);
		}
	};

	generateDatetimes = ( recurrenceRRuleString, exclusionRRuleString ) => {
		// console.log( ' *** RecurringEventsManagerApp.generateDatetimes() *** ' );
		const rruleSet = new RRuleSet();
		if ( recurrenceRRuleString ) {
			// console.log( ' > recurrenceRRuleString: ' + recurrenceRRuleString );
			rruleSet.rrule( RRule.fromString( recurrenceRRuleString ) );
		}
		if ( exclusionRRuleString ) {
			// console.log( ' > exclusionRRuleString: ' + exclusionRRuleString );
			rruleSet.exrule( RRule.fromString( exclusionRRuleString ) );
		}
		const datetimes = rruleSet.all( function( date, i ) {
			// console.log( ' > rruleSet.all() ' + i + ') date: ' + date.toString() );
			return i < 10;
		} );
		// console.log( ' > datetimes: ' );
		// console.log( datetimes );
		return datetimes;
	};

	// addDatetime = ( datetime ) => {
	// };

	// deleteDatetime = ( datetime ) => {
	// };

	render() {
		return (
			<div id="recurring-events" className="recurring-events-manager">
				<h1 id={ 'rem-form-h1' }>{ __( 'Event Datetime Recurrence Pattern Editor', 'event_espresso' ) }</h1>
				<RRulePatternEditor
					type={ PATTERN_TYPE_RECURRENCE }
					rruleString={ this.state.recurrenceRRuleString }
					onChange={ this.onRecurrenceChange }
				/>
				<RRulePatternEditor
					type={ PATTERN_TYPE_EXCLUSION }
					rruleString={ this.state.exclusionRRuleString }
					onChange={ this.onExclusionChange }
				/>
				<GeneratedDatetimes datetimes={ this.state.datetimes } />
			</div>
		);
	}
}

ReactDOM.render(
	<RecurringEventsManagerApp
		recurrenceRRuleString={ 'FREQ=DAILY;INTERVAL=1;COUNT=10' }
		exclusionRRuleString={ '' }
	/>,
	document.getElementById( 'eea-recurring-events-manager-app' )
);
