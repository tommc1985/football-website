<?php
require_once('_base_model.php');

/**
 * Model for Appearance data
 */
class Appearance_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'appearance';
    }

    /**
     * Fetch list of Venue options
     * @return array List of Venue options
     */
    public static function fetchVenues()
    {
        return array(
            'h' => 'Home',
            'a' => 'Away',
            'n'  => 'Neutral',
        );
    }

    /**
     * Fetch list of Status options
     * @return array List of Status options
     */
    public static function fetchStatuses()
    {
        return array(
            'hw' => 'Home Win',
            'aw' => 'Away Win',
            'p'  => 'Postponed',
            'a'  => 'Abandoned',
        );
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        return 'order';
    }

    /**
     * Apply Form Validation for Adding & Updating Matches
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

}