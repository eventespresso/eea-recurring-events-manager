<?php

use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;

/**
 * Class EEE_Recurrence_Datetime
 * Adds a 'recurrence()' function onto each EE_Datetime object.
 *
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EEE_Recurrence_Datetime extends EEE_Base_Class
{

    /**
     * EEE_Recurrence_Datetime constructor.
     *
     * @throws EE_Error
     */
    public function __construct()
    {
        $this->_model_name_extended = 'Datetime';
        parent::__construct();
    }


    /**
     * @param array $query_params
     * @return EE_Base_Class|EE_Recurrence|EE_Soft_Delete_Base_Class|NULL
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
    public function ext_recurrence($query_params = array())
    {
        return EEM_Recurrence::instance()->get_one(
            array_replace_recursive(
                array(array('Recurrence.RCR_ID' => $this->_->get('RCR_ID'))),
                $query_params
            )
        );
    }
}
