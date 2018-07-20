<?php

namespace EventEspresso\RecurringEvents\src\ui\admin;

use DomainException;
use EEM_Event;
use EventEspresso\core\services\loaders\LoaderInterface;
use EventEspresso\RecurringEvents\src\domain\Domain;
use EventEspresso\RecurringEvents\src\domain\services\assets\RecurringEventsAssetManager;
use Exception;



/**
 * RecurringEventsAdmin
 * logic for hooking into Events Admin Pages.
 *
 * @package EventEspresso\RecurringEvents\src\ui
 * @author  Brent Christensen
 * @since   $VID:$
 */
class RecurringEventsAdmin
{

    /**
     * @var RecurringEventsAssetManager $asset_manager
     */
    public $asset_manager;

    /**
     * @var EEM_Event $event_model
     */
    public $event_model;

    /**
     * @var Domain $domain
     */
    private $domain;

    /**
     * @var LoaderInterface $loader
     */
    public $loader;


    /**
     * RecurringEventsAdmin constructor.
     *
     * @param RecurringEventsAssetManager $asset_manager
     * @param EEM_Event $event_model
     * @param Domain $domain
     * @param LoaderInterface $loader
     */
    public function __construct(
        RecurringEventsAssetManager $asset_manager,
        EEM_Event $event_model,
        Domain $domain,
        LoaderInterface $loader
    ) {
        $this->asset_manager = $asset_manager;
        $this->event_model = $event_model;
        $this->domain      = $domain;
        $this->loader      = $loader;
    }



    public function setHooks() {
        add_action(
            'AHEE__caffeinated_admin_new_pricing_templates__event_tickets_datetime_edit_row__actions_column_last',
            array($this, 'editDatetimeRecurrenceAction'),
            10, 2
        );
        add_action(
            'AHEE__caffeinated_admin_new_pricing_templates__event_tickets_metabox_main__before_content',
            array($this, 'recurrencePatternsForm'), 10, 3
        );
        add_filter(
            'FHEE__EE_Event_Editor_Tips___set_tips_array__qtipsa',
            array($this, 'remQtips'),
            999
        );
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
    }


    /**
     * enqueue_scripts - Load the scripts and css
     *
     * @return void
     * @throws DomainException
     */
    public function enqueueScripts()
    {
        wp_enqueue_style(RecurringEventsAssetManager::CSS_HANDLE_REM_APP);
        wp_enqueue_script(RecurringEventsAssetManager::JS_HANDLE_REM_APP);
    }


    /**
     * @return void
     * @throws \LogicException
     * @throws \EE_Error
     */
    public function editDatetimeRecurrenceAction($dtt_row = 0, $DTT_ID = 0)
    {
        echo '
        <span
            data-context="datetime"
            data-datetime-row="'. $dtt_row.'"
            data-datetime-id="'. $DTT_ID.'" 
            class="ee-edit-datetime-recurrence dashicons dashicons-image-rotate clickable"
        >
        </span>';
    }


    /**
     * @return void
     * @throws \InvalidArgumentException
     * @throws \EventEspresso\core\exceptions\InvalidInterfaceException
     * @throws \EventEspresso\core\exceptions\InvalidDataTypeException
     * @throws \LogicException
     * @throws \EE_Error
     * @throws Exception
     */
    public function recurrencePatternsForm(
        $EVT_ID,
        $existing_datetime_ids,
        $existing_ticket_ids)
    {
        echo \EEH_HTML::div(
            '&nbsp;',
            'eea-recurring-events-manager-app'
        );
    }


    /**
     * @param array $qtips
     *
     * @return array
     */
    public function remQtips(array $qtips)
    {
        // ee-edit-datetime-recurrence
        $qtips[] = array(
            'content_id' => 'ee-edit-datetime-recurrence-help',
            'target'     => '.ee-edit-datetime-recurrence',
            'content'    => __('Edit Recurring Event Datetimes', 'event_espresso')
        );
        return $qtips;
    }



}
// End of file RecurringEventsAdmin.php
// Location: /ui/admin/RecurringEventsAdmin.php
