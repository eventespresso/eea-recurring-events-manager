<?php

namespace EventEspresso\RecurringEvents\domain\entities\routing;

use EEM_Datetime;
use EventEspresso\core\domain\entities\admin\GraphQLData\Datetimes;
use EventEspresso\core\services\json\JsonDataNode;
use EventEspresso\core\services\json\JsonDataNodeValidator;
use EventEspresso\RecurringEvents\domain\entities\admin\GraphQLData\Recurrences;
use WP_Post;

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
     * @param Datetimes $datetimes
     * @param EEM_Datetime $datetimes_model
     * @param JsonDataNodeValidator  $validator
     * @param Recurrences $recurrences
     */
    public function __construct(
        Datetimes $datetimes,
        EEM_Datetime $datetimes_model,
        JsonDataNodeValidator $validator,
        Recurrences $recurrences
    ) {
        parent::__construct($validator);
        $this->datetimes = $datetimes;
        $this->datetimes_model = $datetimes_model;
        $this->recurrences = $recurrences;
        $this->setNodeName(RemEditorData::NODE_NAME);
    }


	/**
	 * @inheritDoc
	 */
	public function initialize()
	{
        global $post;

        $recurrences = [];
        $relations = [];

        $eventId = isset($_REQUEST['post']) ? absint($_REQUEST['post']) : 0;
        // if there's no event ID but there IS a WP Post... then use the Post ID
        $use_post_id = $eventId === 0 && $post instanceof WP_Post && $post->post_type === 'espresso_events';
        $eventId = $use_post_id ? $post->ID : $eventId;
        $datetimes = $this->datetimes->getData(['eventId' => $eventId]);
        if (! empty($datetimes['nodes'])) {
            $datetimeIn = wp_list_pluck($datetimes['nodes'], 'id');
            \EEH_Debug_Tools::printr($datetimeIn, '$datetimeIn', __FILE__, __LINE__);
            if (! empty($datetimeIn)) {
                $recurrences = $this->recurrences->getData(['datetimeIn' => $datetimeIn]);
                \EEH_Debug_Tools::printr($recurrences, '$recurrences', __FILE__, __LINE__);
            }
        }

        // $this->datetimes_model->show_next_x_db_queries();
        // $datetimeIDs = $this->datetimes_model->get_col(
        //     [
        //         [
        //             'Event.EVT_ID' => $eventId,
        //             'RCR_ID' => ['IS_NOT_NULL']
        //         ],
        //         'default_where_conditions' => 'none',
        //     ]
        // );
        // if (! empty($datetimeIDs)) {
        //     \EEH_Debug_Tools::printr($datetimeIDs, '$datetimeIDs', __FILE__, __LINE__);
        //     $relations = $this->datetimes->getData(['datetimeIn' => $datetimeIDs]);
        //     \EEH_Debug_Tools::printr($relations, '$relations', __FILE__, __LINE__);
        //
        //     if (! empty($relations['nodes'])) {
        //         $recurrenceIn = wp_list_pluck($relations['nodes'], 'recurrence');
        //         \EEH_Debug_Tools::printr($recurrenceIn, '$recurrenceIn', __FILE__, __LINE__);
        //
        //         if (! empty($recurrenceIn)) {
        //             $recurrences = $this->recurrences->getData(['recurrenceIn' => $datetimeIDs]);
        //             \EEH_Debug_Tools::printr($recurrences, '$recurrences', __FILE__, __LINE__);
        //         }
        //     }
        // }
        $this->addData('recurrences', $recurrences);
        $this->addData('relations', $datetimes);
	}
}