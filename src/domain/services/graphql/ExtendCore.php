<?php

namespace EventEspresso\RecurringEvents\src\domain\services\graphql;

use EventEspresso\core\services\graphql\fields\GraphQLField;
use EventEspresso\core\services\graphql\fields\GraphQLInputField;
use EventEspresso\core\services\graphql\fields\GraphQLOutputField;
use GraphQLRelay\Relay;

/**
 * Class ExtendCore
 * Description
 *
 * @package EventEspresso\RecurringEvents\src\domain\services\graphql
 * @author  Manzoor Wani
 * @since   $VID:$
 */
class ExtendCore
{
    /**
     * @return void
     * @since $VID:$
     */
    public function hookUp()
    {
        add_filter(
            'FHEE__EventEspresso_core_domain_services_graphql_types__datetime_fields',
            [$this, 'registerCoreDatetimeFields']
        );
        add_filter(
            'FHEE__EventEspresso_core_domain_services_graphql_data__datetime_mutation_args',
            [$this, 'addDatetimeMutationArgs'],
            10,
            2
        );
        add_filter(
            'FHEE__EventEspresso_core_domain_services_graphql_connections__datetime_args',
            [$this, 'addDatetimeConnectionArgs']
        );
        add_filter(
            'FHEE__EventEspresso_core_domain_services_graphql_connection_resolvers__datetime_where_params',
            [$this, 'addDatetimeConnectionWhereParams'],
            10,
            3
        );
    }


    /**
     * @param GraphQLFieldInterface[] $fields
     * @return GraphQLFieldInterface[]
     * @since $VID:$
     */
    public function registerCoreDatetimeFields($fields)
    {
        $newFields = [
            // add recurrence field to datetime mutation input
            new GraphQLInputField(
                'recurrence',
                'String',
                null,
                sprintf(
                    '%1$s %2$s',
                    esc_html__('Globally unique ID of the recurrence related to the datetime.', 'event_espresso'),
                    esc_html__('Ignored if empty.', 'event_espresso')
                )
            ),
        ];

        return array_merge($fields, $newFields);
    }


    /**
     * @param array $args  The args that goto DB models.
     * @param array $input Data coming from the GraphQL mutation query input.
     * @return array
     * @since $VID:$
     */
    public function addDatetimeMutationArgs($args, $input)
    {
        if (! empty($input['recurrence'])) {
            $parts = Relay::fromGlobalId(sanitize_text_field($input['recurrence']));
            $args['RCR_ID'] = (! empty($parts['id']) && is_int($parts['id'])) ? $parts['id'] : null;
        }

        return $args;
    }


    /**
     * @param array $args The args for the connection
     * @return array
     * @since $VID:$
     */
    public function addDatetimeConnectionArgs($args)
    {
        $newArgs = [
            'recurrence'  => [
                'type'        => 'ID',
                'description' => esc_html__('Globally unique recurrence ID to get the datetimes for.', 'event_espresso'),
            ],
            'recurrenceIn'  => [
                'type'        => ['list_of' => 'ID'],
                'description' => esc_html__('Globally unique recurrence IDs to get the datetimes for.', 'event_espresso'),
            ],
            'recurrenceId'  => [
                'type'        => 'Int',
                'description' => esc_html__('Recurrence ID to get the datetimes for.', 'event_espresso'),
            ],
            'recurrenceIdIn'  => [
                'type'        => ['list_of' => 'Int'],
                'description' => esc_html__('Recurrence IDs to get the datetimes for.', 'event_espresso'),
            ],
        ];

        return array_merge($args, $newArgs);
    }


    /**
     * @param array $where_params  The params used to query DB models.
     * @param mixed $source The source of the connection.
     * @param array $args The GQL args.
     * @return array
     * @since $VID:$
     */
    public function addDatetimeConnectionWhereParams($where_params, $source, $args)
    {
        $arg_mapping = [
            'recurrence'     => 'RCR_ID',
            'recurrenceIn'   => 'RCR_ID',
            'recurrenceId'   => 'RCR_ID',
            'recurrenceIdIn' => 'RCR_ID',
        ];
        $id_fields = ['recurrence', 'recurrenceIn'];
        foreach ($args['where'] as $arg => $value) {
            if (! array_key_exists($arg, $arg_mapping)) {
                continue;
            }

            if (is_array($value) && ! empty($value)) {
                $value = array_map(
                    static function ($value) {
                        if (is_string($value)) {
                            $value = sanitize_text_field($value);
                        }
                        return $value;
                    },
                    $value
                );
            } elseif (is_string($value)) {
                $value = sanitize_text_field($value);
            }
            $where_params[ $arg_mapping[ $arg ] ] = in_array($arg, $id_fields, true)
                ? $this->convertGlobalId($value)
                : $value;
        }

        // Use the proper operator.
        if (! empty($where_params['RCR_ID']) && is_array($where_params['RCR_ID'])) {
            $where_params['RCR_ID'] = ['IN', $where_params['RCR_ID']];
        }

        return $where_params;
    }


    /**
     * Converts global ID to DB ID.
     *
     * @param string|string[] $ID
     * @return mixed
     */
    protected function convertGlobalId($ID)
    {
        if (is_array($ID)) {
            return array_map([ $this, 'convertGlobalId' ], $ID);
        }
        $parts = Relay::fromGlobalId($ID);
        return ! empty($parts['id']) ? $parts['id'] : null;
    }
}
