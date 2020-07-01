<?php

namespace EventEspresso\RecurringEvents\src\domain\services\graphql\mutators;

use EE_Recurrence;
use EEM_Recurrence;
use EventEspresso\RecurringEvents\src\domain\services\graphql\types\Recurrence;
use EventEspresso\RecurringEvents\src\domain\services\graphql\data\mutations\RecurrenceMutation;
use EventEspresso\core\domain\services\graphql\mutators\EntityMutator;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;

class RecurrenceUpdate extends EntityMutator
{

    /**
     * Defines the mutation data modification closure.
     *
     * @param EEM_Recurrence $model
     * @param Recurrence     $type
     * @return callable
     */
    public static function mutateAndGetPayload(EEM_Recurrence $model, Recurrence $type)
    {
        /**
         * Updates an entity.
         *
         * @param array       $input   The input for the mutation
         * @param AppContext  $context The AppContext passed down to all resolvers
         * @param ResolveInfo $info    The ResolveInfo passed down to all resolvers
         * @return array
         */
        return static function ($input, AppContext $context, ResolveInfo $info) use ($model, $type) {
            try {
                /** @var EE_Recurrence $entity */
                $entity = EntityMutator::getEntityFromInputData($model, $input);

                $tickets = [];
                $args = RecurrenceMutation::prepareFields($input);

                // Update the entity
                $entity->save($args);
            } catch (Exception $exception) {
                EntityMutator::handleExceptions(
                    $exception,
                    esc_html__(
                        'The recurrence could not be updated because of the following error(s)',
                        'event_espresso'
                    )
                );
            }

            return [
                'id' => $entity->ID(),
            ];
        };
    }
}
