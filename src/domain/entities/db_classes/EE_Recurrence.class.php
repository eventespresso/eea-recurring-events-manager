<?php

use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;

defined('EVENT_ESPRESSO_VERSION') || exit;

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
    public function generatedDatesJson()
    {
        return $this->get('RCR_gDates');
    }


    /**
     * @return array
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function generatedDatesArray()
    {
        return json_decode($this->generatedDatesJson(), true);
    }


    /**
     * @param string $dates
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setGeneratedDatesJson($dates)
    {
        $this->set('RCR_gDates', $this->isValidDatesJson($dates));
    }


    /**
     * Accepts:
     *      an array of EE_Datetime objects,
     *      an array of Unix timestamp integers,
     *      an array of date strings in a format recognizable to PHP,
     *
     * @param int[]|EE_Datetime[]|string[] $dates
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setGeneratedDatesArray(array $dates)
    {
        $timestamps = [];
        foreach ($dates as $date) {
            if ($date instanceof EE_Datetime) {
                $timestamps[] = $date->get_raw('DTT_EVT_start');
            } elseif (is_numeric($date) && preg_match(EE_Datetime_Field::unix_timestamp_regex, $date)) {
                $timestamps[] = $date;
            } else {
                $timestamps[] = strtotime($date);
            }
        }
        $this->setGeneratedDatesJson(wp_json_encode($timestamps));
    }


    /**
     * @param $json string
     * @return string
     * @throws InvalidDataTypeException
     */
    private function isValidDatesJson($json)
    {
        json_decode($json, false);
        if (json_last_error()) {
            throw new InvalidDataTypeException('Generated Recurrence Dates Json', $json, 'JSON string');
        }
        return $json;
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
