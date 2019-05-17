<?php

defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * Class EE_DMS_Recurring_Events_1_0_0
 * Description
 *
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EE_DMS_Recurring_Events_1_0_0 extends EE_Data_Migration_Script_Base
{

    public function __construct()
    {
        $this->_pretty_name      = esc_html__('Create Recurring Events Addon table', 'event_espresso');
        parent::__construct();
    }


    public static function eeAddonClass()
    {
        return 'EventEspresso\RecurringEvents\domain\RecurringEventsManager';
    }


    /**
     * @param array $current_database_state_of
     * @return boolean
     */
    public function can_migrate_from_version($current_database_state_of)
    {
        return true;
    }


    /**
     * @return boolean
     */
    public function schema_changes_before_migration()
    {
        $this->_table_is_new_in_this_version(
            'esp_recurrence',
            'RCR_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
					RCR_name varchar(50) NOT NULL,
					RCR_rRule text NOT NULL,
					RCR_exRule text DEFAULT NULL,
					RCR_rDates text DEFAULT NULL,
					RCR_exDates text DEFAULT NULL,
					RCR_gDates text NOT NULL,
					PRIMARY KEY  (RCR_ID)',
            'ENGINE=InnoDB '
        );
        /** @type WPDB */
        global $wpdb;
        $table = "`{$wpdb->prefix}esp_datetime`";
        $result = $wpdb->query(
            "ALTER TABLE {$table}
                ADD `RCR_ID` INT UNSIGNED NULL DEFAULT NULL
                AFTER `DTT_deleted`,
                ADD INDEX `RCR_ID` (`RCR_ID`);"
        );
        return $result > 0;
    }


    /**
     * @return boolean of success
     */
    public function schema_changes_after_migration()
    {
        return true;
    }
}
