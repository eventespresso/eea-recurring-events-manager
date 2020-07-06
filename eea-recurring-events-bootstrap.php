<?php


/**
 *    registers addon with EE core
 */
add_action(
    'AHEE__EE_System__load_espresso_addons',
    static function () {
        if (class_exists('EE_Addon')
            && class_exists('EventEspresso\core\domain\DomainBase')
            && class_exists('EventEspresso\core\domain\services\assets\EspressoEditorAssetManager')
        ) {
            try {
                EE_Psr4AutoloaderInit::psr4_loader()->addNamespace('EventEspresso\RecurringEvents', __DIR__);
                EE_Dependency_Map::register_dependencies(
                    'EventEspresso\RecurringEvents\src\domain\RecurringEventsManager',
                    [
                        'EE_Dependency_Map'                               => EE_Dependency_Map::load_from_cache,
                        'EventEspresso\RecurringEvents\src\domain\Domain' => EE_Dependency_Map::load_from_cache,
                    ]
                );
                EventEspresso\RecurringEvents\src\domain\RecurringEventsManager::registerAddon(
                    EventEspresso\core\domain\DomainFactory::getShared(
                        new EventEspresso\core\domain\values\FullyQualifiedName(
                            'EventEspresso\RecurringEvents\src\domain\Domain'
                        ),
                        [
                            new EventEspresso\core\domain\values\FilePath(EE_REM_PLUGIN_FILE),
                            EventEspresso\core\domain\values\Version::fromString(EE_REM_VERSION),
                        ]
                    )
                );
            } catch (Exception $e) {
                eea_recurring_events_activation_error($e->getMessage());
            }
        } else {
            eea_recurring_events_activation_error();
        }
    }
);


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
