<?php

namespace EventEspresso\RecurringEvents\domain\services\dependencies;

use EE_Dependency_Map;
use EventEspresso\core\services\dependencies\DependencyHandler;

class EventEditorDependencyHandler extends DependencyHandler
{
    /**
     * @inheritDoc
     */
    public function registerDependencies()
    {
        $this->dependency_map->add_alias(
            'EventEspresso\RecurringEvents\domain\Domain',
            'EventEspresso\core\domain\DomainInterface',
            'EventEspresso\RecurringEvents\domain\services\assets\RecurringEventsAssetManager'
        );
        $this->dependency_map->registerDependencies(
            'EventEspresso\RecurringEvents\domain\services\graphql\RegisterSchema',
            ['EventEspresso\core\domain\services\graphql\Utilities' => EE_Dependency_Map::load_from_cache]
        );
        $this->dependency_map->registerDependencies(
            'EventEspresso\RecurringEvents\domain\services\graphql\RegisterResources',
            ['EventEspresso\RecurringEvents\domain\services\graphql\RegisterSchema' => EE_Dependency_Map::load_from_cache]
        );
        $this->dependency_map->registerDependencies(
            'EventEspresso\RecurringEvents\domain\services\graphql\connections\RootQueryRecurrencesConnection',
            ['EEM_Recurrence' => EE_Dependency_Map::load_from_cache]
        );
        $this->dependency_map->registerDependencies(
            'EventEspresso\RecurringEvents\domain\services\graphql\types\Recurrence',
            ['EEM_Recurrence' => EE_Dependency_Map::load_from_cache]
        );
        $this->dependency_map->registerDependencies(
            'EventEspresso\RecurringEvents\domain\services\assets\RecurringEventsAssetManager',
            [
                'EventEspresso\RecurringEvents\domain\Domain'        => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\assets\AssetCollection' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\assets\Registry'        => EE_Dependency_Map::load_from_cache,
            ]
        );
    }
}
