jQuery(document).ready(function($) {

    var EE_REM = new function() {

        this.DATE_TIME_FORMAT = 'YYYY-MM-DD hh:mm a';
        this.RECURRING_EVENTS_FORM_HTML = '';
        this.RECURRING_EVENTS_TABLE     = {};
        this.NO_EXLCUSIONS              = {};
        this.RRULE_OPTIONS              = {};
        this.RRULE                      = {};
        this.DAYS                       = {
            'MO': RRule.MO,
            'TU': RRule.TU,
            'WE': RRule.WE,
            'TH': RRule.TH,
            'FR': RRule.FR,
            'SA': RRule.SA,
            'SU': RRule.SU
        };
        this.DATETIMES              = [];

        /**
         * @function
         */
        this.initialize = function() {
            EE_REM.RECURRING_EVENTS_TABLE = $('#recurring_events');
            EE_REM.NO_EXLCUSIONS          = $('#exclusion-freq-none');
            // var now = new Date();
            // console_log('NOW', now.toString(), true);
            // console_log('function', 'EE_REM.initialize()', true);
            EE_REM.initializeDatepicker();
            EE_REM.resetRecurrenceFrequency();
            // reset day of month selector on load
            $('.by-month-day').each(function() {
                EE_REM.setDayOfMonthOrdinalSuffix($(this));
            });
            // reset recurrence ends options on load
            var patterns = ['recurrence', 'exclusion'];
            $.each(patterns, function(key, pattern) {
                $('.' + pattern + '-ends-option-input').each(function() {
                    if ($(this).prop('checked')) {
                        EE_REM.recurrenceEndsOption($(this), pattern);
                    }
                });
            });

            EE_REM.set_listeners();
            EE_REM.NO_EXLCUSIONS.trigger('change');
            EE_REM.parseFreqDaily('recurrence');
            EE_REM.setRRuleStartAndEndDates('recurrence');
            EE_REM.displayRRule('recurrence');
        };

        /**
         * @function
         */
        this.initializeDatepicker = function() {
            // console_log('function', 'EE_REM.initializeDatepicker()', true);
            $('.rem-datepicker').datetimepicker({
                dateFormat:     'yy-mm-dd',
                timeFormat:     'hh:mm tt',
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
                defaultDate:    new Date(),
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
        this.set_listeners = function() {
            // console_log('function', 'EE_REM.set_listeners()', true);
            var patterns = ['recurrence', 'exclusion'];
            // make sure end dates are always after start dates
            $.each(patterns, function(key, pattern) {
                EE_REM.adjustEndDatesWhenStartDatesChange(pattern);
            });
            // display recurrence options form
            EE_REM.RECURRING_EVENTS_TABLE.on('click',  '.recurrence_freq_option', function() {
                console_log('>> CLICK <<', '.recurrence_freq_option', true);
                EE_REM.displaySelectedFrequency($(this), true);
            });
            // display exclusion options form
            EE_REM.RECURRING_EVENTS_TABLE.on('click',  '.exclusion_freq_option', function() {
                console_log('>> CLICK <<', '.exclusion_freq_option', true);
                EE_REM.displaySelectedFrequency($(this), false);
            });
            // reset day of month selector on change
            EE_REM.RECURRING_EVENTS_TABLE.on('change', '.by-month-day', function() {
                console_log('>> CHANGE <<', '.by-month-day', true);
                EE_REM.setDayOfMonthOrdinalSuffix($(this));
            });
            // reset day of month selector on change
            EE_REM.RECURRING_EVENTS_TABLE.on('click', '.edit-pattern-link', function() {
                console_log('>> CLICK <<', '.edit-pattern-link', true);
                var edit_pattern = '#' + $(this).data('pattern');
                // console_log('edit_pattern', edit_pattern, false);
                $(edit_pattern).toggle();
            });
            // reset recurrence ends options on change
            EE_REM.RECURRING_EVENTS_TABLE.on('click', '.recurrence-ends-option-input', function() {
                console_log('>> CLICK <<', '.recurrence-ends-option-input', true);
                EE_REM.recurrenceEndsOption($(this), 'recurrence');
            });
            EE_REM.RECURRING_EVENTS_TABLE.on('click', '.exclusion-ends-option-input', function() {
                console_log('>> CLICK <<', '.exclusion-ends-option-input', true);
                EE_REM.recurrenceEndsOption($(this), 'exclusion');
            });
            EE_REM.RECURRING_EVENTS_TABLE.on('change', '.recurrence-monthly_freq-by-month-day', function() {
                console_log('>> CHANGE <<', '.recurrence-monthly_freq-by-month-day', true);
                $('#recurrence-monthly-frequency-option-0-0').prop('checked', true);
            });
            EE_REM.RECURRING_EVENTS_TABLE.on('change', '.exclusion-monthly_freq-by-month-day', function() {
                console_log('>> CHANGE <<', '.exclusion-monthly_freq-by-month-day', true);
                $('#exclusion-monthly-frequency-option-0-0').prop('checked', true);
            });
            EE_REM.RECURRING_EVENTS_TABLE.on('change', '.rem-input', function() {
                $.each(patterns, function(key, pattern) {
                    // console_log(' . . . DETECTING .rem-input changes for', pattern, true);
                    EE_REM.RRULE_OPTIONS = {};
                    EE_REM.parsePatternFrequency(pattern);
                    EE_REM.displayRRule(pattern);
                    EE_REM.generateDatetimes(pattern);
                });
                EE_REM.displayGeneratedDatetimes();
            });
        };

        /**
         * @function
         */
        this.adjustEndDatesWhenStartDatesChange = function(pattern) {
            var $start_input = $('#' + pattern + '-dtstart');
            $start_input.on('change', function() {
                console_log('>> CHANGE <<', '#' + pattern + '-dtstart', true);
                var $end_input = $('#' + pattern + '-ends-until'),
                    start_date = moment($start_input.val(), EE_REM.DATE_TIME_FORMAT),
                    end_date   = moment($end_input.val(), EE_REM.DATE_TIME_FORMAT);
                console_log('>> start_date', start_date.format(EE_REM.DATE_TIME_FORMAT), false);
                console_log('>> end_date', end_date.format(EE_REM.DATE_TIME_FORMAT), false);
                console_log('>> moment(start_date).isAfter(end_date)', moment(start_date).isAfter(end_date), false);
                if (moment(start_date).isAfter(end_date)) {
                    end_date = start_date.add(6, 'd');
                    console_log('>> NEW end_date', end_date.format(EE_REM.DATE_TIME_FORMAT), false);
                    $end_input.val(end_date.format(EE_REM.DATE_TIME_FORMAT));
                }
            });
        };

        /**
         * adds one week to end dates if an adjusted start date is now after end date
         *
         * @function
         */
        this.parsePatternFrequency = function(pattern) {
            if (!(pattern === 'exclusion' && EE_REM.NO_EXLCUSIONS.prop('checked'))) {
                if ($('#' + pattern + '-freq-yearly').prop('checked')) {
                    console_log('** Generate RRULE_OPTIONS for YEARLY ' + pattern + ' **', '', true);
                    EE_REM.parseFreqYearly(pattern);
                }
                if ($('#' + pattern + '-freq-monthly').prop('checked')) {
                    console_log('** Generate RRULE_OPTIONS for MONTHLY ' + pattern + ' **', '', true);
                    EE_REM.parseFreqMonthly(pattern);
                }
                if ($('#' + pattern + '-freq-weekly').prop('checked')) {
                    console_log('** Generate RRULE_OPTIONS for WEEKLY ' + pattern + ' **', '', true);
                    EE_REM.parseFreqWeekly(pattern);
                }
                if ($('#' + pattern + '-freq-daily').prop('checked')) {
                    console_log('** Generate RRULE_OPTIONS for DAILY ' + pattern + ' **', '', true);
                    EE_REM.parseFreqDaily(pattern);
                }
                EE_REM.setRRuleStartAndEndDates(pattern);
            }
        };

        /**
         * adds one week to end dates if an adjusted start date is now after end date
         *
         * @function
         */
        this.setRRuleStartAndEndDates = function(pattern) {
            // console_log('function', 'EE_REM.setRRuleStartAndEndDates(pattern = ' + pattern + ')', true);
            var datetime = EE_REM.parseDateTime($('#' + pattern + '-dtstart'), true);
            if (datetime !== null) {
                EE_REM.RRULE_OPTIONS.dtstart = datetime;
                // console_log('RRULE_OPTIONS.dtstart', EE_REM.RRULE_OPTIONS.dtstart.toString(), false);
            }
            // recurrence-ends-option-input-until
            var $until = $('#' + pattern + '-ends-option-input-until');
            // console_log('$until id', $until.attr('id'), false);
            // console_log('$until.prop(\'checked\')', $until.prop('checked'), false);
            if ($until.prop('checked')) {
                // console_log('$until datepicker id', pattern + '-ends-until', false);
                datetime = EE_REM.parseDateTime($('#' + pattern + '-ends-until'), true);
                if (datetime !== null) {
                    EE_REM.RRULE_OPTIONS.until = datetime;
                    // console_log('RRULE_OPTIONS.until', EE_REM.RRULE_OPTIONS.until.toString(), false);
                } else {
                    console_log('INVALID datetime', datetime, false);
                }
            } else {
                EE_REM.RRULE_OPTIONS.count = $('#' + pattern + '-ends-count').val();
                // console_log('RRULE_OPTIONS.count', EE_REM.RRULE_OPTIONS.count, false);
            }
        };

        /**
         * @function
         * #return date
         */
        this.parseDateTime = function($input, recurse) {
            // console_log('function', 'EE_REM.parseDateTime()', true);
            var date_time_string = $input.val();
            // console_log('$input.attr(id)', $input.attr('id'), false);
            // console_log('date_time_string', date_time_string, false);
            // verify that a date has actually been picked
            if (date_time_string === '' || date_time_string === null) {
                // NO? kk, we'll if we haven't been down this path already...
                if (recurse === true) {
                    // force input to have some kind of value
                    $input.trigger('click');
                    // then hit this function again, but turn recursion off
                    return EE_REM.parseDateTime($input, false);
                }
                // recursion is off, so just return nothing
                return null;
            }
            var date;
            if (moment(date_time_string).isValid()) {
                date = moment(date_time_string);
            } else if (moment(date_time_string, EE_REM.DATE_TIME_FORMAT).isValid()) {
                date = moment(date_time_string, EE_REM.DATE_TIME_FORMAT);
            } else {
                console_log('INVALID DATE STRING', date_time_string, false);
            }
            if (moment.isMoment(date) && date.isValid()) {
                // console_log('VALID Date', date.toString(), false);
                return date.toDate();
            }
            console_log('INVALID DATE', date.toString(), false);
            return null;
        };

        /**
         * @function
         */
        this.displayRRule = function(pattern) {
            console_log('function', 'EE_REM.displayRRule(pattern = '+ pattern+')', true);
            var rule_text = 'none';
            console_log('EE_REM.RRULE_OPTIONS.freq', EE_REM.RRULE_OPTIONS.freq, false);
            if (typeof EE_REM.RRULE_OPTIONS.freq !== 'undefined' && EE_REM.RRULE_OPTIONS.freq > 0) {
                EE_REM.RRULE = new RRule(EE_REM.RRULE_OPTIONS, true);
                rule_text    = EE_REM.RRULE.toText();
            } else {
                if (!(pattern === 'exclusion' && EE_REM.NO_EXLCUSIONS.prop('checked'))) {
                    console_log('', 'RESET EE_REM.RRULE for ' + pattern + ' pattern', false);
                    EE_REM.dumpRRuleOptionOptions();
                }
                EE_REM.RRULE = {};
            }
            if (pattern === 'recurrence' && EE_REM.RRULE instanceof RRule) {
                var rule_string = EE_REM.RRULE.toString();
                $('#rem-recurrence-string-display').html(rule_string);
                $('#rem-recurrence-string').val(rule_string);
            }
            if (rule_text !== 'none')  {
                var starting = moment($('#' + pattern + '-dtstart').val(), EE_REM.DATE_TIME_FORMAT);
                rule_text = 'Starting on ' + starting.format('MMMM D, YYYY') + ', repeats ' + rule_text;
            }
            $('#' + pattern + '-desc').html(rule_text);
        };

        /**
         * @function
         */
        this.generateDatetimes = function(pattern) {
            // console_log('function', 'EE_REM.generateDatetimes(pattern = ' + pattern + ')', true);
            // console_log('typeof EE_REM.RRULE.freq', typeof EE_REM.RRULE.freq, false);
            // console_log('EE_REM.RRULE_OPTIONS', EE_REM.RRULE_OPTIONS, false);
            var index = -1;
            if (EE_REM.RRULE instanceof RRule === false) {
                if (!(pattern === 'exclusion' && EE_REM.NO_EXLCUSIONS.prop('checked'))) {
                    console_log(
                        'ERROR!!! Could not generate Datetimes because EE_REM.RRULE is not a valid instance of RRule',
                        '',
                        false
                    );
                }
                EE_REM.debugDump(true);
                return;
            }
            var rule_string = EE_REM.RRULE.toString();
            $('#rem-' + pattern + '-string-display').html(rule_string);
            $('#rem-' + pattern + '-string').val(rule_string);
            EE_REM.debugDump(false);
            var dates = EE_REM.getDatetimes(EE_REM.RRULE, rule_string, true);
            console_log('generateDatetimes', 'pattern: ' + pattern, false);
            if (pattern === 'recurrence') {
                EE_REM.DATETIMES = dates;
                // $.each(EE_REM.DATETIMES, function(key, date) {
                //     console_log(pattern + ' date', date.toString(), false);
                // });
            } else {
                $.each(dates, function(key, date) {
                    // console_log('exclusion', date.toString(), false);
                    function datesMatch(element) {
                        return date.toString() === element.toString();
                    }

                    index = EE_REM.DATETIMES.findIndex(datesMatch);
                    if (index > -1) {
                        // console_log('REMOVED', date.toString(), false);
                        EE_REM.DATETIMES.splice(index, 1);
                    }
                });
            }
            EE_REM.debugDump(false);
        };

        /**
         * @function
         */
        this.getDatetimes = function(rrule, rule_string, recurse) {
            rrule = EE_REM.RRULE instanceof RRule ? rrule : EE_REM.RRULE;
            recurse = typeof recurse !== 'undefined' ? recurse : false;
            rrule = RRule.fromString(rule_string);
            var dates = rrule.all(function(date, i) {
                // console.log(JSON.stringify(i + ') date: ' + date, null, 4));
                return i < 60 || (
                    typeof rrule.options.count !== 'undefined'
                    && rrule.options.count > 0
                    && rrule.options.count < 365
                );
            });
            console_log('#1 dates.length', dates.length, false);
            if (dates.length < 1) {
                if (recurse === true)  {
                    return EE_REM.getDatetimes(RRule.fromString(rule_string), rule_string, false);
                }
                // try again without custom iterator
                dates = rrule.all();
                console_log('#2 dates.length', dates.length, false);
                if (dates.length < 1) {
                    console_log('ERROR!!! Could not generate Datetimes for RRule: ', rule_string, true);
                    console_log('rrule.toText()', rrule.toText(), false);
                    console_log('rrule.options.count', rrule.options.count, false);
                    console_log('rrule.options.constructor.name', rrule.options.constructor.name, false);
                    EE_REM.debugDump(true);
                    return;
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
            var html           = '',
                dt_count       = 1,
                // datetimes_json = {},
                timestamps_json = {};
            $.each(EE_REM.DATETIMES, function(key, date) {
                // console_log('date', date.toString(), false);
                html += '<div class="rem-generated-datetime">' + dt_count + ') ' + date.toString() + '</div>';
                // datetimes_json[dt_count] = date.toString();
                timestamps_json[dt_count] = date.getTime();
                dt_count++;
            });
            $('#rem-generated-datetimes').html(html);
            // $('#rem-generated-datetimes-json').val(JSON.stringify(datetimes_json));
            $('#rem-generated-datetimes-json').val(JSON.stringify(timestamps_json));
        };

        /**
         * @function
         */
        this.displaySelectedFrequency = function($freq, recurrence) {
            // console_log('function', 'EE_REM.displaySelectedFrequency()', true);
            if (recurrence) {
                $('.recurrence_freq').hide();
                $('.recurrence_freq_option_label').removeClass('active_freq_option');
            } else {
                $('.exclusion_freq').hide();
                $('.exclusion_freq_option_label').removeClass('active_freq_option');
            }
            var option_id = $freq.attr('id');
            var target    = '#' + option_id + '-section';
            var label     = '#' + option_id + '-lbl';
            // console_log('displaySelectedFrequency target', target, false);
            $(target).show();
            $(label).addClass('active_freq_option');
        };

        /**
         * @function
         */
        this.resetRecurrenceFrequency = function() {
            // console_log('function', 'EE_REM.resetRecurrenceFrequency()', true);
            $('.recurrence_freq_option').each(function() {
                // console_log($(this).attr('id') + ' checked', $(this).prop('checked'), false);
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
            // console_log('function', 'EE_REM.setDayOfMonthOrdinalSuffix()', true);
            var day_of_month                = $day_of_month.val();
            var day_of_month_ordinal_suffix = '#' + $day_of_month.data('day_of_month');
            // console_log('day_of_month', day_of_month, false);
            // console_log('day_of_month_ordinal_suffix', day_of_month_ordinal_suffix, false);
            if (day_of_month === '1' || day_of_month === '21' || day_of_month === '31') {
                $(day_of_month_ordinal_suffix).html('st');
            } else if (day_of_month === '2' || day_of_month === '22') {
                $(day_of_month_ordinal_suffix).html('nd');
            } else if (day_of_month === '3' || day_of_month === '23') {
                $(day_of_month_ordinal_suffix).html('rd');
            } else {
                $(day_of_month_ordinal_suffix).html('th');
            }

        };

        /**
         * @function
         */
        this.recurrenceEndsOption = function($ends_option, pattern) {
            // console_log('function', 'EE_REM.recurrenceEndsOption()', true);
            $('.' + pattern + '-ends-options').hide();
            var end_option = '#' + pattern + '-ends-option-' + $ends_option.val();
            // console_log('end_option', end_option, false);
            $(end_option).show();
        };

        /**
         * @function
         */
        this.setInterval = function(pattern, frequency) {
            // console_log('** function', 'EE_REM.setInterval() **', true);
            // console_log('pattern', pattern, false);
            // console_log('frequency', frequency, false);
            var interval = $('#' + pattern + '-' + frequency + '-interval').val();
            // console_log('interval', interval, false);
            if (interval /*&& typeof EE_REM.RRULE_OPTIONS.interval === 'undefined'*/) {
                EE_REM.RRULE_OPTIONS.interval = interval;
                // $('.' + pattern + '-interval').val(interval);
                // console_log('.' + pattern + '-interval', $('.' + pattern + '-interval').val(), false);
                var every = frequency.replace('ly', '');
                every     = every === 'dai' ? 'day' : every;
                // console_log('every', every, false);
                $('.' + pattern + '-every-span').html(every);
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
         */
        this.parseFreqDaily = function(pattern) {
            console_log('function', 'EE_REM.parseFreqDaily()', true);
            EE_REM.clearFrequencyOptions();
            if (typeof EE_REM.RRULE_OPTIONS.freq === 'undefined') {
                EE_REM.RRULE_OPTIONS.freq = RRule.DAILY;
            }
            EE_REM.setInterval(pattern, 'daily');
        };

        /**
         * @function
         */
        this.parseFreqWeekly = function(pattern) {
            console_log('function', 'EE_REM.parseFreqWeekly()', true);
            // console_log('pattern', pattern, false);
            // console_log('$("#recurrence-by-weekday-mo").val()', $("#recurrence-by-weekday-mo").val(), false);
            // console_log('$("#recurrence-by-weekday-mo").val()', $("#recurrence-by-weekday-mo").val(), false);
            EE_REM.clearFrequencyOptions();
            $.each(EE_REM.DAYS, function(day, weekday) {
                // console_log('day', day, false);
                // console_log('weekday', weekday, false);
                var by_weekday_input_id = '#' + pattern + '-by-weekday-' + day.toLowerCase();
                var $by_weekday_input = $(by_weekday_input_id);
                // console_log('by_weekday_input_id', by_weekday_input_id, false);
                // console_log('$by_weekday_input.val()', $by_weekday_input.val(), false);
                // recurrence-by-weekday-mo
                if ($by_weekday_input.prop('checked')) {
                    if (typeof EE_REM.RRULE_OPTIONS.byweekday === 'undefined') {
                        EE_REM.RRULE_OPTIONS.byweekday = [];
                    }
                    EE_REM.RRULE_OPTIONS.byweekday.push(weekday);
                }
            });
            if (typeof EE_REM.RRULE_OPTIONS.freq === 'undefined') {
                EE_REM.RRULE_OPTIONS.freq = RRule.WEEKLY;
            }
            EE_REM.setInterval(pattern, 'weekly');
        };

        /**
         * @function
         */
        this.parseFreqMonthly = function(pattern) {
            console_log('function', 'EE_REM.parseFreqMonthly()', true);
            EE_REM.clearFrequencyOptions();
            if ($('#' + pattern + '-monthly-frequency-option-0-0').prop('checked')) {
                EE_REM.RRULE_OPTIONS.bymonthday = [];
                EE_REM.RRULE_OPTIONS.bymonthday.push($('#' + pattern + '-monthly_freq-by-month-day').val());
            } else if ($('#' + pattern + '-monthly-frequency-option-1-1').prop('checked')) {
                EE_REM.RRULE_OPTIONS.byweekday = [];
                var nth_day_of_week            = $('#' + pattern + '-monthly_freq-by-nth-day-of-week').val();
                EE_REM.RRULE_OPTIONS.bysetpos  = parseInt(nth_day_of_week);
                // console_log('nth_day_of_week', nth_day_of_week, false);
                var weekday                    = EE_REM.DAYS[$('#' + pattern + '-monthly_freq-day-of-week').val()];
                // console_log('weekday', weekday, false);
                EE_REM.RRULE_OPTIONS.byweekday.push(weekday);
            }
            if (typeof EE_REM.RRULE_OPTIONS.freq === 'undefined') {
                EE_REM.RRULE_OPTIONS.freq = RRule.MONTHLY;
            }
            EE_REM.setInterval(pattern, 'monthly');
        };

        /**
         * @function
         */
        this.parseFreqYearly = function(pattern) {
            console_log('function', 'EE_REM.parseFreqYearly()', true);
            EE_REM.clearFrequencyOptions();
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
            EE_REM.setInterval(pattern, 'yearly');
        };

        /**
         * @function
         */
        this.debugDump = function(display) {
            if (display){
                EE_REM.dumpRRuleOptionOptions();
                EE_REM.dumpRRuleOptions();
                console_log('EE_REM.RRULE.origOptions', EE_REM.RRULE.origOptions, false);
            }
        };

        /**
         * @function
         */
        this.dumpRRuleOptions = function() {
            console_log('    @function', 'EE_REM.dumpRRuleOptions()', true);
            var properties = ['freq', 'interval', 'dtstart', 'until', 'count', 'byweekday', 'bymonthday', 'bymonth'];
            $.each(properties, function(index, property) {
                if (
                    typeof EE_REM.RRULE.options !== 'undefined'
                    && typeof EE_REM.RRULE.options[property] !== 'undefined'
                    && EE_REM.RRULE.options[property] !== null
                ) {
                    console_log('     > EE_REM.RRULE.options.' + property, EE_REM.RRULE.options[property].toString(), false);
                }
            });
        };

        /**
         * @function
         */
        this.dumpRRuleOptionOptions = function() {
            if (EE_REM.RRULE_OPTIONS.constructor === Object && Object.keys(EE_REM.RRULE_OPTIONS).length > 0) {
                console_log('    @function', 'EE_REM.dumpRRuleOptionOptions()', true);
                var properties = [
                    'freq',
                    'interval',
                    'dtstart',
                    'until',
                    'count',
                    'byweekday',
                    'bymonthday',
                    'bymonth'
                ];
                $.each(properties, function(index, property) {
                    if (
                        typeof EE_REM.RRULE_OPTIONS[property] !== 'undefined'
                        && EE_REM.RRULE_OPTIONS[property] !== null
                    ) {
                        console_log('     > EE_REM.RRULE_OPTIONS.' + property, EE_REM.RRULE_OPTIONS[property].toString(), false);
                    }
                });
            }
        };

    };

    $('#datetime-editing-dtts-table').on('click', '.ee-edit-datetime-recurrence', function() {
        var content = '',
            $recurring_events_patterns_div = $('#event-datetime-recurrence-patterns-div');
            // datetime_id =  $(this).data('datetime-id');
        EE_REM.RECURRING_EVENTS_FORM_HTML = $recurring_events_patterns_div.html();
        $recurring_events_patterns_div.html(content);
        content += '<form id="event-datetime-recurrence-patterns-form">';
        content += EE_REM.RECURRING_EVENTS_FORM_HTML;
        content += '</form>';
        dialogHelper.displayModal().addContent(content);
        EE_REM.initialize();
    });

    $('body').on('eeModalCloseEvent', function() {
        $('#event-datetime-recurrence-patterns-div').html(EE_REM.RECURRING_EVENTS_FORM_HTML);
    });

});
