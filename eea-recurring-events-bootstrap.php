<?php


/**
 *    registers addon with EE core
 */
add_action(
    'AHEE__EE_System__load_espresso_addons',
    static function () {
        if (defined('EE_PLUGIN_DIR_PATH')
            && is_readable(EE_PLUGIN_DIR_PATH . 'core/third_party_libs/wp-graphql')
            && class_exists('EE_Addon')
            && class_exists('EventEspresso\core\domain\DomainBase')
        ) {
            try {
                EE_Psr4AutoloaderInit::psr4_loader()->addNamespace('EventEspresso\RecurringEvents', __DIR__);
                EE_Dependency_Map::register_dependencies(
                    'EventEspresso\RecurringEvents\domain\RecurringEventsManager',
                    [
                        'EE_Dependency_Map'                           => EE_Dependency_Map::load_from_cache,
                        'EventEspresso\RecurringEvents\domain\Domain' => EE_Dependency_Map::load_from_cache,
                    ]
                );
                EE_Dependency_Map::register_class_loader(
                    'EventEspresso\RecurringEvents\domain\Domain',
                    static function () {
                        return getRemDomain();
                    }
                );
                EventEspresso\RecurringEvents\domain\RecurringEventsManager::registerAddon(getRemDomain());
            } catch (Exception $e) {
                eea_recurring_events_activation_error($e->getMessage());
            }
        } else {
            eea_recurring_events_activation_error();
        }
    }
);


/**
 * @returns EventEspresso\core\domain\DomainInterface
 */
function getRemDomain()
{
    static $domain;
    if (! $domain instanceof EventEspresso\RecurringEvents\domain\Domain) {
        $domain = EventEspresso\core\domain\DomainFactory::getShared(
            new EventEspresso\core\domain\values\FullyQualifiedName(
                'EventEspresso\RecurringEvents\domain\Domain'
            ),
            [EE_REM_PLUGIN_FILE, EE_REM_VERSION]
        );
    }
    return $domain;
}


/**
 *    verifies that addon was activated
 */
add_action(
    'init',
    static function () {
        if (! did_action('AHEE__EE_System__load_espresso_addons')) {
            eea_recurring_events_activation_error();
        }
    },
    1
);


/**
 *    captures plugin activation errors for debugging
 */
add_action(
    'activate_eea-recurring-events-manager/eea-recurring-events-manager.php',
    static function () {
        if (WP_DEBUG) {
            $activation_errors = ob_get_contents();
            if (! empty($activation_errors)) {
                file_put_contents(
                    EVENT_ESPRESSO_UPLOAD_DIR . 'logs' . DS . 'espresso_recurring_events_plugin_activation_errors.html',
                    $activation_errors
                );
            }
        }
    }
);
