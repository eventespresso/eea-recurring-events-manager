<?php

namespace EventEspresso\RecurringEvents\domain\services\graphql\data\mutations;

/**
 * Class RecurrenceMutation
 *
 * @package       Event Espresso
 * @author        Manzoor Wani
 */
class RecurrenceMutation
{

    /**
     * Maps the GraphQL input to a format that the model functions can use
     *
     * @param array $input Data coming from the GraphQL mutation query input
     * @return array
     */
    public static function prepareFields(array $input): array
    {
        $args = [];

        if (isset($input['name'])) {
            $args['RCR_name'] = sanitize_text_field($input['name']);
        }

        if (isset($input['rRule'])) {
            $args['RCR_rRule'] = sanitize_textarea_field($input['rRule']);
        }

        if (isset($input['exRule'])) {
            $args['RCR_exRule'] = sanitize_textarea_field($input['exRule']);
        }

        if (isset($input['rDates'])) {
            $args['RCR_rDates'] = sanitize_text_field($input['rDates']);
        }

        if (isset($input['exDates'])) {
            $args['RCR_exDates'] = sanitize_text_field($input['exDates']);
        }

        if (isset($input['dateDuration'])) {
            $args['RCR_date_duration'] = sanitize_text_field($input['dateDuration']);
        }

        return $args;
    }
}
