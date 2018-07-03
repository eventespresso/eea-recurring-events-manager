<?php

namespace EventEspresso\RecurringEvents\src\domain\entities;

use BadMethodCallException;
use EE_Datetime;
use EE_Error;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use InvalidArgumentException;
use ReflectionException;



/**
 * Class RecurringDatetime
 * Description
 *
 * @package EventEspresso\RecurringEvents\src\domain\entities
 * @author  Brent Christensen
 * @since   $VID:$
 */
class RecurringDatetime
{
    const META_KEY_DATETIME_RECURRENCE_PATTERN = 'recurrence-pattern';
    const META_KEY_DATETIME_EXCLUSION_PATTERN = 'exclusion-pattern';

    const DATETIME_FORMAT_JAVASCRIPT = 'D M d Y H:i:s O e';

    /**
     * @var EE_Datetime $datetime
     */
    private $datetime;

    /**
     * @var string $recurrence_pattern
     */
    private $recurrence_pattern;

    /**
     * @var string $exclusion_pattern
     */
    private $exclusion_pattern;


    /**
     * RecurringDatetime constructor.
     *
     * @param EE_Datetime $datetime
     */
    public function __construct(EE_Datetime $datetime)
    {
        $this->datetime = $datetime;
    }


    /**
     * @param int    $timestamp
     * @param string $recurrence_pattern
     * @param string $exclusion_pattern
     * @return RecurringDatetime
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public static function createFromTimeStamp($timestamp = 0, $recurrence_pattern = null, $exclusion_pattern = null)
    {
        // $date_time_zone = $date_time_zone instanceof DateTimeZone
        //     ? $date_time_zone
        //     : new DateTimeZone(get_option('timezone_string'));
        // $datetime = DateTime::createFromFormat('U', $timestamp);
        // $datetime->setTimezone($date_time_zone);
        /** @var RecurringDatetime $datetime */
        $datetime = new RecurringDatetime(
            EE_Datetime::new_instance(
                array(
                    'DTT_EVT_start' => $timestamp,
                    'DTT_EVT_end'   => $timestamp + HOUR_IN_SECONDS
                )
            )
        );
        $datetime->save();
        $datetime->setRecurrencePattern($recurrence_pattern);
        $datetime->setExclusionPattern($exclusion_pattern);
        return $datetime;
    }


    /**
     * @param string $recurrence_pattern
     * @return bool
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setRecurrencePattern($recurrence_pattern = null)
    {
        if($recurrence_pattern !== null) {
            $this->recurrence_pattern = $recurrence_pattern;
            return $this->datetime->add_extra_meta(
                RecurringDatetime::META_KEY_DATETIME_RECURRENCE_PATTERN,
                $recurrence_pattern,
                true
            );
        }
        return false;
    }


    /**
     * @return mixed
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function getRecurrencePattern()
    {
        if($this->recurrence_pattern === null){
            $this->recurrence_pattern = $this->datetime->get_extra_meta(
                RecurringDatetime::META_KEY_DATETIME_RECURRENCE_PATTERN,
                true,
                ''
            );
        }
        return $this->recurrence_pattern;
    }


    /**
     * @param string $exclusion_pattern
     * @return bool
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function setExclusionPattern($exclusion_pattern = null)
    {
        if ($exclusion_pattern !== null) {
            $this->exclusion_pattern = $exclusion_pattern;
            return $this->datetime->add_extra_meta(
                RecurringDatetime::META_KEY_DATETIME_EXCLUSION_PATTERN,
                $exclusion_pattern,
                true
            );
        }
        return false;
    }


    /**
     * @return mixed
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function getExclusionPattern()
    {
        if ($this->exclusion_pattern === null) {
            $this->exclusion_pattern = $this->datetime->get_extra_meta(
                RecurringDatetime::META_KEY_DATETIME_EXCLUSION_PATTERN,
                true,
                ''
            );
        }
        return $this->exclusion_pattern;
    }


    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     * @throws InvalidInterfaceException
     * @throws InvalidDataTypeException
     * @throws EE_Error
     */
    public function save()
    {
        $this->datetime->save();
    }


    /**
     * @param $methodName
     * @param $args
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call($methodName, $args)
    {
        if (method_exists($this->datetime, $methodName)) {
            return call_user_func_array(array($this->datetime, $methodName), $args);
        }
        throw new BadMethodCallException(
            sprintf(
                esc_html__(
                    'The %1$s class does not contain a "%2$s()" method.',
                    'event_espresso'
                ),
                'EventEspresso\RecurringEvents\src\domain\entities\RecurringDatetime',
                $methodName
            )
        );
    }
}
// Location: RecurringDatetime.php
