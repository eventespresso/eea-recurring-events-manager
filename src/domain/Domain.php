<?php

namespace EventEspresso\RecurringEvents\src\domain;

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
    public function adminPath()
    {
        return $this->pluginPath() . 'domain/services/admin/recurring_events/';
    }



    /**
     * @return string
     */
    public function adminPageSlug()
    {
        return 'espresso_recurring_events';
    }



    /**
     * @return string
     */
    public function adminPageLabel()
    {
        return esc_html__('Recurring Events', 'event_espresso');
    }



    /**
     * @return string
     */
    public function adminPageUrl()
    {
        return admin_url('admin.php?page=' . $this->adminPageSlug());
    }



    /**
     * @return string
     * @throws DomainException
     */
    public function adminAssetsPath()
    {
        return $this->adminPath() . 'assets/';
    }



    /**
     * @return string
     * @throws DomainException
     */
    public function adminTemplatePath()
    {
        return $this->adminPath() . 'templates/';
    }



    /**
     * @return string
     * @throws DomainException
     */
    public function adminBaseUrl()
    {
        return $this->pluginUrl() . 'admin/recurring_events/';
    }



    /**
     * @return string
     * @throws DomainException
     */
    public function adminAssetsUrl()
    {
        return $this->adminBaseUrl() . 'assets/';
    }


    /**
     * @return string
     * @throws DomainException
     */
    public function adminTemplateUrl()
    {
        return $this->adminBaseUrl() . 'templates/';
    }



    /**
     * @return string
     * @throws DomainException
     */
    public function adminUiPath()
    {
        return $this->pluginPath() . 'ui/admin/';
    }
}
