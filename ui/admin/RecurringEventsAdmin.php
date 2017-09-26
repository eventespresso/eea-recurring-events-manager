<?php

namespace EventEspresso\RecurringEvents\ui\admin;

use DomainException;
use EE_Error;
use EventEspresso\core\exceptions\ExceptionStackTraceDisplay;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\RecurringEvents\domain\Domain;
use EventEspresso\RecurringEvents\ui\admin\forms\EventEditorRecurringEventsForm;
use Events_Admin_Page;
use Exception;
use InvalidArgumentException;

defined('EVENT_ESPRESSO_VERSION') || exit('NO direct script access allowed');



/**
 * RecurringEventsManagerAdmin
 * logic for hooking into Events Admin Pages.
 *
 * @package EventEspresso\RecurringEvents\ui
 * @author  Brent Christensen
 * @since   $VID:$
 */
class RecurringEventsAdmin
{

    /**
     * @var Events_Admin_Page $admin_page
     */
    public $admin_page;

    /**
     * @var EventEditorRecurringEventsForm $event_editor_recurring_events_form
     */
    public $event_editor_recurring_events_form;


    /**
     * RecurringEventsAdmin constructor.
     */
    public function __construct() {
    }



    public function setHooks() {
        add_action('admin_enqueue_scripts', array($this, 'addMetaboxes'));
        // add_action(
        //     'admin_enqueue_scripts',
        //     array($this, 'removeMetaboxes'),
        //     25
        // );
        add_filter(
            'FHEE__Events_Admin_Page___insert_update_cpt_item__event_update_callbacks',
            array($this, 'eventUpdateCallbacks')
        );
        add_filter(
            'FHEE__Extend_Events_Admin_Page__page_setup__page_config',
            array($this, 'setupPageConfig'),
            999,
            2
        );
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }


    /**
     * callback for FHEE__Extend_Events_Admin_Page__page_setup__page_config
     *
     * @param array             $page_config current page config.
     * @param Events_Admin_Page $admin_page
     * @return array
     * @throws Exception
     * @since  1.0.0
     */
    public function setupPageConfig(array $page_config, Events_Admin_Page $admin_page)
    {
        try {
            $this->admin_page                         = $admin_page;
            $this->event_editor_recurring_events_form = new EventEditorRecurringEventsForm(
                $this->admin_page->get_event_object()
            );
            return $page_config;
        } catch (Exception $exception) {
            new ExceptionStackTraceDisplay($exception);
        }
        return array();
    }



    /**
     * @return void
     */
    public function addMetaboxes()
    {
        add_meta_box(
            'recurring-events-manager-metabox',
            esc_html__('Recurring Events Manager', 'event_espresso'),
            array($this, 'recurringEventsManagerMetaBox'),
            EVENTS_PG_SLUG,
            'normal', // normal    advanced    side
            'high' // high    core    default    low
        );
    }



    /**
     * @return void
     */
    public function removeMetaboxes()
    {
        remove_meta_box(
            'espresso_events_Pricing_Hooks_pricing_metabox_metabox',
            'espresso_events',
            'normal'
        );
    }



    /**
     * enqueue_scripts - Load the scripts and css
     *
     * @return void
     * @throws DomainException
     */
    public function enqueue_scripts()
    {
        wp_register_style(
            'recurring_events',
            Domain::pluginUrl() . 'ui/css/recurring_events_admin.css',
            array(),
            Domain::version()
        );
        wp_register_script(
            'nlp',
            Domain::pluginUrl() . 'ui/js/nlp.js',
            array(),
            '2.2.0',
            true
        );
        wp_register_script(
            'rrule',
            Domain::pluginUrl() . 'ui/js/rrule.js',
            array('nlp'),
            '2.2.0',
            true
        );
        wp_register_script(
            'recurring_events',
            Domain::pluginUrl() . 'ui/js/recurring_events_admin.js',
            array('jquery', 'ee-datepicker', 'rrule'),
            Domain::version(),
            true
        );
        wp_enqueue_style('recurring_events');
        wp_enqueue_script('recurring_events');
    }


    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function recurringEventsManagerMetaBox()
    {
        echo $this->event_editor_recurring_events_form->get_html();
    }


    /**
     * @param array $event_update_callbacks
     * @return array
     */
    public function eventUpdateCallbacks(array $event_update_callbacks)
    {
        foreach ($event_update_callbacks as $key => $callback) {
            if ($callback[1] === 'datetime_and_tickets_caf_update') {
                unset($event_update_callbacks[$key]);
            }
        }
        $event_update_callbacks[] = array($this, 'remEditorUpdate');
        return $event_update_callbacks;
    }



    /**
     * @return void
     */
    // public function remEditorUpdate($event, $data)
    // {
    //     \EEH_Debug_Tools::printr(__FUNCTION__, __CLASS__, __FILE__, __LINE__, 2);
    // }


}
// End of file RecurringEventsAdmin.php
// Location: /ui/admin/RecurringEventsAdmin.php
