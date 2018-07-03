<?php

namespace EventEspresso\RecurringEvents\src\ui\admin\forms;

use DomainException;
use EE_Error;
use EE_Event;
use EE_Form_Section_Proper;
use EE_Registry;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidFormSubmissionException;
use EventEspresso\core\libraries\form_sections\form_handlers\FormHandler;
use EventEspresso\WpUser\domain\entities\exceptions\WpUserLogInRequiredException;
use InvalidArgumentException;
use LogicException;
use ReflectionException;
use RuntimeException;

defined('EVENT_ESPRESSO_VERSION') || exit;



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
     * @throws LogicException
     * @throws InvalidFormSubmissionException
     * @throws EE_Error
     */
    public function process($form_data = array())
    {
        // process form
        // \EEH_Debug_Tools::printr($form_data, '$form_data', __FILE__, __LINE__);
        $valid_data = (array) parent::process($form_data);
        if (empty($valid_data)) {
            throw new InvalidFormSubmissionException($this->formName());
        }
        $recurrence_patterns_form_inputs = (array) $valid_data['recurring_events'];
        if (empty($recurrence_patterns_form_inputs)) {
            throw new InvalidFormSubmissionException($this->formName());
        }
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
