/**
 * External imports
 */
import { Component } from 'react';
import RRule from 'rrule';
import RRuleGenerator from '../../../../react-rrule-generator/src/lib';
import { __ } from '@eventespresso/i18n';
import { PanelBody, PanelRow } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { PATTERN_TYPE_RECURRENCE } from './constants';

export class RRulePatternEditor extends Component {
	/**
	 * @function
	 * @param {string} id
	 * @param {string} type
	 * @param {string} rruleString
	 * @param {Function} onChange
	 * @return {string} rendered reset button
	 */
	getPatternEditor = ( id, type, rruleString, onChange ) => {
		return (
			<RRuleGenerator
				id={ `rrule-${ type }-${ id }` }
				value={ rruleString }
				onChange={ onChange }
				config={ {
					repeat: [
						'Yearly',
						'Monthly',
						'Weekly',
						'Daily',
					],
					end: [ 'After', 'On date' ],
					weekStartsOnSunday: true,
					enableTimepicker: false,
					hideStart: false,
				} }
			/>
		);
	};

	/**
	 * @function
	 * @param {string} label
	 * @param {Function} onChange
	 * @return {string} rendered reset button
	 */
	getPatternEditorControls = ( label, onChange ) => {
		return (
			<button
				id={ 'rem-cancel-button' }
				className={ 'button button-secondary' }
				value={ null }
				onClick={ onChange }
			>
				{ __( 'Reset ' + label, 'event_espresso' ) }
			</button>
		);
	};

	render() {
		const { id, type, rruleString, onChange, initialOpen = false } = this.props;
		const label = type === PATTERN_TYPE_RECURRENCE ?
			__( 'Recurrence Pattern', 'event_espresso' ) :
			__( 'Exclusion Pattern', 'event_espresso' );
		const rrule = rruleString ?
			RRule.fromString( rruleString ) :
			new RRule();
		const rruleText = rruleString &&
		rrule instanceof RRule &&
		rrule.isFullyConvertibleToText() ?
			rrule.toText() :
			'none';
		// console.log( '' );
		// console.log( 'RRulePatternEditor.render() type: ' + type );
		// console.log( 'RRulePatternEditor.render() rruleString: ' + rruleString );
		// console.log( 'RRulePatternEditor.render() rruleText: ' + rruleText );
		// console.log( 'RRulePatternEditor.render() panelOpen: ' + ( rruleText !== 'none' ) );
		return (
			<PanelBody
				title={ label + ' : ' + rruleText }
				className={ `${ type }-rrule-generator-wrapper rrule-generator-wrapper` }
				initialOpen={ initialOpen || rruleText !== 'none' }
			>
				<PanelRow className={ `${ type }-form rem-form-row` }>
					{ this.getPatternEditor( id, type, rruleString, onChange ) }
				</PanelRow>
				<PanelRow className={ `${ type }-form-controls rrule-generator-form-controls rem-form-row` }>
					{ this.getPatternEditorControls( label, onChange ) }
					<div className="clear"></div>
				</PanelRow>
			</PanelBody>
		);
	}
}
