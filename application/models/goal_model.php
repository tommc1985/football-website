<?php
require_once('_base_model.php');

/**
 * Model for Goal data
 */
class Goal_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'goal';
    }

    /**
     * Fetch goal data for a particular match
     * @param  int $matchId  The ID for the specified Match
     * @return object|false  The object (or false if not found)
     */
    public function fetch($matchId)
    {
        $this->db->select('*')
            ->from($this->tableName)
            ->where('match_id', $matchId)
            ->where('deleted', 0)
            ->order_by('minute');

        return $this->db->get()->result();
    }

    /**
     * Apply Form Validation for Adding & Updating Goals
     * @return NULL
     */
    public function formValidation($goalCount)
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules("match_id", 'Match ID', "trim|integer|required|xss_clean");

        $i = 0;
        while ($i < $goalCount) {
            $this->ci->form_validation->set_rules("id[{$i}]", 'Goal', "trim|integer|xss_clean");
            $this->ci->form_validation->set_rules("minute[{$i}]", 'Minute', "trim|integer|callback_is_required[{$i}]|less_than[" . (Configuration::get('max_minute') + 1) . "]|xss_clean");
            $this->ci->form_validation->set_rules("scorer_id[{$i}]", 'Scorer', "trim|integer|callback_is_same_assister[{$i}]|xss_clean");
            $this->ci->form_validation->set_rules("assist_id[{$i}]", 'Assist', "trim|integer|xss_clean");
            $this->ci->form_validation->set_rules("type[{$i}]", 'Type', "trim|integer|callback_is_own_goal[{$i}]|xss_clean");
            $this->ci->form_validation->set_rules("body_part[{$i}]", 'Body Part', "trim|integer|xss_clean");
            $this->ci->form_validation->set_rules("distance[{$i}]", 'Distance', "trim|integer|xss_clean");
            $this->ci->form_validation->set_rules("rating[{$i}]", 'Rating', "trim|integer|less_than[" . (Configuration::get('max_goal_rating') + 1) . "]|xss_clean");
            $this->ci->form_validation->set_rules("description[{$i}]", 'Distance', "trim|xss_clean");

            $i++;
        }
    }

    /**
     * Fetch Goal Types for dropdown
     * @return array List of Goal Types
     */
    public static function fetchTypes()
    {
        return array(
            '1'  => 'Bundled',
            '2'  => 'Corner',
            '3'  => 'Cross',
            '4'  => 'Direct Free Kick',
            '5'  => 'Free Kick',
            '6'  => 'Individual Effort',
            '7'  => 'Opposition Error',
            '8'  => 'Scramble',
            '9'  => 'Through Ball',
            '10' => 'Team Goal',
            '11' => 'Penalty',
            '0'  => 'Own Goal',
        );
    }

    /**
     * Fetch Body Parts for dropdown
     * @return array List of Body Parts
     */
    public static function fetchBodyParts()
    {
        return array(
            '1' => 'Right Foot',
            '2' => 'Left Foot',
            '3' => 'Head',
            '4' => 'Other',
        );
    }

    /**
     * Fetch Goal Distances for dropdown
     * @return array List of Goal Distances
     */
    public static function fetchDistances()
    {
        return array(
            '1' => '6 yard box',
            '2' => 'Penalty Area',
            '3' => 'Edge of Penalty Area',
            '4' => 'Outside Penalty Area',
            '5' => 'Halfway Line',
        );
    }

    /**
     * Fetch Goal Minute Intervals for dropdown
     * @return array List of Goal Distances
     */
    public static function fetchMinuteIntervals()
    {
        return array(
            '1' => '1 - 15',
            '2' => '16 - 30',
            '3' => '31 - 45',
            '4' => '46 - 60',
            '5' => '61 - 75',
            '6' => '76 - 90',
            '7' => '91 - 105',
            '8' => '106 - 120',
        );
    }

    /**
     * Fetch ratings for dropdown
     * @return array List of ratings
     */
    public static function fetchRatings()
    {
        $i = 1;
        $ratings = array();

        while ($i <= Configuration::get('max_goal_rating')) {
            $ratings[$i] = $i;

            $i++;
        }

        $ratings = array_reverse($ratings, true);

        return $ratings;
    }

}