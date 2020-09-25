<?php

namespace EventEspresso\RecurringEvents\domain\services\admin;

use EE_Error;
use EventEspresso\RecurringEvents\domain\entities\admin\RecurringEventsTemplateSettingsForm;
use EventEspresso\RecurringEvents\domain\entities\config\RecurringEventsConfig;
use ReflectionException;

class RecurringEventsTemplateSettingsFormHandler
{
    /**
     * @var RecurringEventsConfig
     */
    protected $config;

    /**
     * @var RecurringEventsTemplateSettingsForm
     */
    protected $form;


    /**
     * UpdateRecurringEventsTemplateSettingsForm constructor.
     *
     * @param RecurringEventsConfig               $config
     * @param RecurringEventsTemplateSettingsForm $form
     */
    public function __construct(RecurringEventsConfig $config, RecurringEventsTemplateSettingsForm $form) {
        $this->config = $config;
        $this->form   = $form;
    }


    /**
     * @throws EE_Error
     * @throws ReflectionException
     * @since   $VID:$
     */
    public function update()
    {
        try {
            // check for form submission
            if ($this->form->was_submitted()) {
                // capture form data
                $this->form->receive_form_submission();
                // validate form data
                if ($this->form->is_valid()) {
                    // grab validated data from form
                    $valid_data = $this->form->valid_data();
                    // set data on config
                    $this->config->setNumberOfDates($valid_data['rem_settings']['numberOfDates']);
                    $this->config->setShowExpired($valid_data['rem_settings']['showExpired']);
                    $this->config->setShowNextUpcomingOnly($valid_data['rem_settings']['showNextUpcomingOnly']);
                    $this->config->setTemplateStyle($valid_data['rem_settings']['templateStyle']);
                    $this->config->setAllowScrolling($valid_data['rem_settings']['allowScrolling']);
                    $this->config->update();
                } else {
                    if ($this->form->submission_error_message() !== '') {
                        EE_Error::add_error(
                            $this->form->submission_error_message(),
                            __FILE__,
                            __FUNCTION__,
                            __LINE__
                        );
                    }
                }
            }
        } catch (EE_Error $e) {
            $e->get_error();
        }
    }
}
