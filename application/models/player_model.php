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
     * Insert a Player from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'first_name'     => $this->ci->form_validation->set_value('first_name', NULL),
            'surname'        => $this->ci->form_validation->set_value('surname', NULL),
            'dob'            => $this->ci->form_validation->set_value('dob', NULL),
            'nationality_id' => $this->ci->form_validation->set_value('nationality_id', NULL),
            'profile'        => $this->ci->form_validation->set_value('profile', NULL),
            'current'        => $this->ci->form_validation->set_value('current', NULL),
            'image_id'       => $this->ci->form_validation->set_value('image_id', NULL),
            'gender'         => $this->ci->form_validation->set_value('gender', NULL),
        ));
    }

    /**
     * Update a Player from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'first_name'     => $this->ci->form_validation->set_value('first_name', NULL),
            'surname'        => $this->ci->form_validation->set_value('surname', NULL),
            'dob'            => $this->ci->form_validation->set_value('dob', NULL),
            'nationality_id' => $this->ci->form_validation->set_value('nationality_id', NULL),
            'profile'        => $this->ci->form_validation->set_value('profile', NULL),
            'current'        => $this->ci->form_validation->set_value('current', NULL),
            'image_id'       => $this->ci->form_validation->set_value('image_id', NULL),
            'gender'         => $this->ci->form_validation->set_value('gender', NULL),
        ));
    }

    /**
     * Fetch list of genders
     * @return array List of genders
     */
    public static function fetchGenders()
    {
        $ci =& get_instance();
        $ci->load->config('player');

        return $ci->config->item('genders', 'player');
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
            case 'nationality_id':
                return 'nationality_id';
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

        $this->ci->form_validation->set_rules('first_name', 'First Name', "trim|required|regex_match[/^[A-Za-z -']+$/]|max_length[" . $this->config->item('first_name_max_length', 'player') . "]|xss_clean");
        $this->ci->form_validation->set_rules('surname', 'Surname', "trim|regex_match[/^[A-Za-z -']+$/]|max_length[" . $this->config->item('surname_max_length', 'player') . "]|xss_clean");
        $this->ci->form_validation->set_rules('dob', 'Date of Birth', 'trim|regex_match[/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/]|xss_clean');
        $this->ci->form_validation->set_rules('nationality_id', 'Nationality', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('profile', 'Profile', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('current', 'Current', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('image_id', 'Image', 'trim|xss_clean');
        $this->ci->form_validation->set_rules('gender', 'Gender', 'trim|regex_match[/^(m)|(f)$/]|xss_clean');
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

    /**
     * Can the Player be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean Can the specified Player be deleted?
     */
    public function isDeletable($id)
    {
        $ci =& get_instance();
        $ci->load->model('Appearance_model');
        $ci->load->model('Card_model');
        $ci->load->model('Goal_model');
        $ci->load->model('Player_Registration_model');
        $ci->load->model('Player_To_Award_model');

        $appearances = $ci->Appearance_model->fetchAllByField('player_id', $id);
        $cards = $ci->Card_model->fetchAllByField('player_id', $id);
        $goals = $ci->Goal_model->fetchAllByField('scorer_id', $id);
        $assists = $ci->Goal_model->fetchAllByField('assist_id', $id);
        $playerRegistrations = $ci->Player_Registration_model->fetchAllByField('player_id', $id);
        $playerAwards = $ci->Player_To_Award_model->fetchAllByField('player_id', $id);

        if ($appearances || $cards || $goals || $assists || $playerRegistrations || $playerAwards) {
            return false;
        }

        return true;
    }

}