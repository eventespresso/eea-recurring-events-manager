<?php

namespace EventEspresso\RecurringEvents\domain\services\graphql\connection_resolvers;

use EE_Datetime;
use EE_Error;
use EEM_Recurrence;
use EventEspresso\core\domain\services\graphql\connection_resolvers\AbstractConnectionResolver;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use InvalidArgumentException;
use ReflectionException;

/**
 * Class RecurrenceConnectionResolver
 */
class RecurrenceConnectionResolver extends AbstractConnectionResolver
{
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function get_loader_name(): string
    {
        return 'espresso_recurrence';
    }

    /**
     * @return EEM_Recurrence
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     * @throws ReflectionException
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function get_query(): EEM_Recurrence
    {
        return EEM_Recurrence::instance();
    }


    /**
     * Return an array of item IDs from the query
     *
     * @return array
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function get_ids(): array
    {
        $results = $this->query->get_col($this->query_args);

        return ! empty($results)
            ? $results
            : [];
    }


    /**
     * Here, we map the args from the input, then we make sure that we're only querying
     * for IDs. The IDs are then passed down the resolve tree, and deferred resolvers
     * handle batch resolution of the posts.
     *
     * @return array
     * @throws EE_Error
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @throws InvalidDataTypeException
     * @throws InvalidInterfaceException
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function get_query_args(): array
    {
        $where_params = [];
        $query_args   = [];

        $query_args['limit'] = $this->getLimit();

        // Avoid multiple entries by join.
        $query_args['group_by'] = 'RCR_ID';

        $query_args['default_where_conditions'] = 'minimum';

        /**
         * Collect the input_fields and sanitize them to prepare them for sending to the Query
         */
        $input_fields = [];
        if (! empty($this->args['where'])) {
            $input_fields = $this->sanitizeInputFields($this->args['where']);

            // Use the proper operator.
            if (! empty($input_fields['Datetime.DTT_ID']) && is_array($input_fields['Datetime.DTT_ID'])) {
                $input_fields['Datetime.DTT_ID'] = ['in', $input_fields['Datetime.DTT_ID']];
            }
        }

        /**
         * Determine where we're at in the Graph and adjust the query context appropriately.
         */
        if ($this->source instanceof EE_Datetime) {
            $where_params['Datetime.DTT_ID'] = $this->source->ID();
        }

        /**
         * Merge the input_fields with the default query_args
         */
        if (! empty($input_fields)) {
            $where_params = array_merge($where_params, $input_fields);
        }

        $query_args[] = $where_params;

        /**
         * Return the $query_args
         */
        return $query_args;
    }


    /**
     * This sets up the "allowed" args, and translates the GraphQL-friendly keys to model
     * friendly keys.
     *
     * @param array $where_args
     * @return array
     */
    public function sanitizeInputFields(array $where_args): array
    {
        $arg_mapping = [
            'event'        => 'Datetime.EVT_ID',
            'eventIn'      => 'Datetime.EVT_ID',
            'eventIdIn'    => 'Datetime.EVT_ID',
            'eventId'      => 'Datetime.EVT_ID', // priority.
            'datetime'     => 'Datetime.DTT_ID',
            'datetimeIn'   => 'Datetime.DTT_ID',
            'datetimeIdIn' => 'Datetime.DTT_ID',
            'datetimeId'   => 'Datetime.DTT_ID', // priority.
        ];
        return $this->sanitizeWhereArgsForInputFields(
            $where_args,
            $arg_mapping,
            ['datetime', 'datetimeIn', 'event', 'eventIn']
        );
    }
}
