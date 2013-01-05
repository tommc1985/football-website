<?php
require_once('_base_model.php');

/**
 * Model for Competition Stage data
 */
class Competition_Stage_model extends Base_Model {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'competition_stage';
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        switch ($orderBy) {
            case 'abbreviation':
                return 'abbreviation';
                break;
        }

        return 'name';
    }

    /**
     * Apply Form Validation for Adding & Updating Competition Stages
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('abbreviation', 'Abbreviation', "trim|required|max_length[" .
        $this->config->item('abbreviation_max_length', 'competition_stage') . "]|alpha_numeric|strtoupper|xss_clean");
    }

    /**
     * Fetch All Competition Stages and format for dropdown
     * @return array List of Competition Stages
     */
    public function fetchForDropdown()
    {
        $results = $this->fetchAll(false, false, 'name', 'asc');

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = $result->name;
        }

        return $dropdownOptions;
    }

}