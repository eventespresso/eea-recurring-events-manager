<?php

namespace EventEspresso\RecurringEvents\domain\entities\routing;

use EE_Admin_Config;
use EE_Dependency_Map;
use EventEspresso\core\domain\entities\routing\handlers\admin\AdminRoute;
use EventEspresso\core\domain\services\capabilities\CapCheck;
use EventEspresso\core\domain\services\capabilities\CapCheckInterface;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\core\services\request\RequestInterface;
use EventEspresso\RecurringEvents\domain\Domain;
use EventEspresso\RecurringEvents\domain\services\dependencies\EventEditorDependencyHandler;
use EventEspresso\RecurringEvents\domain\services\graphql\RegisterResources;

class RemAdminRoute extends AdminRoute
{
    /**
     * @var EventEditorDependencyHandler $dependency_handler
     */
    protected $dependency_handler;


    /**
     * Route constructor. RemAdminRoute
     *
     * @param EE_Admin_Config              $admin_config
     * @param EE_Dependency_Map            $dependency_map
     * @param EventEditorDependencyHandler $dependency_handler
     * @param LoaderInterface              $loader
     * @param RequestInterface             $request
     */
    public function __construct(
        EE_Admin_Config $admin_config,
        EE_Dependency_Map $dependency_map,
        EventEditorDependencyHandler $dependency_handler,
        LoaderInterface $loader,
        RequestInterface $request
    ) {
        $this->dependency_handler = $dependency_handler;
        parent::__construct($admin_config, $dependency_map, $loader, $request);
    }


    /**
     * @return CapCheckInterface
     * @throws InvalidDataTypeException
     */
    public function getCapCheck(): CapCheckInterface
    {
        return new CapCheck(Domain::USER_CAP_REQUIRED, 'access REM admin');
    }


    protected function registerDependencies()
    {
        $this->dependency_handler->registerDependencies();
    }


    /**
     * implements logic required to run during request
     *
     * @return bool
     * @since   $VID:$
     */
    protected function requestHandler(): bool
    {
        /** @var RegisterResources $schema */
        $gqlResources = $this->loader->getShared(RegisterResources::class);
        $gqlResources->initialize();
        return true;
    }
}
