<?php
require_once('_base_frontend_model.php');

/**
 * Model for Player Goal Statistics
 */
class Player_Goal_Statistics_model extends Base_Frontend_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'cache_player_goal_statistics';
    }

    /**
     * Fetch all Player Goal Statistics
     * @param  string $statisticGroup    Statistic Type
     * @param  int $playerId           Player ID
     * @param  string $season          Season of Statistics
     * @param  string $type            Type of Statistics
     * @return array                   Returned Statistics
     */
    public function fetchAll($statisticGroup, $playerId, $season, $type)
    {
        $this->db->select('*');
        $this->db->from($this->tableName);
        $this->db->where('statistic_group', $statisticGroup);
        $this->db->where('player_id', $playerId);
        $this->db->where('season', $season);
        $this->db->where('type', $type);
        $this->db->order_by('CAST(`statistic_value` AS UNSIGNED) DESC');

        return $this->db->get()->result();
    }

    /**
     * Fetch all "By Scorer" Goal Statistics
     * @param  int $playerId           Player ID
     * @param  string $season          Season of Statistics
     * @param  string $type            Type of Statistics
     * @return array                   Returned Statistics
     */
    public function fetchByScorer($playerId, $season, $type)
    {
        $ci =& get_instance();
        $ci->load->model('Goal_model');
        $ci->load->helper('player');
        $ci->lang->load('goal');

        $data = array();

        $results = $this->fetchAll('by_scorer', $playerId, $season, $type);

        foreach ($results as $result) {
            $data[$result->statistic_key] = array(
                'label' => $result->statistic_key == 0 ? $ci->lang->line('goal_own_goal') : Player_helper::fullNameReverse($result->statistic_key, false),
                'value' => (int) $result->statistic_value,
            );
        }

        return $data;
    }

    /**
     * Fetch all "By Assister" Goal Statistics
     * @param  int $playerId           Player ID
     * @param  string $season          Season of Statistics
     * @param  string $type            Type of Statistics
     * @return array                   Returned Statistics
     */
    public function fetchByAssister($playerId, $season, $type)
    {
        $ci =& get_instance();
        $ci->load->model('Goal_model');
        $ci->load->helper('player');
        $ci->lang->load('goal');

        $data = array();

        $results = $this->fetchAll('by_assister', $playerId, $season, $type);

        foreach ($results as $result) {
            $data[$result->statistic_key] = array(
                'label' => $result->statistic_key == 0 ? $ci->lang->line('goal_no_assist') : Player_helper::fullNameReverse($result->statistic_key, false),
                'value' => (int) $result->statistic_value,
            );
        }

        return $data;
    }

    /**
     * Fetch all "By Goal Type" Goal Statistics
     * @param  int $playerId           Player ID
     * @param  string $season          Season of Statistics
     * @param  string $type            Type of Statistics
     * @return array                   Returned Statistics
     */
    public function fetchByGoalType($playerId, $season, $type)
    {
        $ci =& get_instance();
        $ci->load->model('Goal_model');

        $data = array();

        $results   = $this->fetchAll('by_goal_type', $playerId, $season, $type);
        $goalTypes = Goal_model::fetchTypes();

        foreach ($results as $result) {
            $data[$result->statistic_key] = array(
                'label' => $goalTypes[$result->statistic_key],
                'value' => (int) $result->statistic_value,
            );
        }

        foreach ($goalTypes as $index => $goalType) {
            if (!isset($data[$index]) && $index != 0) {
                $data[$index] = array(
                    'label' => $goalType,
                    'value' => 0,
                );
            }
        }

        ksort($data);

        return $data;
    }

    /**
     * Fetch all "By Body Part" Goal Statistics
     * @param  int $playerId           Player ID
     * @param  string $season          Season of Statistics
     * @param  string $type            Type of Statistics
     * @return array                   Returned Statistics
     */
    public function fetchByBodyPart($playerId, $season, $type)
    {
        $ci =& get_instance();
        $ci->load->model('Goal_model');

        $data = array();

        $results = $this->fetchAll('by_body_part', $playerId, $season, $type);
        $bodyParts = Goal_model::fetchBodyParts();

        foreach ($results as $result) {
            $data[$result->statistic_key] = array(
                'label' => $bodyParts[$result->statistic_key],
                'value' => (int) $result->statistic_value,
            );
        }

        foreach ($bodyParts as $index => $bodyPart) {
            if (!isset($data[$index]) && $index != 0) {
                $data[$index] = array(
                    'label' => $bodyPart,
                    'value' => 0,
                );
            }
        }

        ksort($data);

        return $data;
    }

    /**
     * Fetch all "By Minute Interval" Goal Statistics
     * @param  int $playerId           Player ID
     * @param  string $season          Season of Statistics
     * @param  string $type            Type of Statistics
     * @return array                   Returned Statistics
     */
    public function fetchByMinuteInterval($playerId, $season, $type)
    {
        $ci =& get_instance();
        $ci->load->model('Goal_model');

        $data = array();

        $results = $this->fetchAll('by_minute_interval', $playerId, $season, $type);
        $minuteIntervals = Goal_model::fetchMinuteIntervals();

        foreach ($results as $result) {
            $data[$result->statistic_key] = array(
                'label' => $minuteIntervals[$result->statistic_key],
                'value' => (int) $result->statistic_value,
            );
        }

        foreach ($minuteIntervals as $index => $minuteInterval) {
            if (!isset($data[$index]) && $index != 0) {
                $data[$index] = array(
                    'label' => $minuteInterval,
                    'value' => 0,
                );
            }
        }

        ksort($data);

        return $data;
    }

}