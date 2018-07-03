<?php

namespace EventEspresso\RecurringEvents\src\ui\admin;

use EE_Error;
use EE_Event;
use EventEspresso\core\exceptions\ExceptionStackTraceDisplay;
use EventEspresso\core\exceptions\InvalidFormSubmissionException;
use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\core\services\request\RequestInterface;
use EventEspresso\RecurringEvents\src\domain\Domain;
use EventEspresso\RecurringEvents\src\ui\admin\forms\EventEditorRecurrencePatternsFormHandler;
use Exception;
use LogicException;

defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * Class RecurringEventsAdminUpdate
 * logic for hooking into Events Admin Pages during saves and updates
 *
 * @package EventEspresso\RecurringEvents\src\ui\admin
 * @author  Brent Christensen
 * @since   $VID:$
 */
class RecurringEventsAdminUpdate
{

    /**
     * @var Domain $domain
     */
    private $domain;

    /**
     * @var RequestInterface $request
     */
    private $request;

    /**
     * @var EventEditorRecurrencePatternsFormHandler $recurrence_patterns_form_handler
     */
    public $recurrence_patterns_form_handler;

    /**
     * @var LoaderInterface
     */
    private $loader;


    /**
     * RecurringEventsAdmin constructor.
     *
     * @param Domain           $domain
     * @param RequestInterface $request
     * @param LoaderInterface  $loader
     */
    public function __construct(Domain $domain, RequestInterface $request, LoaderInterface $loader)
    {
        $this->domain = $domain;
        $this->request = $request;
        $this->loader = $loader;
    }


    public function setHooks()
    {
        add_filter(
            'FHEE__Events_Admin_Page___insert_update_cpt_item__event_update_callbacks',
            array($this, 'eventUpdateCallbacks'), 5
        );
    }


    /**
     * @param array $event_update_callbacks
     * @return array
     */
    public function eventUpdateCallbacks(array $event_update_callbacks)
    {
        // foreach ($event_update_callbacks as $key => $callback) {
        //     if ($callback[1] === 'datetime_and_tickets_caf_update') {
        //         // unset($event_update_callbacks[ $key ]);
        //     }
        // }
        $event_update_callbacks[] = array($this, 'remEditorUpdate');
        return $event_update_callbacks;
    }


    /**
     * @param EE_Event $event
     * @param array    $form_data
     * @return void
     * @throws LogicException
     * @throws EE_Error
     * @throws Exception
     */
    public function remEditorUpdate(EE_Event $event, array $form_data)
    {
        \EEH_Debug_Tools::printr(__FUNCTION__, __CLASS__, __FILE__, __LINE__, 2);
        $this->recurrence_patterns_form_handler = $this->loader->getShared(
            'EventEspresso\RecurringEvents\src\ui\admin\forms\EventEditorRecurrencePatternsFormHandler',
            array($event)
        );
        // try {
            $this->recurrence_patterns_form_handler->process($form_data);
        // } catch (InvalidFormSubmissionException $exception) {
        //     new ExceptionStackTraceDisplay($exception);
        // }
        exit();
    }
}
