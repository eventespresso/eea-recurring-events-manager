/**
 * External imports
 */
import { Component } from 'react';
import RRule from 'rrule';
import RRuleGenerator from 'react-rrule-generator';
import { __ } from '@eventespresso/i18n';
import { SettingsPanel } from '@eventespresso/components';

/**
 * Internal dependencies
 */
import { PATTERN_TYPE_RECURRENCE } from './constants';

export class RRulePatternEditor extends Component {
	render() {
		const { type, rruleString, onChange } = this.props;
		const label = type === PATTERN_TYPE_RECURRENCE ?
			__( 'Event Dates', 'event_espresso' ) :
			__( 'Exclusions', 'event_espresso' );
		const rrule = rruleString ?
			RRule.fromString( rruleString ) :
			new RRule();
		const rruleText = rrule instanceof RRule && rrule.isFullyConvertibleToText() ?
			rrule.toText() :
			'none';
		// console.log( '' );
		// console.log( 'RRulePatternEditor.render() type: ' + type );
		// console.log( 'RRulePatternEditor.render() rruleString: ' + rruleString );
		// console.log( 'RRulePatternEditor.render() rruleText: ' + rruleText );
		// const rruleStringClass = rruleString ? ' display-rrule-string' : '';
		return (
			<div id={ type + '-form' } className={ 'repeats-div rem-form-row' }>
				<h3 className={ 'repeats-div-heading' }>{ label } : { rruleText }</h3>
				<SettingsPanel
					htmlId={ type + '-rrule-generator' }
					htmlClass={ 'rrule-generator' }
					panelOpen={ rruleText !== 'none' }
					hoverText={ `${ type } pattern` }
				>
					<RRuleGenerator
						value={ rruleString }
						onChange={ onChange }
						config={ {
							repeat: [ 'Yearly', 'Monthly', 'Weekly', 'Daily' ],
							end: [ 'After', 'On date' ],
							weekStartsOnSunday: true,
						} }
					/>
				</SettingsPanel>
			</div>
		);
	}
}

/*
				<div className={ 'rrule-string-div' + rruleStringClass }>
					<span>rruleString: </span>{ rruleString }
				</div>

*/
