<?php

/**
 * Class EEM_Datetime_Recurrence
 * Adds RCR_ID field and getRecurrence() method onto the EEM_Datetime model.
 *
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EEME_Recurrence_Datetime extends EEME_Base
{

    /**
     * EEME_Recurrence_Datetime constructor.
     *
     * @throws EE_Error
     */
    public function __construct()
    {
        $this->_model_name_extended = 'Datetime';
        $this->_extra_relations = [ 'Recurrence' => new EE_Belongs_To_Relation() ];
        $this->_extra_fields = [
            'Datetime' => [
                'RCR_ID' => new EE_Foreign_Key_Int_Field(
                    'RCR_ID',
                    esc_html__('Recurrence ID', 'event_espresso'),
                    true,
                    0,
                    'Recurrence'
                )
            ]
        ];
        parent::__construct();
    }


    /**
     * @param int $RCR_ID
     * @return EE_Base_Class|EE_Soft_Delete_Base_Class|NULL
     * @throws EE_Error
     * @since $VID:$
     */
    public function ext_getRecurrence($RCR_ID = 0)
    {
        // @codingStandardsIgnoreLine
        return $this->_->get_one(array(array('Recurrence.RCR_ID' => $RCR_ID)));
    }
}
