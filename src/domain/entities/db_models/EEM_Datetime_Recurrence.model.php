<?php

defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * Class EEM_Datetime_Recurrence
 *
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EEM_Datetime_Recurrence extends EEM_Base
{

    /**
     * @var EEM_Datetime_Recurrence $_instance
     */
    protected static $_instance;


    /**
     * @throws EE_Error
     */
    protected function __construct()
    {
        $this->singular_item    = esc_html__('Datetime Recurrence', 'event_espresso');
        $this->plural_item      = esc_html__('Datetime Recurrences', 'event_espresso');
        $this->_tables          = array(
            'Datetime_Recurrence' => new EE_Primary_Table('esp_datetime_recurrence', 'RCR_ID')
        );
        $this->_fields          = array(
            'Datetime_Recurrence' => array(
                'DRC_ID'                 => new EE_Primary_Key_Int_Field(
                    'DRC_ID',
                    esc_html__('ID', 'event_espresso')
                ),
                'DTT_ID' => new EE_Foreign_Key_Int_Field(
                    'DTT_ID',
                    esc_html__('Datetime ID', 'event_espresso'),
                    false,
                    0,
                    'Datetime'
                ),
                'RCR_ID' => new EE_Foreign_Key_Int_Field(
                    'RCR_ID',
                    esc_html__('Recurrence ID', 'event_espresso'),
                    false,
                    0,
                    'Recurrence'
                ),
                'DRC_exclude' => new EE_Boolean_Field(
                    'DRC_exclude',
                    esc_html__(
                        'Removes this datetime from the list of recurrences even though it appears in the recurrence pattern',
                        'event_espresso'
                    ),
                    false,
                    false
                ),
            )
        );
        $this->_model_relations = array(
            'Datetime'   => new EE_Belongs_To_Relation(),
            'Recurrence' => new EE_Belongs_To_Relation(),
        );
        parent::__construct();
    }
}
