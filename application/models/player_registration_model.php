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

        $this->ci->load->model('Player_model');
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
     * Fetch all matches from a particular Player
     * @param  int $season           Season
     * @param  int|false $limit      Number of rows to return
     * @param  int|false $offset     The offset
     * @param  string|false $orderBy Which fields to order results by
     * @param  string|false $order   Order the results Ascending or Descending
     * @return array                 Returned rows
     */
    public function fetchBySeason($season)
    {
        $this->db->select("{$this->ci->Player_model->tableName}.*");
        $this->db->from($this->tableName);
        $this->db->join($this->ci->Player_model->tableName, "{$this->tableName}.player_id = {$this->ci->Player_model->tableName}.id");
        $this->db->where('season', $season);
        $this->db->where("{$this->tableName}.deleted", 0);
        $this->db->where("{$this->ci->Player_model->tableName}.deleted", 0);
        $this->db->order_by("{$this->ci->Player_model->tableName}.name", "asc");

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

}