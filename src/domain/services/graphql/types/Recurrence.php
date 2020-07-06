<?php

namespace EventEspresso\RecurringEvents\src\domain\services\graphql\types;

use EEM_Recurrence;
use EventEspresso\core\services\graphql\types\TypeBase;
use EventEspresso\core\services\graphql\fields\GraphQLField;
use EventEspresso\core\services\graphql\fields\GraphQLOutputField;
use EventEspresso\RecurringEvents\src\domain\services\graphql\mutators\RecurrenceCreate;
use EventEspresso\RecurringEvents\src\domain\services\graphql\mutators\RecurrenceDelete;
use EventEspresso\RecurringEvents\src\domain\services\graphql\mutators\RecurrenceUpdate;

/**
 * Class Recurrence
 * Description
 *
 * @package EventEspresso\RecurringEvents\src\domain\services\graphql\types
 * @author  Manzoor Wani
 * @since   $VID:$
 */
class Recurrence extends TypeBase
{

    /**
     * Recurrence constructor.
     *
     * @param EEM_Recurrence $recurrence_model
     */
    public function __construct(EEM_Recurrence $recurrence_model)
    {
        $this->model = $recurrence_model;
        $this->setName($this->namespace . 'Recurrence');
        $this->setDescription(__('A Recurrence', 'event_espresso'));
        $this->setIsCustomPostType(false);
        parent::__construct();
    }


    /**
     * @return GraphQLFieldInterface[]
     * @since $VID:$
     */
    public function getFields()
    {
        return [
            new GraphQLField(
                'id',
                ['non_null' => 'ID'],
                null,
                esc_html__('The globally unique ID for the object.', 'event_espresso')
            ),
            new GraphQLOutputField(
                'dbId',
                ['non_null' => 'Int'],
                'ID',
                esc_html__('The recurrence ID.', 'event_espresso')
            ),
            new GraphQLOutputField(
                'cacheId',
                ['non_null' => 'String'],
                null,
                esc_html__('The cache ID of the object.', 'event_espresso')
            ),
            new GraphQLField(
                'name',
                'String',
                'name',
                esc_html__('Recurrence Pattern Name', 'event_espresso')
            ),
            new GraphQLField(
                'rRule',
                'String',
                'rRule',
                esc_html__('Recurrence Pattern', 'event_espresso')
            ),
            new GraphQLField(
                'exRule',
                'String',
                'exRule',
                esc_html__('Exclusion Pattern', 'event_espresso')
            ),
            new GraphQLField(
                'rDates',
                'String',
                'rDates',
                esc_html__('Recurrence dates', 'event_espresso')
            ),
            new GraphQLField(
                'exDates',
                'String',
                'exDates',
                esc_html__('Excluded dates', 'event_espresso')
            ),
            new GraphQLField(
                'gDates',
                'String',
                'generatedDatesJson',
                esc_html__('Generated Dates', 'event_espresso')
            ),
            new GraphQLField(
                'salesStartOffset',
                'String',
                'salesStartOffset',
                esc_html__('Offset for sales start', 'event_espresso')
            ),
            new GraphQLField(
                'salesEndOffset',
                'String',
                'salesEndOffset',
                esc_html__('Offset for sales end', 'event_espresso')
            ),
        ];
    }


    /**
     * @param array $inputFields The mutation input fields.
     * @throws InvalidArgumentException
     * @throws ReflectionException
     * @since $VID:$
     */
    public function registerMutations(array $inputFields)
    {
        // Register mutation to update an entity.
        register_graphql_mutation(
            'update' . $this->name(),
            [
                'inputFields'         => $inputFields,
                'outputFields'        => [
                    lcfirst($this->name()) => [
                        'type'    => $this->name(),
                        'resolve' => [$this, 'resolveFromPayload'],
                    ],
                ],
                'mutateAndGetPayload' => RecurrenceUpdate::mutateAndGetPayload($this->model),
            ]
        );
        // Register mutation to delete an entity.
        register_graphql_mutation(
            'delete' . $this->name(),
            [
                'inputFields'         => [
                    'id'                => $inputFields['id'],
                    'deletePermanently' => [
                        'type'        => 'Boolean',
                        'description' => esc_html__('Whether to delete the entity permanently.', 'event_espresso'),
                    ],
                ],
                'outputFields'        => [
                    lcfirst($this->name()) => [
                        'type'        => $this->name(),
                        'description' => esc_html__('The object before it was deleted', 'event_espresso'),
                        'resolve'     => static function ($payload) {
                            $deleted = (object) $payload['deleted'];

                            return ! empty($deleted) ? $deleted : null;
                        },
                    ],
                ],
                'mutateAndGetPayload' => RecurrenceDelete::mutateAndGetPayload($this->model),
            ]
        );

        // remove primary key from input.
        unset($inputFields['id']);
        // Register mutation to update an entity.
        register_graphql_mutation(
            'create' . $this->name(),
            [
                'inputFields'         => $inputFields,
                'outputFields'        => [
                    lcfirst($this->name()) => [
                        'type'    => $this->name(),
                        'resolve' => [$this, 'resolveFromPayload'],
                    ],
                ],
                'mutateAndGetPayload' => RecurrenceCreate::mutateAndGetPayload($this->model),
            ]
        );
    }
}
