<?php
require_once('_base_frontend_model.php');

/**
 * Model for League Page
 */
class League_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'league';
    }

    /**
     * Fetch the standings for a particular league,
     * @param  int $id            League ID
     * @param  string $dateUntil  The date to include, or 'overall' for the current standings
     * @param  string $type       Home, Away or overall)
     * @return array              Ordered list of standings
     */
    public function fetchPositionProgress($id, $dateUntil, $type)
    {
        $ci =& get_instance();
        $ci->load->model('League_Collated_Results_model');
        $ci->load->model('League_Match_model');
        $ci->load->helper(array('opposition', 'utility'));

        $distinctDates = array_reverse($ci->League_Match_model->fetchDistinctDates($id));

        $currentStandings = $ci->League_Collated_Results_model->fetchStandings($id, $dateUntil, $type);
        $data = array(
            'teamCount' => count($currentStandings),
            'dates'     => array(),
        );
        foreach ($currentStandings as $currentStanding) {
            if (!isset($data['standings'][$currentStanding->opposition_id])) {
                $data['standings'][$currentStanding->opposition_id] = array(
                    'opposition' => Opposition_helper::name($currentStanding->opposition_id),
                    'standings'  => array(),
                );
            }
        }

        $dateCount = 1;
        foreach ($distinctDates as $distinctDate) {
            $data['dates'][] = Utility_helper::formattedDate($distinctDate->distinct_date, "  d/m");
            $standings = $ci->League_Collated_Results_model->fetchStandings($id, $distinctDate->distinct_date, $type);

            $positionCount = count($standings) + 1;

            $position = 1;
            foreach ($standings as $standing) {
                $data['standings'][$standing->opposition_id]['standings'][] = $positionCount - $position;

                $position++;
            }

            if ($distinctDate->distinct_date == $dateUntil) {
                break;
            }

            $dateCount++;
        }

        $data['dateCount'] = $dateCount;

        return $this->processForChart($data);
    }

    /**
     * Fetch the standings for a particular league,
     * @param  int $id            League ID
     * @param  string $dateUntil  The date to include, or 'overall' for the current standings
     * @param  string $type       Home, Away or overall)
     * @return array              Ordered list of standings
     */
    public function processForChart($data)
    {
        $labels = array();

        $dataset = array();
        foreach ($data['standings'] as $standings) {
            $dataset[] = array(
                'legend'  => $standings['opposition'],
                'dataset' => $standings['standings'],
            );
        }

        return array(
            'labels'   => $data['dates'],
            'datasets' => $dataset,
            'maxValue' => $data['teamCount'],
        );
    }

    /**
     * Return string of fields to order data by
     * @param  string $orderBy Fields passed
     * @return string          Processed string of fields
     */
    public function getOrderBy($orderBy)
    {
        return 'season';
    }

    /**
     * Return "asc" or "desc" depending on value passed
     * @param  string $order Either "asc" or "desc"
     * @return string        Either "asc" or "desc"
     */
    public function getOrder($order)
    {
        if ($order == 'desc') {
            return 'desc';
        }

        return 'asc';
    }

}