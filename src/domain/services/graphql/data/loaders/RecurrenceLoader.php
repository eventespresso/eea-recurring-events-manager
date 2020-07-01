<?php

namespace EventEspresso\RecurringEvents\src\domain\services\graphql\data\loaders;

use EEM_Recurrence;
use EventEspresso\core\domain\services\graphql\data\loaders\AbstractLoader;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use InvalidArgumentException;

/**
 * Class RecurrenceLoader
 */
class RecurrenceLoader extends AbstractLoader
{
    /**
     * @return EE_Base_Class
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
    protected function getQuery()
    {
        return EEM_Recurrence::instance();
    }

    /**
     * @param array $keys
     *
     * @return array
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
    protected function getWhereParams(array $keys)
    {
        return [
            'RCR_ID' => ['IN', $keys],
        ];
    }
}
