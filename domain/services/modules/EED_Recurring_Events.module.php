<?php

use EventEspresso\core\domain\DomainInterface;
use EventEspresso\core\domain\entities\routing\handlers\admin\EspressoEventEditor;
use EventEspresso\core\domain\entities\routing\handlers\frontend\FrontendRequests;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use EventEspresso\core\services\assets\BaristaFactory;
use EventEspresso\core\services\assets\BaristaInterface;
use EventEspresso\core\services\loaders\LoaderFactory;
use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\core\services\routing\RouteInterface;
use EventEspresso\RecurringEvents\domain\Domain;
use EventEspresso\RecurringEvents\domain\entities\admin\RecurringEventsTemplateSettingsForm;
use EventEspresso\RecurringEvents\domain\entities\config\RecurringEventsConfig;
use EventEspresso\RecurringEvents\domain\services\admin\RecurringEventsTemplateSettingsFormHandler;
use EventEspresso\RecurringEvents\domain\services\assets\RecurringEventsAssetManager;

/**
 * Class  EED_Recurring_Events
 *
 * This is where miscellaneous action and filters callbacks should be setup to
 * do your addon's business logic (that doesn't fit neatly into one of the
 * other classes in the mock addon)
 *
 * @package     Event Espresso
 * @subpackage  eea-recurring-events-manager
 * @author      Brent Christensen
 */
class EED_Recurring_Events extends EED_Module
{

    /**
     * @var RecurringEventsConfig
     */
    private static $config;

    /**
     * @var DomainInterface
     */
    private static $domain;

    /**
     * @var LoaderInterface $loader ;
     */
    private static $loader;


    /**
     * @return EED_Module|EED_Recurring_Events
     */
    public static function instance()
    {
        return parent::get_instance(__CLASS__);
    }


    /**
     * @return RecurringEventsConfig
     */
    public static function remConfig()
    {
        if (! EED_Recurring_Events::$config instanceof RecurringEventsConfig) {
            EED_Recurring_Events::$config = EED_Recurring_Events::loader()->getShared(RecurringEventsConfig::class);
        }
        return EED_Recurring_Events::$config;
    }


    /**
     * @return DomainInterface
     */
    public static function domain()
    {
        if (! EED_Recurring_Events::$domain instanceof DomainInterface) {
            EED_Recurring_Events::$domain = EED_Recurring_Events::loader()->getShared(Domain::class);
        }
        return EED_Recurring_Events::$domain;
    }


    /**
     * @return LoaderInterface
     */
    public static function loader()
    {
        if (! EED_Recurring_Events::$loader instanceof LoaderInterface) {
            EED_Recurring_Events::$loader = LoaderFactory::getLoader();
        }
        return EED_Recurring_Events::$loader;
    }


    /**
     * set_hooks - for hooking into EE Core, other modules, etc
     *
     * @return void
     */
    public static function set_hooks()
    {
    }


    /**
     * set_hooks_admin - for hooking into EE Admin Core, other modules, etc
     *
     * @return void
     */
    public static function set_hooks_admin()
    {
        add_action(
            'AHEE__template_settings__template__before_settings_form',
            ['EED_Recurring_Events', 'templateSettingsForm'],
            99
        );
        add_filter(
            'FHEE__General_Settings_Admin_Page__update_template_settings__data',
            ['EED_Recurring_Events', 'updateTemplateSettings'],
            10,
            1
        );
    }


    /**
     * run - initial module setup
     *
     * @param WP $WP
     * @return void
     */
    public function run($WP)
    {
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


    /**
     * @param array         $arguments
     * @return array
     */
    public static function filterDatesListArguments(array $arguments)
    {
        $remConfig = EED_Recurring_Events::remConfig();
        $arguments[4] = $remConfig->showExpired();
        $numberOfDates = $remConfig->numberOfDates();
        if ($remConfig->allowScrolling()) {
            $numberOfDates = null;
        }
        if ($remConfig->showNextUpcomingOnly()) {
            $numberOfDates = 1;
        }
        $arguments[7] = $numberOfDates;
        return $arguments;
    }


    /**
     * @param string      $html
     * @param EE_Datetime $datetime
     * @param array       $arguments
     * @return string
     * @throws EE_Error
     * @throws ReflectionException
     */
    public static function filterDatesListInnerHtml($html, EE_Datetime $datetime, array $arguments)
    {
        $remConfig = EED_Recurring_Events::remConfig();
        switch($remConfig->templateStyle()) {
            case RecurringEventsConfig::TEMPLATE_STYLE_BOX :
            case RecurringEventsConfig::TEMPLATE_STYLE_CARD :
            case RecurringEventsConfig::TEMPLATE_STYLE_CIRCLE :
            case RecurringEventsConfig::TEMPLATE_STYLE_STRIPE :
                return EED_Recurring_Events::remDatesListTemplate($datetime, $arguments);
        }
        return $html;
    }


    /**
     * @param EE_Datetime $datetime
     * @param array       $arguments
     * @return string
     * @throws EE_Error
     * @throws ReflectionException
     * @since   $VID:$
     */
    private static function remDatesListTemplate(EE_Datetime $datetime, array $arguments) {
        // the following variables are utilized in the included template
        $name = $datetime->name();
        $description = $datetime->description();
        list($EVT_ID, $date_format, $time_format, $echo, $show_expired, $format, $add_breaks, $limit) = $arguments;
        ob_start();
        include EED_Recurring_Events::domain()->pluginPath('domain/ui/rem-dates-list-datetime.php');
        return ob_get_clean();
    }


    /**
     * @param string $html
     * @return string
     */
    public static function filterDatesListHtml($html)
    {
        $remConfig = EED_Recurring_Events::remConfig();
        $style = $remConfig->templateStyle();
        $max_height = $remConfig->numberOfDates() * 90 + 16;
        $attributes = "class='ee-rem-dates-list ee-rem-dates-list--{$style}' style='max-height: {$max_height}px;'";
        $html = "<div {$attributes}>" . $html . "</div>";
        $heading = $remConfig->showNextUpcomingOnly()
            ? esc_html__( 'Next Upcoming Date', 'event_espresso' )
            : esc_html__( 'Upcoming Dates', 'event_espresso' );
        $html = "<h3>{$heading}</h3>" . $html;
        return $html;
    }
}
