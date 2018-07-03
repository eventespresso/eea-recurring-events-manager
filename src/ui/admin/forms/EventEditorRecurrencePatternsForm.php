<?php

namespace EventEspresso\RecurringEvents\src\ui\admin\forms;

use EE_Admin_Two_Column_Layout;
use EE_Checkbox_Multi_Input;
use EE_Datepicker_Input;
use EE_Error;
use EE_Event;
use EE_Form_Section_HTML;
use EE_Form_Section_Proper;
use EE_Hidden_Input;
use EE_Integer_Input;
use EE_No_Layout;
use EE_Radio_Button_Input;
use EE_Select_Input;
use EEH_HTML;
use EventEspresso\core\libraries\form_sections\strategies\filter\VsprintfFilter;

defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * Class EventEditorRecurringEventsForm
 * Description
 *
 * @package EventEspresso\RecurringEvents\src\ui\forms
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EventEditorRecurrencePatternsForm extends EE_Form_Section_Proper
{

    const PATTERN_TYPE_RECURRENCE = 'recurrence';

    const PATTERN_TYPE_EXCLUSION  = 'exclusion';

    /**
     * @var EE_Event $event
     */
    protected $event;

    /**
     * @var string $date_format
     */
    protected $date_format;

    /**
     * @var string $time_format
     */
    protected $time_format;

    /**
     * @var string $now
     */
    protected $now;


    /**
     * EventEditorRecurringEventsForm constructor.
     *
     * @param EE_Event $event
     * @throws EE_Error
     */
    public function __construct(EE_Event $event)
    {
        $this->event       = $event;
        $this->date_format = 'Y-m-d';
        $this->time_format = 'h:i a';
        $this->now         = date("{$this->date_format} {$this->time_format}");
        parent::__construct(
            $this->formOptions()
        );
    }



    /**
     * @return array
     * @throws EE_Error
     */
    private function formOptions()
    {
        return array(
            'name'            => 'recurring_events',
            'html_id'         => 'recurring_events',
            'layout_strategy' => new EE_Admin_Two_Column_Layout(),
            'subsections'     => array(
                'recurrence' => $this->recurrenceDescription(
                    EventEditorRecurrencePatternsForm::PATTERN_TYPE_RECURRENCE
                ),
                'exclusion'  => $this->recurrenceDescription(
                    EventEditorRecurrencePatternsForm::PATTERN_TYPE_EXCLUSION
                ),
                'datetimes' => new EE_Form_Section_HTML(
                    EEH_HTML::tr(
                        EEH_HTML::th(
                            esc_html__('Datetimes to be Generated', 'event_espresso'),
                            '',
                            '',
                            '',
                            'scope="row"'
                        )
                        . EEH_HTML::td(
                            EEH_HTML::div(
                                '',
                                'rem-generated-datetimes'
                            )
                        )
                    )
                ),
                'datetimes_json' => new EE_Hidden_Input(
                    array(
                        'html_id' => 'rem-generated-datetimes-json'
                    )
                ),
            ),
        );
    }


    /**
     * @param string $pattern
     * @return EE_Form_Section_Proper
     * @throws EE_Error
     */
    private function recurrenceDescription($pattern)
    {
        $html_label_text = $pattern === EventEditorRecurrencePatternsForm::PATTERN_TYPE_RECURRENCE
            ? esc_html__('Repeats', 'event_espresso')
            : esc_html__('Exclusions', 'event_espresso');
        return new EE_Form_Section_Proper(
            array(
                'name'            => "{$pattern}-form",
                'html_id'         => "{$pattern}-form",
                'layout_strategy' => new EE_Admin_Two_Column_Layout(),
                'subsections'     => array(
                    'desc'   => new EE_Form_Section_HTML(
                        EEH_HTML::tr(
                            EEH_HTML::th(
                                $html_label_text,
                                '',
                                '',
                                '',
                                'scope="row"'
                            )
                            . EEH_HTML::td(
                                EEH_HTML::span(
                                    esc_html__('none', 'event_espresso'),
                                    "{$pattern}-desc",
                                    'pattern-desc-span'
                                )
                                // . EEH_HTML::br()
                                . EEH_HTML::span(
                                    EEH_HTML::link(
                                        '',
                                        "edit {$pattern} pattern",
                                        "edit {$pattern} pattern",
                                        "{$pattern}-edit-pattern-link",
                                        'edit-pattern-link',
                                        '',
                                        ' data-pattern="' . $pattern . '"'
                                    ),
                                    "{$pattern}-edit-pattern-link-span",
                                    'edit-pattern-link-span'
                                )
                            )
                        )
                    ),
                    $pattern => $this->recurrencePattern($pattern),
                ),
            )
        );
    }


    /**
     * @param string $pattern
     * @return EE_Form_Section_Proper
     * @throws EE_Error
     */
    private function recurrencePattern($pattern)
    {
        $starts_label = $pattern === EventEditorRecurrencePatternsForm::PATTERN_TYPE_RECURRENCE
            ? esc_html__('Recurrence', 'event_espresso')
            : esc_html__('Exclusion', 'event_espresso');
        $frequencies = array(
            'DAILY'   => esc_html__('Daily', 'event_espresso'),
            'WEEKLY'  => esc_html__('Weekly', 'event_espresso'),
            'MONTHLY' => esc_html__('Monthly', 'event_espresso'),
            // 'YEARLY'  => esc_html__('Yearly', 'event_espresso'),
        );
        if ($pattern === EventEditorRecurrencePatternsForm::PATTERN_TYPE_EXCLUSION) {
            $frequencies = array('NONE' => esc_html__('None', 'event_espresso')) + $frequencies;
        }
        $default = $pattern === EventEditorRecurrencePatternsForm::PATTERN_TYPE_RECURRENCE
            ? 'DAILY'
            : 'NONE';
        return new EE_Form_Section_Proper(
            array(
                'name'            => $pattern,
                'html_id'         => $pattern,
                'html_class'      => 'pattern-edit-form',
                'layout_strategy' => new EE_Admin_Two_Column_Layout(),
                'subsections'     => array(
                    'dtstart' => new EE_Datepicker_Input(
                        array(
                            'html_label_text' => $starts_label,
                            'html_id'         => $pattern . '-dtstart',
                            'html_class'      => 'regular-text rem-datepicker rem-input',
                            'default'         => $this->now,
                            'required'        => true,
                        )
                    ),
                    'freq'    => new EE_Checkbox_Multi_Input(
                        $frequencies,
                        array(
                            'html_label_text'  => '',
                            'default'          => $default,
                            'html_id'          => $pattern . '-freq',
                            'html_class'       => "{$pattern}_freq_option rem-input",
                            'html_label_class' => "{$pattern}_freq_option_label",
                        )
                    ),
                    'daily'   => $this->dailyFrequencySubsection($pattern),
                    'weekly'  => $this->weeklyFrequencySubsection($pattern),
                    'monthly' => $this->monthlyFrequencySubsection($pattern),
                    // 'yearly'  => $this->yearlyFrequencySubsection($pattern),
                    'until'   => $this->recurrenceEnds($pattern),
                ),
            )
        );
    }


    /**
     * @param string $pattern
     * @return EE_Form_Section_Proper
     * @throws EE_Error
     */
    private function dailyFrequencySubsection($pattern)
    {
        return new EE_Form_Section_Proper(
            array(
                'name'             => 'daily_freq',
                'html_id'          => 'daily_freq',
                'layout_strategy'  => new EE_No_Layout(array('use_break_tags' => false)),
                'form_html_filter' => new VsprintfFilter(
                    esc_html__(
                        '%1$sEvery %6$s %3$sday%4$s(s)%2$s',
                        'event_espresso'
                    ),
                    array(
                        '<div id="'
                        . $pattern
                        . '-freq-daily-section" class="'
                        . $pattern
                        . '_freq" style="display:block;"><div>',
                        '</div></div>',
                        '<span class="' . $pattern . '-every-span">',
                        '</span>',
                    )
                ),
                'subsections'      => array(
                    'interval' => new EE_Integer_Input(
                        array(
                            'default'         => 1,
                            'min_value'       => 1,
                            'max_value'       => 365,
                            'html_label_text' => '',
                            'html_id'         => $pattern . '-daily-interval',
                            'html_class'      => $pattern . '-interval small-text rem-input',
                        )
                    ),
                ),
            )
        );
    }


    /**
     * @param string $pattern
     * @return EE_Form_Section_Proper
     * @throws EE_Error
     */
    private function weeklyFrequencySubsection($pattern)
    {
        return new EE_Form_Section_Proper(
            array(
                'name'             => 'weekly_freq',
                'html_id'          => 'weekly_freq',
                'layout_strategy'  => new EE_No_Layout(array('use_break_tags' => false)),
                'form_html_filter' => new VsprintfFilter(
                    esc_html__('%1$sEvery %7$s %4$sweek%5$s(s) on: %3$s%3$s%8$s%2$s', 'event_espresso'),
                    array(
                        '<div id="' . $pattern . '-freq-weekly-section" class="' . $pattern . '_freq"><div>',
                        '</div></div>',
                        '<br />',
                        '<span class="' . $pattern . '-every-span">',
                        '</span>',
                    )
                ),
                'subsections'      => array(
                    'interval'  => new EE_Integer_Input(
                        array(
                            'default'         => 1,
                            'min_value'       => 1,
                            'max_value'       => 52,
                            'html_label_text' => '',
                            'html_id'         => $pattern . '-weekly-interval',
                            'html_class'      => $pattern . '-interval small-text rem-input',
                        )
                    ),
                    'by_weekday' => new EE_Checkbox_Multi_Input(
                        array(
                            'MO' => esc_html__('Monday'),
                            'TU' => esc_html__('Tuesday'),
                            'WE' => esc_html__('Wednesday'),
                            'TH' => esc_html__('Thursday'),
                            'FR' => esc_html__('Friday'),
                            'SA' => esc_html__('Saturday'),
                            'SU' => esc_html__('Sunday'),
                        ),
                        array(
                            'default'         => '',
                            'html_label_text' => '',
                            'html_id'         => $pattern . '-by-weekday',
                            'html_class'      => $pattern . '-interval rem-input',
                        )
                    ),
                ),
            )
        );
    }


    /**
     * @param string $pattern
     * @return EE_Form_Section_Proper
     * @throws EE_Error
     */
    private function monthlyFrequencySubsection($pattern)
    {
        $day_of_month = $pattern . '-day-of-month-ordinal-suffix';
        return new EE_Form_Section_Proper(
            array(
                'name'             => 'monthly_freq',
                'html_id'          => 'monthly_freq',
                'layout_strategy'  => new EE_No_Layout(array('use_break_tags' => false)),
                'form_html_filter' => new VsprintfFilter(
                    esc_html__(
                        '%1$s%4$sEvery %10$s %7$smonth%8$s(s) on the: %8$s'
                        . ' %6$s %11$s %13$s %5$s day of the  month %8$s'
                        . ' %6$s %12$s %14$s %15$s of the  month %8$s%2$s',
                        'event_espresso'
                    ),
                    array(
                        '<div id="' . $pattern . '-freq-monthly-section" class="' . $pattern . '_freq"><div>',
                        '</div></div>',
                        '<br />',
                        '<span class="monthly-frequency-wrapper">',
                        '<span id="' . $day_of_month . '">th</span>',
                        '<span class="monthly-frequency-option-wrapper">',
                        '<span class="' . $pattern . '-every-span">',
                        '</span>',
                        // full form html
                    )
                ),
                'subsections'      => array(
                    'interval'                  => new EE_Integer_Input(
                        array(
                            'default'         => 1,
                            'min_value'       => 1,
                            'max_value'       => 60,
                            'html_label_text' => '',
                            'html_id'         => $pattern . '-monthly-interval',
                            'html_class'      => $pattern . '-interval small-text rem-input',
                        )
                    ),
                    'monthly_frequency_option_0' => new EE_Radio_Button_Input(
                        array(0 => ' '),
                        array(
                            'html_name'       => 'monthly_frequency_option',
                            'html_id'         => $pattern . '-monthly-frequency-option-0',
                            'html_class'      => 'monthly_frequency_option rem-input',
                            'html_label_text' => '',
                            'default'         => 0,
                            'clear_float'     => false,
                        )
                    ),
                    'monthly_frequency_option_1' => new EE_Radio_Button_Input(
                        array(1 => ' '),
                        array(
                            'html_name'       => 'monthly_frequency_option',
                            'html_id'         => $pattern . '-monthly-frequency-option-1',
                            'html_class'      => 'monthly_frequency_option rem-input',
                            'html_label_text' => '',
                            'default'         => '',
                            'clear_float'     => false,
                        )
                    ),
                    'by_month_day'               => new EE_Integer_Input(
                        array(
                            'default'               => 1,
                            'min_value'             => 1,
                            'max_value'             => 31,
                            'html_label_text'       => '',
                            'html_id'               => $pattern . '-monthly_freq-by-month-day',
                            'html_class'            => 'by-month-day small-text rem-input',
                            'other_html_attributes' => ' data-day_of_month="' . $day_of_month . '"',
                        )
                    ),
                    'by_nth_day_of_week'         => new EE_Select_Input(
                        array(
                            '1'  => esc_html__('1st'),
                            '2'  => esc_html__('2nd'),
                            '3'  => esc_html__('3rd'),
                            '4'  => esc_html__('4th'),
                            '5'  => esc_html__('5th'),
                            '-1' => esc_html__('last'),
                        ),
                        array(
                            'default'         => '1',
                            'html_label_text' => '',
                            'html_id'         => $pattern . '-monthly_freq-by-nth-day-of-week',
                            'html_class'      => 'small-text rem-input',
                        )
                    ),
                    'day_of_week'                => new EE_Select_Input(
                        array(
                            'MO' => esc_html__('Monday'),
                            'TU' => esc_html__('Tuesday'),
                            'WE' => esc_html__('Wednesday'),
                            'TH' => esc_html__('Thursday'),
                            'FR' => esc_html__('Friday'),
                            'SA' => esc_html__('Saturday'),
                            'SU' => esc_html__('Sunday'),
                        ),
                        array(
                            'default'         => 'MO',
                            'html_label_text' => '',
                            'html_id'         => $pattern . '-monthly_freq-day-of-week',
                            'html_class'      => 'small-text rem-input',
                        )
                    ),
                ),
            )
        );
    }


    /**
     * @param string $pattern
     * @return EE_Form_Section_Proper
     * @throws EE_Error
     */
    // private function yearlyFrequencySubsection($pattern)
    // {
    //     return new EE_Form_Section_Proper(
    //         array(
    //             'name'             => 'yearly_freq',
    //             'html_id'          => 'yearly_freq',
    //             'layout_strategy'  => new EE_No_Layout(array('use_break_tags' => false)),
    //             'form_html_filter' => new VsprintfFilter(
    //                 esc_html__('%1$sEvery %7$s %4$syear%5$s(s) on: %3$s%3$s%8$s%2$s', 'event_espresso'),
    //                 array(
    //                     '<div id="' . $pattern . '-freq-yearly-section" class="' . $pattern . '_freq"><div>',
    //                     '</div></div>',
    //                     '<br />',
    //                     '<span class="' . $pattern . '-every-span">',
    //                     '</span>',
    //                 )
    //             ),
    //             'subsections'      => array(
    //                 'interval' => new EE_Integer_Input(
    //                     array(
    //                         'default'         => 1,
    //                         'min_value'       => 1,
    //                         'max_value'       => 10,
    //                         'html_label_text' => '',
    //                         'html_id'         => $pattern . '-yearly-interval',
    //                         'html_class'      => $pattern . '-interval small-text rem-input',
    //                     )
    //                 ),
    //                 'by_month'  => new EE_Checkbox_Multi_Input(
    //                     array(
    //                         1  => esc_html__('January'),
    //                         2  => esc_html__('February'),
    //                         3  => esc_html__('March'),
    //                         4  => esc_html__('April'),
    //                         5  => esc_html__('May'),
    //                         6  => esc_html__('June'),
    //                         7  => esc_html__('July'),
    //                         8  => esc_html__('August'),
    //                         9  => esc_html__('September'),
    //                         10 => esc_html__('October'),
    //                         11 => esc_html__('November'),
    //                         12 => esc_html__('December'),
    //                     ),
    //                     array(
    //                         'default'         => '',
    //                         'html_label_text' => '',
    //                         'html_id'         => $pattern . '-yearly_freq-by-month',
    //                         'html_class'      => 'rem-input',
    //                     )
    //                 ),
    //             ),
    //         )
    //     );
    // }


    /**
     * @param $pattern
     * @return EE_Form_Section_Proper
     * @throws EE_Error
     */
    private function recurrenceEnds($pattern)
    {
        if($pattern === EventEditorRecurrencePatternsForm::PATTERN_TYPE_RECURRENCE) {
            $ends_label = esc_html__('Recurrence Ends', 'event_espresso');
            $string_label = esc_html__('Recurrence String', 'event_espresso');
        } else {
            $ends_label = esc_html__('Exclusion Ends', 'event_espresso');
            $string_label = esc_html__('Exclusion String', 'event_espresso');
        }
        return new EE_Form_Section_Proper(
            array(
                'name'            => 'recurrence_ends',
                'html_id'         => 'recurrence_ends',
                'layout_strategy' => new EE_Admin_Two_Column_Layout(),
                'subsections'     => array(
                    'ends'  => new EE_Radio_Button_Input(
                        array(
                            'until' => esc_html__('Until:', 'event_espresso'),
                            'count' => esc_html__('# Times:', 'event_espresso'),
                        ),
                        array(
                            'html_label_text' => $ends_label,
                            'default'         => 'until',
                            'html_id'         => $pattern . '-ends-option-input',
                            'html_class'      => $pattern . '-ends-option-input rem-input',
                        )
                    ),
                    'until' => $this->recurrenceEndsOnDateSubsection($pattern),
                    'count' => $this->recurrenceEndsAfterSubsection($pattern),
                    'rrule_string_display' => new EE_Form_Section_HTML(
                        EEH_HTML::tr(
                            EEH_HTML::th(
                                $string_label,
                                '',
                                '',
                                '',
                                'scope="row"'
                            )
                            . EEH_HTML::td(
                                EEH_HTML::div(
                                    '',
                                    "rem-{$pattern}-string-display"
                                )
                            )
                        )
                    ),
                    'rrule_string' => new EE_Hidden_Input(
                        array(
                            'html_id' => "rem-{$pattern}-string"
                        )
                    ),
                ),
            )
        );
    }


    /**
     * @param string $pattern
     * @return EE_Form_Section_Proper
     * @throws EE_Error
     */
    private function recurrenceEndsOnDateSubsection($pattern)
    {
        $end_date = $pattern === EventEditorRecurrencePatternsForm::PATTERN_TYPE_RECURRENCE
            ? $this->now
            : date(
                "{$this->date_format} {$this->time_format}",
                strtotime($this->now) + MONTH_IN_SECONDS
            );
        return new EE_Form_Section_Proper(
            array(
                'name'             => 'until',
                'html_id'          => 'until',
                'layout_strategy'  => new EE_No_Layout(array('use_break_tags' => false)),
                'form_html_filter' => new VsprintfFilter(
                    esc_html__(
                        '%1$s repeats up to and including %5$s %2$s',
                        'event_espresso'
                    ),
                    array(
                        '<div id="' . $pattern . '-ends-option-until" class="' . $pattern . '-ends-options">',
                        '</div>',
                        '<br />',
                    )
                ),
                'subsections'      => array(
                    'until' => new EE_Datepicker_Input(
                        array(
                            'html_label_text' => '',
                            'html_id'         =>  $pattern . '-ends-until',
                            'html_class'      =>  $pattern . '-ends-option regular-text rem-datepicker rem-input',
                            'default'         => $end_date,
                        )
                    ),
                ),
            )
        );
    }


    /**
     * @param string $pattern
     * @return EE_Form_Section_Proper
     * @throws EE_Error
     */
    private function recurrenceEndsAfterSubsection($pattern)
    {
        return new EE_Form_Section_Proper(
            array(
                'name'             => 'count',
                'html_id'          => 'count',
                'layout_strategy'  => new EE_No_Layout(array('use_break_tags' => false)),
                'form_html_filter' => new VsprintfFilter(
                    esc_html__(
                        '%1$s repeats %5$s time(s) %2$s',
                        'event_espresso'
                    ),
                    array(
                        '<div id="' . $pattern . '-ends-option-count" class="' . $pattern . '-ends-options">',
                        '</div>',
                        '<br />',
                    )
                ),
                'subsections'      => array(
                    'count' => new EE_Integer_Input(
                        array(
                            'default'         => 1,
                            'min_value'       => 1,
                            'max_value'       => 365,
                            'html_label_text' => '',
                            'html_id'         => $pattern . '-ends-count',
                            'html_class'      => $pattern . '-ends-option small-text rem-input',
                        )
                    ),
                ),
            )
        );
    }



}



// Location: /eea-recurring-events-manager/ui/admin/forms/EventEditorRecurringEventsForm.php
