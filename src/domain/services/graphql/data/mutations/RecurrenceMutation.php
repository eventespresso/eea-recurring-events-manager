<?php

namespace EventEspresso\RecurringEvents\src\domain\services\graphql\data\mutations;

use Recurrence;
use EE_Recurrence;
use EventEspresso\core\exceptions\InvalidDataTypeException;
use EventEspresso\core\exceptions\InvalidInterfaceException;
use Exception;

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
    public static function prepareFields(array $input)
    {
        $args = [];

        if (isset($input['patternHash'])) {
            $args['RCR_pattern_hash'] = sanitize_text_field($input['patternHash']);
        }

        if (isset($input['rRule'])) {
            $args['RCR_recurrence_pattern'] = sanitize_text_field($input['rRule']);
        }

        if (isset($input['exRule'])) {
            $args['RCR_exclusion_pattern'] = sanitize_text_field($input['exRule']);
        }

        if (isset($input['rDates'])) {
            $args['RCR_dates'] = sanitize_text_field($input['rDates']);
        }

        if (isset($input['exDates'])) {
            $args['RCR_exDates'] = sanitize_text_field($input['exDates']);
        }

        if (isset($input['salesStartOffset'])) {
            $args['RCR_sales_start_offset'] = sanitize_text_field($input['salesStartOffset']);
        }

        if (isset($input['salesEndOffset'])) {
            $args['RCR_sales_end_offset'] = sanitize_text_field($input['salesEndOffset']);
        }

        return $args;
    }
}
