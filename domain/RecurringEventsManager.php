<?php

namespace EventEspresso\RecurringEvents\domain;

use DomainException;
use EE_Addon;
use EE_Dependency_Map;
use EE_Error;
use EE_Register_Addon;
use EED_Recurring_Events;
use EventEspresso\core\domain\entities\routing\handlers\admin\EspressoEventEditor;
use EventEspresso\core\domain\entities\routing\handlers\frontend\FrontendRequests;
use EventEspresso\core\domain\DomainInterface;
use EventEspresso\core\domain\entities\routing\handlers\shared\GQLRequests;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\assets\AssetCollection;
use EventEspresso\core\services\assets\BaristaFactory;
use EventEspresso\core\services\assets\BaristaInterface;
use EventEspresso\core\services\assets\Registry;
use EventEspresso\core\services\routing\RouteInterface;
use EventEspresso\RecurringEvents\domain\entities\admin\RecurringEventsTemplateSettingsForm;
use EventEspresso\RecurringEvents\domain\entities\config\RecurringEventsConfig;
use EventEspresso\RecurringEvents\domain\services\admin\RecurringEventsTemplateSettingsFormHandler;
use EventEspresso\RecurringEvents\domain\services\assets\RecurringEventsAssetManager;
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
                'module_paths'          => [
                    $plugin_path . 'domain/services/modules/EED_Recurring_Events.module.php',
                ],
                'dms_paths'             => [
                    RecurringEventsManager::class => $plugin_path . 'domain/services/data_migration_scripts/',
                ],
                'model_paths'           => [
                    RecurringEventsManager::class => $plugin_path . 'domain/entities/db_models/',
                ],
                'class_paths'           => [
                    RecurringEventsManager::class => $plugin_path . 'domain/entities/db_classes/',
                ],
                // EE_Register_Model_Extensions
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
        $this->registerDependencies();
        add_action(
            'AHEE__EventEspresso_core_domain_entities_routes_handlers_Route__handleRequest',
            [$this, 'handleRemRoutes'],
            10,
            1
        );
    }


    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws DomainException
     * @throws InvalidInterfaceException
     * @throws InvalidEntityException
     */
    protected function registerDependencies()
    {
        $this->dependencyMap()->add_alias(
            Domain::class,
            DomainInterface::class,
            RecurringEventsAssetManager::class
        );
        $this->dependencyMap()->registerDependencies(
            RecurringEventsAssetManager::class,
            [
                AssetCollection::class => EE_Dependency_Map::load_from_cache,
                Domain::class          => EE_Dependency_Map::load_from_cache,
                Registry::class        => EE_Dependency_Map::load_from_cache,
            ]
        );
        $this->dependencyMap()->registerDependencies(
            RecurringEventsTemplateSettingsForm::class,
            [RecurringEventsConfig::class => EE_Dependency_Map::load_from_cache]
        );
        $this->dependencyMap()->registerDependencies(
            RecurringEventsTemplateSettingsFormHandler::class,
            [
                RecurringEventsConfig::class               => EE_Dependency_Map::load_from_cache,
                RecurringEventsTemplateSettingsForm::class => EE_Dependency_Map::load_from_cache,
            ]
        );

        $this->dependencyMap()->registerDependencies(
            'EventEspresso\RecurringEvents\domain\services\graphql\types\Recurrence',
            ['EEM_Recurrence' => EE_Dependency_Map::load_from_cache]
        );
        $this->dependencyMap()->registerDependencies(
            'EventEspresso\RecurringEvents\domain\services\graphql\connections\RootQueryRecurrencesConnection',
            ['EEM_Recurrence' => EE_Dependency_Map::load_from_cache]
        );
    }


    /**
     * @param RouteInterface $route
     */
    public function handleRemRoutes(RouteInterface $route)
    {
        if ($route instanceof EspressoEventEditor) {
            $this->registerResources();
            if (apply_filters('FHEE__load_Barista', true)) {
                /** @var BaristaFactory $factory */
                $factory = EED_Recurring_Events::loader()->getShared(BaristaFactory::class);
                $barista = $factory->createFromDomainObject(RecurringEventsManager::$domain);
                if ($barista instanceof BaristaInterface) {
                    $barista->initialize();
                }
            }
            $asset_manager = EED_Recurring_Events::loader()->getShared(RecurringEventsAssetManager::class);
            add_action('admin_enqueue_scripts', [$asset_manager, 'enqueueEventEditor'], 3);
        } elseif ($route instanceof FrontendRequests) {
            $this->registerResources();
            add_filter(
                'FHEE__espresso_list_of_event_dates__arguments',
                ['EED_Recurring_Events', 'filterDatesListArguments'],
                10
            );
            add_filter(
                'FHEE__espresso_list_of_event_dates__datetime_html',
                ['EED_Recurring_Events', 'filterDatesListInnerHtml'],
                10,
                3
            );
            add_filter(
                'FHEE__espresso_list_of_event_dates__html',
                ['EED_Recurring_Events', 'filterDatesListHtml']
            );
            add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        } elseif ($route instanceof GQLRequests) {
            $this->registerResources();
        }
    }


    public function enqueueScripts()
    {
        wp_register_style(
            'ee-rem-dates-list',
            RecurringEventsManager::$domain->distributionAssetsUrl('ee-rem-dates-list.css'),
            [],
            RecurringEventsManager::$domain->version()
        );
        wp_enqueue_style('ee-rem-dates-list');
    }


    /**
     * @since $VID:$
     */
    public function registerResources()
    {
        static $registered = false;
        if (! $registered) {
            add_filter(
                'FHEE__EventEspresso_core_services_graphql_TypeCollection__loadCollection__collection_FQCNs',
                [$this, 'registerTypes']
            );
            add_filter(
                'FHEE__EventEspresso_core_services_graphql_ConnectionCollection__loadCollection__collection_FQCNs',
                [$this, 'registerConnections']
            );
            add_filter(
                'FHEE__EventEspresso_core_services_graphql_DataLoaderCollection__loadCollection__collection_FQCNs',
                [$this, 'registerDataLoaders']
            );
            $registered = true;
        }
    }


    /**
     * @param array $collection_FQCNs
     * @return array
     * @since $VID:$
     */
    public function registerConnections(array $collection_FQCNs = [])
    {
        $collection_FQCNs[] = 'EventEspresso\RecurringEvents\domain\services\graphql\connections';
        return $collection_FQCNs;
    }


    /**
     * @param array $collection_FQCNs
     * @return array
     * @since $VID:$
     */
    public function registerDataLoaders(array $collection_FQCNs = [])
    {
        $collection_FQCNs[] = 'EventEspresso\RecurringEvents\domain\services\graphql\data\domains';
        return $collection_FQCNs;
    }


    /**
     * @param array $collection_FQCNs
     * @return array
     * @since $VID:$
     */
    public function registerTypes(array $collection_FQCNs = [])
    {
        $collection_FQCNs[] = 'EventEspresso\RecurringEvents\domain\services\graphql\types';
        return $collection_FQCNs;
    }
}
