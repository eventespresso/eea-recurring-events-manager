<?php

namespace EventEspresso\RecurringEvents\src\domain;

use DomainException;
use EE_Addon;
use EE_Dependency_Map;
use EE_Error;
use EE_Register_Addon;
use EventEspresso\core\domain\DomainInterface;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\assets\AssetCollection;
use EventEspresso\core\services\assets\Registry;
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
        // register addon via Plugin API
        EE_Register_Addon::register(
            'EventEspresso\RecurringEvents\src\domain\RecurringEventsManager',
            array(
                'version' => $domain->version(),
                'plugin_slug' => 'eea_recurring_events',
                'min_core_version' => Domain::CORE_VERSION_REQUIRED,
                'main_file_path' => $domain->pluginFile(),
                'module_paths' => array(
                    $domain->pluginPath() . 'src/domain/services/modules/EED_Recurring_Events.module.php',
                ),
                'dms_paths' => array(
                    __CLASS__ => $domain->pluginPath() . 'src/domain/services/data_migration_scripts/',
                ),
                'model_paths' => array(
                    __CLASS__ => $domain->pluginPath() . 'src/domain/entities/db_models/',
                ),
                'class_paths' => array(
                    __CLASS__ => $domain->pluginPath() . 'src/domain/entities/db_classes/',
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
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws DomainException
     * @throws InvalidInterfaceException
     * @throws InvalidEntityException
     * @throws EE_Error
     */
    public function after_registration()
    {
        $this->registerDependencies();
    }


    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws DomainException
     * @throws InvalidInterfaceException
     * @throws InvalidEntityException
     * @throws EE_Error
     */
    protected function registerDependencies()
    {
        $this->dependencyMap()->registerDependencies(
            'EventEspresso\RecurringEvents\src\ui\admin\AddNewEventModal',
            array(
                'EventEspresso\RecurringEvents\src\domain\Domain' => EE_Dependency_Map::load_from_cache,
            )
        );
        $this->dependencyMap()->registerDependencies(
            'EventEspresso\RecurringEvents\src\ui\admin\RecurringEventsAdmin',
            array(
                'EventEspresso\RecurringEvents\src\domain\services\assets\RecurringEventsAssetManager' => EE_Dependency_Map::load_from_cache,
                'EEM_Event' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\RecurringEvents\src\domain\Domain' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\loaders\LoaderInterface' => EE_Dependency_Map::load_from_cache,
            )
        );
        $this->dependencyMap()->registerDependencies(
            'EventEspresso\RecurringEvents\src\ui\admin\RecurringEventsAdminUpdate',
            array(
                'EventEspresso\RecurringEvents\src\domain\Domain' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\request\RequestInterface' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\loaders\LoaderInterface' => EE_Dependency_Map::load_from_cache,
            )
        );

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
}
// End of file RecurringEvents.class.php
// Location: wp-content/plugins/eea-recurring-events-manager/RecurringEvents.class.php
