<?php

defined('EVENT_ESPRESSO_VERSION') || exit;



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
                'RCR_pattern_hash' => new EE_Plain_Text_Field(
                    'RCR_pattern_hash',
                    esc_html__('Recurrence/Exclusion Pattern Hash', 'event_espresso'),
                    false
                ),
                'RCR_recurrence_pattern' => new EE_Plain_Text_Field(
                    'RCR_recurrence_pattern',
                    esc_html__('Recurrence Pattern', 'event_espresso'),
                    false
                ),
                'RCR_exclusion_pattern' => new EE_Plain_Text_Field(
                    'RCR_exclusion_pattern',
                    esc_html__('Exclusion Pattern', 'event_espresso'),
                    true,
                    null
                ),
                'RCR_dates' => new EE_Plain_Text_Field(
                    'RCR_dates',
                    esc_html__('Recurrence Dates', 'event_espresso'),
                    false
                ),
            )
        );
        $this->_model_relations = array(
            'Datetime_Recurrence' => new EE_HABTM_Relation('Datetime_Recurrence')
        );
        parent::__construct();
    }


    /**
     * generates and returns something like: rph-8cc0ee69dcdebdac148ef760faec071d
     *
     * @param string $recurrence_pattern
     * @param string $exclusion_pattern
     * @return string
     */
    public function generatePatternHash($recurrence_pattern, $exclusion_pattern)
    {
        return 'rph-' . md5($recurrence_pattern . $exclusion_pattern);
    }


    /**
     * @param string $recurrence_pattern
     * @param string $exclusion_pattern
     * @return EE_Recurrence|null
     * @throws EE_Error
     * @throws InvalidArgumentException
     */
    public function findRecurrenceByPatterns($recurrence_pattern, $exclusion_pattern)
    {
        return $this->findRecurrenceByPatternHash(
            $this->generatePatternHash($recurrence_pattern, $exclusion_pattern)
        );
    }


    /**
     * @param string $pattern_hash
     * @return EE_Recurrence|null
     * @throws EE_Error
     * @throws InvalidArgumentException
     */
    public function findRecurrenceByPatternHash($pattern_hash)
    {
        return $this->get_one(
            array(
                array(
                    'RCR_pattern_hash' => $this->isValidRecurrencePatternHash($pattern_hash),
                )
            )
        );
    }


    /**
     * @param string $pattern_hash
     * @return string
     * @throws InvalidArgumentException
     */
    public function isValidRecurrencePatternHash($pattern_hash)
    {
        if (! is_string($pattern_hash) || strpos($pattern_hash, 'rph-') !== 0) {
            throw new InvalidArgumentException(
                esc_html__('Invalid Recurrence Pattern Hash', 'event_espresso')
            );
        }
        return $pattern_hash;
    }


    /**
     * @param int[] $datetime_IDs
     * @return EE_Base_Class[]|EE_Datetime_Recurrence[]
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
