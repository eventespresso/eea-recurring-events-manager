<?php
/*
  Plugin Name: Event Espresso - Recurring Events Manager (EE 4.9+)
  Plugin URI: http://www.eventespresso.com
  Description: The Event Espresso Recurring Events Manager addon.
  Version: 1.0.0.rc.003
  Author: Event Espresso
  Author URI: http://www.eventespresso.com
  Copyright 2014 Event Espresso (email : support@eventespresso.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 3, as
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
define('EE_REM_VERSION', '1.0.0.rc.003');
define('EE_REM_PLUGIN_FILE', __FILE__);
define('EE_REM_CORE_VERSION_REQUIRED', '4.9.44.rc.0000');

// check php version, if not PHP 7.1 ++ then deactivate and show notice
if (defined('PHP_VERSION_ID') && PHP_VERSION_ID >= 70100) {
    require_once __DIR__ . '/eea-recurring-events-bootstrap.php';
} else {
    add_action(
        'admin_notices',
        static function () {
            unset($_GET['activate'], $_REQUEST['activate']);
            if (! function_exists('deactivate_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            deactivate_plugins(plugin_basename(__FILE__));
            eea_recurring_events_activation_error(
                sprintf(
                    esc_html__(
                        'Event Espresso Recurring Events Manager add-on could not be activated because it requires PHP version %s or greater.',
                        'event_espresso'
                    ),
                    '7.1.0'
                )
            );
        }
    );
}


/**
 * displays activation error admin notice
 *
 * @param string $error_message
 */
function eea_recurring_events_activation_error($error_message = '')
{
    $error_message = ! empty($error_message)
        ? $error_message
        : sprintf(
            esc_html__(
                'Event Espresso Recurring Events Manager add-on could not be activated. Please ensure that Event Espresso version %1$s or higher is activated.',
                'event_espresso'
            ),
            EE_REM_CORE_VERSION_REQUIRED
        );
    add_action(
        'admin_notices',
        static function () use ($error_message) {
            unset($_GET['activate'], $_REQUEST['activate']);
            if (! function_exists('deactivate_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            deactivate_plugins(plugin_basename(__FILE__));
            ?>
            <div class="error">
                <p><?php echo $error_message; ?></p>
            </div>
            <?php
        }
    );
}

// End of file eea-recurring-events-manager.php
// Location: wp-content/plugins/eea-recurring-events-manager/eea-recurring-events-manager-manager.php
