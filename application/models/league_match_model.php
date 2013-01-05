<?php
require_once('_base_model.php');

/**
 * Model for League Match data
 */
class League_Match_model extends Base_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'league_match';
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        return 'date';
    }

    /**
     * Apply Form Validation for Adding & Updating League Matches
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('league_id', 'League', 'trim|required|integer|xss_clean');
        $this->ci->form_validation->set_rules('date', 'Date', 'trim|required||regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]xss_clean');
        $this->ci->form_validation->set_rules('h_opposition_id', 'Home Team', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('a_opposition_id', 'Away Team', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('h_score', 'Home Score', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('a_score', 'Away Score', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('status', 'Status', 'trim|xss_clean');
    }

}