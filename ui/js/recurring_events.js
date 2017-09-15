jQuery(document).ready(function($) {

	var EE_REM = new function() {

		this.RECURRING_EVENTS_TABLE = $('#recurring_events');

		this.RRULE_OPTIONS = {};

		this.RRULE = {};

		this.DAYS = {
			'MO': RRule.MO,
			'TU': RRule.TU,
			'WE': RRule.WE,
			'TH': RRule.TH,
			'FR': RRule.FR,
			'SA': RRule.SA,
			'SU': RRule.SU
		};

		this.DATETIMES = [];

		// this.every = '';

		/**
		 * @function
		 */
		this.initialize = function() {
			console_log('function', 'EE_REM.initialize()', true);
			EE_REM.initializeDatepicker();
			EE_REM.resetRecurrenceFrequency();
			// reset day of month selector on load
			$('.by-month-day').each(function() {
				EE_REM.setDayOfMonthOrdinalSuffix($(this));
			});
			// reset recurrence ends options on load
			$('.recurrence-ends-option-input').each(function() {
				if ($(this).prop('checked')) {
					EE_REM.recurrenceEndsOption($(this));
				}
			});
			EE_REM.set_listeners();
			$('#exclusion-freq-none').trigger('change');
			EE_REM.displayRRule();
		};

		/**
		 * @function
		 */
		this.initializeDatepicker = function() {
			console_log('function', 'EE_REM.initializeDatepicker()', true);
			$('.rem-datepicker').datetimepicker({
				dateFormat:     'yy-mm-dd',
				timeFormat:     'h:mm tt',
				ampm:           true,
				separator:      ' ',
				firstDay:       0,
				stepHour:       1,
				stepMinute:     5,
				hourGrid:       2,
				minuteGrid:     5,
				minDateTime:    null,
				maxDateTime:    null,
				hourMin:        0,
				minuteMin:      0,
				secondMin:      0,
				millisecMin:    0,
				hourMax:        23,
				minuteMax:      59,
				secondMax:      59,
				millisecMax:    999,
				numberOfMonths: 2,
				hour:           null,
				minute:         null,
				defaultDate:    null,
				showOn:         'focus',
				showSecond:     false,
				showMillisec:   false,
				showMicrosec:   false,
				showTimezone:   false
			});
		};

		/**
		 * @function
		 */
		this.set_listeners =  function() {
			console_log('function', 'EE_REM.set_listeners()', true);
			// display recurrence options form
			EE_REM.RECURRING_EVENTS_TABLE.on('click', '.recurrence_freq_option', function() {
				EE_REM.displaySelectedFrequency($(this), true);
			});
			// display exclusion options form
			EE_REM.RECURRING_EVENTS_TABLE.on('click', '.exclusion_freq_option', function() {
				EE_REM.displaySelectedFrequency($(this), false);
			});
			// reset day of month selector on change
			EE_REM.RECURRING_EVENTS_TABLE.on('change', '.by-month-day', function() {
				EE_REM.setDayOfMonthOrdinalSuffix($(this));
			});
			// reset day of month selector on change
			EE_REM.RECURRING_EVENTS_TABLE.on('click', '.edit-pattern-link', function() {
				var edit_pattern = '#' + $(this).data('pattern');
				// console_log('edit_pattern', edit_pattern, false);
				$(edit_pattern).toggle();
			});
			// reset recurrence ends options on change
			EE_REM.RECURRING_EVENTS_TABLE.on('click', '.recurrence-ends-option-input', function() {
				EE_REM.recurrenceEndsOption($(this), 'recurrence');
			});
			EE_REM.RECURRING_EVENTS_TABLE.on('click', '.exclusion-ends-option-input', function() {
				EE_REM.recurrenceEndsOption($(this), 'exclusion');
			});
			EE_REM.RECURRING_EVENTS_TABLE.on('change', '.recurrence-monthly_freq-by-month-day', function() {
				$('#recurrence-monthly-frequency-option-0-0').prop('checked', true);
			});
			EE_REM.RECURRING_EVENTS_TABLE.on('change', '.exclusion-monthly_freq-by-month-day', function() {
				$('#exclusion-monthly-frequency-option-0-0').prop('checked', true);
			});

			EE_REM.RECURRING_EVENTS_TABLE.on('change', '.rem-input', function() {
				var patterns = ['recurrence', 'exclusion'];
				$.each(patterns, function(key, pattern) {
					EE_REM.RRULE_OPTIONS = {};
					if ($('#' + pattern + '-freq-yearly').prop('checked')) {
						// console_log('Generate RRULE_OPTIONS for', 'yearly ' + pattern, false);
						EE_REM.parseFreqYearly(pattern);
					}
					if ($('#' + pattern + '-freq-monthly').prop('checked')) {
						// console_log('Generate RRULE_OPTIONS for', 'monthly ' + pattern, false);
						EE_REM.parseFreqMonthly(pattern);
					}
					if ($('#' + pattern + '-freq-weekly').prop('checked')) {
						// console_log('Generate RRULE_OPTIONS for', 'weekly ' + pattern, false);
						EE_REM.parseFreqWeekly(pattern);
					}
					if ($('#' + pattern + '-freq-daily').prop('checked')) {
						// console_log('Generate RRULE_OPTIONS for', 'daily ' + pattern, false);
						EE_REM.parseFreqDaily(pattern);
					}
					EE_REM.setRRuleStartAndEndDates(pattern);
					EE_REM.displayRRule(pattern);
					EE_REM.generateDatetimes(pattern);
				});
				EE_REM.displayGeneratedDatetimes();
			});

		};

		/**
		 * @function
		 */
		this.setRRuleStartAndEndDates = function(pattern) {
			console_log('function', 'EE_REM.setRRuleStartAndEndDates()', true);
			var datetime = EE_REM.parseDateTime($('#' + pattern + '-dtstart'));
			if (datetime instanceof Date) {
				EE_REM.RRULE_OPTIONS.dtstart = datetime;
			}
			// recurrence-ends-option-input-until
			var $until = $('#' + pattern + '-ends-option-input-until');
			if ($until.prop('checked')) {
				datetime = EE_REM.parseDateTime($('#' + pattern + '-ends-until'));
				if (datetime instanceof Date) {
					EE_REM.RRULE_OPTIONS.until = datetime;
				}
			} else {
				EE_REM.RRULE_OPTIONS.count = $('#' + pattern + '-ends-count').val();
			}
		};

		/**
		 * @function
		 */
		this.parseDateTime = function($input) {
			console_log('function', 'EE_REM.parseDateTime()', true);
			var date_time_string = $input.val();
			// if (date_time_string === '' || date_time_string === null) {
			// 	return null;
			// }
			var date = moment(date_time_string, 'YYYY-MM-DD hh:mm a');
			if (date instanceof Date) {
				return date;
			}
			console_log('parseDateTime() Date', date.toString(), true);
			return null;
		};

		/**
		 * @function
		 */
		this.displayRRule = function(pattern) {
			console_log('function', 'EE_REM.displayRRule()', true);
			var rule_text = 'none';
			if (typeof EE_REM.RRULE_OPTIONS.freq !== 'undefined') {
				EE_REM.RRULE = new RRule(EE_REM.RRULE_OPTIONS, true);
				rule_text = EE_REM.RRULE.toText();
			} else {
				EE_REM.RRULE = {};
			}
			if (pattern === 'recurrence' && EE_REM.RRULE instanceof RRule) {
				$('#rem-recurrence-string').html(EE_REM.RRULE.toString());
			}
			$('#' + pattern + '-desc').html(rule_text);
		};

		/**
		 * @function
		 */
		this.generateDatetimes = function(pattern) {
			console_log('function', 'EE_REM.generateDatetimes()', true);
			var index = -1;
			var dates = [];
			if (! EE_REM.RRULE instanceof RRule) {
				console_log('', 'could not generate Datetimes because EE_REM.RRULE is not a valid instance of RRule', false);
				return;
			}
			var max   = 60;
			console_log('generate Datetimes', '', true);
			dates = EE_REM.RRULE.all(function(date, i) {
				return typeof EE_REM.RRULE_OPTIONS.count !== 'undefined' || i < max;
			});
			if (pattern === 'recurrence') {
				$('#rem-recurrence-string').html(EE_REM.RRULE.toString());
				EE_REM.DATETIMES = dates;
				$.each(EE_REM.DATETIMES, function(key, date) {
					console_log('recurrence', date.toString(), false);
				});
			} else {
				$.each(dates, function(key, date) {
					console_log('exclusion', date.toString(), false);
					function datesMatch(element) {
						return date.toString() === element.toString();
					}
					index = EE_REM.DATETIMES.findIndex(datesMatch);
					if (index > -1) {
						console_log('REMOVED', date.toString(), false);
						EE_REM.DATETIMES.splice(index, 1);
					}
				});
			}
		};

		/**
		 * @function
		 */
		this.displayGeneratedDatetimes = function() {
			console_log('function', 'EE_REM.displayGeneratedDatetimes()', true);
			var html = '';
			$.each(EE_REM.DATETIMES, function(key, date) {
				// console_log('date', date.toString(), false);
				html += '<div class="rem-generated-datetime">' + date.toString() + '</div>';
			});
			$('#rem-generated-datetimes').html(html);
		};

		/**
		 * @function
		 */
		this.displaySelectedFrequency = function($freq, recurrence) {
			console_log('function', 'EE_REM.displaySelectedFrequency()', true);
			if (recurrence) {
				$('.recurrence_freq').hide();
				$('.recurrence_freq_option_label').
				removeClass('active_freq_option');
			} else {
				$('.exclusion_freq').hide();
				$('.exclusion_freq_option_label').
				removeClass('active_freq_option');
			}
			var option_id = $freq.attr('id');
			var target    = '#' + option_id + '-section';
			var label     = '#' + option_id + '-lbl';
			// console_log('target', target, false);
			$(target).show();
			$(label).addClass('active_freq_option');
		};

		/**
		 * @function
		 */
		this.resetRecurrenceFrequency = function() {
			console_log('function', 'EE_REM.resetRecurrenceFrequency()', true);
			$('.recurrence_freq_option').each(function() {
				if ($(this).prop('checked')) {
					EE_REM.displaySelectedFrequency($(this), true);
				}
			});
			$('.exclusion_freq_option').each(function() {
				if ($(this).prop('checked')) {
					EE_REM.displaySelectedFrequency($(this), false);
				}
			});
		};

		/**
		 * @function
		 */
		this.setDayOfMonthOrdinalSuffix = function($day_of_month) {
			console_log('function', 'EE_REM.setDayOfMonthOrdinalSuffix()', true);
			var day_of_month                = $day_of_month.val();
			var day_of_month_ordinal_suffix = '#' + $day_of_month.data('day_of_month');
			// console_log('day_of_month', day_of_month, false);
			// console_log('day_of_month_ordinal_suffix', day_of_month_ordinal_suffix, false);
			if (day_of_month === '1' || day_of_month === '31') {
				$(day_of_month_ordinal_suffix).html('st');
			} else if (day_of_month === '2') {
				$(day_of_month_ordinal_suffix).html('nd');
			} else if (day_of_month === '3') {
				$(day_of_month_ordinal_suffix).html('rd');
			} else {
				$(day_of_month_ordinal_suffix).html('th');
			}

		};

		/**
		 * @function
		 */
		this.recurrenceEndsOption = function($ends_option, pattern) {
			console_log('function', 'EE_REM.recurrenceEndsOption()', true);
			$('.recurrence-ends-options').hide();
			var end_option = '#' + pattern + '-ends-option-' + $ends_option.val();
			console_log('end_option', end_option, false);
			$(end_option).toggle();
		};

		/**
		 * @function
		 */
		this.setInterval = function(pattern, frequency) {
			console_log('function', 'EE_REM.setInterval()', true);
			var interval = $('#' + pattern + '-' + frequency + '-interval').val();
			if (interval > 1 && typeof EE_REM.RRULE_OPTIONS.interval === 'undefined') {
				EE_REM.RRULE_OPTIONS.interval = interval;
				$('.' + pattern + '-interval').val(interval);
				var every = frequency.replace('ly', '');
				every = every === 'dai' ? 'day' : every;
				$('.' + pattern + '-every-span').html(every);
			}
		};

		/**
		 * @function
		 */
		this.parseFreqDaily = function(pattern) {
			console_log('function', 'EE_REM.parseFreqDaily()', true);
			// EE_REM.every = 'day';
			if (typeof EE_REM.RRULE_OPTIONS.freq === 'undefined') {
				EE_REM.RRULE_OPTIONS.freq = RRule.DAILY;
			}
			EE_REM.setInterval(pattern, 'daily');
			// var freq = $('#' + pattern + '-daily_freq-freq').val();
			// if (freq > 1 && typeof EE_REM.RRULE_OPTIONS.interval === 'undefined') {
			// 	EE_REM.RRULE_OPTIONS.interval = freq;
			// }
		};

		/**
		 * @function
		 */
		this.parseFreqWeekly = function(pattern) {
			console_log('function', 'EE_REM.parseFreqWeekly()', true);
			// EE_REM.every = 'week';
			EE_REM.setInterval(pattern, 'weekly');
			// var freq = $('#' + pattern + '-weekly_freq-frequency').val();
			// if (freq > 1 && typeof EE_REM.RRULE_OPTIONS.interval === 'undefined') {
			// 	EE_REM.RRULE_OPTIONS.interval = freq;
			// }

			$.each(EE_REM.DAYS, function(day, weekday) {
				// recurrence-weekly_freq-by_weekday-tu
				if ($('#' + pattern + '-by-weekday-'+day.toLowerCase()).prop('checked')) {
					if (typeof EE_REM.RRULE_OPTIONS.byweekday === 'undefined') {
						EE_REM.RRULE_OPTIONS.byweekday = [];
					}
					EE_REM.RRULE_OPTIONS.byweekday.push(weekday);
				}
			});
			if (typeof EE_REM.RRULE_OPTIONS.freq === 'undefined') {
				EE_REM.RRULE_OPTIONS.freq = RRule.WEEKLY;
			}
		};




		/**
		 * @function
		 */
		this.parseFreqMonthly = function(pattern) {
			console_log('function', 'EE_REM.parseFreqMonthly()', true);
			// EE_REM.every = 'month';
			EE_REM.setInterval(pattern, 'monthly');
			// var freq = $('#' + pattern + '-monthly_freq-frequency').val();
			// if (freq > 1 && typeof EE_REM.RRULE_OPTIONS.interval === 'undefined') {
			// 	EE_REM.RRULE_OPTIONS.interval = freq;
			// }
			if ($('#' + pattern + '-monthly-frequency-option-0-0').prop('checked')) {
				EE_REM.RRULE_OPTIONS.bymonthday = [];
				EE_REM.RRULE_OPTIONS.bymonthday.push($('#' + pattern + '-monthly_freq-by-month-day').val());
			} else if ($('#' + pattern + '-monthly-frequency-option-1-1').prop('checked')) {
				EE_REM.RRULE_OPTIONS.byweekday = [];
				var nth_day_of_week = $('#' + pattern + '-monthly_freq-by-nth-day-of-week').val();
				nth_day_of_week     = parseInt(nth_day_of_week);
				var weekday = EE_REM.DAYS[$('#' + pattern + '-monthly_freq-day-of-week').val()];
				EE_REM.RRULE_OPTIONS.byweekday.push(weekday.nth(nth_day_of_week));
			}
			if (typeof EE_REM.RRULE_OPTIONS.freq === 'undefined') {
				EE_REM.RRULE_OPTIONS.freq = RRule.MONTHLY;
			}

		};

		/**
		 * @function
		 */
		this.parseFreqYearly = function(pattern) {
			console_log('function', 'EE_REM.parseFreqYearly()', true);
			// EE_REM.every = 'year';
			EE_REM.setInterval(pattern, 'yearly');
			// var freq  = $('#' + pattern + '-yearly_freq-frequency').val();
			// if (freq  >   1) {
			// 	EE_REM.RRULE_OPTIONS.interval  = freq;
			// }
			var month;
			for (month = 1; month < 13; month++) {
				// console_log('month', month, false);
				var $month_input = $('#' + pattern + '-yearly_freq-by-month-' + month);
				// console_log('$month_input.val()', $month_input.val(), false);
				if ($month_input.prop('checked')) {
					if (typeof EE_REM.RRULE_OPTIONS.bymonth === 'undefined') {
						EE_REM.RRULE_OPTIONS.bymonth = [];
					}
					EE_REM.RRULE_OPTIONS.bymonth.push($month_input.val());
				}
			}
			if (typeof EE_REM.RRULE_OPTIONS.freq === 'undefined') {
				EE_REM.RRULE_OPTIONS.freq = RRule.YEARLY;
			}
			// bymonth
			/*
			yearly_freq-frequency,
			yearly_freq-by-month-1,
			yearly_freq-by-month-2,
			yearly_freq-by-month-3,
			yearly_freq-by-month-4,
			yearly_freq-by-month-5,
			yearly_freq-by-month-6,
			yearly_freq-by-month-7,
			yearly_freq-by-month-8,
			yearly_freq-by-month-9,
			yearly_freq-by-month-10,
			yearly_freq-by-month-11,
			yearly_freq-by-month-12
			*/
		};

		/**
		 * @function
		 */

	};


	EE_REM.initialize();
});
