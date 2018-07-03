<?php

namespace EventEspresso\RecurringEvents\src\ui\admin\forms;

use EE_Error;
use EE_Event;
use EE_Form_Section_Proper;

defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * Class EventEditorTicketsAndDatetimesForm
 * Description
 *
 * @package EventEspresso\RecurringEvents\src\ui\admin\forms
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EventEditorTicketsAndDatetimesForm extends EE_Form_Section_Proper
{

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
        return  array();
    }
}
