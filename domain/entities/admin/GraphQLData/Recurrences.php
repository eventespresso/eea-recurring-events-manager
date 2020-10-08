<?php

namespace EventEspresso\RecurringEvents\domain\entities\admin\GraphQLData;

use EventEspresso\core\domain\entities\admin\GraphQLData\GraphQLData;

/**
 * Class Recurrences
 * Description
 *
 * @package EventEspresso\RecurringEvents\domain\entities\admin\GraphQLData
 * @author  Manzoor Wani
 * @since   $VID:$
 */
class Recurrences extends GraphQLData
{

    /**
     * @param array $where_params
     * @return array|null
     * @since $VID:$
     */
    public function getData(array $where_params = [])
    {
        $field_key = lcfirst($this->namespace) . 'Recurrences';
        $query = <<<QUERY
        query GET_RECURRENCES(\$where: {$this->namespace}RootQueryRecurrencesConnectionWhereArgs, \$first: Int, \$last: Int ) {
            {$field_key}(where: \$where, first: \$first, last: \$last) {
                nodes {
                    id
                    dbId
                    cacheId
                    exDates
                    exRule
                    patternHash
                    rDates
                    rRule
                    dateDuration
                    salesEndOffset
                    salesStartOffset
                    __typename
                }
                __typename
            }
        }
QUERY;
        $this->setParams([
                             'operation_name' => 'GET_RECURRENCES',
                             'variables'      => [
                                 'first' => 100,
                             ],
                             'query'          => $query,
                         ]);

        return $this->getQueryResponse($field_key, $where_params);
    }
}
