<?php

namespace EventEspresso\RecurringEvents\src\domain;

use DomainException;
use EE_Addon;
use EE_Dependency_Map;
use EE_Error;
use EE_Register_Addon;
use EventEspresso\core\domain\DomainInterface;
use EventEspresso\core\domain\entities\routing\handlers\admin\EspressoEventEditor;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\assets\AssetCollection;
use EventEspresso\core\services\assets\BaristaFactory;
use EventEspresso\core\services\assets\BaristaInterface;
use EventEspresso\core\services\assets\Registry;
use EventEspresso\core\services\loaders\LoaderFactory;
use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\core\services\routing\RouteInterface;
use EventEspresso\RecurringEvents\src\domain\services\assets\RecurringEventsAssetManager;
use InvalidArgumentException;
use ReflectionException;

/**
 * Class  RecurringEventsManager
 *
 * @package     Event Espresso
 * @subpackage  eea-recurring-events-manager
 * @author      Brent Christensen
 */
Class  RecurringEventsManager extends EE_Addon
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
        EE_Register_Addon::register(
            'EventEspresso\RecurringEvents\src\domain\RecurringEventsManager',
            array(
                'version' => $domain->version(),
                'plugin_slug' => 'eea_recurring_events',
                'min_core_version' => Domain::CORE_VERSION_REQUIRED,
                'main_file_path' => $domain->pluginFile(),
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
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws DomainException
     * @throws InvalidInterfaceException
     * @throws InvalidEntityException
     */
    public function after_registration()
    {
        $this->registerDependencies();
        $this->registerAssets();
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
                Domain::class => EE_Dependency_Map::load_from_cache,
                Registry::class => EE_Dependency_Map::load_from_cache
            ]
        );
    }

    /**
     * @since $VID:$
     */
    public function registerAssets()
    {
        if (apply_filters('FHEE__load_Barista', true)) {
            add_action(
                'AHEE__EventEspresso_core_domain_entities_routes_handlers_Route__handleRequest',
                [$this, 'enqueueRemAssets'],
                10,
                1
            );
        }
    }


    /**
     * @param RouteInterface $route
     * @since $VID:$
     */
    public function enqueueRemAssets(RouteInterface $route)
    {
        if ($route instanceof EspressoEventEditor) {
            $loader = LoaderFactory::getLoader();
            /** @var BaristaFactory $factory */
            $factory = $loader->getShared(BaristaFactory::class);
            $barista = $factory->createFromDomainObject(RecurringEventsManager::$domain);
            if ($barista instanceof BaristaInterface) {
                $barista->initialize();
            }
            $asset_manager = $loader->getShared(RecurringEventsAssetManager::class);
            add_action('admin_enqueue_scripts', [$asset_manager, 'enqueueEventEditor'], 99);

        }
    }
}
