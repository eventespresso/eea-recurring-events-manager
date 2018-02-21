<?php

namespace EventEspresso\RecurringEvents\ui\admin;

use DomainException;
use EEH_HTML;
use EventEspresso\RecurringEvents\domain\Domain;
use WP_Post;

defined('EVENT_ESPRESSO_VERSION') || exit;



/**
 * Class AddNewEventModal
 * Description
 *
 * @package EventEspresso\RecurringEvents\ui\admin
 * @author  Brent Christensen
 * @since   $VID:$
 */
class AddNewEventModal
{

    /**
     * @var Domain $domain
     */
    private $domain;



    /**
     * AddNewEventModal constructor.
     *
     * @param Domain $domain
     */
    public function __construct(Domain $domain)
    {
        \EEH_Debug_Tools::printr(__FUNCTION__, __CLASS__, __FILE__, __LINE__, 2);
        $this->domain = $domain;
    }


    public function setHooks()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action('edit_form_top', array($this, 'addNewEventTypeModal'));
    }



    /**
     * enqueue_scripts - Load the scripts and css
     *
     * @return void
     * @throws DomainException
     */
    public function enqueueScripts()
    {
        // wp_register_style(
        //     'add_new_event_modal',
        //     $this->domain->pluginUrl() . 'ui/css/add_new_event_modal.css',
        //     array(),
        //     $this->domain->version()
        // );
        wp_register_script(
            'add_new_event_modal',
            $this->domain->pluginUrl() . 'ui/js/add_new_event_modal.js',
            array('jquery'),
            $this->domain->version(),
            true
        );
        // wp_enqueue_style('add_new_event_modal');
        wp_enqueue_script('add_new_event_modal');
    }


    public function addNewEventTypeModal(WP_Post $post)
    {
        if($post->post_type !== 'espresso_events') {
            return;
        }
        $html = EEH_HTML::div();
    }


    public function calendarIcon()
    {
        for($day=1; $day<=28; $day++){

        }
        $days =
        $html = EEH_HTML::table(
            EEH_HTML::tr(

            )
        );
    }


}
// Location: AddNewEventModal.php
