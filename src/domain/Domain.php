<?php

namespace EventEspresso\RecurringEvents\src\domain;

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

}
