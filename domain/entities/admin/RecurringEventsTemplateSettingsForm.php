<?php

namespace EventEspresso\RecurringEvents\domain\entities\admin;

use EE_Admin_Two_Column_Layout;
use EE_Div_Per_Section_Layout;
use EE_Error;
use EE_Form_Section_HTML;
use EE_Form_Section_Proper;
use EE_Integer_Input;
use EE_Select_Input;
use EE_Yes_No_Input;
use EEH_HTML;
use EventEspresso\RecurringEvents\domain\entities\config\RecurringEventsConfig;

/**
 * Class RecurringEventsTemplateSettingsForm
 *
 * @package EventEspresso\RecurringEvents\domain\entities\admin
 * @since   $VID:$
 */
class RecurringEventsTemplateSettingsForm extends EE_Form_Section_Proper
{

    /**
     * RecurringEventsTemplateSettingsForm constructor.
     *
     * @param RecurringEventsConfig $config
     * @throws EE_Error
     */
    public function __construct(RecurringEventsConfig $config)
    {
        parent::__construct(
            array(
                'name'            => 'rem_settings_form',
                'html_id'         => 'rem_settings_form',
                'layout_strategy' => new EE_Div_Per_Section_Layout(),
                'subsections'     => apply_filters(
                    'FHEE__EED_Ticket_Selector_Caff___rem_settings_form__form_subsections',
                    array(
                        'rem_settings_hdr' => new EE_Form_Section_HTML(
                            EEH_HTML::br(2) .
                            EEH_HTML::h2(esc_html__('Recurring Events Template Settings', 'event_espresso'))
                        ),
                        'rem_settings'     => new EE_Form_Section_Proper(
                            array(
                                'name'            => 'rem_settings_tbl',
                                'html_id'         => 'rem_settings_tbl',
                                'html_class'      => 'form-table',
                                'layout_strategy' => new EE_Admin_Two_Column_Layout(),
                                'subsections'     => array(
                                    'numberOfDates' => new EE_Integer_Input(
                                        array(
                                            'html_label_text'         => esc_html__(
                                                'Number of Recurring Dates to Display',
                                                'event_espresso'
                                            ),
                                            'html_help_text'          => sprintf(
                                                esc_html__(
                                                    'Sets the number of dates that will be displayed for all event listings with recurring datetimes. Datetimes will be displayed in the order set in the event editor',
                                                    'event_espresso'
                                                ),
                                                '<br>'
                                            ),
                                            'default'                 => $config->numberOfDates(),
                                            'display_html_label_text' => false,
                                            'min_value'               => 1,
                                        )
                                    ),
                                    'allowScrolling'        => new EE_Yes_No_Input(
                                        array(
                                            'html_label_text'         => esc_html__(
                                                'Allow Scrolling?',
                                                'event_espresso'
                                            ),
                                            'html_help_text'          => esc_html__(
                                                'If set to "Yes" then the "Number of Recurring Dates to Display" value above will control how many dates are initially visible, but the list will be scrollable allowing the remaining dates to be viewed',
                                                'event_espresso'
                                            ),
                                            'default'                 => $config->allowScrolling(),
                                            'display_html_label_text' => false,
                                        )
                                    ),
                                    'showExpired'        => new EE_Yes_No_Input(
                                        array(
                                            'html_label_text'         => esc_html__(
                                                'Show Expired Datetimes?',
                                                'event_espresso'
                                            ),
                                            'html_help_text'          => esc_html__(
                                                'Indicate whether to show expired datetimes in event listings',
                                                'event_espresso'
                                            ),
                                            'default'                 => $config->showExpired(),
                                            'display_html_label_text' => false,
                                        )
                                    ),
                                    'showNextUpcomingOnly'    => new EE_Yes_No_Input(
                                        array(
                                            'html_label_text'         => esc_html__(
                                                'Only Show Next Upcoming Datetime?',
                                                'event_espresso'
                                            ),
                                            'html_help_text'          => esc_html__(
                                                'Whether to only display the very next upcoming datetime in event listings. This is effectively the same as setting the number of datetimes displayed to one, not displaying expired events, and ordering the dates chronologically.',
                                                'event_espresso'
                                            ),
                                            'default'                 => $config->showNextUpcomingOnly(),
                                            'display_html_label_text' => false,
                                        )
                                    ),
                                    'templateStyle'  => new EE_Select_Input(
                                        $config->templateStyleOptions(),
                                        array(
                                            'default'         => $config->templateStyle(),
                                            'html_label_text' => esc_html__('Template Style', 'event_espresso'),
                                            'html_help_text'  => esc_html__(
                                                'Changes the appearance of the dates list for events with recurring dates.',
                                                'event_espresso'
                                            ),
                                        )
                                    ),
                                ),
                            )
                        ),
                    )
                ),
            )
        );
    }
}
