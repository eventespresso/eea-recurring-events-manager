<?php

namespace EventEspresso\RecurringEvents\domain\entities\config;

use EventEspresso\core\services\json\JsonConfig;

/**
 * Class RecurringEventsConfig
 *
 * @package EventEspresso\RecurringEvents\domain\entities\config
 * @since   $VID:$
 */
class RecurringEventsConfig extends JsonConfig
{

    const TEMPLATE_STYLE_BOX = 'box';
    const TEMPLATE_STYLE_CARD = 'card';
    const TEMPLATE_STYLE_CIRCLE = 'circle';
    const TEMPLATE_STYLE_DEFAULT = 'default';
    const TEMPLATE_STYLE_STRIPE = 'stripe';

    /**
     * @var boolean $allow_scrolling
     */
    protected $allow_scrolling;

    /**
     * @var int $number_of_dates
     */
    protected $number_of_dates;

    /**
     * @var boolean $show_next_upcoming_only
     */
    protected $show_next_upcoming_only;

    /**
     * @var boolean $show_expired
     */
    protected $show_expired;

    /**
     * @var string $template_style
     */
    protected $template_style;


    /**
     * RecurringEventsConfig constructor.
     *
     * @param bool   $allow_scrolling
     * @param int    $number_of_dates
     * @param bool   $show_expired
     * @param bool   $show_next_upcoming_only
     * @param string $template_style
     */
    public function __construct(
        $allow_scrolling = true,
        $number_of_dates = 5,
        $show_expired = false,
        $show_next_upcoming_only = false,
        $template_style = RecurringEventsConfig::TEMPLATE_STYLE_STRIPE
    ) {
        parent::__construct(
            [
                'allow_scrolling'         => $allow_scrolling,
                'number_of_dates'         => $number_of_dates,
                'show_expired'            => $show_expired,
                'show_next_upcoming_only' => $show_next_upcoming_only,
                'template_style'          => $template_style,
            ]
        );
    }


    /**
     * @return array
     */
    protected function getProperties()
    {
        return get_object_vars($this);
    }


    /**
     * @return bool
     */
    public function allowScrolling()
    {
        return $this->allow_scrolling;
    }


    /**
     * @param bool $allow_scrolling
     */
    public function setAllowScrolling($allow_scrolling)
    {
        $this->setProperty('allow_scrolling', filter_var($allow_scrolling, FILTER_VALIDATE_BOOLEAN));
    }


    /**
     * @return int
     */
    public function numberOfDates()
    {
        return $this->number_of_dates;
    }


    /**
     * @param int $number_of_dates
     */
    public function setNumberOfDates($number_of_dates = 6)
    {
        $number_of_dates = absint($number_of_dates);
        $number_of_dates = $number_of_dates > 0 ? $number_of_dates : 1;
        $this->setProperty('number_of_dates', $number_of_dates);
    }


    /**
     * @return bool
     */
    public function showExpired()
    {
        return $this->show_expired;
    }


    /**
     * @param bool $show_expired
     */
    public function setShowExpired($show_expired = false)
    {
        $this->setProperty('show_expired', filter_var($show_expired, FILTER_VALIDATE_BOOLEAN));
    }


    /**
     * @return bool
     */
    public function showNextUpcomingOnly()
    {
        return $this->show_next_upcoming_only;
    }


    /**
     * @param bool $show_next_upcoming_only
     */
    public function setShowNextUpcomingOnly($show_next_upcoming_only = false)
    {
        $this->setProperty('show_next_upcoming_only', filter_var($show_next_upcoming_only, FILTER_VALIDATE_BOOLEAN));
    }


    /**
     * @return string
     */
    public function templateStyle()
    {
        return $this->template_style;
    }


    /**
     * @return array
     */
    public function templateStyles()
    {
        return [
            RecurringEventsConfig::TEMPLATE_STYLE_BOX,
            RecurringEventsConfig::TEMPLATE_STYLE_CARD,
            RecurringEventsConfig::TEMPLATE_STYLE_CIRCLE,
            RecurringEventsConfig::TEMPLATE_STYLE_DEFAULT,
            RecurringEventsConfig::TEMPLATE_STYLE_STRIPE,
        ];
    }


    /**
     * @return array
     */
    public function templateStyleOptions()
    {
        return [
            RecurringEventsConfig::TEMPLATE_STYLE_BOX     => RecurringEventsConfig::TEMPLATE_STYLE_BOX,
            RecurringEventsConfig::TEMPLATE_STYLE_CARD    => RecurringEventsConfig::TEMPLATE_STYLE_CARD,
            RecurringEventsConfig::TEMPLATE_STYLE_CIRCLE  => RecurringEventsConfig::TEMPLATE_STYLE_CIRCLE,
            RecurringEventsConfig::TEMPLATE_STYLE_DEFAULT => RecurringEventsConfig::TEMPLATE_STYLE_DEFAULT,
            RecurringEventsConfig::TEMPLATE_STYLE_STRIPE  => RecurringEventsConfig::TEMPLATE_STYLE_STRIPE,
        ];
    }


    /**
     * @param string $template_style
     */
    public function setTemplateStyle($template_style = '')
    {
        $this->setProperty(
            'template_style',
            in_array($template_style, $this->templateStyles()) ? $template_style : RecurringEventsConfig::TEMPLATE_STYLE_STRIPE
        );
    }
}
