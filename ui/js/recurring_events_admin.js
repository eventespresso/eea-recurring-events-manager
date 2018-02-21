jQuery(document).ready(function($) {

    var EE_REM = new function() {

        this.RECURRING_EVENTS_TABLE = $('#recurring_events');
        this.NO_EXLCUSIONS          = $('#exclusion-freq-none');
        this.RRULE_OPTIONS          = {};
        this.RRULE                  = {};
        this.DAYS                   = {
            'MO': RRule.MO,
            'TU': RRule.TU,
            'WE': RRule.WE,
            'TH': RRule.TH,
            'FR': RRule.FR,
            'SA': RRule.SA,
            'SU': RRule.SU
        };
        this.DATETIMES              = [];

        // this.every = '';

        /**
         * @function
         */
        this.initialize = function() {
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
            EE_REM.displayRRule();
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
            // display recurrence options form
            EE_REM.RECURRING_EVENTS_TABLE.on('click', '.recurrence_freq_option',
                function() {
                    EE_REM.displaySelectedFrequency($(this), true);
                });
            // display exclusion options form
            EE_REM.RECURRING_EVENTS_TABLE.on('click', '.exclusion_freq_option',
                function() {
                    EE_REM.displaySelectedFrequency($(this), false);
                });
            // reset day of month selector on change
            EE_REM.RECURRING_EVENTS_TABLE.on('change', '.by-month-day', function() {
                EE_REM.setDayOfMonthOrdinalSuffix($(this));
            });
            // reset day of month selector on change
            EE_REM.RECURRING_EVENTS_TABLE.on('click', '.edit-pattern-link',
                function() {
                    var edit_pattern = '#' + $(this).data('pattern');
                    // console_log('edit_pattern', edit_pattern, false);
                    $(edit_pattern).toggle();
                });
            // reset recurrence ends options on change
            EE_REM.RECURRING_EVENTS_TABLE.on('click', '.recurrence-ends-option-input',
                function() {
                    EE_REM.recurrenceEndsOption($(this), 'recurrence');
                });
            EE_REM.RECURRING_EVENTS_TABLE.on('click', '.exclusion-ends-option-input',
                function() {
                    EE_REM.recurrenceEndsOption($(this), 'exclusion');
                });
            EE_REM.RECURRING_EVENTS_TABLE.on('change',
                '.recurrence-monthly_freq-by-month-day', function() {
                    $('#recurrence-monthly-frequency-option-0-0').prop('checked', true);
                });
            EE_REM.RECURRING_EVENTS_TABLE.on('change',
                '.exclusion-monthly_freq-by-month-day', function() {
                    $('#exclusion-monthly-frequency-option-0-0').prop('checked', true);
                });

            EE_REM.RECURRING_EVENTS_TABLE.on('change', '.rem-input', function() {
                var patterns = ['recurrence', 'exclusion'];
                $.each(patterns, function(key, pattern) {
                    EE_REM.RRULE_OPTIONS = {};
                    if (!(pattern === 'exclusion' && EE_REM.NO_EXLCUSIONS.prop('checked'))) {
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
                    }
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
            } else if (moment(date_time_string, 'YYYY-MM-DD hh:mm a').isValid()) {
                date = moment(date_time_string, 'YYYY-MM-DD hh:mm a');
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
            // console_log('function', 'EE_REM.displayRRule()', true);
            var rule_text = 'none';
            if (typeof EE_REM.RRULE_OPTIONS.freq !== 'undefined' && EE_REM.RRULE_OPTIONS.freq > 0) {
                EE_REM.RRULE = new RRule(EE_REM.RRULE_OPTIONS, true);
                rule_text    = EE_REM.RRULE.toText();
            } else {
                console_log('', 'RESET EE_REM.RRULE', false);
                // EE_REM.dumpRRuleOptionOptions();
                EE_REM.RRULE = {};
            }
            if (pattern === 'recurrence' && EE_REM.RRULE instanceof RRule) {
                $('#rem-recurrence-string-display').html(EE_REM.RRULE.toString());
                $('#rem-recurrence-string').val(EE_REM.RRULE.toString());
            }
            $('#' + pattern + '-desc').html(rule_text);
        };

        /**
         * @function
         */
        this.generateDatetimes = function(pattern) {
            // console_log('function', 'EE_REM.generateDatetimes(pattern = ' + pattern + ')', true);
            var index = -1;
            // console_log('EE_REM.RRULE instanceof RRule', EE_REM.RRULE instanceof RRule, false);
            if (EE_REM.RRULE instanceof RRule === false) {
                console_log('', 'could not generate Datetimes because EE_REM.RRULE is not a valid instance of RRule', false);
                // EE_REM.dumpRRuleOptionOptions();
                return;
            }
            var dates = EE_REM.RRULE.all(function(date, i) {
                return i < 60 || (
                    typeof EE_REM.RRULE.options.count !== 'undefined'
                    && EE_REM.RRULE.options.count > 0
                    && EE_REM.RRULE.options.count < 365
                );
            });
            $('#rem-' + pattern + '-string-display').html(EE_REM.RRULE.toString());
            $('#rem-' + pattern + '-string').val(EE_REM.RRULE.toString());
            if (dates.length < 1) {
                console_log('dates.length', dates.length, false);
                // EE_REM.dumpRRuleOptions();
                return;
            }
            if (pattern === 'recurrence') {
                EE_REM.DATETIMES = dates;
                $.each(EE_REM.DATETIMES, function(key, date) {
                    // console_log('recurrence', date.toString(), false);
                });
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
        };

        /**
         * @function
         */
        this.displayGeneratedDatetimes = function() {
            // console_log('function', 'EE_REM.displayGeneratedDatetimes()', true);
            var html           = '',
                count          = 1,
                datetimes_json = {};
            $.each(EE_REM.DATETIMES, function(key, date) {
                // console_log('date', date.toString(), false);
                html += '<div class="rem-generated-datetime">' + count + ') ' + date.toString() + '</div>';
                datetimes_json[count] = date.toString();
                count++;
            });
            $('#rem-generated-datetimes').html(html);
            $('#rem-generated-datetimes-json').val(JSON.stringify(datetimes_json));
        };

        /**
         * @function
         */
        this.displaySelectedFrequency = function($freq, recurrence) {
            // console_log('function', 'EE_REM.displaySelectedFrequency()', true);
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
            // console_log('function', 'EE_REM.resetRecurrenceFrequency()', true);
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
            // console_log('function', 'EE_REM.setDayOfMonthOrdinalSuffix()', true);
            var day_of_month                = $day_of_month.val();
            var day_of_month_ordinal_suffix = '#' +
                $day_of_month.data('day_of_month');
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
            // console_log('function', 'EE_REM.recurrenceEndsOption()', true);
            $('.recurrence-ends-options').hide();
            var end_option = '#' + pattern + '-ends-option-' + $ends_option.val();
            // console_log('end_option', end_option, false);
            $(end_option).show();
        };

        /**
         * @function
         */
        this.setInterval = function(pattern, frequency) {
            // console_log('function', 'EE_REM.setInterval()', true);
            // console_log('pattern', pattern, false);
            // console_log('frequency', frequency, false);
            var interval = $('#' + pattern + '-' + frequency + '-interval').val();
            // console_log('interval', interval, false);
            if (interval > 1 && typeof EE_REM.RRULE_OPTIONS.interval === 'undefined') {
                EE_REM.RRULE_OPTIONS.interval = interval;
                // $('.' + pattern + '-interval').val(interval);
                var every = frequency.replace('ly', '');
                every     = every === 'dai' ? 'day' : every;
                // console_log('every', every, false);
                // console_log("'.' + pattern + '-every-span'", '.' + pattern + '-every-span', false);
                $('.' + pattern + '-every-span').html(every);
            }
        };

        /**
         * @function
         */
        this.parseFreqDaily = function(pattern) {
            // console_log('function', 'EE_REM.parseFreqDaily()', true);
            if (typeof EE_REM.RRULE_OPTIONS.freq === 'undefined') {
                EE_REM.RRULE_OPTIONS.freq = RRule.DAILY;
            }
            EE_REM.setInterval(pattern, 'daily');
        };

        /**
         * @function
         */
        this.parseFreqWeekly = function(pattern) {
            // console_log('function', 'EE_REM.parseFreqWeekly()', true);
            // console_log('pattern', pattern, false);
            // console_log('$("#recurrence-by-weekday-mo").val()', $("#recurrence-by-weekday-mo").val(), false);
            EE_REM.setInterval(pattern, 'weekly');
            // console_log('$("#recurrence-by-weekday-mo").val()', $("#recurrence-by-weekday-mo").val(), false);
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
        };

        /**
         * @function
         */
        this.parseFreqMonthly = function(pattern) {
            // console_log('function', 'EE_REM.parseFreqMonthly()', true);
            EE_REM.setInterval(pattern, 'monthly');
            if ($('#' + pattern + '-monthly-frequency-option-0-0').prop('checked')) {
                EE_REM.RRULE_OPTIONS.bymonthday = [];
                EE_REM.RRULE_OPTIONS.bymonthday.push($('#' + pattern + '-monthly_freq-by-month-day').val());
            } else if ($('#' + pattern + '-monthly-frequency-option-1-1').prop('checked')) {
                EE_REM.RRULE_OPTIONS.byweekday = [];
                var nth_day_of_week            = $('#' + pattern + '-monthly_freq-by-nth-day-of-week').val();
                nth_day_of_week                = parseInt(nth_day_of_week);
                var weekday                    = EE_REM.DAYS[$('#' + pattern + '-monthly_freq-day-of-week').val()];
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
            // console_log('function', 'EE_REM.parseFreqYearly()', true);
            EE_REM.setInterval(pattern, 'yearly');
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
        };

        /**
         * @function
         */
        this.parseFreqYearly = function(pattern) {
            // console_log('function', 'EE_REM.parseFreqYearly()', true);
            EE_REM.setInterval(pattern, 'yearly');
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
        };

        /**
         * @function
         */
        this.dumpRRuleOptions = function() {
            console_log('function', 'EE_REM.dumpRRuleOptions()', true);
            var properties = ['freq', 'interval', 'dtstart', 'until', 'count', 'byweekday', 'bymonthday', 'bymonth'];
            $.each(properties, function(index, property) {
                if (EE_REM.RRULE.options[property] !== null) {
                    console_log('EE_REM.RRULE.options.' + property, EE_REM.RRULE.options[property].toString(), false);
                }
            });
            // console_log('EE_REM.RRULE.options.freq', EE_REM.RRULE.options.freq, false);
            // console_log('EE_REM.RRULE.options.interval', EE_REM.RRULE.options.interval, false);
            // console_log('EE_REM.RRULE.options.dtstart', EE_REM.RRULE.options.dtstart.toString(), false);
            // console_log('EE_REM.RRULE.options.until', EE_REM.RRULE.options.until.toString(), false);
            // console_log('EE_REM.RRULE.options.count', EE_REM.RRULE.options.count, false);
            // console_log('EE_REM.RRULE.options.byweekday', EE_REM.RRULE.options.byweekday.toString(), false);
            // console_log('EE_REM.RRULE.options.bymonthday', EE_REM.RRULE.options.bymonthday.toString(), false);
            // console_log('EE_REM.RRULE.options.bymonth', EE_REM.RRULE.options.bymonth.toString(), false);
        };

        /**
         * @function
         */
        this.dumpRRuleOptionOptions = function() {
            console_log('function', 'EE_REM.dumpRRuleOptionOptions()', true);
            var properties = ['freq', 'interval', 'dtstart', 'until', 'count', 'byweekday', 'bymonthday', 'bymonth'];
            $.each(properties, function(index, property) {
                if (typeof EE_REM.RRULE_OPTIONS[property] !== 'undefined' && EE_REM.RRULE_OPTIONS[property] !== null) {
                    console_log('EE_REM.RRULE_OPTIONS.' + property, EE_REM.RRULE_OPTIONS[property].toString(), false);
                }
            });

            // console_log('EE_REM.RRULE_OPTIONS.freq', EE_REM.RRULE_OPTIONS.freq, false);
            // console_log('EE_REM.RRULE_OPTIONS.interval', EE_REM.RRULE_OPTIONS.interval, false);
            // if (typeof EE_REM.RRULE_OPTIONS.dtstart === Date) {
            //   console_log('EE_REM.RRULE_OPTIONS.dtstart', EE_REM.RRULE_OPTIONS.dtstart.toString(), false);
            // } else {
            //   console_log('EE_REM.RRULE_OPTIONS.dtstart', EE_REM.RRULE_OPTIONS.dtstart, false);
            // }
            // if (typeof EE_REM.RRULE_OPTIONS.until === Date) {
            //   console_log('EE_REM.RRULE_OPTIONS.until', EE_REM.RRULE_OPTIONS.until.toString(), false);
            // } else {
            //   console_log('EE_REM.RRULE_OPTIONS.until', EE_REM.RRULE_OPTIONS.until, false);
            // }
            // console_log('EE_REM.RRULE_OPTIONS.count', EE_REM.RRULE_OPTIONS.count, false);
            // console_log('EE_REM.RRULE_OPTIONS.byweekday', EE_REM.RRULE_OPTIONS.byweekday, false);
            // console_log('EE_REM.RRULE_OPTIONS.bymonthday', EE_REM.RRULE_OPTIONS.bymonthday, false);
            // console_log('EE_REM.RRULE_OPTIONS.bymonth', EE_REM.RRULE_OPTIONS.bymonth, false);
        };

    };

    EE_REM.initialize();

});
