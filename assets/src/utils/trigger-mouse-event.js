import { Component } from 'react';

class TriggerMouseEvent extends Component {
	handleClick = ( event ) => {
		console.log( 'TriggerMouseEvent', event.target );
		const mouseEvent = event.target.dataset.mouseEvent || 'mouseover';
		const elementId = event.target.dataset.elementId || '';
		const elementClass = event.target.dataset.elementClass || '';
		let target = document.getElementById( elementId );
		if ( target === null ) {
			console.log(
				'The target element with an ID of' +
				elementId + ' was not found',
			);
			return;
		}
		if ( elementClass ) {
			target = target.getElementsByClassName( elementClass )[ 0 ];
			if ( target === null ) {
				console.log(
					'The target element with a class of' +
					elementClass + ' was not found' );
				return;
			}
		}
		target.dispatchEvent(
			new MouseEvent(
				mouseEvent,
				{
					view: window,
					bubbles: true,
					cancelable: true,
				},
			),
		);
	};

	render() {
		const {
			elementId,
			elementClass,
			label = 'Trigger Mouse Event',
			mouseEvent = 'mouseover',
		} = this.props;
		return (
			<button
				data-mouse-event={ mouseEvent }
				data-element-id={ elementId }
				data-element-class={ elementClass }
				onClick={ this.handleClick }
			>
				{ label }
			</button>
		);
	}
}

export default TriggerMouseEvent;
