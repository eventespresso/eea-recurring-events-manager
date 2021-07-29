<?php

namespace EventEspresso\RecurringEvents\domain\services\graphql\data\domains;

use EventEspresso\RecurringEvents\domain\services\graphql\data\loaders as Loaders;
use EventEspresso\core\services\graphql\loaders\GQLDataDomainInterface;
use WPGraphQL\AppContext;

/**
 * Class EspressoEditor
 * Description
 *
 * @package EventEspresso\core\domain\services\graphql\data\domains
 * @author  Brent Christensen
 * @since   $VID:$
 */
class EspressoEditor implements GQLDataDomainInterface
{

    /**
     * @param array      $loaders The loaders accessible in the AppContext
     * @param AppContext $context The AppContext
     * @return array
     * @return array
     * @since $VID:$
     */
    public function registerLoaders(array $loaders, AppContext $context): array
    {
        $newLoaders = ['espresso_recurrence' => new Loaders\RecurrenceLoader($context)];
        return array_merge($loaders, $newLoaders);
    }
}
