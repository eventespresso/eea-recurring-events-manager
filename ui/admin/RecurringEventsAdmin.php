<?php

namespace EventEspresso\RecurringEvents\ui\admin;

use DomainException;
use EventEspresso\core\exceptions\ExceptionStackTraceDisplay;
use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\RecurringEvents\domain\Domain;
use EventEspresso\RecurringEvents\ui\admin\forms\EventEditorRecurrencePatternsFormHandler;
use EventEspresso\RecurringEvents\ui\admin\forms\EventEditorTicketsAndDatetimesForm;
use Events_Admin_Page;
use Exception;

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
     * @param Domain          $domain
     * @param LoaderInterface $loader
     */
    public function __construct(Domain $domain, LoaderInterface $loader) {
        $this->domain = $domain;
        $this->loader = $loader;
    }



    public function setHooks() {
        add_action('admin_enqueue_scripts', array($this, 'addMetaboxes'));
        add_action(
            'admin_enqueue_scripts',
            array($this, 'removeMetaboxes'),
            25
        );
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
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
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
            $this->admin_page                              = $admin_page;
            // $this->event_editor_tickets_and_datetimes_form = new EventEditorTicketsAndDatetimesForm(
            //     $this->admin_page->get_event_object()
            // );
            $this->recurrence_patterns_form_handler = $this->loader->getShared(
                'EventEspresso\RecurringEvents\ui\admin\forms\EventEditorRecurrencePatternsFormHandler',
                array($this->admin_page->get_event_object())
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
            'recurrence-patterns-metabox',
            esc_html__('Recurring Events Manager', 'event_espresso'),
            array($this, 'recurrencePatternsMetaBox'),
            EVENTS_PG_SLUG,
            'normal', // normal    advanced    side
            'high' // high    core    default    low
        );
        add_meta_box(
            'tickets-and-datetimes-metabox',
            esc_html__('Event Tickets & Datetimes', 'event_espresso'),
            array($this, 'ticketsAndDatetimesMetaBox'),
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
    public function recurrencePatternsMetaBox()
    {
        echo $this->recurrence_patterns_form_handler->display();
    }



}
// End of file RecurringEventsAdmin.php
// Location: /ui/admin/RecurringEventsAdmin.php
