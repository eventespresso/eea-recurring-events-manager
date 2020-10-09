<?php

namespace EventEspresso\RecurringEvents\domain\entities\routing;

use EE_Datetime;
use EE_Dependency_Map;
use EE_Error;
use EventEspresso\core\domain\entities\routing\handlers\frontend\FrontendRequests as CoreFrontendRequests;
use EventEspresso\RecurringEvents\domain\Domain;
use EventEspresso\RecurringEvents\domain\entities\config\RecurringEventsConfig;
use ReflectionException;

class FrontendRequests extends CoreFrontendRequests
{

    /**
     * @var Domain
     */
    private $domain;

    /**
     * @var RecurringEventsConfig
     */
    private $rem_config;


    /**
     * @since $VID:$
     */
    protected function registerDependencies()
    {
        $this->dependency_map->registerDependencies(
            'EE_Front_Controller',
            [
                'EE_Registry'              => EE_Dependency_Map::load_from_cache,
                'EE_Request_Handler'       => EE_Dependency_Map::load_from_cache,
                'EE_Module_Request_Router' => EE_Dependency_Map::load_from_cache,
            ]
        );
    }


    /**
     * implements logic required to run during request
     *
     * @return bool
     * @since   $VID:$
     */
    protected function requestHandler()
    {
        $this->domain = $this->loader->getShared(Domain::class);
        $this->rem_config = $this->loader->getShared(RecurringEventsConfig::class);
        add_filter('FHEE__espresso_list_of_event_dates__arguments', [$this, 'filterDatesListArguments'], 10);
        add_filter('FHEE__espresso_list_of_event_dates__datetime_html', [$this, 'filterDatesListInnerHtml'], 10, 3);
        add_filter('FHEE__espresso_list_of_event_dates__html', [$this, 'filterDatesListHtml']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        return true;
    }


    /**
     * @param array $arguments
     * @return array
     */
    public function filterDatesListArguments(array $arguments)
    {
        $arguments[4]  = $this->rem_config->showExpired();
        $numberOfDates = $this->rem_config->numberOfDates();
        if ($this->rem_config->allowScrolling()) {
            $numberOfDates = null;
        }
        if ($this->rem_config->showNextUpcomingOnly()) {
            $numberOfDates = 1;
        }
        $arguments[7] = $numberOfDates;
        return $arguments;
    }


    /**
     * @param string      $html
     * @param EE_Datetime $datetime
     * @param array       $arguments
     * @return string
     * @throws EE_Error
     * @throws ReflectionException
     */
    public function filterDatesListInnerHtml(string $html, EE_Datetime $datetime, array $arguments)
    {
        switch ($this->rem_config->templateStyle()) {
            case RecurringEventsConfig::TEMPLATE_STYLE_BOX:
            case RecurringEventsConfig::TEMPLATE_STYLE_CARD:
            case RecurringEventsConfig::TEMPLATE_STYLE_CIRCLE:
            case RecurringEventsConfig::TEMPLATE_STYLE_STRIPE:
                return $this->remDatesListTemplate($datetime, $arguments);
        }
        return $html;
    }


    /**
     * @param EE_Datetime $datetime
     * @param array       $arguments
     * @return string
     * @throws EE_Error
     * @throws ReflectionException
     * @since   $VID:$
     */
    private function remDatesListTemplate(EE_Datetime $datetime, array $arguments)
    {
        // the following variables are utilized in the included template
        $name        = $datetime->name();
        $description = $datetime->description();
        [$EVT_ID, $date_format, $time_format, $echo, $show_expired, $format, $add_breaks, $limit] = $arguments;
        ob_start();
        
        include $this->domain->pluginPath('domain/ui/rem-dates-list-datetime.php');
        return ob_get_clean();
    }


    /**
     * @param string $html
     * @return string
     */
    public function filterDatesListHtml(string $html)
    {
        $style      = $this->rem_config->templateStyle();
        $max_height = $this->rem_config->numberOfDates() * 96 + 6;
        $max_height = $this->rem_config->allowScrolling() ? " style='max-height: {$max_height}px;'" : '';
        $attributes = "class='ee-rem-dates-list ee-rem-dates-list--{$style}'{$max_height}";
        $html       = "<div {$attributes}>" . $html . "</div>";
        $heading    = $this->rem_config->showNextUpcomingOnly()
            ? esc_html__('Next Upcoming Date', 'event_espresso')
            : esc_html__('Upcoming Dates', 'event_espresso');
        $html       = "<h3>{$heading}</h3>" . $html;
        return $html;
    }


    public function enqueueScripts()
    {
        wp_register_style(
            'ee-rem-dates-list',
            $this->domain->pluginUrl('domain/ui/ee-rem-dates-list.css'),
            [],
            $this->domain->version()
        );
        wp_enqueue_style('ee-rem-dates-list');
    }
}
