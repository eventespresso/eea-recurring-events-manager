<?php

namespace EventEspresso\RecurringEvents\src\domain\services\graphql\mutators;

use EE_Recurrence;
use EEM_Recurrence;
use EE_Error;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use InvalidArgumentException;
use ReflectionException;
use Exception;
use EventEspresso\core\domain\services\graphql\mutators\EntityMutator;
use GraphQL\Type\Definition\ResolveInfo;
use WPGraphQL\AppContext;

class RecurrenceDelete extends EntityMutator
{

    /**
     * Defines the mutation data modification closure.
     *
     * @param EEM_Recurrence $model
     * @return callable
     */
    public static function mutateAndGetPayload(EEM_Recurrence $model)
    {
        /**
         * Deletes an entity.
         *
         * @param array       $input   The input for the mutation
         * @param AppContext  $context The AppContext passed down to all resolvers
         * @param ResolveInfo $info    The ResolveInfo passed down to all resolvers
         * @return array|void
         */
        return static function ($input, AppContext $context, ResolveInfo $info) use ($model) {
            try {
                /** @var EE_Recurrence $entity */
                $entity = EntityMutator::getEntityFromInputData($model, $input);

                // Delete the entity
                if (! empty($input['deletePermanently'])) {
                    $result = $entity->delete_permanently();
                } else {
                    $result = $entity->delete();
                }
                EntityMutator::validateResults($result);
            } catch (Exception $exception) {
                EntityMutator::handleExceptions(
                    $exception,
                    esc_html__(
                        'The recurrence could not be deleted because of the following error(s)',
                        'event_espresso'
                    )
                );
            }

            return [
                'deleted' => $entity,
            ];
        };
    }
}
