<?php

use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;

defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * Class EE_Datetime_Recurrence
 * Description
 *
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EE_Datetime_Recurrence extends EE_Base_Class
{


    /**
     * @param array  $props_n_values    incoming values
     * @param string $timezone          incoming timezone (if not set the timezone set for the website will be used.)
     * @param array  $date_formats      incoming date_formats in an array where the first value is the date_format
     *                                  and the second value is the time format
     * @return EE_Datetime_Recurrence
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public static function new_instance($props_n_values = array(), $timezone = null, $date_formats = array())
    {
        $has_object = parent::_check_for_object(
            $props_n_values,
            __CLASS__,
            $timezone,
            $date_formats
        );
        return $has_object
            ? $has_object
            : new self($props_n_values, false, $timezone, $date_formats);
    }


    /**
     * @param array  $props_n_values  incoming values from the database
     * @param string $timezone        incoming timezone as set by the model.  If not set the timezone for
     *                                the website will be used.
     * @return EE_Datetime_Recurrence
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public static function new_instance_from_db($props_n_values = array(), $timezone = null)
    {
        return new self($props_n_values, true, $timezone);
    }


    /**
     * @return mixed
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function getID()
    {
        return $this->get('DRC_ID');
    }


    /**
     * @param mixed $ID
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setID($ID)
    {
        if (! is_numeric($ID)) {
            throw new InvalidDataTypeException('Datetime Recurrence ID', $ID, 'integer');
        }
        $this->set('DRC_ID', $ID);
    }


    /**
     * @return mixed
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function getDatetimeID()
    {
        return $this->get('DTT_ID');
    }


    /**
     * @return EE_Base_Class|EE_Datetime
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws ReflectionException
     */
    public function getDatetime()
    {
        return $this->get_first_related('Datetime');
    }


    /**
     * @param mixed $DTT_ID
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setDatetimeID($DTT_ID)
    {
        if (! is_numeric($DTT_ID)) {
            throw new InvalidDataTypeException('Datetime ID', $DTT_ID, 'integer');
        }
        $this->set('DTT_ID', $DTT_ID);
    }


    /**
     * @return mixed
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function getRecurrenceID()
    {
        return $this->get('RCR_ID');
    }


    /**
     * @return EE_Base_Class|EE_Recurrence
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws ReflectionException
     */
    public function getRecurrence()
    {
        return $this->get_first_related('Recurrence');
    }


    /**
     * @param mixed $RCR_ID
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setRecurrenceID($RCR_ID)
    {
        if (! is_numeric($RCR_ID)) {
            throw new InvalidDataTypeException('Recurrence ID', $RCR_ID, 'integer');
        }
        $this->set('RCR_ID', $RCR_ID);
    }


    /**
     * @return boolean
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function isExcluded()
    {
        return $this->get('DRC_exclude');
    }


    /**
     * @param mixed $exclude
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setExclude($exclude)
    {
        $this->set('DRC_exclude', filter_var($exclude, FILTER_VALIDATE_BOOLEAN));
    }


}
