<?php
/*
  Plugin Name: Event Espresso - Recurring Events Manager (EE 4.9+)
  Plugin URI: http://www.eventespresso.com
  Description: The Event Espresso Recurring Events Manager addon...
  Version: 1.0.0.dev.000
  Author: Event Espresso
  Author URI: http://www.eventespresso.com
  Copyright 2014 Event Espresso (email : support@eventespresso.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA02110-1301USA
 *
 * ------------------------------------------------------------------------
 *
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package    Event Espresso
 * @ author     Event Espresso
 * @ copyright  (c) 2008-2014 Event Espresso  All Rights Reserved.
 * @ license    http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link       http://www.eventespresso.com
 * @ version    EE4
 *
 * ------------------------------------------------------------------------
 */


// define versions and this file
define('EE_REM_VERSION', '1.0.0.rc.000');
define('EE_REM_PLUGIN_FILE', __FILE__);
define('EE_REM_CORE_VERSION_REQUIRED', '4.9.44.rc.0000');


/**
 *    captures plugin activation errors for debugging
 */
function espresso_recurring_events_plugin_activation_errors()
{
    // \EEH_Debug_Tools::printr(__FUNCTION__, __CLASS__, __FILE__, __LINE__, 2);
    if (WP_DEBUG) {
        $activation_errors = ob_get_contents();
        if (!empty($activation_errors)) {
            file_put_contents(
                EVENT_ESPRESSO_UPLOAD_DIR . 'logs' . DS . 'espresso_recurring_events_plugin_activation_errors.html',
                $activation_errors
            );
        }
    }
}

add_action('activate_eea-recurring-events-manager/eea-recurring-events-manager.php', 'espresso_recurring_events_plugin_activation_errors');


/**
 *    registers addon with EE core
 */
function load_espresso_recurring_events()
{
    if (
        defined('PHP_VERSION_ID')
        && PHP_VERSION_ID > 50600
        && class_exists('EE_Addon')
        && class_exists('EventEspresso\core\domain\DomainBase')
    ) {
        try {
            EE_Psr4AutoloaderInit::psr4_loader()->addNamespace('EventEspresso\RecurringEvents', __DIR__);
            EE_Dependency_Map::register_dependencies(
                'EventEspresso\RecurringEvents\src\domain\RecurringEventsManager',
                array(
                    'EE_Dependency_Map' => EE_Dependency_Map::load_from_cache,
                    'EventEspresso\RecurringEvents\src\domain\Domain' => EE_Dependency_Map::load_from_cache,
                )
            );
            EventEspresso\RecurringEvents\src\domain\RecurringEventsManager::registerAddon(
                EventEspresso\core\domain\DomainFactory::getShared(
                    new EventEspresso\core\domain\values\FullyQualifiedName(
                        'EventEspresso\RecurringEvents\src\domain\Domain'
                    ),
                    array(
                        new EventEspresso\core\domain\values\FilePath(EE_REM_PLUGIN_FILE),
                        EventEspresso\core\domain\values\Version::fromString(EE_REM_VERSION),
                    )
                )
            );
        } catch (Exception $e) {
            add_action(
                'admin_notices',
                function () use ($e) {
                    echo '<div class="error"><p>' . $e->getMessage() . '</p></div>';
                }
            );
        }
    } else {
        add_action('admin_notices', 'espresso_recurring_events_activation_error');
    }
}

add_action('AHEE__EE_System__load_espresso_addons', 'load_espresso_recurring_events');


/**
 *    verifies that addon was activated
 */
function espresso_recurring_events_activation_check()
{
    if (!did_action('AHEE__EE_System__load_espresso_addons')) {
        add_action('admin_notices', 'espresso_recurring_events_activation_error');
    }
}

add_action('init', 'espresso_recurring_events_activation_check', 1);


/**
 *    displays activation error admin notice
 */
function espresso_recurring_events_activation_error()
{
    unset($_GET['activate'], $_REQUEST['activate']);
    if (!function_exists('deactivate_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    deactivate_plugins(plugin_basename(__FILE__));
    $error_message = sprintf(
        esc_html__(
            'Event Espresso Recurring Events addon could not be activated. Please ensure that Event Espresso version %1$s or higher is activated and your server is running PHP version %2$s or greater.',
            'event_espresso'
        ),
        EE_REM_CORE_VERSION_REQUIRED,
        '5.6'
    );
    ?>
    <div class="error">
        <p><?php echo $error_message; ?></p>
    </div>
    <?php
}

// End of file eea-recurring-events-manager.php
// Location: wp-content/plugins/eea-recurring-events-manager/eea-recurring-events-manager-manager.php
