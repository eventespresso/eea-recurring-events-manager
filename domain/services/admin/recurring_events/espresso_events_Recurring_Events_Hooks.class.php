<?php
defined('EVENT_ESPRESSO_VERSION') || exit('NO direct script access allowed');



/**
 * espresso_events_Registration_Form_Hooks
 * Hooks various messages logic so that it runs on indicated Events Admin Pages.
 * Commenting/docs common to all children classes is found in the EE_Admin_Hooks parent.
 *
 * @package     espresso_events_Recurring_Events_Hooks
 * @subpackage  \domain\services\admin\recurring_events\espresso_events_Recurring_Events_Hooks.class.php
 * @author      Brent Christensen
 */
class espresso_events_Recurring_Events_Hooks extends EE_Admin_Hooks
{

    protected function _set_hooks_properties()
    {
        $this->_name = 'recurring_events';
    }

}
// End of file espresso_events_Recurring_Events_Hooks.class.php
// Location: /domain/services/admin/recurring_events/espresso_events_Recurring_Events_Hooks.class.php
