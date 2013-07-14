<?php
require_once('_base_model.php');

/**
 * Model for Player to Award data
 */
class Player_To_Award_model extends Base_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'player_to_award';

        if (class_exists('Player_model')) {
            $this->ci->load->model('Player_model');
        }

        if (class_exists('Award_model')) {
            $this->ci->load->model('Award_model');
        }
    }

    /**
     * Insert a Player Award from a valid submitted form
     * @return int Inserted ID
     */
    public function processInsert()
    {
        return $this->insertEntry(array(
            'player_id' => $this->ci->form_validation->set_value('player_id', NULL),
            'award_id' => $this->ci->form_validation->set_value('award_id', NULL),
            'season' => $this->ci->form_validation->set_value('season', NULL),
            'placing' => $this->ci->form_validation->set_value('placing', NULL),
        ));
    }

    /**
     * Update a Player Award from a valid submitted form
     * @param  int $int    ID
     * @return int         Updated ID
     */
    public function processUpdate($id)
    {
        return $this->updateEntry($id, array(
            'player_id' => $this->ci->form_validation->set_value('player_id', NULL),
            'award_id' => $this->ci->form_validation->set_value('award_id', NULL),
            'season' => $this->ci->form_validation->set_value('season', NULL),
            'placing' => $this->ci->form_validation->set_value('placing', NULL),
        ));
    }

    /**
     * Apply Form Validation for Adding & Updating Player Award
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('player_id', 'Player', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('award_id', 'Award', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('season', 'Season', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('placing', 'Placing', "trim|required|greater_than[" . $this->config->item('placing_min', 'player_to_award') . "]|less_than[" . $this->config->item('placing_max', 'player_to_award') . "]|xss_clean");
    }

    /**
     * Fetch all Player Awards for a particular Season
     * @param  int $season          Season
     * @return array                Returned rows
     */
    public function fetchBySeason($season)
    {
        $this->db->select("{$this->ci->Player_model->tableName}.*, {$this->ci->Award_model->tableName}.*");
        $this->db->from($this->tableName);
        $this->db->join($this->ci->Player_model->tableName, "{$this->tableName}.player_id = {$this->ci->Player_model->tableName}.id");
        $this->db->join($this->ci->Award_model->tableName, "{$this->tableName}.award_id = {$this->ci->Award_model->tableName}.id");
        $this->db->where('season', $season);
        $this->db->where("{$this->tableName}.deleted", 0);
        $this->db->where("{$this->ci->Player_model->tableName}.deleted", 0);
        $this->db->where("{$this->ci->Award_model->tableName}.deleted", 0);
        $this->db->order_by("{$this->tableName}.placing DESC, {$this->ci->Award_model->tableName}.importance ASC");

        return $this->db->get()->result();
    }

    /**
     * Fetch list of Placings for Dropdown
     * @param  int $season          Season
     * @return array                Returned rows
     */
    public function fetchPlacingForDropdown()
    {
        $data = array();

        $i = 1;
        while($i < $this->config->item('placing_max', 'player_to_award')) {
            $data[$i] = Utility_helper::ordinalWithSuffix($i);
            $i++;
        }

        return $data;
    }

    /**
     * Can the Player Award be deleted without affecting other data
     * @param  int $int    ID
     * @return boolean Can the specified Player Award be deleted?
     */
    public function isDeletable($id)
    {
        return true;
    }

}