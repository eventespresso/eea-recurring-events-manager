<?php

namespace EventEspresso\RecurringEvents\src\ui\admin;

use DomainException;
use EE_Event;
use EEM_Event;
use EventEspresso\core\exceptions\ExceptionStackTraceDisplay;
use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\RecurringEvents\src\domain\Domain;
use EventEspresso\RecurringEvents\src\ui\admin\forms\EventEditorRecurrencePatternsFormHandler;
 use EventEspresso\RecurringEvents\src\ui\admin\forms\EventEditorTicketsAndDatetimesForm;
use Events_Admin_Page;
use Exception;

defined('EVENT_ESPRESSO_VERSION') || exit('NO direct script access allowed');



/**
 * RecurringEventsAdmin
 * logic for hooking into Events Admin Pages.
 *
 * @package EventEspresso\RecurringEvents\src\ui
 * @author  Brent Christensen
 * @since   $VID:$
 */
class RecurringEventsAdmin
{

    /**
     * @var EEM_Event $event_model
     */
    public $event_model;

    /**
     * @var Domain $domain
     */
    private $domain;

    /**
     * @var EventEditorTicketsAndDatetimesForm $event_editor_tickets_and_datetimes_form
     */
    public $event_editor_tickets_and_datetimes_form;

    /**
     * @var EventEditorRecurrencePatternsFormHandler $recurrence_patterns_form_handler
     */
    public $recurrence_patterns_form_handler;

    /**
     * @var LoaderInterface $loader
     */
    public $loader;


    /**
     * RecurringEventsAdmin constructor.
     *
     * @param EEM_Event       $event_model
     * @param Domain          $domain
     * @param LoaderInterface $loader
     */
    public function __construct(EEM_Event $event_model, Domain $domain, LoaderInterface $loader) {
        $this->event_model = $event_model;
        $this->domain      = $domain;
        $this->loader      = $loader;
    }



    public function setHooks() {
        add_action(
            'AHEE__caffeinated_admin_new_pricing_templates__event_tickets_datetime_edit_row__actions_column_last',
            array($this, 'editDatetimeRecurrenceAction'),
            10, 2
        );
        add_action(
            'AHEE__caffeinated_admin_new_pricing_templates__event_tickets_metabox_main__metabox_bottom',
            array($this, 'recurrencePatternsForm'), 10, 3
        );
        // add_action('admin_enqueue_scripts', array($this, 'addMetaboxes'));
        // add_action(
        //     'admin_enqueue_scripts',
        //     array($this, 'removeMetaboxes'),
        //     25
        // );
        // add_filter(
        //     'FHEE__Events_Admin_Page___insert_update_cpt_item__event_update_callbacks',
        //     array($this, 'eventUpdateCallbacks')
        // );
        // add_filter(
        //     'FHEE__Extend_Events_Admin_Page__page_setup__page_config',
        //     array($this, 'setupPageConfig'),
        //     999,
        //     2
        // );
        add_filter(
            'FHEE__EE_Event_Editor_Tips___set_tips_array__qtipsa',
            array($this, 'remQtips'),
            999,
            2
        );
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
    }


    /**
     * @param EE_Event $event
     * @return EventEditorRecurrencePatternsFormHandler
     * @throws Exception
     * @since  1.0.0
     */
    public function getRecurrencePatternsFormHandler(EE_Event $event)
    {
        if(! $this->recurrence_patterns_form_handler instanceof EventEditorRecurrencePatternsFormHandler){
            try {
                $this->recurrence_patterns_form_handler = $this->loader->getShared(
                    'EventEspresso\RecurringEvents\src\ui\admin\forms\EventEditorRecurrencePatternsFormHandler',
                    array($event)
                );
            } catch (Exception $exception) {
                new ExceptionStackTraceDisplay($exception);
            }
        }
        return $this->recurrence_patterns_form_handler;
    }



    /**
     * @return void
     */
    public function addMetaboxes()
    {
        // add_meta_box(
        //     'recurrence-patterns-metabox',
        //     esc_html__('Recurring Events Manager', 'event_espresso'),
        //     array($this, 'recurrencePatternsMetaBox'),
        //     EVENTS_PG_SLUG,
        //     'normal', // normal    advanced    side
        //     'high' // high    core    default    low
        // );
        // add_meta_box(
        //     'tickets-and-datetimes-metabox',
        //     esc_html__('Event Tickets & Datetimes', 'event_espresso'),
        //     array($this, 'ticketsAndDatetimesMetaBox'),
        //     EVENTS_PG_SLUG,
        //     'normal', // normal    advanced    side
        //     'high' // high    core    default    low
        // );
    }



    /**
     * @return void
     */
    public function removeMetaboxes()
    {
        // remove_meta_box(
        //     'espresso_events_Pricing_Hooks_pricing_metabox_metabox',
        //     'espresso_events',
        //     'normal'
        // );
    }



    /**
     * enqueue_scripts - Load the scripts and css
     *
     * @return void
     * @throws DomainException
     */
    public function enqueueScripts()
    {
        wp_register_style(
            'recurring_events',
            $this->domain->pluginUrl() . 'ui/css/recurring_events_admin.css',
            array(),
            $this->domain->version()
        );
        wp_register_script(
            'nlp',
            $this->domain->pluginUrl() . 'ui/js/nlp.js',
            array(),
            '2.2.0',
            true
        );
        wp_register_script(
            'rrule',
            $this->domain->pluginUrl() . 'ui/js/rrule.js',
            array('nlp'),
            '2.2.0',
            true
        );
        wp_register_script(
            'recurring_events',
            $this->domain->pluginUrl() . 'ui/js/recurring_events_admin.js',
            array('jquery', 'ee-datepicker', 'rrule'),
            $this->domain->version(),
            true
        );
        wp_enqueue_style('recurring_events');
        wp_enqueue_script('recurring_events');
    }


    /**
     * @return void
     */
    public function ticketsAndDatetimesMetaBox()
    {
        // echo $this->event_editor_tickets_and_datetimes_form->get_html();
    }


    /**
     * @return void
     * @throws \LogicException
     * @throws \EE_Error
     */
    public function editDatetimeRecurrenceAction($dtt_row = 0, $DTT_ID = 0)
    {
        echo '
        <span data-context="datetime" data-datetime-row="'. $dtt_row.'" data-datetime-id="'. $DTT_ID.'" 
        class="dashicons dashicons-image-rotate clickable ee-edit-datetime-recurrence">
        </span>';
    }


    /**
     * @return void
     * @throws \InvalidArgumentException
     * @throws \EventEspresso\core\exceptions\InvalidInterfaceException
     * @throws \EventEspresso\core\exceptions\InvalidDataTypeException
     * @throws \LogicException
     * @throws \EE_Error
     * @throws Exception
     */
    public function recurrencePatternsForm(
        $EVT_ID,
        $existing_datetime_ids,
        $existing_ticket_ids)
    {
        $recurrence_patterns_form_handler = $this->getRecurrencePatternsFormHandler(
            $this->event_model->get_one_by_ID($EVT_ID)
        );
        echo \EEH_HTML::div(
            $recurrence_patterns_form_handler->display(),
            'event-datetime-recurrence-patterns-div', 'hidden','max-width:800px;'
        );
    }


    /**
     * @param array $qtips
     * @return array
     */
    public function remQtips(array $qtips)
    {
        // ee-edit-datetime-recurrence
        $qtips[] = array(
            'content_id' => 'ee-edit-datetime-recurrence-help',
            'target'     => '.ee-edit-datetime-recurrence',
            'content'    => __('Edit Recurring Event Datetimes', 'event_espresso')
        );
        return $qtips;
    }



}
// End of file RecurringEventsAdmin.php
// Location: /ui/admin/RecurringEventsAdmin.php
