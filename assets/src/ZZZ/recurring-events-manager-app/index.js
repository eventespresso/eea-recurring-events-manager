/**
 * External imports
 */
import ReactDOM from 'react-dom';
import { Component } from 'react';
import { __ } from '@eventespresso/i18n';
import { hooks } from '@eventespresso/eejs';
import { SidebarMenuItem } from '@eventespresso/editor';

/**
 * Internal imports
 */
import './style.css';
import { default as EditDatetimeRecurrence } from './edit-datetime-recurrence';

/**
 * RecurringEventsManagerApp
 *
 * @constructor
 */
class RecurringEventsManagerApp extends Component {
	constructor( props ) {
		super( props );
		// console.log( '' );
		// console.log( 'RecurringEventsManagerApp props: ', props );
		this.state = {
			editorOpen: false,
			eventDate: {},
		};
		const self = this;
		hooks.addFilter(
			'FHEE__EditorDates__EditorDateSidebar__SidebarMenuItems',
			'RecurringEventsManagerApp',
			function( sidebarLinks, eventDate ) {
				sidebarLinks.push( self.sidebarMenuItem( eventDate, sidebarLinks.length ) );
				return sidebarLinks;
			}
		);
	}

	/**
	 * @function
	 * @param {Object} eventDate        JSON object defining the Event Date
	 * @param {string} index
	 * @return {string}        rendered sidebar menu item
	 */
	sidebarMenuItem = ( eventDate, index ) => {
		const data = { eventDate };
		return <SidebarMenuItem
			index={ index }
			title={ __( 'edit event date recurrence pattern', 'event_espresso' ) }
			id={ 'edit-recurrence-' + eventDate.id }
			htmlClass={ 'edit-recurrence' }
			dashicon={ 'image-rotate' }
			onClick={ ( event ) => this.editDatetimeRecurrence( event, data ) }
		/>;
	};

	/**
	 * @function
	 * @param {Object} event
	 * @param {Object} data    JSON object defining the Event Date
	 */
	editDatetimeRecurrence = ( event, data ) => {
		event.preventDefault();
		this.setState( ( prevState ) => (
			{
				editorOpen: ! prevState.editorOpen,
				eventDate: data.eventDate,
			}
		) );
	};

	/**
	 * @function
	 * @param {Object} event
	 */
	toggleEditor = event => {
		event.preventDefault();
		this.setState( prevState => ( { editorOpen: ! prevState.editorOpen } ) );
	};

	render() {
		return <EditDatetimeRecurrence
			editorOpen={ this.state.editorOpen }
			eventDate={ this.state.eventDate }
			toggleEditor={ this.toggleEditor }
		/>;
	}
}

ReactDOM.render(
	<RecurringEventsManagerApp />,
	document.getElementById( 'eea-recurring-events-manager-app' )
);

