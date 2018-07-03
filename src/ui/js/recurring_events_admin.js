import { RRule, RRuleSet } from './rrule';
import moment from 'moment';
import dialogHelper from 'dialogHelper';

jQuery( document ).ready( function( $ ) {
	const EE_REM = new function() {
		this.DATE_TIME_FORMAT = 'YYYY-MM-DD hh:mm a';
		this.RECURRING_EVENTS_FORM_HTML = '';
		this.RECURRING_EVENTS_TABLE = {};
		this.NO_EXLCUSIONS = {};
		this.RRULE_OPTIONS = {};
		this.RRULE = {};
		this.RRULE_SET = {};
		this.DAYS = {
			MO: RRule.MO,
			TU: RRule.TU,
			WE: RRule.WE,
			TH: RRule.TH,
			FR: RRule.FR,
			SA: RRule.SA,
			SU: RRule.SU,
		};
		this.DATETIMES = [];

		/**
		 * @function
		 */
		this.initialize = function() {
			EE_REM.RRULE_SET = new RRuleSet();
			EE_REM.RECURRING_EVENTS_TABLE = $( '#recurring_events' );
			EE_REM.NO_EXLCUSIONS = $( '#exclusion-freq-none' );
			// var now = new Date();
			// console_log('NOW', now.toString(), true);
			// console_log('function', 'EE_REM.initialize()', true);
			EE_REM.initializeDatepicker();
			EE_REM.resetRecurrenceFrequency();
			// reset day of month selector on load
			$( '.by-month-day' ).each( function() {
				EE_REM.setDayOfMonthOrdinalSuffix( $( this ) );
			} );
			// reset recurrence ends options on load
			const patterns = [ 'recurrence', 'exclusion' ];
			$.each( patterns, function( key, pattern ) {
				$( '.' + pattern + '-ends-option-input' ).each( function() {
					if ( $( this ).prop( 'checked' ) ) {
						EE_REM.recurrenceEndsOption( $( this ), pattern );
					}
				} );
			} );

			EE_REM.set_listeners();
			EE_REM.NO_EXLCUSIONS.trigger( 'change' );
			EE_REM.parseFreqDaily( 'recurrence' );
			EE_REM.setRRuleStartAndEndDates( 'recurrence' );
			EE_REM.displayRRule( 'recurrence' );
		};

		/**
		 * @function
		 */
		this.initializeDatepicker = function() {
			// console_log('function', 'EE_REM.initializeDatepicker()', true);
			$( '.rem-datepicker' ).datetimepicker( {
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm tt',
				ampm: true,
				separator: ' ',
				firstDay: 0,
				stepHour: 1,
				stepMinute: 5,
				hourGrid: 2,
				minuteGrid: 5,
				minDateTime: null,
				maxDateTime: null,
				hourMin: 0,
				minuteMin: 0,
				secondMin: 0,
				millisecMin: 0,
				hourMax: 23,
				minuteMax: 59,
				secondMax: 59,
				millisecMax: 999,
				numberOfMonths: 2,
				hour: null,
				minute: null,
				defaultDate: new Date(),
				showOn: 'focus',
				showSecond: false,
				showMillisec: false,
				showMicrosec: false,
				showTimezone: false,
			} );
		};

		/**
		 * @function
		 */
		this.set_listeners = function() {
			// console_log('function', 'EE_REM.set_listeners()', true);
			const patterns = [ 'recurrence', 'exclusion' ];
			// make sure end dates are always after start dates
			$.each( patterns, function( key, pattern ) {
				EE_REM.adjustEndDatesWhenStartDatesChange( pattern );
			} );
			// display recurrence options form
			EE_REM.RECURRING_EVENTS_TABLE.on( 'click', '.recurrence_freq_option', function() {
				// console_log( '>> CLICK <<', '.recurrence_freq_option', true );
				EE_REM.displaySelectedFrequency( $( this ), true );
			} );
			// display exclusion options form
			EE_REM.RECURRING_EVENTS_TABLE.on( 'click', '.exclusion_freq_option', function() {
				// console_log( '>> CLICK <<', '.exclusion_freq_option', true );
				EE_REM.displaySelectedFrequency( $( this ), false );
			} );
			// reset day of month selector on change
			EE_REM.RECURRING_EVENTS_TABLE.on( 'change', '.by-month-day', function() {
				// console_log( '>> CHANGE <<', '.by-month-day', true );
				EE_REM.setDayOfMonthOrdinalSuffix( $( this ) );
			} );
			// reset day of month selector on change
			EE_REM.RECURRING_EVENTS_TABLE.on( 'click', '.edit-pattern-link', function() {
				// console_log( '>> CLICK <<', '.edit-pattern-link', true );
				const editPattern = '#' + $( this ).data( 'pattern' );
				// console_log('editPattern', editPattern, false);
				$( editPattern ).toggle();
			} );
			// reset recurrence ends options on change
			EE_REM.RECURRING_EVENTS_TABLE.on( 'click', '.recurrence-ends-option-input', function() {
				// console_log( '>> CLICK <<', '.recurrence-ends-option-input', true );
				EE_REM.recurrenceEndsOption( $( this ), 'recurrence' );
			} );
			EE_REM.RECURRING_EVENTS_TABLE.on( 'click', '.exclusion-ends-option-input', function() {
				// console_log( '>> CLICK <<', '.exclusion-ends-option-input', true );
				EE_REM.recurrenceEndsOption( $( this ), 'exclusion' );
			} );
			EE_REM.RECURRING_EVENTS_TABLE.on( 'change', '.recurrence-monthly_freq-by-month-day', function() {
				// console_log( '>> CHANGE <<', '.recurrence-monthly_freq-by-month-day', true );
				$( '#recurrence-monthly-frequency-option-0-0' ).prop( 'checked', true );
			} );
			EE_REM.RECURRING_EVENTS_TABLE.on( 'change', '.exclusion-monthly_freq-by-month-day', function() {
				// console_log( '>> CHANGE <<', '.exclusion-monthly_freq-by-month-day', true );
				$( '#exclusion-monthly-frequency-option-0-0' ).prop( 'checked', true );
			} );
			EE_REM.RECURRING_EVENTS_TABLE.on( 'change', '.rem-input', function() {
				$.each( patterns, function( key, pattern ) {
					// console_log(' . . . DETECTING .rem-input changes for', pattern, true);
					EE_REM.RRULE_OPTIONS = {};
					EE_REM.parsePatternFrequency( pattern );
					EE_REM.displayRRule( pattern );
					EE_REM.generateDatetimes( pattern );
				} );
				EE_REM.displayGeneratedDatetimes();
			} );
		};

		/**
		 * @function
		 * @param {string} pattern 	whether "recurrence" or "exclusion"
		 */
		this.adjustEndDatesWhenStartDatesChange = function( pattern ) {
			const $startInput = $( '#' + pattern + '-dtstart' );
			$startInput.on( 'change', function() {
				// console_log( '>> CHANGE <<', '#' + pattern + '-dtstart', true );
				const $endInput = $( '#' + pattern + '-ends-until' ),
					startDate = moment( $startInput.val(), EE_REM.DATE_TIME_FORMAT );
				let endDate = moment( $endInput.val(), EE_REM.DATE_TIME_FORMAT );
				// console_log( '>> startDate', startDate.format( EE_REM.DATE_TIME_FORMAT ), false );
				// console_log( '>> endDate', endDate.format( EE_REM.DATE_TIME_FORMAT ), false );
				// console_log( '>> moment(startDate).isAfter(endDate)', moment( startDate ).isAfter( endDate ), false );
				if ( moment( startDate ).isAfter( endDate ) ) {
					endDate = startDate.add( 6, 'd' );
					// console_log( '>> NEW endDate', endDate.format( EE_REM.DATE_TIME_FORMAT ), false );
					$endInput.val( endDate.format( EE_REM.DATE_TIME_FORMAT ) );
				}
			} );
		};

		/**
		 * adds one week to end dates if an adjusted start date is now after end date
		 *
		 * @function
		 * @param {string} pattern 	whether "recurrence" or "exclusion"
		 */
		this.parsePatternFrequency = function( pattern ) {
			if ( ! ( pattern === 'exclusion' && EE_REM.NO_EXLCUSIONS.prop( 'checked' ) ) ) {
				if ( $( '#' + pattern + '-freq-yearly' ).prop( 'checked' ) ) {
					// console_log( '** Generate RRULE_OPTIONS for YEARLY ' + pattern + ' **', '', true );
					EE_REM.parseFreqYearly( pattern );
				}
				if ( $( '#' + pattern + '-freq-monthly' ).prop( 'checked' ) ) {
					// console_log( '** Generate RRULE_OPTIONS for MONTHLY ' + pattern + ' **', '', true );
					EE_REM.parseFreqMonthly( pattern );
				}
				if ( $( '#' + pattern + '-freq-weekly' ).prop( 'checked' ) ) {
					// console_log( '** Generate RRULE_OPTIONS for WEEKLY ' + pattern + ' **', '', true );
					EE_REM.parseFreqWeekly( pattern );
				}
				if ( $( '#' + pattern + '-freq-daily' ).prop( 'checked' ) ) {
					// console_log( '** Generate RRULE_OPTIONS for DAILY ' + pattern + ' **', '', true );
					EE_REM.parseFreqDaily( pattern );
				}
				EE_REM.setRRuleStartAndEndDates( pattern );
			}
		};

		/**
		 * adds one week to end dates if an adjusted start date is now after end date
		 *
		 * @function
		 * @param {string} pattern 	whether "recurrence" or "exclusion"
		 */
		this.setRRuleStartAndEndDates = function( pattern ) {
			// console_log('function', 'EE_REM.setRRuleStartAndEndDates(pattern = ' + pattern + ')', true);
			let datetime = EE_REM.parseDateTime( $( '#' + pattern + '-dtstart' ), true );
			if ( datetime !== null ) {
				EE_REM.RRULE_OPTIONS.dtstart = datetime;
				// console_log('RRULE_OPTIONS.dtstart', EE_REM.RRULE_OPTIONS.dtstart.toString(), false);
			}
			// recurrence-ends-option-input-until
			const $until = $( '#' + pattern + '-ends-option-input-until' );
			// console_log('$until id', $until.attr('id'), false);
			// console_log('$until.prop(\'checked\')', $until.prop('checked'), false);
			if ( $until.prop( 'checked' ) ) {
				// console_log('$until datepicker id', pattern + '-ends-until', false);
				datetime = EE_REM.parseDateTime( $( '#' + pattern + '-ends-until' ), true );
				if ( datetime !== null ) {
					EE_REM.RRULE_OPTIONS.until = datetime;
					// console_log('RRULE_OPTIONS.until', EE_REM.RRULE_OPTIONS.until.toString(), false);
				} else {
					// console_log( 'INVALID datetime', datetime, false );
				}
			} else {
				EE_REM.RRULE_OPTIONS.count = $( '#' + pattern + '-ends-count' ).val();
				// console_log('RRULE_OPTIONS.count', EE_REM.RRULE_OPTIONS.count, false);
			}
		};

		/**
		 * @function
		 * @param {Object} $input  		jQuery selector for input to parse for date
		 * @param {boolean} recurse 	whether to revisit function after "clicking" on input
		 * @return {Date} A Date Object
		 */
		this.parseDateTime = function( $input, recurse ) {
			// console_log('function', 'EE_REM.parseDateTime()', true);
			const dateTimeString = $input.val();
			// console_log('$input.attr(id)', $input.attr('id'), false);
			// console_log('dateTimeString', dateTimeString, false);
			// verify that a date has actually been picked
			if ( dateTimeString === '' || dateTimeString === null ) {
				// NO? kk, we'll if we haven't been down this path already...
				if ( recurse === true ) {
					// force input to have some kind of value
					$input.trigger( 'click' );
					// then hit this function again, but turn recursion off
					return EE_REM.parseDateTime( $input, false );
				}
				// recursion is off, so just return nothing
				return null;
			}
			let date;
			if ( moment( dateTimeString ).isValid() ) {
				date = moment( dateTimeString );
			} else if ( moment( dateTimeString, EE_REM.DATE_TIME_FORMAT ).isValid() ) {
				date = moment( dateTimeString, EE_REM.DATE_TIME_FORMAT );
			} else {
				// console_log( 'INVALID DATE STRING', dateTimeString, false );
			}
			if ( moment.isMoment( date ) && date.isValid() ) {
				// console_log('VALID Date', date.toString(), false);
				return date.toDate();
			}
			// console_log( 'INVALID DATE', date.toString(), false );
			return null;
		};

		/**
		 * @function
		 * @param {string} pattern 	whether "recurrence" or "exclusion"
		 */
		this.displayRRule = function( pattern ) {
			// console_log( 'function', 'EE_REM.displayRRule(pattern = ' + pattern + ')', true );
			let ruleText = 'none';
			// console_log( 'EE_REM.RRULE_OPTIONS.freq', EE_REM.RRULE_OPTIONS.freq, false );
			if ( typeof EE_REM.RRULE_OPTIONS.freq !== 'undefined' && EE_REM.RRULE_OPTIONS.freq > 0 ) {
				EE_REM.RRULE = new RRule( EE_REM.RRULE_OPTIONS, true );
				ruleText = EE_REM.RRULE.toText();
			} else {
				if ( ! ( pattern === 'exclusion' && EE_REM.NO_EXLCUSIONS.prop( 'checked' ) ) ) {
					// console_log( '', 'RESET EE_REM.RRULE for ' + pattern + ' pattern', false );
					EE_REM.dumpRRuleOptionOptions();
				}
				EE_REM.RRULE = {};
			}
			if ( pattern === 'recurrence' && EE_REM.RRULE instanceof RRule ) {
				const ruleString = EE_REM.RRULE.toString();
				$( '#rem-recurrence-string-display' ).html( ruleString );
				$( '#rem-recurrence-string' ).val( ruleString );
			}
			if ( ruleText !== 'none' ) {
				const starting = moment( $( '#' + pattern + '-dtstart' ).val(), EE_REM.DATE_TIME_FORMAT );
				ruleText = 'Starting on ' + starting.format( 'MMMM D, YYYY' ) + ', repeats ' + ruleText;
			}
			$( '#' + pattern + '-desc' ).html( ruleText );
		};

		/**
		 * @function
		 * @param {string} pattern 	whether "recurrence" or "exclusion"
		 */
		this.generateDatetimes = function( pattern ) {
			// console_log('function', 'EE_REM.generateDatetimes(pattern = ' + pattern + ')', true);
			// console_log('typeof EE_REM.RRULE.freq', typeof EE_REM.RRULE.freq, false);
			// console_log('EE_REM.RRULE_OPTIONS', EE_REM.RRULE_OPTIONS, false);
			let index = -1;
			if ( EE_REM.RRULE instanceof RRule === false ) {
				if ( ! ( pattern === 'exclusion' && EE_REM.NO_EXLCUSIONS.prop( 'checked' ) ) ) {
					// console_log(
					// 	'ERROR!!! Could not generate Datetimes because EE_REM.RRULE is not a valid instance of RRule',
					// 	'',
					// 	false
					// );
				}
				EE_REM.debugDump( true );
				return;
			}
			const ruleString = EE_REM.RRULE.toString();
			$( '#rem-' + pattern + '-string-display' ).html( ruleString );
			$( '#rem-' + pattern + '-string' ).val( ruleString );
			EE_REM.debugDump( false );
			const dates = EE_REM.getDatetimes( EE_REM.RRULE, ruleString, true );
			// console_log( 'generateDatetimes', 'pattern: ' + pattern, false );
			if ( pattern === 'recurrence' ) {
				EE_REM.DATETIMES = dates;
				// $.each(EE_REM.DATETIMES, function(key, date) {
				//     console_log(pattern + ' date', date.toString(), false);
				// });
			} else {
				$.each( dates, function( key, date ) {
					// console_log('exclusion', date.toString(), false);
					function datesMatch( element ) {
						return date.toString() === element.toString();
					}

					index = EE_REM.DATETIMES.findIndex( datesMatch );
					if ( index > -1 ) {
						// console_log('REMOVED', date.toString(), false);
						EE_REM.DATETIMES.splice( index, 1 );
					}
				} );
			}
			EE_REM.debugDump( false );
		};

		/**
		 * @function
		 * @param {Object} rrule
		 * @param {string} ruleString
		 * @param {boolean} recurse
		 * @return {Date[]} 						An array of Dates
		 */
		this.getDatetimes = function( rrule, ruleString, recurse ) {
			rrule = EE_REM.RRULE instanceof RRule ? rrule : EE_REM.RRULE;
			recurse = typeof recurse !== 'undefined' ? recurse : false;
			rrule = RRule.fromString( ruleString );
			let dates = rrule.all( function( date, i ) {
				// console.log(JSON.stringify(i + ') date: ' + date, null, 4));
				return i < 60 || (
					typeof rrule.options.count !== 'undefined' &&
					rrule.options.count > 0 &&
					rrule.options.count < 365
				);
			} );
			// console_log( '#1 dates.length', dates.length, false );
			if ( dates.length < 1 ) {
				if ( recurse === true ) {
					return EE_REM.getDatetimes( RRule.fromString( ruleString ), ruleString, false );
				}
				// try again without custom iterator
				dates = rrule.all();
				// console_log( '#2 dates.length', dates.length, false );
				if ( dates.length < 1 ) {
					// console_log( 'ERROR!!! Could not generate Datetimes for RRule: ', ruleString, true );
					// console_log( 'rrule.toText()', rrule.toText(), false );
					// console_log( 'rrule.options.count', rrule.options.count, false );
					// console_log( 'rrule.options.constructor.name', rrule.options.constructor.name, false );
					EE_REM.debugDump( true );
					return [];
				}
			}
			EE_REM.RRULE = rrule;
			return dates;
		};

		/**
		 * @function
		 */
		this.displayGeneratedDatetimes = function() {
			// console_log('function', 'EE_REM.displayGeneratedDatetimes()', true);
			let html = '',
				dtCount = 1;
				// datetimes_json = {},
			const timestampsJson = {};
			$.each( EE_REM.DATETIMES, function( key, date ) {
				// console_log('date', date.toString(), false);
				html += '<div class="rem-generated-datetime">' + dtCount + ') ' + date.toString() + '</div>';
				// datetimes_json[dtCount] = date.toString();
				timestampsJson[ dtCount ] = date.getTime();
				dtCount++;
			} );
			$( '#rem-generated-datetimes' ).html( html );
			// $('#rem-generated-datetimes-json').val(JSON.stringify(datetimes_json));
			$( '#rem-generated-datetimes-json' ).val( JSON.stringify( timestampsJson ) );
		};

		/**
		 * @function
		 * @param {Object} $freq
		 * @param {boolean} recurrence
		 */
		this.displaySelectedFrequency = function( $freq, recurrence ) {
			// console_log('function', 'EE_REM.displaySelectedFrequency()', true);
			if ( recurrence ) {
				$( '.recurrence_freq' ).hide();
				$( '.recurrence_freq_option_label' ).removeClass( 'active_freq_option' );
			} else {
				$( '.exclusion_freq' ).hide();
				$( '.exclusion_freq_option_label' ).removeClass( 'active_freq_option' );
			}
			const optionId = $freq.attr( 'id' );
			const target = '#' + optionId + '-section';
			const label = '#' + optionId + '-lbl';
			// console_log('displaySelectedFrequency target', target, false);
			$( target ).show();
			$( label ).addClass( 'active_freq_option' );
		};

		/**
		 * @function
		 */
		this.resetRecurrenceFrequency = function() {
			// console_log('function', 'EE_REM.resetRecurrenceFrequency()', true);
			$( '.recurrence_freq_option' ).each( function() {
				// console_log($(this).attr('id') + ' checked', $(this).prop('checked'), false);
				if ( $( this ).prop( 'checked' ) ) {
					EE_REM.displaySelectedFrequency( $( this ), true );
				}
			} );
			$( '.exclusion_freq_option' ).each( function() {
				if ( $( this ).prop( 'checked' ) ) {
					EE_REM.displaySelectedFrequency( $( this ), false );
				}
			} );
		};

		/**
		 * @function
		 * @param {Object} $dayOfMonth
		 */
		this.setDayOfMonthOrdinalSuffix = function( $dayOfMonth ) {
			// console_log('function', 'EE_REM.setDayOfMonthOrdinalSuffix()', true);
			const dayOfMonth = $dayOfMonth.val();
			const dayOfMonthOrdinalSuffix = '#' + $dayOfMonth.data( 'dayOfMonth' );
			// console_log('dayOfMonth', dayOfMonth, false);
			// console_log('dayOfMonthOrdinalSuffix', dayOfMonthOrdinalSuffix, false);
			if ( dayOfMonth === '1' || dayOfMonth === '21' || dayOfMonth === '31' ) {
				$( dayOfMonthOrdinalSuffix ).html( 'st' );
			} else if ( dayOfMonth === '2' || dayOfMonth === '22' ) {
				$( dayOfMonthOrdinalSuffix ).html( 'nd' );
			} else if ( dayOfMonth === '3' || dayOfMonth === '23' ) {
				$( dayOfMonthOrdinalSuffix ).html( 'rd' );
			} else {
				$( dayOfMonthOrdinalSuffix ).html( 'th' );
			}
		};

		/**
		 * @function
		 * @param {Object} $endsOption
		 * @param {string} pattern
		 */
		this.recurrenceEndsOption = function( $endsOption, pattern ) {
			// console_log('function', 'EE_REM.recurrenceEndsOption()', true);
			$( '.' + pattern + '-ends-options' ).hide();
			const endOption = '#' + pattern + '-ends-option-' + $endsOption.val();
			// console_log('endOption', endOption, false);
			$( endOption ).show();
		};

		/**
		 * @function
		 * @param {string} pattern 	whether "recurrence" or "exclusion"
		 * @param {string} frequency
		 */
		this.setInterval = function( pattern, frequency ) {
			// console_log('** function', 'EE_REM.setInterval() **', true);
			// console_log('pattern', pattern, false);
			// console_log('frequency', frequency, false);
			const interval = $( '#' + pattern + '-' + frequency + '-interval' ).val();
			// console_log('interval', interval, false);
			if ( interval /*&& typeof EE_REM.RRULE_OPTIONS.interval === 'undefined'*/ ) {
				EE_REM.RRULE_OPTIONS.interval = interval;
				// $('.' + pattern + '-interval').val(interval);
				// console_log('.' + pattern + '-interval', $('.' + pattern + '-interval').val(), false);
				let every = frequency.replace( 'ly', '' );
				every = every === 'dai' ? 'day' : every;
				// console_log('every', every, false);
				$( '.' + pattern + '-every-span' ).html( every );
			}
		};

		/**
		 * @function
		 */
		this.clearFrequencyOptions = function() {
			delete EE_REM.RRULE_OPTIONS.freq;
			delete EE_REM.RRULE_OPTIONS.byweekday;
			delete EE_REM.RRULE_OPTIONS.bymonthday;
			delete EE_REM.RRULE_OPTIONS.bymonth;
			// if (typeof EE_REM.RRULE.options !== 'undefined' && typeof EE_REM.RRULE.options.bymonthday !== 'undefined') {
			//     EE_REM.RRULE.options.bymonthday = null;
			// }
		};

		/**
		 * @function
		 * @param {string} pattern 	whether "recurrence" or "exclusion"
		 */
		this.parseFreqDaily = function( pattern ) {
			// console_log( 'function', 'EE_REM.parseFreqDaily()', true );
			EE_REM.clearFrequencyOptions();
			if ( typeof EE_REM.RRULE_OPTIONS.freq === 'undefined' ) {
				EE_REM.RRULE_OPTIONS.freq = RRule.DAILY;
			}
			EE_REM.setInterval( pattern, 'daily' );
		};

		/**
		 * @function
		 * @param {string} pattern 	whether "recurrence" or "exclusion"
		 */
		this.parseFreqWeekly = function( pattern ) {
			// console_log( 'function', 'EE_REM.parseFreqWeekly()', true );
			// console_log('pattern', pattern, false);
			// console_log('$("#recurrence-by-weekday-mo").val()', $("#recurrence-by-weekday-mo").val(), false);
			// console_log('$("#recurrence-by-weekday-mo").val()', $("#recurrence-by-weekday-mo").val(), false);
			EE_REM.clearFrequencyOptions();
			$.each( EE_REM.DAYS, function( day, weekday ) {
				// console_log('day', day, false);
				// console_log('weekday', weekday, false);
				const byWeekdayInputId = '#' + pattern + '-by-weekday-' + day.toLowerCase();
				const $byWeekdayInput = $( byWeekdayInputId );
				// console_log('byWeekdayInputId', byWeekdayInputId, false);
				// console_log('$byWeekdayInput.val()', $byWeekdayInput.val(), false);
				// recurrence-by-weekday-mo
				if ( $byWeekdayInput.prop( 'checked' ) ) {
					if ( typeof EE_REM.RRULE_OPTIONS.byweekday === 'undefined' ) {
						EE_REM.RRULE_OPTIONS.byweekday = [];
					}
					EE_REM.RRULE_OPTIONS.byweekday.push( weekday );
				}
			} );
			if ( typeof EE_REM.RRULE_OPTIONS.freq === 'undefined' ) {
				EE_REM.RRULE_OPTIONS.freq = RRule.WEEKLY;
			}
			EE_REM.setInterval( pattern, 'weekly' );
		};

		/**
		 * @function
		 * @param {string} pattern 	whether "recurrence" or "exclusion"
		 */
		this.parseFreqMonthly = function( pattern ) {
			// console_log( 'function', 'EE_REM.parseFreqMonthly()', true );
			EE_REM.clearFrequencyOptions();
			if ( $( '#' + pattern + '-monthly-frequency-option-0-0' ).prop( 'checked' ) ) {
				EE_REM.RRULE_OPTIONS.bymonthday = [];
				EE_REM.RRULE_OPTIONS.bymonthday.push( $( '#' + pattern + '-monthly_freq-by-month-day' ).val() );
			} else if ( $( '#' + pattern + '-monthly-frequency-option-1-1' ).prop( 'checked' ) ) {
				EE_REM.RRULE_OPTIONS.byweekday = [];
				const nthDayOfWeek = $( '#' + pattern + '-monthly_freq-by-nth-day-of-week' ).val();
				EE_REM.RRULE_OPTIONS.bysetpos = parseInt( nthDayOfWeek );
				// console_log('nthDayOfWeek', nthDayOfWeek, false);
				const weekday = EE_REM.DAYS[ $( '#' + pattern + '-monthly_freq-day-of-week' ).val() ];
				// console_log('weekday', weekday, false);
				EE_REM.RRULE_OPTIONS.byweekday.push( weekday );
			}
			if ( typeof EE_REM.RRULE_OPTIONS.freq === 'undefined' ) {
				EE_REM.RRULE_OPTIONS.freq = RRule.MONTHLY;
			}
			EE_REM.setInterval( pattern, 'monthly' );
		};

		/**
		 * @function
		 * @param {string} pattern 	whether "recurrence" or "exclusion"
		 */
		this.parseFreqYearly = function( pattern ) {
			// console_log( 'function', 'EE_REM.parseFreqYearly()', true );
			EE_REM.clearFrequencyOptions();
			let month;
			for ( month = 1; month < 13; month++ ) {
				// console_log('month', month, false);
				const $monthInput = $( '#' + pattern + '-yearly_freq-by-month-' + month );
				// console_log('$monthInput.val()', $monthInput.val(), false);
				if ( $monthInput.prop( 'checked' ) ) {
					if ( typeof EE_REM.RRULE_OPTIONS.bymonth === 'undefined' ) {
						EE_REM.RRULE_OPTIONS.bymonth = [];
					}
					EE_REM.RRULE_OPTIONS.bymonth.push( $monthInput.val() );
				}
			}
			if ( typeof EE_REM.RRULE_OPTIONS.freq === 'undefined' ) {
				EE_REM.RRULE_OPTIONS.freq = RRule.YEARLY;
			}
			EE_REM.setInterval( pattern, 'yearly' );
		};

		/**
		 * @function
		 * @param {boolean} display
		 */
		this.debugDump = function( display ) {
			if ( display ) {
				EE_REM.dumpRRuleOptionOptions();
				EE_REM.dumpRRuleOptions();
				// console_log( 'EE_REM.RRULE.origOptions', EE_REM.RRULE.origOptions, false );
			}
		};

		/**
		 * @function
		 */
		this.dumpRRuleOptions = function() {
			// console_log( '    @function', 'EE_REM.dumpRRuleOptions()', true );
			const properties = [ 'freq', 'interval', 'dtstart', 'until', 'count', 'byweekday', 'bymonthday', 'bymonth' ];
			$.each( properties, function( index, property ) {
				if (
					typeof EE_REM.RRULE.options !== 'undefined' &&
					typeof EE_REM.RRULE.options[ property ] !== 'undefined' &&
					EE_REM.RRULE.options[ property ] !== null
				) {
					// console_log( '     > EE_REM.RRULE.options.' + property, EE_REM.RRULE.options[ property ].toString(), false );
				}
			} );
		};

		/**
		 * @function
		 */
		this.dumpRRuleOptionOptions = function() {
			if ( EE_REM.RRULE_OPTIONS.constructor === Object && Object.keys( EE_REM.RRULE_OPTIONS ).length > 0 ) {
				// console_log( '    @function', 'EE_REM.dumpRRuleOptionOptions()', true );
				const properties = [
					'freq',
					'interval',
					'dtstart',
					'until',
					'count',
					'byweekday',
					'bymonthday',
					'bymonth',
				];
				$.each( properties, function( index, property ) {
					if (
						typeof EE_REM.RRULE_OPTIONS[ property ] !== 'undefined' &&
						EE_REM.RRULE_OPTIONS[ property ] !== null
					) {
						// console_log( '     > EE_REM.RRULE_OPTIONS.' + property, EE_REM.RRULE_OPTIONS[ property ].toString(), false );
					}
				} );
			}
		};
	};

	$( '#datetime-editing-dtts-table' ).on( 'click', '.ee-edit-datetime-recurrence', function() {
		let content = '';
		const $recurringEventsPatternsDiv = $( '#event-datetime-recurrence-patterns-div' );
		// datetime_id =  $(this).data('datetime-id');
		EE_REM.RECURRING_EVENTS_FORM_HTML = $recurringEventsPatternsDiv.html();
		$recurringEventsPatternsDiv.html( content );
		content += '<form id="event-datetime-recurrence-patterns-form">';
		content += EE_REM.RECURRING_EVENTS_FORM_HTML;
		content += '</form>';
		dialogHelper.displayModal().addContent( content );
		EE_REM.initialize();
	} );

	$( 'body' ).on( 'eeModalCloseEvent', function() {
		$( '#event-datetime-recurrence-patterns-div' ).html( EE_REM.RECURRING_EVENTS_FORM_HTML );
	} );
} );
