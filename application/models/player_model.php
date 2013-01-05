<?php
require_once('_base_model.php');

/**
 * Model for Player data
 */
class Player_model extends Base_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'player';
    }

    /**
     * Return string of fields to order a SQL statement by (dependent upon argument passed)
     * @param  string $orderBy Field Name
     * @return string          Field Names
     */
    public function getOrderBy($orderBy)
    {
        switch ($orderBy) {
            case 'firstname':
                return 'first_name, surname';
                break;
            case 'dob':
                return 'dob';
                break;
            case 'nationality':
                return 'nationality';
                break;
        }

        return 'surname, first_name';
    }

    /**
     * Apply Form Validation for Adding & Updating Players
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('surname', 'Surname', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('dob', 'Date of Birth', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('nationality', 'Nationality', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('profile', 'Profile', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('current', 'Current', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('image_id', 'Image', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
    }

    /**
     * Fetch All Players and format for dropdown
     * @return array List of Players
     */
    public function fetchForDropdown()
    {
        $results = $this->fetchAll();

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = "{$result->surname}, {$result->first_name}";
        }

        return $dropdownOptions;
    }

}