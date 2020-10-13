<?php

namespace EventEspresso\RecurringEvents\domain\entities\routing;

use EE_Dependency_Map;
use EventEspresso\core\domain\entities\routing\handlers\shared\GQLRequests as CoreGQLRequests;
use EventEspresso\core\services\assets\AssetManifestFactory;
use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\core\services\request\RequestInterface;
use EventEspresso\RecurringEvents\domain\services\dependencies\EventEditorDependencyHandler;
use EventEspresso\RecurringEvents\domain\services\graphql\RegisterResources;

class GQLRequests extends CoreGQLRequests
{
    /**
     * @var EventEditorDependencyHandler $dependency_handler
     */
    protected $dependency_handler;


    /**
     * AssetRequests constructor.
     *
     * @param AssetManifestFactory $manifest_factory
     * @param EE_Dependency_Map $dependency_map
     * @param EventEditorDependencyHandler $dependency_handler
     * @param LoaderInterface $loader
     * @param RequestInterface $request
     */
    public function __construct(
        AssetManifestFactory $manifest_factory,
        EE_Dependency_Map $dependency_map,
        EventEditorDependencyHandler $dependency_handler,
        LoaderInterface $loader,
        RequestInterface $request
    ) {
        $this->dependency_handler = $dependency_handler;
        parent::__construct($dependency_map, $loader, $request, $manifest_factory);
    }


    /**
     * @since $VID:$
     */
    protected function registerDependencies()
    {
        parent::registerDependencies();
        $this->dependency_handler->registerDependencies();
    }


    /**
     * implements logic required to run during request
     *
     * @return bool
     * @since   $VID:$
     */
    protected function requestHandler()
    {
        /** @var RegisterResources $schema */
        $gqlResources = $this->loader->getShared(RegisterResources::class);
        $gqlResources->initialize();
        return true;
    }
}
