<?php

namespace EventEspresso\RecurringEvents\src\ui\admin\forms;

use DomainException;
use EE_Error;
use EE_Event;
use EE_Form_Section_Proper;
use EE_Recurrence;
use EE_Registry;
use EEM_Datetime_Recurrence;
use EEM_Recurrence;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidFormSubmissionException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\RecurringEvents\src\domain\entities\RecurringDatetime;
use InvalidArgumentException;
use LogicException;
use ReflectionException;
use RuntimeException;



/**
 * Class EventEditorRecurringEventsFormHandler
 * Description
 *
 * @package EventEspresso\RecurringEvents\src\ui\admin\forms
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EventEditorRecurrencePatternsFormHandler extends FormHandler
{

    /**
     * @var EE_Event $event
     */
    protected $event;


    /**
     * Form constructor.
     *
     * @param EE_Event    $event
     * @param EE_Registry $registry
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     */
    public function __construct(EE_Event $event, EE_Registry $registry)
    {
        parent::__construct(
            esc_html__('Event Editor Recurrence Patterns', 'event_espresso'),
            esc_html__('Event Editor Recurrence Patterns', 'event_espresso'),
            'event_editor_recurrence_patterns',
            '',
            FormHandler::DO_NOT_SETUP_FORM,
            $registry
        );
        $this->event = $event;
    }


    /**
     * creates and returns the actual form
     *
     * @return EE_Form_Section_Proper
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws ReflectionException
     */
    public function generate()
    {
        return $this->registry->create(
            'EventEspresso\RecurringEvents\src\ui\admin\forms\EventEditorRecurrencePatternsForm',
            array($this->event)
        );
    }


    /**
     * handles processing the form submission
     * returns true or false depending on whether the form was processed successfully or not
     *
     * @param array $form_data
     * @return void
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidFormSubmissionException
     * @throws InvalidInterfaceException
     * @throws LogicException
     * @throws ReflectionException
     */
    public function process($form_data = array())
    {
        // process form
        // \EEH_Debug_Tools::printr($form_data, '$form_data', __FILE__, __LINE__);
        $valid_data = (array) parent::process($form_data);
        if (
            ! isset(
                $valid_data['recurrence']['recurrence'],
                $valid_data['exclusion']['exclusion'],
                $valid_data['datetimes_json']
            )
        ) {
            throw new InvalidFormSubmissionException($this->formName());
        }
        // \EEH_Debug_Tools::printr($valid_data, '$valid_data', __FILE__, __LINE__);
        $recurrence_pattern = isset($valid_data['recurrence']['recurrence']['until']['rrule_string'])
            ? $valid_data['recurrence']['recurrence']['until']['rrule_string']
            : '';
        $exclusion_pattern = isset($valid_data['exclusion']['exclusion']['until']['rrule_string'])
            ? $valid_data['exclusion']['exclusion']['until']['rrule_string']
            : '';
        $pattern_hash = EEM_Recurrence::instance()->generatePatternHash(
            $recurrence_pattern,
            $exclusion_pattern
        );
        $recurrence = EEM_Recurrence::instance()->findRecurrenceByPatternHash($pattern_hash);
        if(! $recurrence instanceof EE_Recurrence) {
            \EEH_Debug_Tools::printr(__FUNCTION__, __CLASS__, __FILE__, __LINE__, 2);
            $recurrence = EE_Recurrence::new_instance(
                array(
                    'RCR_pattern_hash'       => $pattern_hash,
                    'RCR_recurrence_pattern' => $recurrence_pattern,
                    'RCR_exclusion_pattern'  => $exclusion_pattern,
                    'RCR_dates'              => $valid_data['datetimes_json']
                )
            );
            $recurrence->save();
        }
        \EEH_Debug_Tools::printr($recurrence->ID(), '$recurrence->ID()', __FILE__, __LINE__);
        \EEH_Debug_Tools::printr($recurrence->patternHash(), 'patternHash', __FILE__, __LINE__);
        \EEH_Debug_Tools::printr($recurrence->recurrencePattern(), 'recurrencePattern', __FILE__, __LINE__);
        \EEH_Debug_Tools::printr($recurrence->exclusionPattern(), 'exclusionPattern', __FILE__, __LINE__);
        \EEH_Debug_Tools::printr($recurrence->recurrenceDatesJson(), 'recurrenceDatesJson', __FILE__, __LINE__);
        \EEH_Debug_Tools::printr($recurrence->recurrenceDatesArray(), 'recurrenceDatesArray', __FILE__, __LINE__);
        $existing_datetimes    = $this->event->datetimes();
        $existing_datetime_IDs = array_keys($existing_datetimes);
        \EEH_Debug_Tools::printr($existing_datetime_IDs, '$existing_datetime_IDs', __FILE__, __LINE__);
        $existing_recurrences = EEM_Recurrence::instance()->findRecurrencesForDatetimes($existing_datetime_IDs);
        \EEH_Debug_Tools::printr($existing_recurrences, '$existing_recurrences', __FILE__, __LINE__);
        // $datetimes_json = json_decode($valid_data['datetimes_json'], true);
        // $datetimes = array();
        // $PST = new \DateTimeZone(get_option('timezone_string'));
        // foreach ($datetimes_json as $key => $js_datetime) {
            // $datetimes[ $key ] = RecurringDatetime::createFromTimeStamp(
            //     $js_datetime / 1000,
            //     isset($valid_data['recurrence']['recurrence']['until']['rrule_string'])
            //         ? $valid_data['recurrence']['recurrence']['until']['rrule_string']
            //         : '',
            //     isset($valid_data['exclusion']['exclusion']['until']['rrule_string'])
            //         ? $valid_data['exclusion']['exclusion']['until']['rrule_string']
            //         : ''
            // );
            // $datetimes[ $key ] =
            // \EEH_Debug_Tools::printr(
            //     $datetimes[ $key ]->start_date_and_time(),
            //     'Recurring Datetime start_date_and_time',
            //     __FILE__, __LINE__
            // );
            // \EEH_Debug_Tools::printr(
            //     $datetimes[ $key ]->getRecurrencePattern(),
            //     'Recurring Datetime Recurrence Pattern',
            //     __FILE__, __LINE__
            // );
            // \EEH_Debug_Tools::printr(
            //     $datetimes[ $key ]->getExclusionPattern(),
            //     'Recurring Datetime Exclusion Pattern',
            //     __FILE__, __LINE__
            // );

        // }
        /*

1) Wed Feb 21 2018 18:00:00 GMT-0800 (Pacific Standard Time)
2) Wed Mar 07 2018 18:00:00 GMT-0800 (Pacific Standard Time)
3) Wed Mar 21 2018 18:00:00 GMT-0700 (Pacific Summer Time)
4) Wed Apr 04 2018 18:00:00 GMT-0700 (Pacific Summer Time)
5) Wed Apr 18 2018 18:00:00 GMT-0700 (Pacific Summer Time)
6) Wed May 02 2018 18:00:00 GMT-0700 (Pacific Summer Time)
7) Wed May 16 2018 18:00:00 GMT-0700 (Pacific Summer Time)
8) Wed May 30 2018 18:00:00 GMT-0700 (Pacific Summer Time)
9) Wed Jun 13 2018 18:00:00 GMT-0700 (Pacific Summer Time)
10) Wed Jun 27 2018 18:00:00 GMT-0700 (Pacific Summer Time)
11) Wed Jul 11 2018 18:00:00 GMT-0700 (Pacific Summer Time)
12) Wed Jul 25 2018 18:00:00 GMT-0700 (Pacific Summer Time)
13) Wed Aug 08 2018 18:00:00 GMT-0700 (Pacific Summer Time)
14) Wed Aug 22 2018 18:00:00 GMT-0700 (Pacific Summer Time)
15) Wed Sep 05 2018 18:00:00 GMT-0700 (Pacific Summer Time)
16) Wed Sep 19 2018 18:00:00 GMT-0700 (Pacific Summer Time)
17) Wed Oct 03 2018 18:00:00 GMT-0700 (Pacific Summer Time)
18) Wed Oct 17 2018 18:00:00 GMT-0700 (Pacific Summer Time)
19) Wed Oct 31 2018 18:00:00 GMT-0700 (Pacific Summer Time)
20) Wed Nov 14 2018 18:00:00 GMT-0800 (Pacific Standard Time)
21) Wed Nov 28 2018 18:00:00 GMT-0800 (Pacific Standard Time)
22) Wed Dec 12 2018 18:00:00 GMT-0800 (Pacific Standard Time)
23) Wed Dec 26 2018 18:00:00 GMT-0800 (Pacific Standard Time)
24) Wed Jan 09 2019 18:00:00 GMT-0800 (Pacific Standard Time)
25) Wed Jan 23 2019 18:00:00 GMT-0800 (Pacific Standard Time)
26) Wed Feb 06 2019 18:00:00 GMT-0800 (Pacific Standard Time)


        */
        // return $this->registry->BUS->execute(
        //     $this->registry->create(
        //         'EventEspresso\WaitList\domain\services\commands\CreateWaitListRegistrationsCommand',
        //         array(
        //             isset($wait_list_form_inputs['registrant_name'])
        //                 ? $wait_list_form_inputs['registrant_name']
        //                 : '',
        //         )
        //     )
        // );
    }
}
