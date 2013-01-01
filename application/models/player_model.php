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
     * Fetch list of genders
     * @return array List of genders
     */
    public static function fetchGenders()
    {
        return array(
            'm' => 'Male',
            'f' => 'Female'
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
        $this->load->config('player', true);

        $this->ci->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[' .
        $this->config->item('first_name_max_length', 'player') . ']|xss_clean');
        $this->ci->form_validation->set_rules('surname', 'Surname', 'trim|max_length[' .
        $this->config->item('surname_max_length', 'player') . ']|xss_clean');
        $this->ci->form_validation->set_rules('dob', 'Date of Birth', 'trim|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]|xss_clean');
        $this->ci->form_validation->set_rules('nationality', 'Nationality', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('profile', 'Profile', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('current', 'Current', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('image_id', 'Image', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('gender', 'Gender', 'trim|regex_match[/^(m)|(f)$/]|xss_clean');
    }

}