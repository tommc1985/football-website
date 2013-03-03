<?php
require_once('_base_model.php');

/**
 * Model for Player Registration data
 */
class Player_Registration_model extends Base_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'player_registration';

        if (class_exists('Player_model')) {
            $this->ci->load->model('Player_model');
        }
    }

    /**
     * Insert a Player Registration from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'player_id' => $this->ci->form_validation->set_value('player_id', NULL),
            'season' => $this->ci->form_validation->set_value('season', NULL),
        ));
    }

    /**
     * Update a Player Registration from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'player_id' => $this->ci->form_validation->set_value('player_id', NULL),
            'season' => $this->ci->form_validation->set_value('season', NULL),
        ));
    }

    /**
     * Apply Form Validation for Adding & Updating Player Registrations
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('player_id', 'Player', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('season', 'Season', 'trim|required|xss_clean');
    }

    /**
     * Fetch all players registered a particular Season
     * @param  int $season          Season
     * @return array                Returned rows
     */
    public function fetchBySeason($season)
    {
        $this->db->select("{$this->ci->Player_model->tableName}.*");
        $this->db->from($this->tableName);
        $this->db->join($this->ci->Player_model->tableName, "{$this->tableName}.player_id = {$this->ci->Player_model->tableName}.id");
        $this->db->where('season', $season);
        $this->db->where("{$this->tableName}.deleted", 0);
        $this->db->where("{$this->ci->Player_model->tableName}.deleted", 0);
        $this->db->order_by("{$this->ci->Player_model->tableName}.surname, {$this->ci->Player_model->tableName}.first_name", "asc");

        return $this->db->get()->result();
    }

    /**
     * Fetch All Player Registrations and format for dropdown
     * @param  int $season          Season
     * @return array                List of Player Registrations
     */
    public function fetchForDropdown($season)
    {
        $results = $this->fetchBySeason($season);

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = "{$result->surname}, {$result->first_name}";
        }

        return $dropdownOptions;
    }

    /**
     * Can the Player Registrations be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean Can the specified Player Registrations be deleted?
     */
    public function isDeletable($id)
    {
        return true;
    }

}