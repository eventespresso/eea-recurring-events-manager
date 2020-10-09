<?php

namespace EventEspresso\RecurringEvents\domain\entities\routing;

use EE_Dependency_Map;
use EventEspresso\core\services\assets\BaristaFactory;
use EventEspresso\core\services\assets\BaristaInterface;
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
    public function matchesCurrentRequest()
    {
        return parent::matchesCurrentRequest()
            && $this->admin_config->useAdvancedEditor()
            && (
                $this->request->getRequestParam('action') === 'create_new'
                || $this->request->getRequestParam('action') === 'edit'
            );
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
            ]
        );
        /** @var RemEditorData $data_node */
        $data_node = $this->loader->getShared(RemEditorData::class);
        $this->setDataNode($data_node);
    }


    /**
     * implements logic required to run during request
     *
     * @return bool
     * @since   $VID:$
     */
    protected function requestHandler()
    {
        if (apply_filters('FHEE__load_Barista', true)) {
            /** @var Domain $factory */
            $domain = $this->loader->getShared(Domain::class);
            /** @var BaristaFactory $factory */
            $factory = $this->loader->getShared(BaristaFactory::class);
            $barista = $factory->createFromDomainObject($domain);
            if ($barista instanceof BaristaInterface) {
                $barista->initialize();
            }
        }
        /** @var RecurringEventsAssetManager $schema */
        $asset_manager = $this->loader->getShared(RecurringEventsAssetManager::class);
        add_action('admin_enqueue_scripts', [$asset_manager, 'enqueueEventEditor'], 3);
        return parent::requestHandler();
    }
}
