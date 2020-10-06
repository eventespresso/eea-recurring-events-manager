<?php

use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;

/**
 * Class EE_Recurrence
 * Description
 *
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EE_Recurrence extends EE_Base_Class
{

    /**
     * @param array  $props_n_values    incoming values
     * @param string $timezone          incoming timezone (if not set the timezone set for the website will be used.)
     * @param array  $date_formats      incoming date_formats in an array where the first value is the date_format
     *                                  and the second value is the time format
     * @return EE_Recurrence
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public static function new_instance($props_n_values = [], $timezone = null, $date_formats = [])
    {
        $has_object = parent::_check_for_object(
            $props_n_values,
            EE_Recurrence::class,
            $timezone,
            $date_formats
        );
        return $has_object
            ? $has_object
            : new EE_Recurrence($props_n_values, false, $timezone, $date_formats);
    }


    /**
     * @param array  $props_n_values  incoming values from the database
     * @param string $timezone        incoming timezone as set by the model.  If not set the timezone for
     *                                the website will be used.
     * @return EE_Recurrence
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public static function new_instance_from_db($props_n_values = [], $timezone = null)
    {
        return new EE_Recurrence($props_n_values, true, $timezone);
    }


    /**
     * @return int
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function ID()
    {
        return $this->get('RCR_ID');
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function name()
    {
        return $this->get('RCR_name');
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
            throw new InvalidDataTypeException('Recurrence ID', $ID, 'integer');
        }
        $this->set('RCR_ID', $ID);
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function rRule()
    {
        return $this->get('RCR_rRule');
    }


    /**
     * @param string $rRule
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setRRule($rRule)
    {
        if (! is_string($rRule)) {
            throw new InvalidDataTypeException('Recurrence Rule', $rRule, 'string');
        }
        $this->set('RCR_rRule', $rRule);
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function exRule()
    {
        return $this->get('RCR_exRule');
    }


    /**
     * @param string $exRule
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setExRule($exRule)
    {
        if (! is_string($exRule)) {
            throw new InvalidDataTypeException('Exclusion Rule', $exRule, 'string');
        }
        $this->set('RCR_exRule', $exRule);
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function rDates()
    {
        return $this->get('RCR_rDates');
    }


    /**
     * @param string $rDates
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setRDates($rDates)
    {
        if (! is_string($rDates)) {
            throw new InvalidDataTypeException('Recurrence Dates', $rDates, 'string');
        }
        $this->set('RCR_rDates', $rDates);
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function exDates()
    {
        return $this->get('RCR_exDates');
    }


    /**
     * @param string $exDates
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setExDates($exDates)
    {
        if (! is_string($exDates)) {
            throw new InvalidDataTypeException('Exclusion Dates', $exDates, 'string');
        }
        $this->set('RCR_exDates', $exDates);
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function dateDuration()
    {
        return $this->get('RCR_date_duration');
    }


    /**
     * @param string $date_duration
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setDateDuration($date_duration)
    {
        if (! is_string($date_duration)) {
            throw new InvalidDataTypeException('Date Duration', $date_duration, 'string');
        }
        $this->set('RCR_date_duration', $date_duration);
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function salesStartOffset()
    {
        return $this->get('RCR_sales_start_offset');
    }


    /**
     * @param string $sales_start_offset
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setSalesStartOffset($sales_start_offset)
    {
        if (! is_string($sales_start_offset)) {
            throw new InvalidDataTypeException(
                'Ticket Sales Start Date Offset',
                $sales_start_offset,
                'string'
            );
        }
        $this->set('RCR_sales_start_offset', $sales_start_offset);
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function salesEndOffset()
    {
        return $this->get('RCR_sales_end_offset');
    }


    /**
     * @param string $sales_end_offset
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setSalesEndOffset($sales_end_offset)
    {
        if (! is_string($sales_end_offset)) {
            throw new InvalidDataTypeException(
                'Ticket Sales End Date Offset',
                $sales_end_offset,
                'string'
            );
        }
        $this->set('RCR_sales_end_offset', $sales_end_offset);
    }
}
