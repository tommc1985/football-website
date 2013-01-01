<?php
require_once('_base_model.php');

/**
 * Model for Competition data
 */
class Competition_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'competition';
    }

    /**
     * Fetch list of Types
     * @return array List of competition types
     */
    public static function fetchTypes()
    {
        return array(
            'friendly' => 'Friendly',
            'league' => 'League',
            'cup' => 'Cup',
            'europe' => 'Europe',
            'other' => 'Other',
        );
    }

    /**
     * Fetch list of Competitive/Non-Competitive options
     * @return array List of Competitive/Non-Competitive options
     */
    public static function fetchCompetitive()
    {
        return array(
            '1' => 'Competitive',
            '0' => 'Non-Competitive',
        );
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        switch ($orderBy) {
            case 'type':
                return 'type, name';
                break;
            case 'abbreviation':
                return 'abbreviation';
                break;
        }

        return 'name';
    }

    /**
     * Apply Form Validation for Adding & Updating Competitions
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('short_name', 'Short Name', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('abbreviation', 'Abbreviation', "trim|requiredregex_match[/^[A-Za-z0-9']+$/]|max_length[" .
        $this->config->item('abbreviation_max_length', 'competition') . "]|strtoupper|xss_clean");
        $this->ci->form_validation->set_rules('type', 'Type', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('starts', 'Starts', 'trim|is_natural_no_zero|xss_clean');
        $this->ci->form_validation->set_rules('subs', 'Substitutes', 'trim|is_natural|xss_clean');
        $this->ci->form_validation->set_rules('competitive', 'Competitive', 'trim|integer|xss_clean');
    }

}