<?php

namespace EventEspresso\RecurringEvents\domain;

use EventEspresso\core\domain\DomainBase;

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
     * User Capability Required for Add-on
     */
    const USER_CAP_REQUIRED = 'ee_recurring_events_manager';
}
