<?php
require_once('_base_model.php');

class Player_model extends Base_Model {

    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'player';
    }

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

    public function formValidation()
    {
        $this->ci->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('surname', 'Surname', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('dob', 'Date of Birth', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('nationality', 'Nationality', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('profile', 'Profile', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('current', 'Current', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('image_id', 'Image', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
    }

}