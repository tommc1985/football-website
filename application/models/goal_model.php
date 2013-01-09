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

}