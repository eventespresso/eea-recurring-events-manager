<?php

namespace EventEspresso\RecurringEvents\domain\services\assets;

use DomainException;
use EventEspresso\core\domain\Domain;
use EventEspresso\core\domain\services\assets\ReactAssetManager;

/**
 * Class RecurringEventsAssetManager
 *
 * @package EventEspresso\RecurringEvents\domain\services\assets\RecurringEventsAssetManager
 * @author  Brent Christensen
 *
 * @since   $VID:$
 */
class RecurringEventsAssetManager extends ReactAssetManager
{
    const DOMAIN = 'rem';

    const ASSET_HANDLE = Domain::ASSET_NAMESPACE . '-' . RecurringEventsAssetManager::DOMAIN;


    /**
     * @throws DomainException
     */
    public function enqueueEventEditor()
    {
        if ($this->verifyAssetIsRegistered(RecurringEventsAssetManager::ASSET_HANDLE)) {
            wp_enqueue_script(RecurringEventsAssetManager::ASSET_HANDLE);
            wp_enqueue_style(RecurringEventsAssetManager::ASSET_HANDLE);
        }
    }
}
