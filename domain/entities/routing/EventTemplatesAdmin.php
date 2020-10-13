<?php

namespace EventEspresso\RecurringEvents\domain\entities\routing;

use EE_Dependency_Map;
use EE_Error;
use EE_Template_Config;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\loaders\LoaderFactory;
use EventEspresso\RecurringEvents\domain\entities\admin\RecurringEventsTemplateSettingsForm;
use EventEspresso\RecurringEvents\domain\entities\config\RecurringEventsConfig;
use EventEspresso\RecurringEvents\domain\services\admin\RecurringEventsTemplateSettingsFormHandler;
use InvalidArgumentException;
use ReflectionException;

/**
 * Class EventTemplatesAdmin
 *
 * @package EventEspresso\RecurringEvents\domain\entities\routing
 * @since   $VID:$
 */
class EventTemplatesAdmin extends RemAdminRoute
{
    /**
     * returns true if the current request matches this route
     *
     * @return bool
     */
    public function matchesCurrentRequest()
    {
        return parent::matchesCurrentRequest() && (
            $this->request->getRequestParam('action') === 'template_settings'
            || $this->request->getRequestParam('action') === 'update_template_settings'
        );
    }


    protected function registerDependencies()
    {
        parent::registerDependencies();
        $this->dependency_map->registerDependencies(
            RecurringEventsTemplateSettingsForm::class,
            [
                RecurringEventsConfig::class => EE_Dependency_Map::load_from_cache,
            ]
        );
        $this->dependency_map->registerDependencies(
            RecurringEventsTemplateSettingsFormHandler::class,
            [
                RecurringEventsConfig::class               => EE_Dependency_Map::load_from_cache,
                RecurringEventsTemplateSettingsForm::class => EE_Dependency_Map::load_from_cache,
            ]
        );
    }


    /**
     * implements logic required to run during request
     *
     * @return bool
     */
    protected function requestHandler()
    {
        add_action(
            'AHEE__template_settings__template__before_settings_form',
            [$this, 'templateSettingsForm'],
            99
        );
        add_filter(
            'FHEE__General_Settings_Admin_Page__update_template_settings__data',
            [$this, 'updateTemplateSettings'],
            10,
            1
        );
        return parent::requestHandler();
    }


    /**
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws EE_Error
     */
    public static function templateSettingsForm()
    {
        /** @var RecurringEventsTemplateSettingsForm $template_settings_form */
        $template_settings_form = LoaderFactory::getLoader()->getShared(RecurringEventsTemplateSettingsForm::class);
        echo $template_settings_form->get_html();
    }


    /**
     * @param EE_Template_Config $template_config
     * @return EE_Template_Config
     * @throws EE_Error
     * @throws ReflectionException
     */
    public static function updateTemplateSettings(EE_Template_Config $template_config)
    {
        /** @var RecurringEventsTemplateSettingsFormHandler $form_handler */
        $form_handler = LoaderFactory::getLoader()->getShared(RecurringEventsTemplateSettingsFormHandler::class);
        $form_handler->update();
        return $template_config;
    }
}
