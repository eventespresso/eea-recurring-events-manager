<?php

/**
 * Class EEM_Recurrence
 *
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EEM_Recurrence extends EEM_Base
{

    /**
     * @var EEM_Recurrence $_instance
     */
    protected static $_instance;


    /**
     * @throws EE_Error
     */
    protected function __construct()
    {
        $this->singular_item = esc_html__('Recurrence', 'event_espresso');
        $this->plural_item   = esc_html__('Recurrences', 'event_espresso');
        $this->_tables = array(
            'Recurrence' => new EE_Primary_Table('esp_recurrence', 'RCR_ID')
        );
        $this->_fields = array(
            'Recurrence' => array(
                'RCR_ID'          => new EE_Primary_Key_Int_Field(
                    'RCR_ID',
                    esc_html__('ID', 'event_espresso')
                ),
                'RCR_name' => new EE_Plain_Text_Field(
                    'RCR_name',
                    esc_html__('Recurrence Pattern Name', 'event_espresso'),
                    false
                ),
                'RCR_rRule' => new EE_Plain_Text_Field(
                    'RCR_rRule',
                    esc_html__('Recurrence Rule', 'event_espresso'),
                    false
                ),
                'RCR_exRule' => new EE_Plain_Text_Field(
                    'RCR_exRule',
                    esc_html__('Exclusion Rule', 'event_espresso'),
                    true,
                    null
                ),
                'RCR_rDates' => new EE_Plain_Text_Field(
                    'RCR_rDates',
                    esc_html__('Recurrence Dates', 'event_espresso'),
                    true,
                    null
                ),
                'RCR_exDates' => new EE_Plain_Text_Field(
                    'RCR_exDates',
                    esc_html__('Exclusion Dates', 'event_espresso'),
                    true,
                    null
                ),
                'RCR_date_duration' => new EE_Plain_Text_Field(
                    'RCR_date_duration',
                    esc_html__('Recurring Datetime Duration', 'event_espresso'),
                    true,
                    '1 day'
                ),
            )
        );
        $this->_model_relations = array('Datetime' => new EE_Has_Many_Relation());
        parent::__construct();
    }


    /**
     * @param int[] $datetime_IDs
     * @return EE_Base_Class[]|EE_Recurrence[]
     * @throws EE_Error
     * @throws InvalidArgumentException
     */
    public function findRecurrencesForDatetimes($datetime_IDs)
    {
        return $this->get_all(
            array(
                array(
                    'Datetime.DTT_ID' => array('IN', $datetime_IDs),
                )
            )
        );
    }
}
