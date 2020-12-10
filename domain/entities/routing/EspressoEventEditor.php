<?php

namespace EventEspresso\RecurringEvents\domain\entities\routing;

use EE_Dependency_Map;
use EventEspresso\RecurringEvents\domain\Domain;
use EventEspresso\RecurringEvents\domain\services\assets\RecurringEventsAssetManager;

class EspressoEventEditor extends RemAdminRoute
{

    /**
     * @var boolean $registered
     */
    protected $registered = false;

    /**
     * returns true if the current request matches this route
     *
     * @return bool
     * @since   $VID:$
     */
    public function matchesCurrentRequest(): bool
    {
        return parent::matchesCurrentRequest()
            && $this->admin_config->useAdvancedEditor()
            && (
                $this->request->getRequestParam('action') === 'create_new'
                || $this->request->getRequestParam('action') === 'edit'
            );
    }


    /**
     * called just before matchesCurrentRequest()
     * and allows Route to perform any setup required such as calling setSpecification()
     *
     * @return void
     */
    public function initialize()
    {
        $this->initializeBaristaForDomain(Domain::class);
    }


    protected function registerDependencies()
    {
        parent::registerDependencies();
        $this->dependency_map->registerDependencies(
            RemEditorData::class,
            [
                'EventEspresso\core\domain\entities\admin\GraphQLData\Datetimes'              => EE_Dependency_Map::load_from_cache,
                'EEM_Datetime'                                                                => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\services\json\JsonDataNodeValidator'                      => EE_Dependency_Map::load_from_cache,
                'EventEspresso\RecurringEvents\domain\entities\admin\GraphQLData\Recurrences' => EE_Dependency_Map::load_from_cache,
                'EventEspresso\core\domain\services\graphql\Utilities'                        => EE_Dependency_Map::load_from_cache,
            ]
        );
    }


    /**
     * @return string
     */
    protected function dataNodeClass(): string
    {
        return RemEditorData::class;
    }


    /**
     * implements logic required to run during request
     *
     * @return bool
     * @since   $VID:$
     */
    protected function requestHandler(): bool
    {
        /** @var RecurringEventsAssetManager $schema */
        $asset_manager = $this->loader->getShared(RecurringEventsAssetManager::class);
        add_action('admin_enqueue_scripts', [$asset_manager, 'enqueueEventEditor'], 3);
        return parent::requestHandler();
    }
}
