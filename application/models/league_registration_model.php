<?php
require_once('_base_model.php');

/**
 * Model for League Registration data
 */
class League_Registration_model extends Base_Model {

    /**
     * Constructor
     * @return NULL
     */
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();

        $this->tableName = 'league_registration';

        $this->ci->load->model('Opposition_model');
    }

    /**
     * Apply Form Validation for Adding & Updating League Registrations
     * @return NULL
     */
    public function formValidation()
    {
        $this->ci->load->library('form_validation');

        $this->ci->form_validation->set_rules('league_id', 'League', 'trim|required|xss_clean');
        $this->ci->form_validation->set_rules('opposition_id', 'Team', 'trim|required|xss_clean');
    }

    /**
     * Fetch all matches from a particular League
     * @param  int $leagueId         League ID
     * @param  int|false $limit      Number of rows to return
     * @param  int|false $offset     The offset
     * @param  string|false $orderBy Which fields to order results by
     * @param  string|false $order   Order the results Ascending or Descending
     * @return array                 Returned rows
     */
    public function fetchByLeagueId($leagueId)
    {
        $this->db->select("{$this->ci->Opposition_model->tableName}.*");
        $this->db->from($this->tableName);
        $this->db->join($this->ci->Opposition_model->tableName, "{$this->tableName}.opposition_id = {$this->ci->Opposition_model->tableName}.id");
        $this->db->where('league_id', $leagueId);
        $this->db->where("{$this->tableName}.deleted", 0);
        $this->db->where("{$this->ci->Opposition_model->tableName}.deleted", 0);
        $this->db->order_by("{$this->ci->Opposition_model->tableName}.name", "asc");

        return $this->db->get()->result();
    }

    /**
     * Fetch All League Registrations and format for dropdown
     * @param  int $leagueId         League ID
     * @return array List of League Registrations
     */
    public function fetchForDropdown($leagueId)
    {
        $results = $this->fetchByLeagueId($leagueId);

        $dropdownOptions = array();

        foreach ($results as $result) {
            $dropdownOptions[$result->id] = $result->name;
        }

        return $dropdownOptions;
    }

}