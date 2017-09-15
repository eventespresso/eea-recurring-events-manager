<?php

use EventEspresso\core\services\loaders\LoaderFactory;
use EventEspresso\RecurringEvents\domain\Domain;
use EventEspresso\RecurringEvents\ui\admin\forms\EventEditorRecurringEventsForm;

if (! defined('EVENT_ESPRESSO_VERSION')) {
    exit('No direct script access allowed');
}


/**
 * Class  EED_Recurring_Events
 *
 * This is where miscellaneous action and filters callbacks should be setup to
 * do your addon's business logic (that doesn't fit neatly into one of the
 * other classes in the mock addon)
 *
 * @package     Event Espresso
 * @subpackage  eea-recurring-events
 * @author      Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class EED_Recurring_Events extends EED_Module
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
     * @return EED_Module|EED_Recurring_Events
     */
    public static function instance()
    {
        return parent::get_instance(__CLASS__);
    }



    /**
     * set_hooks - for hooking into EE Core, other modules, etc
     *
     * @return void
     */
    public static function set_hooks()
    {
        EE_Config::register_route('recurring_events', 'EED_Recurring_Events', 'run');
    }



    /**
     * set_hooks_admin - for hooking into EE Admin Core, other modules, etc
     *
     * @return void
     */
    public static function set_hooks_admin()
    {
        $request = LoaderFactory::getLoader()->getShared('EE_Request');
        if(
            $request instanceof EE_Request
            && $request->get('page') === 'espresso_events'
            && $request->get('action') === 'edit'
        ) {

            add_action(
                'admin_enqueue_scripts',
                array(EED_Recurring_Events::instance(), 'addMetaboxes')
            );
            // add_action(
            //     'admin_enqueue_scripts',
            //     array(EED_Recurring_Events::instance(), 'removeMetaboxes'),
            //     25
            // );
            add_filter(
                'FHEE__Events_Admin_Page___insert_update_cpt_item__event_update_callbacks',
                array(EED_Recurring_Events::instance(), 'eventUpdateCallbacks')
            );
            add_filter(
                'FHEE__Extend_Events_Admin_Page__page_setup__page_config',
                array(EED_Recurring_Events::instance(), 'setupPageConfig'),
                999,
                2
            );
            add_action(
                'admin_enqueue_scripts',
                array(EED_Recurring_Events::instance(), 'enqueue_scripts')
            );
        }
    }


    // /**
    //  * config
    //  *
    //  * @return EE_Recurring_Events_Config
    //  */
    // public function config()
    // {
    //     // config settings are setup up individually for EED_Modules
    //     // via the EE_Configurable class that all modules inherit from,
    //     // so
    //     //      $this->config();
    //     // can be used anywhere to retrieve it's config, and:
    //     //      $this->_update_config( $EE_Config_Base_object );
    //     // can be used to supply an updated instance of it's config object
    //     // to piggy back off of the config setup for the base EE_Recurring_Events class,
    //     // just use the following (note: updates would have to occur from within that class)
    //     return EE_Registry::instance()->addons->EE_Recurring_Events->config();
    // }



    /**
     * run - initial module setup
     *
     * @param WP $WP
     * @return void
     */
    public function run($WP)
    {
        // add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }


    /**
     * callback for FHEE__Extend_Events_Admin_Page__page_setup__page_config
     *
     * @param array             $page_config current page config.
     * @param Events_Admin_Page $admin_page
     * @return array
     * @since  1.0.0
     */
    public function setupPageConfig(array $page_config, Events_Admin_Page $admin_page)
    {
        $this->admin_page                         = $admin_page;
        $this->event_editor_recurring_events_form = new EventEditorRecurringEventsForm(
            $this->admin_page->get_event_object()
        );
        // $this->admin_page->load_scripts_styles_edit();
        return $page_config;
    }



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
        // Check to see if the recurring_events css file exists in the '/uploads/espresso/' directory
        // if (is_readable(EVENT_ESPRESSO_UPLOAD_DIR . 'css/recurring_events.css')) {
        //     //This is the url to the css file if available
        //     wp_register_style('espresso_recurring_events', EVENT_ESPRESSO_UPLOAD_URL . 'ui/css/recurring_events.css');
        // } else {
        //     // EE recurring_events style
            wp_register_style('recurring_events', Domain::pluginUrl() . 'ui/css/recurring_events.css');
        // }
        // recurring_events script
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
            Domain::pluginUrl() . 'ui/js/recurring_events.js',
            array('jquery', 'ee-datepicker', 'rrule'),
            Domain::version(),
            true
        );
        // is the shortcode or widget in play?
        // if (EED_Recurring_Events::$shortcode_active) {
            wp_enqueue_style('recurring_events');
            wp_enqueue_script('recurring_events');
        // }
    }

    public function recurringEventsManagerMetaBox()
    {
        // \EEH_Debug_Tools::printr(__FUNCTION__, __CLASS__, __FILE__, __LINE__, 2);
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



    public function remEditorUpdate($event, $data)
    {

    }


}
// End of file EED_Recurring_Events.module.php
// Location: /eea-recurring-events/ui/modules/EED_Recurring_Events.module.php
