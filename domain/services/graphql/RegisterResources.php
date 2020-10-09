<?php

namespace EventEspresso\RecurringEvents\domain\services\graphql;

/**
 * Class RegisterResources
 *
 * @package EventEspresso\RecurringEvents\domain\services\graphql
 * @since   $VID:$
 */
class RegisterResources
{

    /**
     * @var RegisterSchema $schema
     */
    protected $schema;

    /**
     * @var boolean $registered
     */
    protected $registered = false;


    /**
     * RegisterResources constructor.
     *
     * @param RegisterSchema $schema
     */
    public function __construct(RegisterSchema $schema)
    {
        $this->schema = $schema;
    }


    public function initialize()
    {
        if (! $this->registered) {
            $this->schema->addFilters();
            add_filter(
                'FHEE__EventEspresso_core_services_graphql_TypeCollection__loadCollection__collection_FQCNs',
                [$this, 'registerTypes']
            );
            add_filter(
                'FHEE__EventEspresso_core_services_graphql_ConnectionCollection__loadCollection__collection_FQCNs',
                [$this, 'registerConnections']
            );
            add_filter(
                'FHEE__EventEspresso_core_services_graphql_DataLoaderCollection__loadCollection__collection_FQCNs',
                [$this, 'registerDataLoaders']
            );
            $this->registered = true;
        }
    }



    /**
     * @param array $collection_FQCNs
     * @return array
     */
    public function registerConnections(array $collection_FQCNs = [])
    {
        $collection_FQCNs[] = 'EventEspresso\RecurringEvents\domain\services\graphql\connections';
        return $collection_FQCNs;
    }


    /**
     * @param array $collection_FQCNs
     * @return array
     */
    public function registerDataLoaders(array $collection_FQCNs = [])
    {
        $collection_FQCNs[] = 'EventEspresso\RecurringEvents\domain\services\graphql\data\domains';
        return $collection_FQCNs;
    }


    /**
     * @param array $collection_FQCNs
     * @return array
     */
    public function registerTypes(array $collection_FQCNs = [])
    {
        $collection_FQCNs[] = 'EventEspresso\RecurringEvents\domain\services\graphql\types';
        return $collection_FQCNs;
    }
}
