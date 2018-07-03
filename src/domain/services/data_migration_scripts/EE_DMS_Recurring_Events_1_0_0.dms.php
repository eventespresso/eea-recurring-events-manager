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
        return true;
    }


    /**
     * Performs the database schema changes that need to occur AFTER the data has been migrated.
     * Usually this will mean we'll be removing old columns. Eg, if we were changing passwords
     * from plaintext to encoded versions, and we had added a column called "encoded_password",
     * this function would probably remove the old column "password" (which still holds the plaintext password)
     * and possibly rename "encoded_password" to "password"
     *
     * @return boolean of success
     */
    public function schema_changes_after_migration()
    {
        $this->_table_is_new_in_this_version(
            'esp_datetime_recurrence',
            'DRC_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
					DTT_ID INT UNSIGNED NOT NULL,
					RCR_ID INT UNSIGNED NOT NULL,
					DRC_exclude TINYINT(1) NOT NULL DEFAULT 0,
					PRIMARY KEY  (DRC_ID),
					KEY DTT_RCR (DTT_ID,RCR_ID)',
            'ENGINE=InnoDB '
        );
        $this->_table_is_new_in_this_version(
            'esp_recurrence',
            'RCR_ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
					RCR_pattern_hash VARCHAR(36) NOT NULL,
					RCR_recurrence_pattern text NOT NULL,
					RCR_exclusion_pattern text DEFAULT NULL,
					RCR_dates text NOT NULL,
					PRIMARY KEY  (RCR_ID),
					KEY pattern_hash (RCR_pattern_hash)',
            'ENGINE=InnoDB '
        );
        return true;
    }
}
