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
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('opposition_id', 'Opposition', 'trim|required|integer|xss_clean');
        $this->ci->form_validation->set_rules('competition_id', 'Competition', 'trim|required|integer|xss_clean');
        $this->ci->form_validation->set_rules('competition_stage_id', 'Competition Stage', 'trim|integer|xss_clean');
        $this->ci->form_validation->set_rules('venue', 'Venue', "trim|required|regex_match[/^(h)|(a)|(n)$/|xss_clean");
        $this->ci->form_validation->set_rules('location', 'Location', "trim||max_length[" . $this->config->item('location_max_length', 'match') . "]xss_clean");
        $this->ci->form_validation->set_rules('official_id', 'Official', 'trim|integer|xss_clean');
        $this->ci->form_validation->set_rules('h', 'Your Score', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('a', 'Opposition Score', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('report', 'Report', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('date', 'Date', 'trim|required|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]|xss_clean');
        $this->ci->form_validation->set_rules('time', 'Time', 'trim|required|regex_match[/^[0-9]{2}:[0-9]{2}$/]|xss_clean');
        $this->ci->form_validation->set_rules('h_et', 'Your Goals After 90 mins (If Extra Time is played)', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('a_et', 'Opposition Goals After 90 mins (If Extra Time is played)', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('h_pen', 'Your Score Penalties', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('a_pen', 'Opposition Score Penalties', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('status', 'Status', 'trim|regex_match[/^(hw)|(aw)|(p)|(a)$/]|xss_clean');
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