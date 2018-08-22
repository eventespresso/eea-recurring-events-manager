<?php

namespace EventEspresso\RecurringEvents\src\domain\services\assets;

use DomainException;
use EventEspresso\core\domain\services\assets\AdminRefactorAssetManager;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidEntityException;
use EventEspresso\core\services\assets\AssetManager;
use EventEspresso\core\services\collections\DuplicateCollectionIdentifierException;

/**
 * Class RecurringEventsAssetManager
 * Description
 *
 * @package EventEspresso\RecurringEvents\src\domain\services\assets\RecurringEventsAssetManager
 * @author  Brent Christensen
 *
 * @since   $VID:$
 */
class RecurringEventsAssetManager extends AssetManager
{
    const JS_HANDLE_REM_APP = 'eea-recurring-events-manager-app';
    const CSS_HANDLE_REM_APP = 'eea-recurring-events-manager-app';
    const ASSET_CHUNK_NAME = 'recurring-events-manager-app';

    /**
     * @since 4.9.62.p
     * @throws DomainException
     * @throws InvalidDataTypeException
     * @throws InvalidEntityException
     * @throws DuplicateCollectionIdentifierException
     */
    public function addAssets()
    {
        $this->registerJavascript();
        $this->registerStyleSheets();
    }


    /**
     * Register javascript assets
     *
     * @throws DomainException
     * @throws InvalidDataTypeException
     * @throws InvalidEntityException
     * @throws DuplicateCollectionIdentifierException
     */
    private function registerJavascript()
    {
        $this->addJavascript(
            self::JS_HANDLE_REM_APP,
            $this->registry->getJsUrl(
                $this->domain->assetNamespace(),
                self::ASSET_CHUNK_NAME
            ),
            [
                AdminRefactorAssetManager::JS_HANDLE_EDITOR,
                // self::JS_HANDLE_RRULE
            ]
        )
            ->setRequiresTranslation()
            ->setVersion($this->domain->version());
    }


    /**
     * Register CSS assets.
     *
     * @throws DomainException
     * @throws DuplicateCollectionIdentifierException
     * @throws InvalidDataTypeException
     * @throws InvalidEntityException
     */
    private function registerStyleSheets()
    {
        $this->addStylesheet(
            self::CSS_HANDLE_REM_APP,
            $this->registry->getCssUrl(
                $this->domain->assetNamespace(),
                self::ASSET_CHUNK_NAME
            ),
            [ AdminRefactorAssetManager::CSS_HANDLE_EDITOR ]
        );
    }
}
