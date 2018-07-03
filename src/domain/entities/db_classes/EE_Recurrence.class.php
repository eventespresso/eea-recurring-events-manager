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
     * @return EE_Recurrence
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
    public function recurrencePattern()
    {
        return $this->get('RCR_recurrence_pattern');
    }


    /**
     * @param string $recurrence_pattern
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setRecurrencePattern($recurrence_pattern)
    {
        if (! is_string($recurrence_pattern)) {
            throw new InvalidDataTypeException('Recurrence Pattern', $recurrence_pattern, 'string');
        }
        $this->set('RCR_recurrence_pattern', $recurrence_pattern);
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function patternHash()
    {
        return $this->get('RCR_pattern_hash');
    }


    /**
     * @param string $pattern_hash
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setPatternHash($pattern_hash)
    {
        if(EEM_Recurrence::instance()->isValidRecurrencePatternHash($pattern_hash)) {
            $this->set('RCR_pattern_hash', $pattern_hash);
        }
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function exclusionPattern()
    {
        return $this->get('RCR_exclusion_pattern');
    }


    /**
     * @param string $exclusion_pattern
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setExclusionPattern($exclusion_pattern)
    {
        if (! is_string($exclusion_pattern)) {
            throw new InvalidDataTypeException('Exclusion Pattern', $exclusion_pattern, 'string');
        }
        $this->set('RCR_exclusion_pattern', $exclusion_pattern);
    }


    /**
     * @return string
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function recurrenceDatesJson()
    {
        return $this->get('RCR_dates');
    }


    /**
     * @return array
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function recurrenceDatesArray()
    {
        return json_decode($this->recurrenceDatesJson(), true);
    }


    /**
     * @param string $dates
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setRecurrenceDatesJson($dates)
    {
        $this->set('RCR_dates', $this->isValidDatesJson($dates));
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
    public function setRecurrenceDatesArray(array $dates)
    {
        $timestamps = array();
        foreach ($dates as $date) {
            if ($date instanceof EE_Datetime) {
                $timestamps[] = $date->get_raw('DTT_EVT_start');
            } else if (is_numeric($date) && preg_match(EE_Datetime_Field::unix_timestamp_regex, $date)) {
                $timestamps[] = $date;
            } else {
                $timestamps[] = strtotime($date);
            }
        }
        $this->setRecurrenceDatesJson(wp_json_encode($timestamps));
    }


    /**
     * @param $json string
     * @return string
     * @throws InvalidDataTypeException
     */
    private function isValidDatesJson($json)
    {
        json_decode($json);
        if (json_last_error()) {
            throw new InvalidDataTypeException('Recurrence Dates Json', $json, 'JSON string');
        }
        return $json;
    }
}
