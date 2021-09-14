<?php

/**
 * Class EEM_Recurrence
 *
 * @author  Brent Christensen
 * @since   $VID:$
 * @method EE_Recurrence[] get_all(array $query_params)
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
        $this->singular_item    = esc_html__('Recurrence', 'event_espresso');
        $this->plural_item      = esc_html__('Recurrences', 'event_espresso');
        $this->_tables          = [
            'Recurrence' => new EE_Primary_Table('esp_recurrence', 'RCR_ID'),
        ];
        $this->_fields          = [
            'Recurrence' => [
                'RCR_ID'            => new EE_Primary_Key_Int_Field(
                    'RCR_ID',
                    esc_html__('ID', 'event_espresso')
                ),
                'RCR_name'          => new EE_Plain_Text_Field(
                    'RCR_name',
                    esc_html__('Recurrence Pattern Name', 'event_espresso'),
                    false
                ),
                'RCR_rRule'         => new EE_Plain_Text_Field(
                    'RCR_rRule',
                    esc_html__('Recurrence Rule', 'event_espresso'),
                    false
                ),
                'RCR_exRule'        => new EE_Plain_Text_Field(
                    'RCR_exRule',
                    esc_html__('Exclusion Rule', 'event_espresso'),
                    true,
                    ''
                ),
                'RCR_rDates'        => new EE_Plain_Text_Field(
                    'RCR_rDates',
                    esc_html__('Recurrence Dates', 'event_espresso'),
                    true,
                    ''
                ),
                'RCR_exDates'       => new EE_Plain_Text_Field(
                    'RCR_exDates',
                    esc_html__('Exclusion Dates', 'event_espresso'),
                    true,
                    ''
                ),
                'RCR_date_duration' => new EE_Plain_Text_Field(
                    'RCR_date_duration',
                    esc_html__('Recurring Datetime Duration', 'event_espresso'),
                    true,
                    '1 day'
                ),
            ],
        ];
        $this->_model_relations = ['Datetime' => new EE_Has_Many_Relation()];
        parent::__construct();
    }


    /**
     * @param int[] $datetime_IDs
     * @return EE_Base_Class[]|EE_Recurrence[]
     * @throws EE_Error
     * @throws InvalidArgumentException
     */
    public function findRecurrencesForDatetimes(array $datetime_IDs): array
    {
        return $this->get_all(
            [
                [
                    'Datetime.DTT_ID' => ['IN', $datetime_IDs],
                ],
            ]
        );
    }
}
