<?php

namespace EventEspresso\RecurringEvents\domain\services\graphql\connections;

use EEM_Recurrence;
use EventEspresso\RecurringEvents\domain\services\graphql\connection_resolvers\RecurrenceConnectionResolver;
use EventEspresso\core\domain\services\graphql\abstracts\AbstractRootQueryConnection;
use Exception;

/**
 * Class RootQueryRecurrencesConnection
 * Description
 *
 * @package EventEspresso\RecurringEvents\domain\services\graphql\connections
 * @author  Manzoor Wani
 * @since   $VID:$
 */
class RootQueryRecurrencesConnection extends AbstractRootQueryConnection
{

    /**
     * RecurrenceConnection constructor.
     *
     * @param EEM_Recurrence               $model
     */
    public function __construct(EEM_Recurrence $model)
    {
        $this->model = $model;
    }


    /**
     * @return array
     * @since $VID:$
     */
    public function config()
    {
        return [
            'fromType'           => 'RootQuery',
            'toType'             => $this->namespace . 'Recurrence',
            'fromFieldName'      => lcfirst($this->namespace . 'Recurrences'),
            'connectionTypeName' => "{$this->namespace}RootQueryRecurrencesConnection",
            'connectionArgs'     => RootQueryRecurrencesConnection::get_connection_args(),
            'resolve'            => [$this, 'resolveConnection'],
        ];
    }


    /**
     * @param $entity
     * @param $args
     * @param $context
     * @param $info
     * @return RecurrenceConnectionResolver
     * @throws Exception
     * @since $VID:$
     */
    public function getConnectionResolver($entity, $args, $context, $info)
    {
        return new RecurrenceConnectionResolver($entity, $args, $context, $info);
    }

    /**
     * Given an optional array of args, this returns the args to be used in the connection
     *
     * @access public
     * @param array $args The args to modify the defaults
     *
     * @return array
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public static function get_connection_args($args = [])
    {
        return array_merge(
            [
                'datetime' => [
                    'type'        => 'ID',
                    'description' => esc_html__(
                        'Globally unique datetime ID to get the recurrences for.',
                        'event_espresso'
                    ),
                ],
                'datetimeIn' => [
                    'type'        => ['list_of' => 'ID'],
                    'description' => esc_html__(
                        'Globally unique datetime IDs to get the recurrences for.',
                        'event_espresso'
                    ),
                ],
                'datetimeId' => [
                    'type'        => 'Int',
                    'description' => esc_html__('Datetime ID to get the recurrences for.', 'event_espresso'),
                ],
                'datetimeIdIn' => [
                    'type'        => ['list_of' => 'Int'],
                    'description' => esc_html__('Datetime IDs to get the recurrences for.', 'event_espresso'),
                ],
            ],
            $args
        );
    }
}
