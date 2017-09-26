<?php
use EventEspresso\RecurringEvents\domain\Domain;

defined('EVENT_ESPRESSO_VERSION') || exit();


/**
 * Class  EE_Recurring_Events
 *
 * @package     Event Espresso
 * @subpackage  eea-recurring-events-manager
 * @author      Brent Christensen
 */
Class  EE_Recurring_Events extends EE_Addon
{

    /**
     * !!! IMPORTANT !!!
     * this is not the place to perform any logic or add any other filter or action callbacks
     * this is just to bootstrap your addon; and keep in mind the addon might be DE-registered
     * in which case your callbacks should probably not be executed.
     * EED_Recurring_Events is typically the best place for most filter and action callbacks
     * to be placed (relating to the primary business logic of your addon)
     * IF however for some reason, a module does not work because you have some logic
     * that needs to run earlier than when the modules load,
     * then please see the after_registration() method below.
     *
     * @throws EE_Error
     * @throws \DomainException
     */
    public static function register_addon()
    {
        // register addon via Plugin API
        EE_Register_Addon::register(
            'recurring_events',
            array(
                'version'               => Domain::version(),
                'plugin_slug'           => 'eea_recurring_events',
                'min_core_version'      => Domain::CORE_VERSION_REQUIRED,
                'main_file_path'        => Domain::pluginFile(),
                'namespace'             => array(
                    'FQNS' => 'EventEspresso\RecurringEvents',
                    'DIR'  => __DIR__,
                ),
                'module_paths'          => array(
                    Domain::pluginPath() . 'ui' . DS . 'modules' . DS . 'EED_Recurring_Events.module.php',
                ),
            )
        );
    }



    /**
     * uncomment this method and use it as
     * a safe space to add additional logic like setting hooks
     * that will run immediately after addon registration
     * making this a great place for code that needs to be "omnipresent"
     *
     * @since 4.9.26
     */
    public function after_registration()
    {
        // your logic here
    }



}
// End of file RecurringEvents.class.php
// Location: wp-content/plugins/eea-recurring-events-manager/RecurringEvents.class.php
