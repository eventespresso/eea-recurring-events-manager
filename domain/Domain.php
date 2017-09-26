<?php

namespace EventEspresso\RecurringEvents\domain;

use DomainException;
use EventEspresso\core\domain\DomainBase;

defined('EVENT_ESPRESSO_VERSION') || exit;


/**
 * Domain Class
 * A container for all domain data related to Recurring Events
 *
 * @package     Event Espresso
 * @subpackage  Recurring Events
 * @author      Event Espresso
 */
class Domain extends DomainBase
{

    /**
     * EE Core Version Required for Add-on
     */
    const CORE_VERSION_REQUIRED = EE_REM_CORE_VERSION_REQUIRED;



    /**
     * @return string
     * @throws DomainException
     */
    public static function entitiesPath()
    {
        return self::pluginPath() . 'domain/entities/';
    }



    /**
     * @return string
     * @throws DomainException
     */
    public static function servicesPath()
    {
        return self::pluginPath() . 'domain/services/';
    }



    /**
     * @return string
     * @throws DomainException
     */
    public static function adminPath()
    {
        return self::pluginPath() . 'domain/services/admin/recurring_events/';
    }



    /**
     * @return string
     */
    public static function adminPageSlug()
    {
        return 'espresso_recurring_events';
    }



    /**
     * @return string
     */
    public static function adminPageLabel()
    {
        return esc_html__('Recurring Events', 'event_espresso');
    }



    /**
     * @return string
     */
    public static function adminPageUrl()
    {
        return admin_url('admin.php?page=' . Domain::adminPageSlug());
    }



    /**
     * @return string
     * @throws DomainException
     */
    public static function adminAssetsPath()
    {
        return Domain::adminPath() . 'assets/';
    }



    /**
     * @return string
     * @throws DomainException
     */
    public static function adminTemplatePath()
    {
        return Domain::adminPath() . 'templates/';
    }



    /**
     * @return string
     * @throws DomainException
     */
    public static function adminBaseUrl()
    {
        return Domain::pluginUrl() . 'admin/recurring_events/';
    }



    /**
     * @return string
     * @throws DomainException
     */
    public static function adminAssetsUrl()
    {
        return Domain::adminBaseUrl() . 'assets/';
    }


    /**
     * @return string
     * @throws DomainException
     */
    public static function adminTemplateUrl()
    {
        return Domain::adminBaseUrl() . 'templates/';
    }



    /**
     * @return string
     * @throws DomainException
     */
    public static function adminUiPath()
    {
        return self::pluginPath() . 'ui/admin/';
    }


}
