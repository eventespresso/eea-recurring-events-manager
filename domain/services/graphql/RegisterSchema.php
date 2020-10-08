<?php

namespace EventEspresso\RecurringEvents\domain\services\graphql;

use EventEspresso\core\domain\services\graphql\Utilities;
use EventEspresso\core\services\graphql\fields\GraphQLFieldInterface;
use EventEspresso\core\services\graphql\fields\GraphQLInputField;
use GraphQLRelay\Relay;

/**
 * Class RegisterSchema
 * Description
 *
 * @package EventEspresso\RecurringEvents\domain\services\graphql
 * @author  Manzoor Wani
 * @since   $VID:$
 */
class RegisterSchema
{

    /**
     * @var Utilities
     */
    private $utilities;


    /**
     * RegisterSchema constructor.
     *
     * @param Utilities $utilities
     */
    public function __construct(Utilities $utilities)
    {
        $this->utilities = $utilities;
    }


    /**
     * @return void
     * @since $VID:$
     */
    public function addFilters()
    {
        add_filter(
            'FHEE__EventEspresso_core_domain_services_graphql_types__datetime_fields',
            [$this, 'registerCoreDatetimeFields']
        );
        add_filter(
            'FHEE__EventEspresso_core_domain_services_graphql_data_mutations__datetime_args',
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
        $where_params = $this->utilities->sanitizeWhereArgs(
            $where_params,
            [
                'recurrence'     => 'RCR_ID',
                'recurrenceIn'   => 'RCR_ID',
                'recurrenceId'   => 'RCR_ID',
                'recurrenceIdIn' => 'RCR_ID',
            ],
            ['recurrence', 'recurrenceIn'],
            ['include_all_args' => true, 'use_IN_operator' => true]
        );
        return $where_params;
    }
}
