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

        $this->ci->form_validation->set_rules('name', 'Name', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('short_name', 'Short Name', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('abbreviation', 'Abbreviation', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('type', 'Type', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('starts', 'Starts', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('subs', 'Substitutes', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('competitive', 'Competitive', 'trim|xss_clean');
    }

}