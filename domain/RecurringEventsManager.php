<?php

namespace EventEspresso\RecurringEvents\domain;

use DomainException;
use EE_Addon;
use EE_Dependency_Map;
use EE_Error;
use EE_Register_Addon;
use EventEspresso\core\domain\DomainInterface;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\routing\PrimaryRoute;
use EventEspresso\core\services\routing\RouteHandler;
use EventEspresso\RecurringEvents\domain\services\dependencies\EventEditorDependencyHandler;
use Exception;
use InvalidArgumentException;
use ReflectionException;

/**
 * Class  RecurringEventsManager
 *
 * @package     Event Espresso
 * @subpackage  eea-recurring-events-manager
 * @author      Brent Christensen
 * @since       $VID:$
 */
class RecurringEventsManager extends EE_Addon
{

    /**
     * @var DomainInterface
     */
    private static $domain;


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
     * @param DomainInterface $domain
     * @throws DomainException
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws ReflectionException
     */
    public static function registerAddon(DomainInterface $domain)
    {
        RecurringEventsManager::$domain = $domain;
        // register addon via Plugin API
        $plugin_path = $domain->pluginPath();
        EE_Register_Addon::register(
            'EventEspresso\RecurringEvents\domain\RecurringEventsManager',
            [
                'version'               => $domain->version(),
                'plugin_slug'           => 'eea_recurring_events',
                'min_core_version'      => Domain::CORE_VERSION_REQUIRED,
                'main_file_path'        => $domain->pluginFile(),
                'dms_paths'             => [
                    RecurringEventsManager::class => $plugin_path . 'domain/services/data_migration_scripts/',
                ],
                'model_paths'           => [
                    RecurringEventsManager::class => $plugin_path . 'domain/entities/db_models/',
                ],
                'class_paths'           => [
                    RecurringEventsManager::class => $plugin_path . 'domain/entities/db_classes/',
                ],
                'model_extension_paths' => [
                    RecurringEventsManager::class => $plugin_path . 'domain/entities/db_model_extensions/',
                ],
                'class_extension_paths' => [
                    RecurringEventsManager::class => $plugin_path . 'domain/entities/db_class_extensions/',
                ],
            ]
        );
    }


    /**
     * uncomment this method and use it as
     * a safe space to add additional logic like setting hooks
     * that will run immediately after addon registration
     * making this a great place for code that needs to be "omnipresent"
     *
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws DomainException
     * @throws InvalidInterfaceException
     * @throws InvalidEntityException
     */
    // @codingStandardsIgnoreLine
    public function after_registration()
    {
        add_action(
            'AHEE__EventEspresso_core_services_routing_Router__brewEspresso',
            [$this, 'handleRemRoutes'],
            10,
            3
        );
        add_filter(
            'FHEE__EventEspresso_core_domain_services_capabilities_FeatureFlags',
            function ($capabilities) {
                return array_merge($capabilities, [
                    'use_bulk_edit'              => true,
                ]);
            }
        );
    }


    /**
     * @param RouteHandler      $router
     * @param string            $route_request_type
     * @param EE_Dependency_Map $dependency_map
     * @throws Exception
     */
    public function handleRemRoutes(
        RouteHandler $router,
        string $route_request_type,
        EE_Dependency_Map $dependency_map
    ) {
        if ($route_request_type === PrimaryRoute::ROUTE_REQUEST_TYPE_REGULAR) {
            $admin_dependencies    = [
                'EE_Admin_Config'                                      => EE_Dependency_Map::load_from_cache,
                'EE_Dependency_Map'                                    => EE_Dependency_Map::load_from_cache,
                EventEditorDependencyHandler::class                    => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\loaders\LoaderInterface'  => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\request\RequestInterface' => EE_Dependency_Map::load_from_cache,
            ];
            $frontend_dependencies = [
                'EE_Maintenance_Mode'                                  => EE_Dependency_Map::load_from_cache,
                'EE_Dependency_Map'                                    => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\loaders\LoaderInterface'  => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\request\RequestInterface' => EE_Dependency_Map::load_from_cache,
            ];
            $gql_dependencies      = [
                'EventEspresso\core\services\assets\AssetManifestFactory' => EE_Dependency_Map::load_from_cache,
                'EE_Dependency_Map'                                       => EE_Dependency_Map::load_from_cache,
                EventEditorDependencyHandler::class                       => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\loaders\LoaderInterface'     => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\request\RequestInterface'    => EE_Dependency_Map::load_from_cache,
            ];
            $routes = [
                'EventEspresso\RecurringEvents\domain\entities\routing\EspressoEventEditor' => $admin_dependencies,
                'EventEspresso\RecurringEvents\domain\entities\routing\EventTemplatesAdmin'  => $admin_dependencies,
                'EventEspresso\RecurringEvents\domain\entities\routing\FrontendRequests'     => $frontend_dependencies,
                'EventEspresso\RecurringEvents\domain\entities\routing\GQLRequests'          => $gql_dependencies,
            ];
            $dependency_map->registerDependencies(
                EventEditorDependencyHandler::class,
                ['EE_Dependency_Map' => EE_Dependency_Map::load_from_cache]
            );
            foreach ($routes as $route => $dependencies) {
                $dependency_map->registerDependencies($route, $dependencies);
                $router->addRoute($route);
            }
        }
    }
}
