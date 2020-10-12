<?php

namespace EventEspresso\RecurringEvents\domain\entities\routing;

use EEM_Datetime;
use EEM_Recurrence;
use EventEspresso\core\domain\entities\admin\GraphQLData\Datetimes;
use EventEspresso\core\services\json\JsonDataNode;
use EventEspresso\core\services\json\JsonDataNodeValidator;
use EventEspresso\RecurringEvents\domain\entities\admin\GraphQLData\Recurrences;
use WP_Post;
use GraphQLRelay\Relay;

class RemEditorData extends JsonDataNode
{

    const NODE_NAME = 'remEditorData';

    /**
     * @var Datetimes $datetimes
     */
    protected $datetimes;

    /**
     * @var EEM_Datetime $datetimes_model
     */
    protected $datetimes_model;

    /**
     * @var Recurrences $recurrences
     */
    protected $recurrences;


    /**
     * EventEditor JsonDataNode constructor.
     *
     * @param Datetimes             $datetimes
     * @param EEM_Datetime          $datetimes_model
     * @param JsonDataNodeValidator $validator
     * @param Recurrences           $recurrences
     */
    public function __construct(
        Datetimes $datetimes,
        EEM_Datetime $datetimes_model,
        JsonDataNodeValidator $validator,
        Recurrences $recurrences
    ) {
        parent::__construct($validator);
        $this->datetimes       = $datetimes;
        $this->datetimes_model = $datetimes_model;
        $this->recurrences     = $recurrences;
        $this->setNodeName(RemEditorData::NODE_NAME);
    }


    /**
     * @inheritDoc
     */
    public function initialize()
    {
        global $post;

        $recurrences = [];
        $relations   = [];

        $eventId = isset($_REQUEST['post']) ? absint($_REQUEST['post']) : 0;
        // if there's no event ID but there IS a WP Post... then use the Post ID
        $use_post_id = $eventId === 0 && $post instanceof WP_Post && $post->post_type === 'espresso_events';
        $eventId     = $use_post_id ? $post->ID : $eventId;
        $datetimes   = $this->datetimes->getData(['eventId' => $eventId]);
        if (! empty($datetimes['nodes'])) {
            $datetimeIn = wp_list_pluck($datetimes['nodes'], 'id');
            if (! empty($datetimeIn)) {
                $recurrences = $this->recurrences->getData(['datetimeIn' => $datetimeIn]);
            }
        }

        $recurrence_model = EEM_Recurrence::instance();

        if (!empty($recurrences['nodes'])) {
            foreach ($recurrences['nodes'] as $recurrence) {
                $GID = $recurrence['id'];
    
                // Get the IDs of related entities for the recurrence ID.
                $Ids = $this->datetimes_model->get_col([
                    [ 'Recurrence.RCR_ID' => $recurrence['dbId'] ],
                    'default_where_conditions' => 'minimum',
                ]);
                $relations['recurrences'][ $GID ]['datetimes'] = ! empty($Ids)
                    ? $this->convertToGlobalId($this->datetimes_model->item_name(), $Ids)
                    : [];
            }
            // we are here, which means $datetimes['nodes'] will be defined
            foreach ($datetimes['nodes'] as $datetime) {
                $GID = $datetime['id'];
    
                // Get the IDs of related entities for the datetime ID.
                $Ids = $recurrence_model->get_col([
                    [ 'Datetime.DTT_ID' => $datetime['dbId'] ],
                    'default_where_conditions' => 'minimum',
                ]);
                $relations['datetimes'][ $GID ]['recurrences'] = ! empty($Ids)
                    ? $this->convertToGlobalId($recurrence_model->item_name(), $Ids)
                    : [];
            }
        }
        $this->addData('recurrences', $recurrences);
        $this->addData('relations', $relations);
    }


    /**
     * Convert the DB ID into GID
     *
     * @param string    $type
     * @param int|int[] $ID
     * @return mixed
     */
    public function convertToGlobalId($type, $ID)
    {
        $convertToGlobalId = [$this, 'convertToGlobalId'];
        if (is_array($ID)) {
            return array_map(
                static function ($id) use ($convertToGlobalId, $type) {
                    return $convertToGlobalId($type, $id);
                },
                $ID
            );
        }
        return Relay::toGlobalId($type, $ID);
    }
}
