<?php

use EventEspresso\core\exceptions\ExceptionStackTraceDisplay;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\loaders\LoaderFactory;
use EventEspresso\core\services\request\RequestInterface;

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
 * @subpackage  eea-recurring-events-manager
 * @author      Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class EED_Recurring_Events extends EED_Module
{



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
        // EE_Config::register_route('recurring_events', 'EED_Recurring_Events', 'run');
    }



    /**
     * set_hooks_admin - for hooking into EE Admin Core, other modules, etc
     *
     * @return void
     */
    public static function set_hooks_admin()
    {
        add_action(
            'AHEE__EE_System__initialize',
            array('EED_Recurring_Events', 'loadRecurringEventsAdminComponents')
        );
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
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public static function loadRecurringEventsAdminComponents()
    {
        try {
            $request = LoaderFactory::getLoader()->getShared('EventEspresso\core\services\request\RequestInterface');
            if ($request instanceof RequestInterface) {
                $page   = $request->getRequestParam('page');
                $action = $request->getRequestParam('action');
                if ($page === 'espresso_events') {
                    if ($action === 'create_new') {
                        EED_Recurring_Events::AddNewEventModal();
                    }
                    if ($action === 'edit' || $action === 'create_new') {
                        EED_Recurring_Events::loadRecurringEventsAdmin();
                    }
                } /*elseif ($page === null) {
                    if ($action === 'editpost') {
                        EED_Recurring_Events::processRecurringEventsAdmin();
                    }
                }*/
            }
        } catch (Exception $exception) {
            new ExceptionStackTraceDisplay($exception);
        }
    }


    /**
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
    private static function AddNewEventModal()
    {
        /** @var EventEspresso\RecurringEvents\src\ui\admin\AddNewEventModal $add_new_event_modal */
        $add_new_event_modal = LoaderFactory::getLoader()->getShared(
            'EventEspresso\RecurringEvents\src\ui\admin\AddNewEventModal'
        );
        $add_new_event_modal->setHooks();
    }


    /**
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
    private static function loadRecurringEventsAdmin()
    {
        /** @var EventEspresso\RecurringEvents\src\ui\admin\RecurringEventsAdmin $recurring_events_admin */
        $recurring_events_admin = LoaderFactory::getLoader()->getShared(
            'EventEspresso\RecurringEvents\src\ui\admin\RecurringEventsAdmin'
        );
        $recurring_events_admin->setHooks();
    }


    /**
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
//    private static function processRecurringEventsAdmin()
//    {
//        /** @var EventEspresso\RecurringEvents\src\ui\admin\RecurringEventsAdminUpdate $recurring_events_admin_update */
//        $recurring_events_admin_update = LoaderFactory::getLoader()->getShared(
//            'EventEspresso\RecurringEvents\src\ui\admin\RecurringEventsAdminUpdate'
//        );
//        $recurring_events_admin_update->setHooks();
//    }

}
// End of file EED_Recurring_Events.module.php
// Location: /eea-recurring-events-manager/ui/modules/EED_Recurring_Events.module.php
